<?php

function get_rates($data_in)
{
    libxml_use_internal_errors(true);
    $error_xml = "";
    $url = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=$data_in";
    $xml = new DOMDocument();
    $xml->load($url);
    if (!$xml->schemaValidate('http://www.cbr.ru/StaticHtml/File/92172/ValCurs.xsd')) {
        $error_xml = "error";
    };
    $xml = simplexml_load_file($url);
    return array($xml, $error_xml);
};

function dates()
{
    $day_ago = date("d/m/Y", strtotime("-5 day"));
    $today = date("d/m/Y");
    return array($day_ago, $today);
};

function parse_xml_to_array($date, $rates)
{
    $con = json_encode($rates);
    $newArr = json_decode($con, true);
    $valute = [];
    $nominal = [];
    $value = [];
    $rates_out = [];

    foreach ($newArr as $sub) {
        foreach ($sub as $elem) {
            if (is_array($elem)) {
                foreach ($elem as $key22 => $elem22) {
                    if ($key22 == 'CharCode')
                        $valute[] = $elem22;
                    /* print($elem22); */
                    if ($key22 == 'Nominal')
                        $nominal[] = $elem22;
                    if ($key22 == 'Value')
                        $value[] = $elem22;
                }
            }
        }
    };

    array_push($rates_out, $date, $valute, $nominal, $value);
    return $rates_out;
};

function json_to_form($day, $day_ago)
{
    $change = [];
    $change_0 = array();
    array_push($change_0, $day[3], $day_ago[3]);
    for ($i = 0; $i < count($change_0[0]); $i++) {
        $a = str_replace(',', '.', $change_0[0][$i]);
        $b = str_replace(',', '.', $change_0[1][$i]);
        $c =  $a - $b;
        $ccc = number_format($c, 4, '.', '');
        $change[] = $ccc;
    };
    $rates_to_form[] = array("Валюта", "Номинал", "ЦБ на&ensp;{$day[0]}", "ЦБ на&ensp;{$day_ago[0]}", "Изменение");
    array_push($rates_to_form, $day[1], $day[2], $day[3], $day_ago[3], $change);
    return $rates_to_form;
};

function start()
{
    $error_message = "";

    $dates_out = dates();
    $date_today = $dates_out[1];
    $date_ago = $dates_out[0];

    $rates_day_ago = get_rates($date_ago);
    $rates_today = get_rates($date_today);

    if ($rates_day_ago[1] or $rates_today[1] == "error") {
        $error_message = "There were errors parsing or getting the XML file";
    };

    $rates_today_array = parse_xml_to_array($date_today, $rates_today[0]);
    $rates_day_ago_array = parse_xml_to_array($date_ago, $rates_day_ago[0]);
    $rates_to_table_js = json_to_form($rates_today_array, $rates_day_ago_array);
    return array($rates_to_table_js, $error_message);
};

if (isset($_POST['button1'])) {
    $rates_valute_and_error_message = start();
};
