<?php

#
#
# update .json
# $ php update.php
#
#

# error control
error_reporting(E_ALL & ~E_NOTICE);

# timezone
date_default_timezone_set('Asia/Tokyo');

# const
const _SRC_URL = 'https://www.city.yokohama.lg.jp/city-info/koho-kocho/koho/topics/corona-data.html';
const _SRC_DATA_JSON = 'data.json';
const _SRC_PATIENT_DISTRICT_JSON = 'agency.json';
const _SRC_PATIENT_STATUS_JSON = 'agency2.json';
const _SRC_PATIENT_DETAIL_JSON = 'agency3.json';
const _UPDATE_AWARE_FILE = 'update.txt';

# global
$twitter_comment = '';
$html = file_get_contents(_SRC_URL);



patients_status_trend();

echo "$twitter_comment";

exit;



#
# patients status trend
#

function patients_status_trend()
{
    # get html of yokohama web site
    $html = $GLOBALS['html'];

    # last update
    preg_match("|陽性患者の状況（([0-9]+)月([0-9]+)日時点）|us",$html,$match);
    $positive_patiant['date'] = date("Y").'/'.$match[1].'/'.$match[2];  // 2020/4/28
    $positive_patiant['md']   = $match[1].'/'.$match[2];  // 4/28

    # make number
    preg_match("|<caption>陽性患者の状況</caption>(.*?)</table><br>|us",$html,$match);
    preg_match_all("|>(\d+?)人|us",$match[1],$match2);
    preg_match_all("|row\">(.+?)<|us",$match[1],$match3);
    foreach( $match3[1] as $key => $val)
        $positive_patiant[$val] = (int)$match2[1][$key];
    $positive_patiant['無症状から中等症'] = $positive_patiant['無症状'] + $positive_patiant['軽症'] + $positive_patiant['中等症'];

    # get array from json url
    $data_json_arr = jsonUrl2array(_SRC_PATIENT_STATUS_JSON);

    # if unmatch last update betweem json and web
    if($positive_patiant['date'] != $data_json_arr['date'])
    {
        # add twitter comment
        $GLOBALS['twitter_comment'] .= "　・横浜市 陽性患者数、状況\n";

        # date update for agency2.json
        $data_json_arr['date'] = $positive_patiant['date'];
        $data_json_arr['labels'][] = $positive_patiant['md'];
        $data_json_arr['datasets'][0]['data'][] = $positive_patiant['無症状から中等症'];
        $data_json_arr['datasets'][1]['data'][] = $positive_patiant['重症'];
        $data_json_arr['datasets'][2]['data'][] = $positive_patiant['死亡'];
        $data_json_arr['datasets'][3]['data'][] = $positive_patiant['退院等'];
        $data_json_arr['datasets'][4]['data'][] = $positive_patiant['調査中'];

        # write to json file
        arr2writeJson($data_json_arr, _SRC_PATIENT_STATUS_JSON);

        # echo
        echo "update agency2.json: positive number\n";
    }
    else
    {
        echo "___no update agency2.json: positive number\n";
    }


}





#
# jsonUrl2array
#

function jsonUrl2array($json_url)
{
    $json  = file_get_contents($json_url);
    return json_decode($json, true);
}



#
# arr2writeJson
#

function arr2writeJson($arr,$json_url)
{
    $json = json_encode($arr, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    file_put_contents($json_url, $json);
}


//
// #
// # Get Yokohama html
// #
//
//
// # Get last update date
// preg_match("|最終更新日 ([0-9]+)年([0-9]+)月([0-9]+)日|us",$html,$match);
//
// $lastUpDate['y'] = $match[1];
// $lastUpDate['m'] = $match[2];
// $lastUpDate['d'] = $match[3];
// $lastUpDate['ymd'] = $match[1].'/'.$match[2].'/'.$match[3];

# get patients csv
preg_match("|<a class=\"csv\" href=\"(.*?)\">陽性患者の発生状況のオープンデータ|us",$html,$match);
$_SRC_CSV_URL = "https://www.city.yokohama.lg.jp/city-info/koho-kocho/koho/topics/".$match[1];




#
# Existing data
#




# agency.json
$patient_district_json       = file_get_contents(_SRC_PATIENT_DISTRICT_JSON);
$patient_district_arr_exist  = json_decode($patient_district_json, true);
$patient_district_arr_update = $patient_district_arr_exist;

# agency3.json
$patient_detail_json       = file_get_contents(_SRC_PATIENT_DETAIL_JSON);
$patient_detail_arr_exist  = json_decode($patient_detail_json, true);
$patient_detail_arr_update = $patient_detail_arr_exist;




#
# csv to arr
#

$csv = file($_SRC_CSV_URL);
foreach( $csv as $key => $line)
{

    $line = rtrim($line);

    # line 0 is label
    if($key==0)
    {
        $patient_arr_label = explode(',', $line);
    }
    else
    {
        # make arr
        $patient_arr_items = explode(',', $line);

        # item into label
        foreach( $patient_arr_label as $label_num => $label )
        {
            $patient_arr[$label][] = $patient_arr_items[$label_num];
        }
    }
}

// Array
// (
//     [0] => No
//     [1] => 全国地方自治体コード
//     [2] => 都道府県名
//     [3] => 市区町村名
//     [4] => 公表日
//     [5] => 患者_年代
//     [6] => 患者_状態
//     [7] => 患者_退院済フラグ
//
// )







#
# Make data of Patients district 区別発生状況（患者所在地）（4月24日時点）
#

# extract html
preg_match("|区別発生状況（患者所在地）（([0-9]+)月([0-9]+)日時点）|us",$html,$match_district);

# extract date
$patient_district['date'] = date("Y").'/'.$match_district[1].'/'.$match_district[2];  // 2020/4/28
$patient_district['md']   = $match_district[1].'/'.$match_district[2];  // 4/28

# extract number
preg_match("|<span>区別発生状況（患者所在地）(.*?)<th scope=\"row\">合計</th>|us",$html,$match_district);
preg_match_all("|>(\d+?)人|us",$match_district[1],$match_district2);
preg_match_all("|row\">(.+?)<|us",$match_district[1],$match_district3);

foreach( $match_district3[1] as $key => $val)
    $patient_district[$val] = (int)$match_district2[1][$key];




#
# count patient age, status
#

foreach( $patient_arr['患者_状態'] as $arr_num => $status )
{
    $patient_age = $patient_arr['患者_年代'][$arr_num];
    $patient_status[$status][$patient_age]++;
}

// Array
// (
//     [退院] => Array
//         (
//             [60代] => 10
//             [30代] => 7
//         )
//     [死亡] => Array
//         (
//             [90代] => 3


#
# merge 無症状-中等症
#

foreach( $patient_status as $status_label => $status_arr)
{

    if($status_label == '調査中')     continue;
    elseif($status_label == '重症')   continue;
    elseif($status_label == '死亡')   continue;
    elseif($status_label == '退院')   continue;

    foreach( $status_arr as $label => $status_num)
        $patient_status['無症状-中等症'][$label] += $status_num;

}

//     print_r($patient_status);exit;
//
// Array
// (
//     [退院] => Array
//         (
//             [60代] => 10
//             [30代] => 7
//             [50代] => 14

//
//
// 非公表
// 幼児
// 10歳未満
// 10代
// 20代
// 30代
// 40代
// 50代
// 60代
// 70代
// 80代
// 90代




#
# Compare & update
#

# data.json
# if unmatch last update
if($positive_patiant['date'] != $data_arr_exist['main_summary']['children'][0]['date'])
{
    # add twitter comment
    $twitter_comment .= '　・横浜市 陽性患者数、状況
';

    #
    # update arr
    #

    foreach( $patient_detail_arr_update['datasets'] as $key => $datasets_arr)
    {
        # data reset
        $patient_detail_arr_update['datasets'][$key]['data'] = [];

        # 退院
        $status_label = $datasets_arr['label'];

                                                         # 10代
        foreach( $patient_detail_arr_update['labels'] as $age_label)
        {
            if(isset($patient_status[$status_label][$age_label]))
                $patient_detail_arr_update['datasets'][$key]['data'][] = $patient_status[$status_label][$age_label];
            else
                $patient_detail_arr_update['datasets'][$key]['data'][] = 0;
        }
    }

    # last update day
    foreach( $patient_arr['公表日'] as $v )
        $ymd = $v;

    $ymd = str_replace("-0", "-", $ymd);
    $ymd = str_replace("-", "/", $ymd);

    $patient_detail_arr_update['date'] = $ymd;



    echo "1 update positive patioen num: data.json, agency2.json\n";
}


# agency.json
# if unmatch last update
if($patient_district['date'] != $patient_district_arr_exist['date'])
{
    # wite json
    $update_flag = true;

    # add twitter comment
    $twitter_comment .= '　・横浜市 区別の陽性患者数
';

    # date update for agency.json
    $patient_district_arr_update['date']     = $patient_district['date'];
    $patient_district_arr_update['labels'][] = $patient_district['md'];
    $patient_district_arr_update['datasets'][0]['data'][] = $patient_district['鶴見区'];
    $patient_district_arr_update['datasets'][1]['data'][] = $patient_district['神奈川区'];
    $patient_district_arr_update['datasets'][2]['data'][] = $patient_district['西区'];
    $patient_district_arr_update['datasets'][3]['data'][] = $patient_district['中区'];
    $patient_district_arr_update['datasets'][4]['data'][] = $patient_district['南区'];
    $patient_district_arr_update['datasets'][5]['data'][] = $patient_district['港南区'];
    $patient_district_arr_update['datasets'][6]['data'][] = $patient_district['保土ケ谷区'];
    $patient_district_arr_update['datasets'][7]['data'][] = $patient_district['旭区'];
    $patient_district_arr_update['datasets'][8]['data'][] = $patient_district['磯子区'];
    $patient_district_arr_update['datasets'][9]['data'][] = $patient_district['金沢区'];
    $patient_district_arr_update['datasets'][10]['data'][] = $patient_district['港北区'];
    $patient_district_arr_update['datasets'][11]['data'][] = $patient_district['緑区'];
    $patient_district_arr_update['datasets'][12]['data'][] = $patient_district['青葉区'];
    $patient_district_arr_update['datasets'][13]['data'][] = $patient_district['都筑区'];
    $patient_district_arr_update['datasets'][14]['data'][] = $patient_district['戸塚区'];
    $patient_district_arr_update['datasets'][15]['data'][] = $patient_district['栄区'];
    $patient_district_arr_update['datasets'][16]['data'][] = $patient_district['泉区'];
    $patient_district_arr_update['datasets'][17]['data'][] = $patient_district['瀬谷区'];
    $patient_district_arr_update['datasets'][18]['data'][] = $patient_district['市外'];

    # date update for data.json->patients
    $data_arr_update['patients']['date'] = $patient_district['date'];
    $data_arr_update['patients']['data'] = [];
    foreach( $patient_district as $k => $v )
    {
        # if district
        if(is_numeric($v))
        {
            # loop patients time
            for($i=1;$i<=$v;$i++)
            {
                $data_arr_update['patients']['data'][] = array('居住地' => $k);
            }
        }
    }

    # date update for data.json->cities
    $data_arr_update['cities']['date'] = $patient_district['date'];
    foreach( $patient_district as $k => $v )
    {
        # if district
        if(is_numeric($v))
        {
            $only_district_arr[$k] = (int)$v;
        }
    }
    arsort($only_district_arr);
    $data_arr_update['cities']['data']['labels'] = [];
    $data_arr_update['cities']['data']['datasets'][0]['data'] = [];
    foreach( $only_district_arr as $k => $v )
    {
        $data_arr_update['cities']['data']['labels'][] = $k;
        $data_arr_update['cities']['data']['datasets'][0]['data'][] = $v;
    }

    echo "2 update district\n";
}





#
# write to Json
#


# wite json
if($update_flag){




    # write agency2.json
    $patient_status_json = json_encode($patient_status_arr_update, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    file_put_contents(_SRC_PATIENT_STATUS_JSON, $patient_status_json);

    # write agency.json
    $patient_district_json = json_encode($patient_district_arr_update, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    file_put_contents(_SRC_PATIENT_DISTRICT_JSON, $patient_district_json);

    # write agency3.json
    $patient_detail_json = json_encode($patient_detail_arr_update, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    file_put_contents(_SRC_PATIENT_DETAIL_JSON, $patient_detail_json);


    # for git.sh

$tweet_txt = "${lastUpDate['ymd']}時点のデータに更新しました。
{$twitter_comment}
#横浜市 #新型コロナ
#COVID19 #yokohama

https://covid19.yokohama";

    file_put_contents(_UPDATE_AWARE_FILE, $tweet_txt);
}
else
{
    echo "no update\n";
}

