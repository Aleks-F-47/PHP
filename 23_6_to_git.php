<?php
// sudo apt-get install php-mbstring - т.к. используется библиотека mbstring
// sudo service apache2 restart
// php -i | grep -i mbstring

function getFullnameFromParts($surname, $name, $patronymic)
{
    $result =  $surname . ' ' . $name . ' ' . $patronymic;
    return $result;
};

function getPartsFromFullname($date)
{
    $values = (explode(' ', $date));
    $keys = ['surname', 'name', 'patronymic'];
    if (count($values) == 3) {
        $ArrFullName = array_combine($keys, $values);
        return $ArrFullName;
    }
    return $ArrFullName = 'Введены неверные данные';
};

function getShortName($date) // без библиотеки mb_string
{
    $ArrFullName = getPartsFromFullname($date);

    $Name = $ArrFullName['name'];
    $SurName = $ArrFullName['surname'];
    $FirstEl = iconv('UTF-8', 'windows-1251', $SurName); //Меняем кодировку на windows-1251
    $FirstEl = substr($FirstEl, 0, 1); //Получаем требуемый(1) символ строки
    $FirstEl = iconv('windows-1251', 'UTF-8', $FirstEl); //Меняем кодировку на windows-1251
    $tchk = '.';
    $ShortName = $Name . ' ' . $FirstEl . '' . $tchk;
    return $ShortName;
};

function getPart($element, $index)
{
    $part = iconv('UTF-8', 'windows-1251', $element);
    $len = (strlen($part));
    $index_of_end = ($len - $index) - $len;
    $part = substr($part, $index_of_end, $index);
    $part = iconv('windows-1251', 'UTF-8', $part);
    return $part;
};

function getGenderFromName($date)
{
    $ArrFullName = getPartsFromFullname($date);
    switch ($ArrFullName) {
        case getPart($ArrFullName['patronymic'], 3) == 'вна' and getPart($ArrFullName['name'], 1) == 'а'
            and getPart($ArrFullName['surname'], 2) == 'ва':
            $gen = -1;
            continue;
        case getPart($ArrFullName['patronymic'], 2) == 'ич' and getPart($ArrFullName['name'], 1) == 'н'
            and getPart($ArrFullName['surname'], 1) == 'в':
            $gen = 1;
            continue;
        case getPart($ArrFullName['patronymic'], 2) == 'ич' and getPart($ArrFullName['name'], 1) == 'й'
            and getPart($ArrFullName['surname'], 1) == 'в':
            $gen = 1;
            continue;
        default:
            $gen = 0;
            break;
    }
    return $gen;
};

function getGenderDescription($date)
{
    $parse_name = array_column($date, 'fullname');
    $male_count = 0;
    $female_count  = 0;
    $XZ_count = 0;
    foreach ($parse_name as $value) {
        $gen1 = getGenderFromName($value);
        if ($gen1 == '1') {
            $male_count = $male_count + 1;
        } elseif ($gen1 == '-1') {
            $female_count = $female_count + 1;
        } elseif ($gen1 == '0') {
            $XZ_count = $XZ_count + 1;
        }
    };

    $male_count = round($male_count * 100 / count($parse_name), 2);
    $female_count = round($female_count * 100 / count($parse_name), 2);
    $XZ_count = round($XZ_count * 100 / count($parse_name), 2);

    $result_count = <<<Count_DOC
        Гендерный состав аудитории:
        ---------------------------
        Мужчины - $male_count%
        Женщины - $female_count%
        Не удалось определить - $XZ_count%
Count_DOC;
    return $result_count;
};

function getPerfectPartner($name_part_one, $name_part_two, $name_part_three, $persons_array)
{
    $full_str = getFullnameFromParts($name_part_one, $name_part_two, $name_part_three);
    $full_str_normal = mb_convert_case($full_str, MB_CASE_TITLE, "UTF-8");
    $full_str_normal_short = getShortName($full_str_normal);
    $full_str_gender = getGenderFromName($full_str_normal);
    $result_PerfectPartner = '';
    $emod = "\u{2661}"; // знак сердечка
    $ranmod = rand(0, 10000)/100;
    if ($full_str_gender ==  '0') {
        echo "Невозможно определить пол";
    } else {
        while (true) {
            $ran = rand(0, (count($persons_array) - 1));
            $parse_name = (array_column($persons_array, 'fullname'))[$ran];
            $parse_name_gender = getGenderFromName($parse_name);
            if ($parse_name_gender != '0' and $parse_name_gender != $full_str_gender) {
                $full_str_normal_short_2 = getShortName($parse_name);
                $result_PerfectPartner = <<<DOC
                $full_str_normal_short + $full_str_normal_short_2 = 
                $emod Идеально на $ranmod% $emod
DOC;
                break;
            }
        };
    };
    return $result_PerfectPartner;
};

function start_first()
{
    $a = 'Васильев';
    $b = 'Иван';
    $c = 'Иванович';
    $d = 'Васильев Иван Иванович';
    $dd = 'Васильева Анна Ивановна';
    $persons_array = [
        [
            'fullname' => 'Иванов Иван Иванович',
            'job' => 'tester',
        ],
        [
            'fullname' => 'Петрова Алиса Арийявна',
            'job' => 'frontend-developer',
        ],
        [
            'fullname' => 'Степанова Инна Степановна',
            'job' => 'frontend-developer',
        ],
        [
            'fullname' => 'Пащенков Владимирн Александрович',
            'job' => 'analyst',
        ],
        [
            'fullname' => 'Громов Александрий Иванович',
            'job' => 'fullstack-developer',
        ],
        [
            'fullname' => 'Славин Семён Сергеевич',
            'job' => 'analyst',
        ],
        [
            'fullname' => 'Цой Владимир Антонович',
            'job' => 'frontend-developer',
        ],
        [
            'fullname' => 'Быстрова Юлиана Сергеевна',
            'job' => 'PR-manager',
        ],
        [
            'fullname' => 'Васинова Антонина Сергеевна',
            'job' => 'HR-manager',
        ],
        [
            'fullname' => 'Васинов Василий Юрьевич',
            'job' => 'HR-manager',
        ],
        [
            'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
            'job' => 'analyst',
        ],
        [
            'fullname' => 'Бардо Жаклин Фёдоровна',
            'job' => 'android-developer',
        ],
        [
            'fullname' => 'Папуасов ибн Африканич',
            'job' => 'staff',
        ],
    ];

    $result_1 = getFullnameFromParts($a, $b, $c);
    echo 'getFullnameFromParts - ', $result_1;
    echo "\n";
    echo "\n";

    $result_2 = getPartsFromFullname($d);
    echo 'getPartsFromFullname - ';
    print_r($result_2);
    echo "\n";
    echo "\n";

    $result_3 = getShortName($d);
    echo 'getShortName - ';
    print_r($result_3);
    echo "\n";
    echo "\n";

    $result_4 = getGenderFromName($dd);
    echo 'getGenderFromName - ';
    print_r($result_4);
    echo "\n";
    echo "\n";

    $result_5 =  getGenderDescription($persons_array);
    echo 'getGenderDescription: ';
    echo "\n";
    print_r($result_5);
    echo "\n";
    echo "\n";

    /* $a = 'Петрова';
    $b = 'Иванна';
    $c = 'Петровна'; */
    $a = 'Васильев';
    $b = 'Иван';
    $c = 'Иванович';
    $result_6 = getPerfectPartner($a, $b, $c, $persons_array);
    echo 'getPerfectPartner: ';
    echo "\n";
    print_r($result_6);
};

start_first();