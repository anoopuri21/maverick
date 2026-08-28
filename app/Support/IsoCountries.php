<?php

namespace App\Support;

class IsoCountries
{
    /**
     * ISO 3166-1 catalog keyed by numeric id (3-digit, zero-padded).
     * Congo uses 178 / CG to match the existing globe payload.
     *
     * @return array<string, array{numeric: string, iso2: string, name: string}>
     */
    public static function all(): array
    {
        static $cache = null;

        if ($cache !== null) {
            return $cache;
        }

        $rows = [
            ['004', 'AF', 'Afghanistan'],
            ['008', 'AL', 'Albania'],
            ['012', 'DZ', 'Algeria'],
            ['020', 'AD', 'Andorra'],
            ['024', 'AO', 'Angola'],
            ['028', 'AG', 'Antigua and Barbuda'],
            ['032', 'AR', 'Argentina'],
            ['051', 'AM', 'Armenia'],
            ['036', 'AU', 'Australia'],
            ['040', 'AT', 'Austria'],
            ['031', 'AZ', 'Azerbaijan'],
            ['048', 'BH', 'Bahrain'],
            ['050', 'BD', 'Bangladesh'],
            ['052', 'BB', 'Barbados'],
            ['112', 'BY', 'Belarus'],
            ['056', 'BE', 'Belgium'],
            ['084', 'BZ', 'Belize'],
            ['204', 'BJ', 'Benin'],
            ['064', 'BT', 'Bhutan'],
            ['068', 'BO', 'Bolivia'],
            ['070', 'BA', 'Bosnia and Herzegovina'],
            ['072', 'BW', 'Botswana'],
            ['076', 'BR', 'Brazil'],
            ['096', 'BN', 'Brunei'],
            ['100', 'BG', 'Bulgaria'],
            ['854', 'BF', 'Burkina Faso'],
            ['108', 'BI', 'Burundi'],
            ['116', 'KH', 'Cambodia'],
            ['120', 'CM', 'Cameroon'],
            ['124', 'CA', 'Canada'],
            ['132', 'CV', 'Cape Verde'],
            ['140', 'CF', 'Central African Republic'],
            ['148', 'TD', 'Chad'],
            ['152', 'CL', 'Chile'],
            ['156', 'CN', 'China'],
            ['170', 'CO', 'Colombia'],
            ['174', 'KM', 'Comoros'],
            ['178', 'CG', 'Congo'],
            ['180', 'CD', 'Congo (DRC)'],
            ['188', 'CR', 'Costa Rica'],
            ['191', 'HR', 'Croatia'],
            ['192', 'CU', 'Cuba'],
            ['196', 'CY', 'Cyprus'],
            ['203', 'CZ', 'Czechia'],
            ['208', 'DK', 'Denmark'],
            ['262', 'DJ', 'Djibouti'],
            ['214', 'DO', 'Dominican Republic'],
            ['218', 'EC', 'Ecuador'],
            ['818', 'EG', 'Egypt'],
            ['222', 'SV', 'El Salvador'],
            ['226', 'GQ', 'Equatorial Guinea'],
            ['232', 'ER', 'Eritrea'],
            ['233', 'EE', 'Estonia'],
            ['231', 'ET', 'Ethiopia'],
            ['242', 'FJ', 'Fiji'],
            ['246', 'FI', 'Finland'],
            ['250', 'FR', 'France'],
            ['266', 'GA', 'Gabon'],
            ['270', 'GM', 'Gambia'],
            ['268', 'GE', 'Georgia'],
            ['276', 'DE', 'Germany'],
            ['288', 'GH', 'Ghana'],
            ['300', 'GR', 'Greece'],
            ['320', 'GT', 'Guatemala'],
            ['324', 'GN', 'Guinea'],
            ['624', 'GW', 'Guinea-Bissau'],
            ['328', 'GY', 'Guyana'],
            ['332', 'HT', 'Haiti'],
            ['340', 'HN', 'Honduras'],
            ['344', 'HK', 'Hong Kong'],
            ['348', 'HU', 'Hungary'],
            ['352', 'IS', 'Iceland'],
            ['356', 'IN', 'India'],
            ['360', 'ID', 'Indonesia'],
            ['364', 'IR', 'Iran'],
            ['368', 'IQ', 'Iraq'],
            ['372', 'IE', 'Ireland'],
            ['376', 'IL', 'Israel'],
            ['380', 'IT', 'Italy'],
            ['388', 'JM', 'Jamaica'],
            ['392', 'JP', 'Japan'],
            ['400', 'JO', 'Jordan'],
            ['398', 'KZ', 'Kazakhstan'],
            ['404', 'KE', 'Kenya'],
            ['414', 'KW', 'Kuwait'],
            ['417', 'KG', 'Kyrgyzstan'],
            ['418', 'LA', 'Laos'],
            ['428', 'LV', 'Latvia'],
            ['422', 'LB', 'Lebanon'],
            ['426', 'LS', 'Lesotho'],
            ['430', 'LR', 'Liberia'],
            ['434', 'LY', 'Libya'],
            ['438', 'LI', 'Liechtenstein'],
            ['440', 'LT', 'Lithuania'],
            ['442', 'LU', 'Luxembourg'],
            ['446', 'MO', 'Macao'],
            ['450', 'MG', 'Madagascar'],
            ['454', 'MW', 'Malawi'],
            ['458', 'MY', 'Malaysia'],
            ['462', 'MV', 'Maldives'],
            ['466', 'ML', 'Mali'],
            ['470', 'MT', 'Malta'],
            ['478', 'MR', 'Mauritania'],
            ['480', 'MU', 'Mauritius'],
            ['484', 'MX', 'Mexico'],
            ['498', 'MD', 'Moldova'],
            ['492', 'MC', 'Monaco'],
            ['496', 'MN', 'Mongolia'],
            ['499', 'ME', 'Montenegro'],
            ['504', 'MA', 'Morocco'],
            ['508', 'MZ', 'Mozambique'],
            ['104', 'MM', 'Myanmar'],
            ['516', 'NA', 'Namibia'],
            ['524', 'NP', 'Nepal'],
            ['528', 'NL', 'Netherlands'],
            ['554', 'NZ', 'New Zealand'],
            ['558', 'NI', 'Nicaragua'],
            ['562', 'NE', 'Niger'],
            ['566', 'NG', 'Nigeria'],
            ['408', 'KP', 'North Korea'],
            ['807', 'MK', 'North Macedonia'],
            ['578', 'NO', 'Norway'],
            ['512', 'OM', 'Oman'],
            ['586', 'PK', 'Pakistan'],
            ['275', 'PS', 'Palestine'],
            ['591', 'PA', 'Panama'],
            ['598', 'PG', 'Papua New Guinea'],
            ['600', 'PY', 'Paraguay'],
            ['604', 'PE', 'Peru'],
            ['608', 'PH', 'Philippines'],
            ['616', 'PL', 'Poland'],
            ['620', 'PT', 'Portugal'],
            ['634', 'QA', 'Qatar'],
            ['642', 'RO', 'Romania'],
            ['643', 'RU', 'Russia'],
            ['646', 'RW', 'Rwanda'],
            ['682', 'SA', 'Saudi Arabia'],
            ['686', 'SN', 'Senegal'],
            ['688', 'RS', 'Serbia'],
            ['690', 'SC', 'Seychelles'],
            ['694', 'SL', 'Sierra Leone'],
            ['702', 'SG', 'Singapore'],
            ['703', 'SK', 'Slovakia'],
            ['705', 'SI', 'Slovenia'],
            ['706', 'SO', 'Somalia'],
            ['710', 'ZA', 'South Africa'],
            ['410', 'KR', 'South Korea'],
            ['728', 'SS', 'South Sudan'],
            ['724', 'ES', 'Spain'],
            ['144', 'LK', 'Sri Lanka'],
            ['729', 'SD', 'Sudan'],
            ['740', 'SR', 'Suriname'],
            ['752', 'SE', 'Sweden'],
            ['756', 'CH', 'Switzerland'],
            ['760', 'SY', 'Syria'],
            ['158', 'TW', 'Taiwan'],
            ['762', 'TJ', 'Tajikistan'],
            ['834', 'TZ', 'Tanzania'],
            ['764', 'TH', 'Thailand'],
            ['626', 'TL', 'Timor-Leste'],
            ['768', 'TG', 'Togo'],
            ['780', 'TT', 'Trinidad and Tobago'],
            ['788', 'TN', 'Tunisia'],
            ['792', 'TR', 'Turkey'],
            ['795', 'TM', 'Turkmenistan'],
            ['800', 'UG', 'Uganda'],
            ['804', 'UA', 'Ukraine'],
            ['784', 'AE', 'UAE'],
            ['826', 'GB', 'UK'],
            ['840', 'US', 'USA'],
            ['858', 'UY', 'Uruguay'],
            ['860', 'UZ', 'Uzbekistan'],
            ['548', 'VU', 'Vanuatu'],
            ['862', 'VE', 'Venezuela'],
            ['704', 'VN', 'Vietnam'],
            ['887', 'YE', 'Yemen'],
            ['894', 'ZM', 'Zambia'],
            ['716', 'ZW', 'Zimbabwe'],
        ];

        $cache = [];
        foreach ($rows as [$numeric, $iso2, $name]) {
            $cache[$numeric] = [
                'numeric' => $numeric,
                'iso2' => $iso2,
                'name' => $name,
            ];
        }

        return $cache;
    }

    /** @return array<string, string> numeric => display name */
    public static function options(): array
    {
        $options = [];
        foreach (self::all() as $numeric => $row) {
            $options[$numeric] = $row['name'];
        }
        asort($options, SORT_NATURAL | SORT_FLAG_CASE);

        return $options;
    }

    /** @return array{numeric: string, iso2: string, name: string}|null */
    public static function find(string $numeric): ?array
    {
        $numeric = str_pad($numeric, 3, '0', STR_PAD_LEFT);

        return self::all()[$numeric] ?? null;
    }
}
