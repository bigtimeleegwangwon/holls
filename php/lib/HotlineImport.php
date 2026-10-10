<?php
// -*- coding: utf-8 -*-
/**
 * HotlineImport — BP 연락처 일괄 등록 파서.
 * hotline_import.py 완전 이식본.
 *
 * 탭/콤마 구분 CSV 를 받아 BP 이름을 명부와 연결한다.
 * 자동으로 붙이면 틀린 회사에 남의 연락처가 들어가므로 미리보기를 반드시 거친다.
 */
declare(strict_types=1);

final class HotlineImport
{
    // hotline_import.py COLUMNS
    public const COLUMNS = [
        'BP(표준)', '소재군', 'BP명(자체)', 'BPcode', '담당자', '연락처', '메일', '비고', '송부',
    ];

    // ── 정규화 ────────────────────────────────────────────────────

    /**
     * 이름을 정규화한다. hotline_import.py norm() 이식.
     * NFKC 정규화, 소문자, 괄호/회사 접미사/공백 제거.
     */
    public static function norm(string $s): string
    {
        // NFKC 정규화 (PHP 에서는 Normalizer 확장이 필요, 없으면 근사)
        if (class_exists('Normalizer')) {
            $s = \Normalizer::normalize($s, \Normalizer::FORM_KC) ?: $s;
        }
        $s = mb_strtolower($s);
        // 괄호와 내용 제거
        $s = preg_replace('/\([^)]*\)|\[[^\]]*\]|（[^）]*）/', '', $s) ?? $s;
        // 회사 접미사 제거
        $s = preg_replace('/\s*(주식회사|유한회사|합자회사|사단법인|재단법인|co\.?,?\s*ltd\.?|inc\.?|corp\.?|llc\.?|gmbh|s\.a\.|b\.v\.)\.?\s*$/iu', '', $s) ?? $s;
        $s = preg_replace('/^(주식회사|유한회사)\s*/u', '', $s) ?? $s;
        return trim($s);
    }

    /**
     * 명부로부터 이름→정본 BP 이름 역색인을 만든다.
     * hotline_import.py build_index() 이식.
     *
     * @param array[] $rows  bp_resolved.json 행 배열
     * @return array<string,string>  정규화된 이름 → 정본 bpName
     */
    public static function buildIndex(array $rows): array
    {
        $idx = [];
        foreach ($rows as $r) {
            $bp = $r['bpName'] ?? '';
            if (!$bp) {
                continue;
            }
            $idx[self::norm($bp)] = $bp;
            foreach (($r['bpAliases'] ?? []) as $a) {
                if ($a) {
                    $idx[self::norm($a)] = $bp;
                }
            }
        }
        return $idx;
    }

    /**
     * 원시 이름을 정본 BP 이름에 매핑한다.
     * hotline_import.py match_bp() 이식.
     * 정확 → 부분 → 부분문자열 순.
     *
     * @return string|null  매칭된 정본 BP 이름 또는 null
     */
    public static function matchBp(string $raw, array $idx): ?string
    {
        $key = self::norm($raw);
        if (!$key) {
            return null;
        }
        // 정확 매칭
        if (isset($idx[$key])) {
            return $idx[$key];
        }
        // 부분 매칭: 정규화 키에 key 가 포함
        foreach ($idx as $k => $bp) {
            if (mb_strpos($k, $key) !== false || mb_strpos($key, $k) !== false) {
                return $bp;
            }
        }
        return null;
    }

    /**
     * 원시 텍스트에서 소재군을 추출한다.
     * hotline_import.py match_groups() 이식.
     *
     * @param string[] $known  알려진 소재군 목록
     * @return string[]
     */
    public static function matchGroups(string $raw, array $known): array
    {
        $found = [];
        foreach ($known as $g) {
            // 단어 경계 또는 구분자 기반
            $pattern = '/(?<![^\s,\/·|])\s*' . preg_quote($g, '/') . '\s*(?![^\s,\/·|])/u';
            if (preg_match($pattern, $raw)) {
                $found[] = $g;
            } elseif (mb_strpos($raw, $g) !== false) {
                $found[] = $g;
            }
        }
        return array_values(array_unique($found));
    }

    /**
     * 한국어 자모 로마자 변환 (간이). hotline_import.py romanize() 이식.
     * 완전한 로마자 변환 대신 초성+중성만 처리 — 이름 퍼지 매칭용.
     */
    public static function romanize(string $s): string
    {
        $onsets = ['g','kk','n','d','tt','r','m','b','pp','s','ss','','j','jj','ch','k','t','p','h'];
        $nuclei = ['a','ae','ya','yae','eo','e','yeo','ye','o','wa','wae','oe','yo','u','wo','we','wi','yu','eu','ui','i'];
        $codas = ['','g','kk','gs','n','nj','nh','d','l','lg','lm','lb','ls','lt','lp','lh','m','b','bs','s','ss','ng','j','ch','k','t','p','h'];
        $result = '';
        $len = mb_strlen($s);
        for ($i = 0; $i < $len; $i++) {
            $ch = mb_substr($s, $i, 1);
            $cp = mb_ord($ch);
            if ($cp >= 0xAC00 && $cp <= 0xD7A3) {
                $idx = $cp - 0xAC00;
                $coda = $idx % 28;
                $idx = intdiv($idx, 28);
                $nucleus = $idx % 21;
                $onset = intdiv($idx, 21);
                $result .= ($onsets[$onset] ?? '') . ($nuclei[$nucleus] ?? '') . ($codas[$coda] ?? '');
            } else {
                $result .= mb_strtolower($ch);
            }
        }
        return $result;
    }

    /**
     * 영어 약자 → 한국어 자모 발음 (간이). hotline_import.py spell_out() 이식.
     */
    public static function spellOut(string $s): string
    {
        $map = [
            'A'=>'에이','B'=>'비','C'=>'씨','D'=>'디','E'=>'이','F'=>'에프',
            'G'=>'지','H'=>'에이치','I'=>'아이','J'=>'제이','K'=>'케이','L'=>'엘',
            'M'=>'엠','N'=>'엔','O'=>'오','P'=>'피','Q'=>'큐','R'=>'알',
            'S'=>'에스','T'=>'티','U'=>'유','V'=>'브이','W'=>'더블유','X'=>'엑스',
            'Y'=>'와이','Z'=>'제트',
        ];
        $result = '';
        $upper = strtoupper($s);
        for ($i = 0; $i < strlen($upper); $i++) {
            $ch = $upper[$i];
            $result .= $map[$ch] ?? $ch;
        }
        return $result;
    }

    /**
     * 후보 목록 제안. hotline_import.py suggest() 이식.
     * difflib similar_text 유사도 + 로마자화 점수.
     *
     * @param string[] $idx  buildIndex() 결과 키 배열
     * @return string[]  최대 k 개 추천 정본 BP 이름
     */
    public static function suggest(string $raw, array $idx, int $k = 5): array
    {
        $key = self::norm($raw);
        if (!$key) {
            return [];
        }
        $romKey = self::romanize($key);

        $scores = [];
        $seen = [];
        foreach ($idx as $normName => $bp) {
            if (isset($seen[$bp])) {
                continue;
            }
            similar_text($key, $normName, $pct);
            $romScore = 0.0;
            $romName = self::romanize($normName);
            if ($romKey && $romName) {
                similar_text($romKey, $romName, $romPct);
                $romScore = $romPct / 100.0 * 0.3;
            }
            $scores[$bp] = max($scores[$bp] ?? 0, $pct / 100.0 + $romScore);
            $seen[$bp] = true;
        }
        arsort($scores);
        return array_slice(array_keys($scores), 0, $k);
    }

    /**
     * CSV/TSV 텍스트를 파싱해 BP 별로 묶는다.
     * hotline_import.py parse() 이식.
     *
     * @param string  $text  붙여넣은 CSV/TSV 텍스트
     * @param array[] $rows  bp_resolved.json 행 배열
     * @return array  {byBp, unmatched, suggestions, stats}
     */
    public static function parse(string $text, array $rows): array
    {
        $idx = self::buildIndex($rows);
        $knownGroups = array_unique(array_merge(...array_map(
            fn($r) => $r['materialGroups'] ?? [],
            array_filter($rows, fn($r) => ($r['active'] ?? true) !== false)
        )));

        // 구분자 감지 (탭 우선)
        $lines = preg_split('/\r?\n/', trim($text));
        $sep = (substr_count($text, "\t") > substr_count($text, ',')) ? "\t" : ',';

        // 헤더 행 감지 및 컬럼 인덱스 매핑
        $colMap = [];
        $dataLines = [];
        $firstLine = $lines[0] ?? '';
        $headerCells = str_getcsv($firstLine, $sep);
        $isHeader = false;
        foreach (self::COLUMNS as $col) {
            foreach ($headerCells as $i => $h) {
                if (mb_strpos(mb_strtolower($h), mb_strtolower($col)) !== false) {
                    $colMap[$col] = $i;
                    $isHeader = true;
                }
            }
        }
        $dataLines = $isHeader ? array_slice($lines, 1) : $lines;

        $byBp = [];
        $unmatched = [];
        $matched = 0;
        $total = 0;

        foreach ($dataLines as $line) {
            $line = trim($line);
            if (!$line) {
                continue;
            }
            $cells = str_getcsv($line, $sep);
            $total++;

            // 컬럼 매핑
            $get = function (string $col) use ($cells, $colMap, $isHeader): string {
                if ($isHeader && isset($colMap[$col])) {
                    return trim($cells[$colMap[$col]] ?? '');
                }
                // 위치 기반 폴백
                $pos = array_search($col, self::COLUMNS, true);
                return $pos !== false ? trim($cells[$pos] ?? '') : '';
            };

            $bpRaw = $get('BP(표준)') ?: $get('BP명(자체)');
            $contactName = $get('담당자');
            $phone = $get('연락처');
            $email = $get('메일');
            $note = $get('비고');
            $bpCode = $get('BPcode');
            $groupRaw = $get('소재군');

            if (!$bpRaw && !$contactName) {
                continue;
            }

            $bp = self::matchBp($bpRaw, $idx);
            $groups = $groupRaw ? self::matchGroups($groupRaw, $knownGroups) : [];

            $contact = array_filter([
                'contactName' => $contactName,
                'phone' => $phone,
                'email' => $email,
                'note' => $note,
                'bpCode' => $bpCode,
                'materialGroups' => $groups ?: null,
            ], fn($v) => $v !== null && $v !== '' && $v !== []);

            if ($bp) {
                $byBp[$bp][] = $contact;
                $matched++;
            } else {
                $suggestions = $bpRaw ? self::suggest($bpRaw, $idx) : [];
                $unmatched[] = [
                    'raw' => $bpRaw,
                    'suggestions' => $suggestions,
                    'contact' => $contact,
                ];
            }
        }

        return [
            'byBp' => $byBp,
            'unmatched' => $unmatched,
            'stats' => [
                'total' => $total,
                'matched' => $matched,
                'unmatched' => count($unmatched),
            ],
        ];
    }

    /**
     * 연락처 빈 BP 와 채운 BP 를 분리한다.
     * hotline_import.py gaps() 이식.
     *
     * @param array[] $rows  bp_resolved.json 행 배열
     * @return array  {empty, filled, stats}
     */
    public static function gaps(array $rows): array
    {
        $byBp = [];
        foreach ($rows as $r) {
            if (($r['active'] ?? true) === false) {
                continue;
            }
            $bp = $r['bpName'] ?? '';
            if (!$bp) {
                continue;
            }
            $d = $byBp[$bp] ?? [
                'bp' => $bp,
                'grade' => $r['grade'] ?? '',
                'tier' => $r['tier'] ?? '',
                'groups' => [],
                'countries' => [],
                'sites' => 0,
                'contacts' => 0,
            ];
            $d['sites']++;
            foreach ($r['materialGroups'] ?? [] as $g) {
                if (!in_array($g, $d['groups'], true)) {
                    $d['groups'][] = $g;
                }
            }
            if ($r['country'] ?? '') {
                if (!in_array($r['country'], $d['countries'], true)) {
                    $d['countries'][] = $r['country'];
                }
            }
            // 연락처 수
            $hot = $r['hotline'] ?? [];
            $contacts = $r['contacts'] ?? [];
            if (is_array($contacts)) {
                $d['contacts'] += count(array_filter($contacts, fn($c) => !empty($c['phone']) || !empty($c['email'])));
            } elseif (is_array($hot)) {
                if (!empty($hot['contactName']) || !empty($hot['phone']) || !empty($hot['email'])) {
                    $d['contacts']++;
                }
            }
            $byBp[$bp] = $d;
        }

        $empty = [];
        $filled = [];
        foreach ($byBp as $d) {
            if ($d['contacts'] > 0) {
                $filled[] = $d;
            } else {
                $empty[] = $d;
            }
        }

        // 정렬: 등급 → 이름
        $sortKey = fn($a) => [$a['grade'], $a['bp']];
        usort($empty, fn($a, $b) => $sortKey($a) <=> $sortKey($b));
        usort($filled, fn($a, $b) => $sortKey($a) <=> $sortKey($b));

        return [
            'empty' => $empty,
            'filled' => $filled,
            'stats' => [
                'total' => count($byBp),
                'empty' => count($empty),
                'filled' => count($filled),
                'rate' => count($byBp) > 0
                    ? round(count($filled) / count($byBp) * 100, 1)
                    : 0,
            ],
        ];
    }

    /**
     * 연락처 양식 CSV 행을 내보낸다. hotline_import.py export_rows() 이식.
     *
     * @param array[] $rows  bp_resolved.json 행 배열
     * @return array  [columns[], rows[]]
     */
    public static function exportRows(array $rows): array
    {
        $cols = self::COLUMNS;
        $out = [];
        foreach ($rows as $r) {
            if (($r['active'] ?? true) === false) {
                continue;
            }
            $bp = $r['bpName'] ?? '';
            $groups = implode(' / ', $r['materialGroups'] ?? []);
            $hot = $r['hotline'] ?? [];
            $contacts = $r['contacts'] ?? [];
            if (!is_array($contacts) || empty($contacts)) {
                $contacts = is_array($hot) && $hot ? [$hot] : [[]];
            }
            foreach ($contacts as $c) {
                $out[] = [
                    $bp,
                    $groups,
                    '',    // BP명(자체) — 비워서 내보냄
                    $c['bpCode'] ?? '',
                    $c['contactName'] ?? '',
                    $c['phone'] ?? '',
                    $c['email'] ?? '',
                    $c['note'] ?? '',
                    '',    // 송부
                ];
            }
        }
        return [$cols, $out];
    }
}
