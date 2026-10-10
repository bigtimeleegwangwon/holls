<?php
// -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * DetectRules — 재난 판정 (사건 → BP 매칭).
 *
 * Port of detect_rules.py.
 *
 * 판정의 축은 행정구역이다. 반경이 아니다. 사건이 어느 도/현에 걸리면 그 안의
 * BP 를 후보로 내고, 사람이 근방을 집중조사한다.
 *
 * 지리 참조 의존:
 *   GeoRef::adminAreaCandidates(country, lat, lon, opts)  → array{admin1: string}[]
 *   GeoRef::haversineKm(lat1, lon1, lat2, lon2)           → float
 *   GeoRef::resolveAdmin1(country, name)                  → string|null
 *   GeoRef::regionFor(country, admin1)                    → string|null
 *   GeoRef::nameKo()                                      → array<string, array{string, string}>
 */
final class DetectRules
{
    // JMA 진도 순서 — 숫자가 아니라 문자열이므로 순서를 명시한다.
    private const JMA_SCALE = ['1', '2', '3', '4', '5-', '5+', '6-', '6+', '7'];

    private const PRECISION_LABEL = [
        'verified_site'       => '실측 좌표 기준',
        'address_geocoded'    => '주소 기준(±수 km)',
        'city_centroid'       => '시 중심 기준(±수십 km)',
        'prefecture_centroid' => '도/현 중심 기준(±수십 km)',
    ];

    // ── 내부 유틸 ─────────────────────────────────────────────────

    private static function dt(?string $value): ?\DateTimeImmutable
    {
        if (!$value) {
            return null;
        }
        try {
            return new \DateTimeImmutable(str_replace('Z', '+00:00', $value));
        } catch (\Exception $e) {
            return null;
        }
    }

    private static function intensityAtLeast(?string $value, ?string $threshold): bool
    {
        if ($value === null || $threshold === null) {
            return false;
        }
        $vi = array_search($value,     self::JMA_SCALE, true);
        $ti = array_search($threshold, self::JMA_SCALE, true);
        if ($vi === false || $ti === false) {
            return false;
        }
        return $vi >= $ti;
    }

    /**
     * 행정구역 표기를 참조표 키로 맞춘다.
     * JMA '熊本県' ↔ BP 데이터 '熊本' 처럼 접미사가 다른 경우를 정규화한다.
     */
    private static function canonArea(?string $country, ?string $name): ?string
    {
        if (!$name) {
            return null;
        }
        // GeoRef 의존 — 없으면 원값 그대로 쓴다.
        if (class_exists('GeoRef', false)) {
            return GeoRef::resolveAdmin1($country, $name) ?: trim($name);
        }
        return trim($name);
    }

    /**
     * 한 BP 행에서 행정구역 키 집합을 꺼낸다.
     * @return string[]
     */
    private static function adminOf(array $row): array
    {
        $country = $row['country'] ?? null;
        $out     = [];
        foreach (['admin1', 'admin1Ko', 'prefecture', 'prefectureKo'] as $k) {
            $v = $row[$k] ?? null;
            if ($v) {
                $c = self::canonArea($country, (string) $v);
                if ($c) {
                    $out[$c] = true;
                }
            }
        }
        return array_keys($out);
    }

    // ── 공개 API ──────────────────────────────────────────────────

    /**
     * 사건의 도/현 후보군.
     *
     * 소스가 준 값이 있으면 그것을 쓴다 — JMA 의 도도부현이 좌표 추정보다 정확하다.
     * 없으면(USGS·GDACS) 좌표로 복원한다.
     *
     * @return array{0: string[], 1: string}  [areas, source='source'|'coordinates'|'none']
     */
    public static function resolveAdminAreas(array $event, ?float $marginKm = null): array
    {
        $given = array_values(array_filter(
            $event['affectedRegions'] ?? [],
            fn($a) => trim((string) $a) !== ''
        ));
        if (!empty($given)) {
            return [$given, 'source'];
        }

        $lat = $event['lat'] ?? null;
        $lon = $event['lon'] ?? null;
        if ($lat === null || $lon === null) {
            return [[], 'none'];
        }

        if (!class_exists('GeoRef', false)) {
            return [[], 'none'];
        }
        $opts  = $marginKm !== null ? ['margin_km' => $marginKm] : [];
        $cands = GeoRef::adminAreaCandidates($event['country'] ?? null, (float) $lat, (float) $lon, $opts);
        return [array_column($cands, 'admin1'), 'coordinates'];
    }

    /**
     * 도/현 또는 광역권이 겹치는 BP site 를 고른다.
     *
     * matchType: same_administrative_area | same_region
     * 거리는 참고값으로만 붙인다. 판정에 쓰지 않는다.
     *
     * @param array<array<string, mixed>> $rows  bp_resolved 행들
     * @param string[]                   $adminAreas  resolveAdminAreas 결과
     * @return array<array<string, mixed>>
     */
    public static function matchBps(array $event, array $rows, array $adminAreas): array
    {
        $country = $event['country'] ?? null;

        // 사건 도/현 → 정규화 키 집합
        $wanted = [];
        foreach ($adminAreas as $a) {
            $c = self::canonArea($country, (string) $a);
            if ($c) {
                $wanted[$c] = true;
            }
        }

        // 도/현 → 광역권
        $wantedRegions = [];
        if (class_exists('GeoRef', false)) {
            foreach (array_keys($wanted) as $a) {
                $rc = GeoRef::regionFor($country, $a);
                if ($rc) {
                    $wantedRegions[$rc] = true;
                }
            }
        }

        $nameKo = class_exists('GeoRef', false) ? GeoRef::nameKo() : [];
        $eLat   = $event['lat'] ?? null;
        $eLon   = $event['lon'] ?? null;

        $out = [];
        foreach ($rows as $r) {
            if (($r['active'] ?? true) === false) {
                continue;
            }
            if (($r['country'] ?? null) !== $country) {
                continue;
            }

            $adminHit  = !empty(array_intersect(self::adminOf($r), array_keys($wanted)));
            $rc        = $r['regionCode'] ?? null;
            $regionHit = $rc && isset($wantedRegions[$rc]);

            if (!$adminHit && !$regionHit) {
                continue;
            }

            $hit                = $r;
            $hit['matchType']   = $adminHit ? 'same_administrative_area' : 'same_region';
            $hit['matchLabel']  = $adminHit ? '동일 행정권역' : '동일 광역권';
            $hit['regionKo']    = $r['regionKo'] ?? ($nameKo[$rc][1] ?? '');

            // 거리 — 판정에 쓰지 않는다. 근거를 함께 적지 않으면 정밀한 값으로 오해한다.
            $rLat = $r['lat'] ?? null;
            $rLon = $r['lon'] ?? null;
            if ($eLat !== null && $eLon !== null && $rLat !== null && $rLon !== null
                && class_exists('GeoRef', false)) {
                $km    = (int) round(GeoRef::haversineKm(
                    (float) $eLat, (float) $eLon,
                    (float) $rLat, (float) $rLon
                ));
                $cs    = $r['coordinateStatus'] ?? '';
                $basis = self::PRECISION_LABEL[$cs] ?? '좌표 근거 미상';
                $exact = ($cs === 'verified_site');
                $hit['referenceDistanceKm']   = $km;
                $hit['referenceDistanceText']  = $exact
                    ? "{$km}km ({$basis})"
                    : "약 {$km}km ({$basis})";
            }
            $out[] = $hit;
        }

        // 행정권역 우선 → 등급 순 → 이름 순
        $rank = ['QD' => 0, 'Q' => 1];
        usort($out, static function (array $a, array $b) use ($rank): int {
            $ta = ($a['matchType'] === 'same_administrative_area') ? 0 : 1;
            $tb = ($b['matchType'] === 'same_administrative_area') ? 0 : 1;
            if ($ta !== $tb) {
                return $ta <=> $tb;
            }
            $ga = $rank[substr(strtoupper((string) ($a['grade'] ?? '')), 0, 2)] ?? 9;
            $gb = $rank[substr(strtoupper((string) ($b['grade'] ?? '')), 0, 2)] ?? 9;
            if ($ga !== $gb) {
                return $ga <=> $gb;
            }
            return strcmp((string) ($a['bpName'] ?? ''), (string) ($b['bpName'] ?? ''));
        });

        return $out;
    }

    /**
     * 사건 하나를 판정한다. 기각이면 반드시 사유를 담는다.
     *
     * @param array<string, mixed> $event
     * @param array<string, mixed> $thresholds
     * @param array<array<string, mixed>> $rows
     * @return array<string, mixed>
     */
    public static function evaluate(
        array $event,
        array $thresholds,
        array $rows,
        ?\DateTimeImmutable $now = null,
        ?float $marginKm = null
    ): array {
        $now      = $now ?? new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $occurred = self::dt($event['occurredAt'] ?? null);

        if (!$occurred) {
            return ['trigger' => false, 'reason' => 'invalid_event_time'];
        }

        $nowTs      = $now->getTimestamp();
        $occurredTs = $occurred->getTimestamp();
        $lookbackH  = (float) ($thresholds['lookbackHours'] ?? 6);

        if ($occurredTs > $nowTs + 300) {          // 5분 여유
            return ['trigger' => false, 'reason' => 'future_event_time'];
        }
        if (($nowTs - $occurredTs) > $lookbackH * 3600) {
            return ['trigger' => false, 'reason' => 'outside_lookback'];
        }

        $eventCountry = $event['country'] ?? null;
        $country      = ($thresholds['countries'] ?? [])[$eventCountry] ?? null;
        if (!$country) {
            return ['trigger' => false, 'reason' => 'country_not_configured'];
        }

        $severity = null;
        $reason   = null;

        if (($event['source'] ?? null) === 'GDACS') {
            $g = $thresholds['gdacs'] ?? [];
            if (!in_array($event['sourceType'] ?? null, $g['enabledEventTypes'] ?? [], true)) {
                return ['trigger' => false, 'reason' => 'gdacs_event_type_disabled'];
            }
            $alert = $event['gdacsAlert'] ?? null;
            if (in_array($alert, $g['criticalLevels'] ?? [], true)) {
                [$severity, $reason] = ['critical', 'gdacs_red'];
            } elseif (in_array($alert, $g['warningLevels'] ?? [], true)) {
                [$severity, $reason] = ['warning', 'gdacs_orange'];
            } else {
                return ['trigger' => false, 'reason' => 'gdacs_below_threshold'];
            }
        } elseif (($event['type'] ?? null) === 'earthquake') {
            $eq    = $country['earthquake'] ?? [];
            $inten = $event['intensity'] ?? null;
            $mag   = isset($event['magnitude']) ? (float) $event['magnitude'] : null;

            if ($inten && self::intensityAtLeast((string) $inten, $eq['intensityCritical'] ?? null)) {
                [$severity, $reason] = ['critical', 'local_intensity_critical'];
            } elseif ($inten && self::intensityAtLeast((string) $inten, $eq['intensityWarning'] ?? null)) {
                [$severity, $reason] = ['warning', 'local_intensity_warning'];
            } elseif ($mag !== null && $mag >= (float) ($eq['regionalMagnitudeCritical'] ?? 99)) {
                [$severity, $reason] = ['critical', 'magnitude_critical'];
            } elseif ($mag !== null && $mag >= (float) ($eq['regionalMagnitudeWarning'] ?? 99)) {
                [$severity, $reason] = ['warning', 'magnitude_warning'];
            } else {
                return [
                    'trigger' => false,
                    'reason'  => $inten ? 'local_intensity_below_threshold' : 'below_magnitude',
                ];
            }
        } else {
            return ['trigger' => false, 'reason' => 'event_type_not_configured'];
        }

        [$areas, $areaSource] = self::resolveAdminAreas($event, $marginKm);
        if (empty($areas)) {
            return ['trigger' => false, 'reason' => 'admin_area_unresolved'];
        }

        $matched = self::matchBps($event, $rows, $areas);

        // 사건이 아무리 커도 우리 BP 가 없으면 우리 일이 아니다.
        if (($thresholds['requireBpMatch'] ?? true) && empty($matched)) {
            return [
                'trigger'         => false,
                'reason'          => 'no_region_matched_bp',
                'adminAreas'      => $areas,
                'adminAreaSource' => $areaSource,
            ];
        }

        $bpNames = array_unique(array_filter(array_column($matched, 'bpName')));

        return [
            'trigger'         => true,
            'severity'        => $severity,
            'reason'          => $reason,
            'adminAreas'      => $areas,
            'adminAreaSource' => $areaSource,
            'matchedBps'      => $matched,
            'bpCount'         => count($bpNames),
            'siteCount'       => count($matched),
        ];
    }

    /**
     * 전체 판정 + 퍼널. 어디서 몇 건이 걸러졌는지 남긴다.
     *
     * 같은 사건이 여러 소스로 들어온다 — 등급 높은 쪽을 남긴다.
     *
     * @param array<array<string, mixed>> $events
     * @return array{candidates: array, evaluated: array, funnel: array}
     */
    public static function run(
        array $events,
        array $thresholds,
        array $rows,
        ?\DateTimeImmutable $now = null,
        ?float $marginKm = null
    ): array {
        $now       = $now ?? new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $evaluated = [];
        $reasons   = [];

        foreach ($events as $e) {
            $v           = self::evaluate($e, $thresholds, $rows, $now, $marginKm);
            $evaluated[] = ['event' => $e, 'evaluation' => $v];
            $r           = $v['reason'];
            $reasons[$r] = ($reasons[$r] ?? 0) + 1;
        }

        $triggered = array_values(array_filter($evaluated, fn($x) => $x['evaluation']['trigger']));

        $rank = ['critical' => 2, 'warning' => 1];
        $best = [];
        foreach ($triggered as $x) {
            $ev  = $x['event'];
            $key = implode('|', [
                $ev['country'] ?? '',
                substr((string) ($ev['occurredAt'] ?? ''), 0, 13),
                $ev['type'] ?? '',
            ]);
            $cur  = $best[$key] ?? null;
            $xSev = $rank[$x['evaluation']['severity'] ?? ''] ?? 0;
            $cSev = $cur ? ($rank[$cur['evaluation']['severity'] ?? ''] ?? 0) : -1;
            if (!$cur || $xSev > $cSev) {
                $best[$key] = $x;
            }
        }

        $candidates = array_values($best);
        usort($candidates, fn($a, $b) =>
            ($rank[$b['evaluation']['severity'] ?? ''] ?? 0) <=>
            ($rank[$a['evaluation']['severity'] ?? ''] ?? 0)
        );

        return [
            'candidates' => $candidates,
            'evaluated'  => $evaluated,
            'funnel'     => [
                'fetched'   => count($events),
                'triggered' => count($triggered),
                'deduped'   => count($candidates),
                'byReason'  => $reasons,
            ],
        ];
    }
}
