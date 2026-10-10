<?php
// -*- coding: utf-8 -*-
/**
 * GeoRef — 행정구역 중심점 참조표.
 * geo_ref.py 완전 이식본.
 *
 * 외부 지오코딩 호출 없음. 표준 라이브러리만 사용.
 * 보장: "정확한 공장 위치"가 아니라 최소한 맞는 도/현 안에 있다는 것.
 */
declare(strict_types=1);

final class GeoRef
{
    // ── 행정구역 중심점 (lat, lon, 허용반경 km) ──────────────────

    public static array $KR = [
        '서울' => [37.5665, 126.9780, 35], '부산' => [35.1796, 129.0756, 45],
        '대구' => [35.8714, 128.6014, 40], '인천' => [37.4563, 126.7052, 90],
        '광주' => [35.1595, 126.8526, 30], '대전' => [36.3504, 127.3845, 30],
        '울산' => [35.5384, 129.3114, 45], '세종' => [36.4800, 127.2890, 30],
        '경기' => [37.4138, 127.5183, 95], '강원' => [37.8228, 128.1555, 110],
        '충북' => [36.6357, 127.4917, 90], '충남' => [36.5184, 126.8000, 90],
        '전북' => [35.7175, 127.1530, 85], '전남' => [34.8679, 126.9910, 130],
        '경북' => [36.4919, 128.8889, 130], '경남' => [35.4606, 128.2132, 95],
        '제주' => [33.4996, 126.5312, 45],
    ];

    public static array $JP = [
        '北海道' => [43.50, 142.80, 320], '青森' => [40.82, 140.74, 90],
        '岩手' => [39.70, 141.15, 100], '宮城' => [38.27, 140.87, 85],
        '秋田' => [39.72, 140.10, 100], '山形' => [38.24, 140.36, 85],
        '福島' => [37.75, 140.47, 100], '茨城' => [36.34, 140.45, 80],
        '栃木' => [36.57, 139.88, 75], '群馬' => [36.39, 139.06, 75],
        '埼玉' => [35.86, 139.65, 70], '千葉' => [35.61, 140.12, 85],
        '東京' => [35.69, 139.69, 60], '神奈川' => [35.45, 139.64, 55],
        '新潟' => [37.90, 139.07, 130], '富山' => [36.70, 137.21, 60],
        '石川' => [36.59, 136.63, 90], '福井' => [36.07, 136.22, 75],
        '山梨' => [35.66, 138.57, 60], '長野' => [36.65, 138.18, 110],
        '岐阜' => [35.39, 136.72, 95], '静岡' => [34.98, 138.38, 95],
        '愛知' => [35.18, 136.91, 70], '三重' => [34.73, 136.51, 90],
        '滋賀' => [35.00, 135.87, 60], '京都' => [35.02, 135.76, 80],
        '大阪' => [34.69, 135.52, 50], '兵庫' => [34.69, 135.18, 90],
        '奈良' => [34.69, 135.83, 60], '和歌山' => [34.23, 135.17, 75],
        '鳥取' => [35.50, 134.24, 70], '島根' => [35.47, 133.05, 110],
        '岡山' => [34.66, 133.93, 70], '広島' => [34.40, 132.46, 80],
        '山口' => [34.19, 131.47, 80], '徳島' => [34.07, 134.56, 60],
        '香川' => [34.29, 134.04, 45], '愛媛' => [33.84, 132.77, 85],
        '高知' => [33.56, 133.53, 90], '福岡' => [33.61, 130.42, 75],
        '佐賀' => [33.25, 130.30, 50], '長崎' => [32.74, 129.87, 130],
        '熊本' => [32.79, 130.74, 85], '大分' => [33.24, 131.61, 75],
        '宮崎' => [31.91, 131.42, 90], '鹿児島' => [31.56, 130.56, 170],
        '沖縄' => [26.21, 127.73, 200],
    ];

    public static array $TW = [
        '台北' => [25.03, 121.56, 15], '新北' => [24.95, 121.60, 25],
        '桃園' => [24.99, 121.30, 22], '新竹' => [24.80, 120.97, 20],
        '苗栗' => [24.56, 120.82, 22], '台中' => [24.14, 120.68, 25],
        '彰化' => [24.07, 120.54, 18], '雲林' => [23.71, 120.43, 20],
        '嘉義' => [23.48, 120.44, 20], '台南' => [23.00, 120.23, 25],
        '高雄' => [22.63, 120.30, 28], '屏東' => [22.55, 120.55, 30],
        '宜蘭' => [24.70, 121.74, 25], '花蓮' => [23.98, 121.60, 40],
        '台東' => [22.76, 121.14, 40],
    ];

    // ── 별칭 테이블 ───────────────────────────────────────────────

    private static ?array $_jpAlias = null;
    private static ?array $_krAlias = null;
    private static ?array $_twAlias = null;
    private static ?array $_cnAlias = null;
    private static ?array $_usAlias = null;
    private static ?array $_euAlias = null;
    private static ?array $_sgAlias = null;
    private static ?array $_myAlias = null;
    private static ?array $_phAlias = null;

    // JP 별칭 (kanji/romaji/한글 → 한자 키)
    private static function jpRomajiMap(): array
    {
        return [
            '北海道' => ['Hokkaido', '홋카이도'], '青森' => ['Aomori', '아오모리'],
            '岩手' => ['Iwate', '이와테'], '宮城' => ['Miyagi', '미야기'],
            '秋田' => ['Akita', '아키타'], '山形' => ['Yamagata', '야마가타'],
            '福島' => ['Fukushima', '후쿠시마'], '茨城' => ['Ibaraki', '이바라키'],
            '栃木' => ['Tochigi', '도치기'], '群馬' => ['Gunma', '군마', '구마'],
            '埼玉' => ['Saitama', '사이타마'], '千葉' => ['Chiba', '지바', '치바'],
            '東京' => ['Tokyo', '도쿄', '토쿄'], '神奈川' => ['Kanagawa', '가나가와'],
            '新潟' => ['Niigata', '니가타'], '富山' => ['Toyama', '도야마'],
            '石川' => ['Ishikawa', '이시카와'], '福井' => ['Fukui', '후쿠이'],
            '山梨' => ['Yamanashi', '야마나시'], '長野' => ['Nagano', '나가노'],
            '岐阜' => ['Gifu', '기후', '기부'], '静岡' => ['Shizuoka', '시즈오카', '시즈오까'],
            '愛知' => ['Aichi', '아이치'], '三重' => ['Mie', '미에'],
            '滋賀' => ['Shiga', '시가'], '京都' => ['Kyoto', '교토', '쿄토'],
            '大阪' => ['Osaka', '오사카'], '兵庫' => ['Hyogo', '효고'],
            '奈良' => ['Nara', '나라'], '和歌山' => ['Wakayama', '와카야마'],
            '鳥取' => ['Tottori', '돗토리'], '島根' => ['Shimane', '시마네'],
            '岡山' => ['Okayama', '오카야마'], '広島' => ['Hiroshima', '히로시마'],
            '山口' => ['Yamaguchi', '야마구치'], '徳島' => ['Tokushima', '도쿠시마'],
            '香川' => ['Kagawa', '가가와'], '愛媛' => ['Ehime', '에히메'],
            '高知' => ['Kochi', '고치'], '福岡' => ['Fukuoka', '후쿠오카', '후쿠오까'],
            '佐賀' => ['Saga', '사가'], '長崎' => ['Nagasaki', '나가사키'],
            '熊本' => ['Kumamoto', '구마모토'], '大分' => ['Oita', '오이타'],
            '宮崎' => ['Miyazaki', '미야자키'], '鹿児島' => ['Kagoshima', '가고시마'],
            '沖縄' => ['Okinawa', '오키나와'],
        ];
    }

    public static function jpAlias(): array
    {
        if (self::$_jpAlias !== null) {
            return self::$_jpAlias;
        }
        $out = [];
        foreach (self::jpRomajiMap() as $kanji => $names) {
            $all = array_merge([$kanji], $names);
            foreach ($all as $n) {
                $nl = mb_strtolower($n);
                $out[$nl] = $kanji;
                $out[mb_strtolower($n . '県')] = $kanji;
                $out[mb_strtolower($n . '府')] = $kanji;
                $out[mb_strtolower($n . '都')] = $kanji;
            }
        }
        self::$_jpAlias = $out;
        return $out;
    }

    public static function krAlias(): array
    {
        if (self::$_krAlias !== null) {
            return self::$_krAlias;
        }
        $map = [
            '서울' => ['서울특별시', 'Seoul'], '부산' => ['부산광역시', 'Busan'],
            '대구' => ['대구광역시', 'Daegu'], '인천' => ['인천광역시', 'Incheon'],
            '광주' => ['광주광역시', 'Gwangju'], '대전' => ['대전광역시', 'Daejeon'],
            '울산' => ['울산광역시', 'Ulsan'], '세종' => ['세종특별자치시', 'Sejong'],
            '경기' => ['경기도', 'Gyeonggi'], '강원' => ['강원도', '강원특별자치도', 'Gangwon'],
            '충북' => ['충청북도', 'Chungbuk', 'Chungcheongbuk'],
            '충남' => ['충청남도', 'Chungnam', 'Chungcheongnam'],
            '전북' => ['전라북도', '전북특별자치도', 'Jeonbuk', 'Jeollabuk'],
            '전남' => ['전라남도', 'Jeonnam', 'Jeollanam'],
            '경북' => ['경상북도', 'Gyeongbuk', 'Gyeongsangbuk'],
            '경남' => ['경상남도', 'Gyeongnam', 'Gyeongsangnam'],
            '제주' => ['제주도', '제주특별자치도', 'Jeju'],
        ];
        $out = [];
        foreach ($map as $k => $names) {
            $out[mb_strtolower($k)] = $k;
            foreach ($names as $n) {
                $out[mb_strtolower($n)] = $k;
            }
        }
        self::$_krAlias = $out;
        return $out;
    }

    public static function twAlias(): array
    {
        if (self::$_twAlias !== null) {
            return self::$_twAlias;
        }
        $map = [
            '台北' => ['taipei', '臺北'], '新北' => ['new taipei', 'newtaipei', '臺北縣'],
            '桃園' => ['taoyuan', '桃园'], '新竹' => ['hsinchu', 'hsin-chu', 'zhubei', '竹北'],
            '苗栗' => ['miaoli', 'chunan', 'zhunan', '竹南'], '台中' => ['taichung', '臺中', '台中市'],
            '彰化' => ['changhua'], '雲林' => ['yunlin', '云林'], '嘉義' => ['chiayi', '嘉义'],
            '台南' => ['tainan', '臺南'], '高雄' => ['kaohsiung'], '屏東' => ['pingtung', '屏东'],
            '宜蘭' => ['yilan', 'ilan', '宜兰'], '花蓮' => ['hualien', '花莲'], '台東' => ['taitung', '臺東'],
        ];
        $out = [];
        foreach ($map as $k => $names) {
            $out[mb_strtolower($k)] = $k;
            foreach ($names as $n) {
                $out[mb_strtolower($n)] = $k;
            }
        }
        self::$_twAlias = $out;
        return $out;
    }

    public static function cnAlias(): array
    {
        if (self::$_cnAlias !== null) {
            return self::$_cnAlias;
        }
        $map = [
            '北京' => ['beijing'], '上海' => ['shanghai'], '天津' => ['tianjin'],
            '重庆' => ['chongqing', '重慶'], '广东' => ['guangdong', '廣東', 'guangzhou', '广州'],
            '江苏' => ['jiangsu', '江蘇', 'nanjing', '南京', 'suzhou', '苏州', '蘇州'],
            '浙江' => ['zhejiang', '浙江', 'hangzhou', '杭州', 'ningbo', '寧波', '宁波'],
            '山东' => ['shandong', '山東', 'qingdao', '青島', '青岛'],
            '福建' => ['fujian', '福建', 'xiamen', '廈門', '厦门'],
            '辽宁' => ['liaoning', '遼寧', 'shenyang', '沈陽', '沈阳', 'dalian', '大連', '大连'],
            '湖北' => ['hubei', '湖北', 'wuhan', '武漢', '武汉'],
            '湖南' => ['hunan', '湖南', 'changsha', '長沙', '长沙'],
            '四川' => ['sichuan', '四川', 'chengdu', '成都'],
            '陕西' => ['shaanxi', '陕西', 'xian', 'xi\'an', '西安'],
            '河南' => ['henan', '河南', 'zhengzhou', '鄭州', '郑州'],
            '河北' => ['hebei', '河北'], '山西' => ['shanxi', '山西'],
            '安徽' => ['anhui', '安徽'], '江西' => ['jiangxi', '江西'],
            '吉林' => ['jilin', '吉林'], '黑龙江' => ['heilongjiang', '黑龍江'],
            '内蒙古' => ['inner mongolia', 'nei mongol', '內蒙古'],
            '新疆' => ['xinjiang', '新疆'], '云南' => ['yunnan', '雲南'],
            '贵州' => ['guizhou', '貴州'], '广西' => ['guangxi', '廣西'],
            '海南' => ['hainan', '海南'], '甘肃' => ['gansu', '甘肅'],
            '宁夏' => ['ningxia', '寧夏'], '青海' => ['qinghai', '青海'],
            '西藏' => ['tibet', 'xizang', '西藏'],
        ];
        $out = [];
        foreach ($map as $k => $names) {
            $out[mb_strtolower($k)] = $k;
            foreach ($names as $n) {
                $out[mb_strtolower($n)] = $k;
            }
        }
        self::$_cnAlias = $out;
        return $out;
    }

    public static function usAlias(): array
    {
        if (self::$_usAlias !== null) {
            return self::$_usAlias;
        }
        $states = [
            'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
            'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut',
            'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia',
            'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois',
            'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
            'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine',
            'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan',
            'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri',
            'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
            'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico',
            'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota',
            'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon',
            'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
            'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas',
            'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia',
            'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin',
            'WY' => 'Wyoming', 'DC' => 'District of Columbia',
        ];
        $out = [];
        foreach ($states as $abbr => $name) {
            $out[strtolower($abbr)] = $name;
            $out[strtolower($name)] = $name;
        }
        self::$_usAlias = $out;
        return $out;
    }

    public static function euAlias(): array
    {
        if (self::$_euAlias !== null) {
            return self::$_euAlias;
        }
        $eu_nations = [
            'DE' => 'Germany', 'FR' => 'France', 'IT' => 'Italy',
            'ES' => 'Spain', 'PL' => 'Poland', 'NL' => 'Netherlands',
            'BE' => 'Belgium', 'SE' => 'Sweden', 'AT' => 'Austria',
            'CZ' => 'Czech Republic', 'RO' => 'Romania', 'HU' => 'Hungary',
            'PT' => 'Portugal', 'FI' => 'Finland', 'SK' => 'Slovakia',
            'DK' => 'Denmark', 'GR' => 'Greece', 'IE' => 'Ireland',
            'HR' => 'Croatia', 'BG' => 'Bulgaria', 'LT' => 'Lithuania',
            'SI' => 'Slovenia', 'LV' => 'Latvia', 'EE' => 'Estonia',
            'CY' => 'Cyprus', 'LU' => 'Luxembourg', 'MT' => 'Malta',
        ];
        $out = [];
        foreach ($eu_nations as $code => $name) {
            $out[strtolower($code)] = $name;
            $out[strtolower($name)] = $name;
        }
        self::$_euAlias = $out;
        return $out;
    }

    public static function sgAlias(): array
    {
        if (self::$_sgAlias !== null) {
            return self::$_sgAlias;
        }
        $out = [
            'singapore' => 'Singapore', 'sg' => 'Singapore',
            '싱가포르' => 'Singapore',
        ];
        self::$_sgAlias = $out;
        return $out;
    }

    public static function myAlias(): array
    {
        if (self::$_myAlias !== null) {
            return self::$_myAlias;
        }
        $states = [
            'Johor' => ['johor', 'jb', 'johor bahru'],
            'Kedah' => ['kedah'],
            'Kelantan' => ['kelantan'],
            'Melaka' => ['melaka', 'malacca'],
            'Negeri Sembilan' => ['negeri sembilan', 'ns', 'n.s.'],
            'Pahang' => ['pahang'],
            'Perak' => ['perak', 'ipoh'],
            'Perlis' => ['perlis'],
            'Penang' => ['penang', 'pulau pinang', 'pg'],
            'Sabah' => ['sabah', 'kota kinabalu'],
            'Sarawak' => ['sarawak', 'kuching'],
            'Selangor' => ['selangor', 'shah alam', 'petaling jaya', 'pj'],
            'Terengganu' => ['terengganu'],
            'Kuala Lumpur' => ['kuala lumpur', 'kl', 'klang valley'],
            'Labuan' => ['labuan'],
            'Putrajaya' => ['putrajaya'],
        ];
        $out = [];
        foreach ($states as $name => $aliases) {
            $out[strtolower($name)] = $name;
            foreach ($aliases as $a) {
                $out[strtolower($a)] = $name;
            }
        }
        self::$_myAlias = $out;
        return $out;
    }

    public static function phAlias(): array
    {
        if (self::$_phAlias !== null) {
            return self::$_phAlias;
        }
        $regions = [
            'NCR' => ['ncr', 'metro manila', 'manila', '마닐라'],
            'Luzon' => ['luzon', '루손'],
            'Visayas' => ['visayas', '비사야'],
            'Mindanao' => ['mindanao', '민다나오'],
            'Cebu' => ['cebu', '세부'],
            'Batangas' => ['batangas'],
            'Calabarzon' => ['calabarzon'],
            'Laguna' => ['laguna'],
            'Cavite' => ['cavite'],
            'Pampanga' => ['pampanga', 'clark'],
            'Subic' => ['subic', 'olongapo'],
        ];
        $out = [];
        foreach ($regions as $name => $aliases) {
            $out[strtolower($name)] = $name;
            foreach ($aliases as $a) {
                $out[strtolower($a)] = $name;
            }
        }
        self::$_phAlias = $out;
        return $out;
    }

    // ── 내부 유틸 ─────────────────────────────────────────────────

    /**
     * 텍스트에서 키가 나오는지 검사. geo_ref.py _hit() 이식.
     * ASCII 는 단어 경계, 비ASCII 는 부분 문자열.
     */
    private static function hit(string $key, string $text): bool
    {
        if ($key === '') {
            return false;
        }
        $text = mb_strtolower($text);
        // ASCII 만이면 단어 경계 적용
        if (preg_match('/^[A-Za-z0-9 \-\.]+$/', $key)) {
            return (bool)preg_match('/\b' . preg_quote($key, '/') . '\b/i', $text);
        }
        return mb_strpos($text, mb_strtolower($key)) !== false;
    }

    /**
     * 국가+후보 텍스트들 → 해당하는 admin1 키 배열 반환.
     * geo_ref.py resolve_admin1_all() 이식.
     *
     * @param string   $country    'KR'|'JP'|'TW'|'CN'|'US'|'EU'|'SG'|'MY'|'PH'
     * @param string[] $candidates 후보 텍스트들
     * @return string[]
     */
    public static function resolveAdmin1All(string $country, string ...$candidates): array
    {
        [$table, $alias] = self::tableFor(mb_strtoupper($country));
        if ($table === null) {
            return [];
        }
        $found = [];
        foreach ($candidates as $cand) {
            if ($cand === '') {
                continue;
            }
            // alias 직접 매핑
            $lc = mb_strtolower($cand);
            if (isset($alias[$lc]) && isset($table[$alias[$lc]])) {
                $found[] = $alias[$lc];
                continue;
            }
            // 테이블 키들을 텍스트에서 찾음
            foreach (array_keys($table) as $k) {
                if (self::hit($k, $cand)) {
                    $found[] = $k;
                }
            }
        }
        return array_values(array_unique($found));
    }

    /**
     * geo_ref.py resolve_admin1() 이식. 첫 번째 매칭만 반환.
     */
    public static function resolveAdmin1(string $country, string ...$candidates): ?string
    {
        $all = self::resolveAdmin1All($country, ...$candidates);
        return $all[0] ?? null;
    }

    /**
     * 좌표 → 근접 admin1 후보 배열. geo_ref.py admin_area_candidates() 이식.
     *
     * @return string[]
     */
    public static function adminAreaCandidates(string $country, float $lat, float $lon, float $marginKm = 60.0): array
    {
        [$table] = self::tableFor(mb_strtoupper($country));
        if ($table === null) {
            return [];
        }
        $hits = [];
        foreach ($table as $name => $coords) {
            [$clat, $clon, $radius] = $coords;
            $d = self::haversineKm($lat, $lon, (float)$clat, (float)$clon);
            if ($d <= (float)$radius + $marginKm) {
                $hits[] = ['name' => $name, 'd' => $d];
            }
        }
        usort($hits, fn($a, $b) => $a['d'] <=> $b['d']);
        return array_column($hits, 'name');
    }

    /**
     * Haversine 공식 (km). geo_ref.py haversine_km() 이식.
     */
    public static function haversineKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $R = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }

    // ── 국가별 테이블 조회 ────────────────────────────────────────

    /**
     * 국가 코드 → [table, alias] 반환.
     * @return array{0: array|null, 1: array}
     */
    private static function tableFor(string $country): array
    {
        return match($country) {
            'KR' => [self::$KR, self::krAlias()],
            'JP' => [self::$JP, self::jpAlias()],
            'TW' => [self::$TW, self::twAlias()],
            'CN' => [[], self::cnAlias()],
            'US' => [[], self::usAlias()],
            'EU' => [[], self::euAlias()],
            'SG' => [[], self::sgAlias()],
            'MY' => [[], self::myAlias()],
            'PH' => [[], self::phAlias()],
            default => [null, []],
        };
    }

    // ── 권역 코드 · 이름표 (region_map.py 이식) ──────────────────
    // regionCode 는 경보 엔진이 "이 사건의 권역에 있는 BP" 를 고르는 축이다.
    // 비어 있으면 그 site 는 어떤 지역 사건에도 걸리지 않는다 — 조용히 빠진다.

    private const KR_REGION = [
        '서울' => 'KR-SUDOGWON', '인천' => 'KR-SUDOGWON', '경기' => 'KR-SUDOGWON',
        '대전' => 'KR-CHUNGCHEONG', '세종' => 'KR-CHUNGCHEONG',
        '충북' => 'KR-CHUNGCHEONG', '충남' => 'KR-CHUNGCHEONG',
        '광주' => 'KR-HONAM', '전북' => 'KR-HONAM', '전남' => 'KR-HONAM',
        '부산' => 'KR-YEONGNAM', '대구' => 'KR-YEONGNAM', '울산' => 'KR-YEONGNAM',
        '경북' => 'KR-YEONGNAM', '경남' => 'KR-YEONGNAM',
        '강원' => 'KR-GANGWON', '제주' => 'KR-JEJU',
    ];
    private const JP_REGION = [
        '北海道' => 'JP-HOKKAIDO',
        '青森' => 'JP-TOHOKU', '岩手' => 'JP-TOHOKU', '宮城' => 'JP-TOHOKU',
        '秋田' => 'JP-TOHOKU', '山形' => 'JP-TOHOKU', '福島' => 'JP-TOHOKU',
        '茨城' => 'JP-KANTO', '栃木' => 'JP-KANTO', '群馬' => 'JP-KANTO',
        '埼玉' => 'JP-KANTO', '千葉' => 'JP-KANTO', '東京' => 'JP-KANTO', '神奈川' => 'JP-KANTO',
        '新潟' => 'JP-CHUBU', '富山' => 'JP-CHUBU', '石川' => 'JP-CHUBU', '福井' => 'JP-CHUBU',
        '山梨' => 'JP-CHUBU', '長野' => 'JP-CHUBU', '岐阜' => 'JP-CHUBU',
        '静岡' => 'JP-CHUBU', '愛知' => 'JP-CHUBU',
        '三重' => 'JP-KANSAI', '滋賀' => 'JP-KANSAI', '京都' => 'JP-KANSAI',
        '大阪' => 'JP-KANSAI', '兵庫' => 'JP-KANSAI', '奈良' => 'JP-KANSAI', '和歌山' => 'JP-KANSAI',
        '鳥取' => 'JP-CHUGOKU', '島根' => 'JP-CHUGOKU', '岡山' => 'JP-CHUGOKU',
        '広島' => 'JP-CHUGOKU', '山口' => 'JP-CHUGOKU',
        '徳島' => 'JP-SHIKOKU', '香川' => 'JP-SHIKOKU', '愛媛' => 'JP-SHIKOKU', '高知' => 'JP-SHIKOKU',
        '福岡' => 'JP-KYUSHU-OKINAWA', '佐賀' => 'JP-KYUSHU-OKINAWA',
        '長崎' => 'JP-KYUSHU-OKINAWA', '熊本' => 'JP-KYUSHU-OKINAWA',
        '大分' => 'JP-KYUSHU-OKINAWA', '宮崎' => 'JP-KYUSHU-OKINAWA',
        '鹿児島' => 'JP-KYUSHU-OKINAWA', '沖縄' => 'JP-KYUSHU-OKINAWA',
    ];
    private const CN_REGION = [
        '辽宁' => 'CN-NORTHEAST', '吉林' => 'CN-NORTHEAST', '黑龙江' => 'CN-NORTHEAST',
        '北京' => 'CN-NORTH', '天津' => 'CN-NORTH', '河北' => 'CN-NORTH',
        '山西' => 'CN-NORTH', '内蒙古' => 'CN-NORTH',
        '上海' => 'CN-EAST', '江苏' => 'CN-EAST', '浙江' => 'CN-EAST', '安徽' => 'CN-EAST',
        '福建' => 'CN-EAST', '江西' => 'CN-EAST', '山东' => 'CN-EAST',
        '河南' => 'CN-CENTRAL-SOUTH', '湖北' => 'CN-CENTRAL-SOUTH', '湖南' => 'CN-CENTRAL-SOUTH',
        '广东' => 'CN-CENTRAL-SOUTH', '广西' => 'CN-CENTRAL-SOUTH', '海南' => 'CN-CENTRAL-SOUTH',
        '重庆' => 'CN-SOUTHWEST', '四川' => 'CN-SOUTHWEST', '贵州' => 'CN-SOUTHWEST',
        '云南' => 'CN-SOUTHWEST', '西藏' => 'CN-SOUTHWEST',
        '陕西' => 'CN-NORTHWEST', '甘肃' => 'CN-NORTHWEST', '青海' => 'CN-NORTHWEST',
        '宁夏' => 'CN-NORTHWEST', '新疆' => 'CN-NORTHWEST',
    ];
    private const TW_REGION = [
        '台北' => 'TW-NORTH', '新北' => 'TW-NORTH', '桃園' => 'TW-NORTH', '新竹' => 'TW-NORTH',
        '台中' => 'TW-CENTRAL', '苗栗' => 'TW-CENTRAL', '彰化' => 'TW-CENTRAL',
        '台南' => 'TW-SOUTH', '高雄' => 'TW-SOUTH',
    ];
    private const NAME_KO = [
        'KR-SUDOGWON' => ['Sudogwon', '수도권'], 'KR-CHUNGCHEONG' => ['Chungcheong', '충청권'],
        'KR-HONAM' => ['Honam', '호남권'], 'KR-YEONGNAM' => ['Yeongnam', '영남권'],
        'KR-GANGWON' => ['Gangwon', '강원권'], 'KR-JEJU' => ['Jeju', '제주권'],
        'JP-HOKKAIDO' => ['Hokkaido', '홋카이도'], 'JP-TOHOKU' => ['Tohoku', '도호쿠'],
        'JP-KANTO' => ['Kanto', '간토'], 'JP-CHUBU' => ['Chubu', '주부'],
        'JP-KANSAI' => ['Kansai', '간사이'], 'JP-CHUGOKU' => ['Chugoku', '주고쿠'],
        'JP-SHIKOKU' => ['Shikoku', '시코쿠'],
        'JP-KYUSHU-OKINAWA' => ['Kyushu-Okinawa', '규슈·오키나와'],
        'CN-NORTHEAST' => ['Northeast', '동북권'], 'CN-NORTH' => ['North', '화북권'],
        'CN-EAST' => ['East', '화동권'], 'CN-CENTRAL-SOUTH' => ['Central-South', '중남권'],
        'CN-SOUTHWEST' => ['Southwest', '서남권'], 'CN-NORTHWEST' => ['Northwest', '서북권'],
        'TW-NORTH' => ['North', '북부권'], 'TW-CENTRAL' => ['Central', '중부권'],
        'TW-SOUTH' => ['South', '남부권'],
    ];

    /** 권역 코드. US·EU·SG 는 권역을 쓰지 않으므로 null. */
    public static function regionFor(?string $country, ?string $admin1): ?string
    {
        $tables = ['KR' => self::KR_REGION, 'JP' => self::JP_REGION, 'CN' => self::CN_REGION, 'TW' => self::TW_REGION];
        $t = $tables[(string)$country] ?? [];
        return $t[trim((string)$admin1)] ?? null;
    }

    /** 권역 코드 → [영문, 한글] */
    public static function nameKo(): array
    {
        return self::NAME_KO;
    }
}
