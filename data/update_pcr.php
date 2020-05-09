<?php

#
#
# update php script. update .json
# $ php update.php
#
#

# timezone
date_default_timezone_set('Asia/Tokyo');

# Define
const _SRC_URL = 'https://www.city.yokohama.lg.jp/city-info/koho-kocho/koho/topics/corona-data.html';
const _SRC_PCR_JSON = 'agency4.json';
const _UPDATE_AWARE_FILE = 'update.txt';
const _SRC_PATIENT_STATUS_JSON = 'agency2.json';



#
# update flag
#

$update = false;



#
# Get PCR html
#

$html = file_get_contents(_SRC_URL);
preg_match("|<span>ＰＣＲ検査数</span>(.*?)</table>|us",$html,$match);
$html_pcr = $match[0];

// <span>ＰＣＲ検査数</span></h2></div></div>
// <div class="t-box2">
// <table width="80%" class="table01">
//   <caption>検査実施状況（累計）　（4月26日時点）</caption>
//   <tr>
//     <th class="center top" colspan="2" style="width: 60%" scope="col">&nbsp;</th>
//     <th class="center top" scope="col">累積</th>
//   </tr>
//   <tr>
//     <td rowspan="2">衛生研究所</td>
//     <td><p>検査実施者数（人）<br>（※１）</p></td>
//     <td style="text-align: right">1,780</td>
//   </tr>
//   <tr>
//     <td>検査実施件数（件）　</td>
//     <td style="text-align: right">2,144</td>
//   </tr>
//   <tr>
//     <td colspan="2"><p>医療機関での検査実施者数（民間検査機関等）（人）<br>（※２）</p></td>
//     <td style="text-align: right">1,001</td>
//   </tr>
//   <tr>
//     <td colspan="2"><p>検査実施者総数（人）　<br>（※１）＋（※２）</p></td>
//     <td style="text-align: right">2,781</td>
//   </tr>
// </table>


#
# Existing data
#

# data.json
$data_json       = file_get_contents(_SRC_PCR_JSON);
$data_arr_exist  = json_decode($data_json, true);
$data_arr_update = $data_arr_exist;

// $data_arr_update
//
// Array
// (
//     [date] => 2020/5/2
//     [labels] => Array
//         (
//             [0] => 4/26
//         )
//
//     [datasets] => Array
//         (
//             [0] => Array
//                 (
//                     [label] => 陰性
//                     [data] => Array
//                         (
//                             [0] => 2
//                         )
//
//                 )
//
//             [1] => Array
//                 (
//                     [label] => 陽性
//                     [data] => Array
//                         (
//                             [0] => 8
//                         )
//
//                 )
//
//         )
//
// )


# agency2.json : get total positive number
$patient_status_json       = file_get_contents(_SRC_PATIENT_STATUS_JSON);
$patient_status_arr_exist  = json_decode($patient_status_json, true);

// Array
// (
//     [date] => 2020/5/8
//     [labels] => Array
//         (
//             [0] => 4/27
//             [1] => 4/28
//             [2] => 4/29
//             [3] => 4/30
//             [4] => 5/1
//             [5] => 5/2
//             [6] => 5/3
//             [7] => 5/4
//             [8] => 5/5
//             [9] => 5/6
//             [10] => 5/7
//             [11] => 5/8
//         )
//
//     [datasets] => Array
//         (
//             [0] => Array
//                 (
//                     [label] => 無症状-中等症
//                     [data] => Array
//                         (
//                             [0] => 254
//                             [1] => 247
//                             [2] => 261




#
# Make data
#


# Get last update date
preg_match("|（([0-9]+)月([0-9]+)日時点）|us",$html_pcr,$match);

$lastUpDate['y'] = date('Y');
$lastUpDate['m'] = $match[1];
$lastUpDate['d'] = $match[2];

$lastUpDate['ymd'] = $lastUpDate['y'].'/'.$lastUpDate['m'].'/'.$lastUpDate['d'];
$lastUpDate['md'] = $lastUpDate['m'].'/'.$lastUpDate['d'];


# extract number
//     <td colspan="2"><p>検査実施者総数（人）　<br>（※１）＋（※２）</p></td>
//     <td style="text-align: right">2,781</td>

preg_match("|検査実施者総数(.*?)</table>|us",$html_pcr,$match);
preg_match("|right\">(.*?)<|us",$match[1],$match2);

$pcr_num = str_replace(",","",$match2[1]);


#
# get positive num
#

# get arr key 5/3
foreach( $patient_status_arr_exist['labels'] as $key_of_pcr_date => $patient_status_date)
    if($lastUpDate['md'] == $patient_status_date)
        break;

# sum from dataset using key
foreach( $patient_status_arr_exist['datasets'] as $patient_status_date)
    $pcr_date_positive_sum += $patient_status_date["data"][$key_of_pcr_date];




#
# Compare & update
#

# agency4.json
# if unmatch last update
if($data_arr_exist['date'] != $lastUpDate['ymd'])
{
    # wite json
    $update = true;

    # add twitter comment
    $twitter_comment .= '　・横浜市 PCR検査数、結果数
';

    # date update for json
    $data_arr_update['date']      = $lastUpDate['ymd'];
    $data_arr_update['labels'][]  = $lastUpDate['md'];
    $data_arr_update['datasets'][0]['data'][] = $pcr_num - $pcr_date_positive_sum;
    $data_arr_update['datasets'][1]['data'][] = $pcr_date_positive_sum;

    echo "Update agency4.json: PCR number\n";
}


#
# write to Json
#


# wite json
if($update){

    # write agency3.json
    $pcr_json = json_encode($data_arr_update, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    file_put_contents(_SRC_PCR_JSON, $pcr_json);

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

