<?php

#
#
# update php script. update data.json
# $ php update.php
#
#



# Define
const _SRC_URL = 'https://www.city.yokohama.lg.jp/city-info/koho-kocho/koho/topics/corona-data.html';
const _SRC_DATA_JSON = 'data.json';
const _DST_DATA_JSON = 'data.json';



#
# update flag
#

$update = false;



#
# Get Yokohama html
#

$html = file_get_contents(_SRC_URL);

# Get last update date
preg_match("|最終更新日 ([0-9]+)年([0-9]+)月([0-9]+)日|us",$html,$match);

$lastUpDate['y'] = $match[1];
$lastUpDate['m'] = $match[2];
$lastUpDate['d'] = $match[3];
$lastUpDate['ymd'] = $match[1].'/'.$match[2].'/'.$match[3];



#
# Existing data
#

$data_json       = file_get_contents(_SRC_DATA_JSON);
$data_arr_exist  = json_decode($data_json, true);
$data_arr_update = $data_arr_exist;



#
# Make data of Positive Patients 陽性患者の状況（4月28日時点）
#

# extract html
preg_match("|陽性患者の状況（([0-9]+)月([0-9]+)日時点）|us",$html,$match);

# extract date
$positive_patiant['date'] = $lastUpDate['y'].'/'.$match[1].'/'.$match[2];  // 2020/4/28

# extract number
preg_match("|<caption>陽性患者の状況</caption>(.*?)</table><br>|us",$html,$match);
preg_match_all("|>(\d+?)人|us",$match[1],$match2);
preg_match_all("|row\">(.+?)<|us",$match[1],$match3);

foreach( $match3[1] as $key => $val)
    $positive_patiant[$val] = (int)$match2[1][$key];

$positive_patiant['無症状から中等症'] = $positive_patiant['無症状'] + $positive_patiant['軽症'] + $positive_patiant['中等症'];



#
# Compare & update
#

# if unmatch last update
if($positive_patiant['date'] != $data_arr_exist['main_summary']['children'][0]['date'])
{
    # wite json
    $update = true;

    # date update
    $data_arr_update['main_summary']['children'][0]['date']  = $positive_patiant['date'];
    $data_arr_update['main_summary']['children'][0]['value'] = $positive_patiant['陽性患者数'];
    $data_arr_update['main_summary']['children'][0]['children'][0]['value'] = $positive_patiant['調査中'];
    $data_arr_update['main_summary']['children'][0]['children'][0]['children'][0]['value'] = $positive_patiant['無症状から中等症'];
    $data_arr_update['main_summary']['children'][0]['children'][0]['children'][1]['value'] = $positive_patiant['重症'];
    $data_arr_update['main_summary']['children'][0]['children'][1]['value'] = $positive_patiant['死亡'];
    $data_arr_update['main_summary']['children'][0]['children'][2]['value'] = $positive_patiant['退院'];

    echo "update positive patioen num\n";
}



#
# write to JOSN
#


# wite json
if($update){

    # update date
    $data_arr_update['lastUpdate'] = $lastUpDate['ymd'];

    # write
    $data_json = json_encode($data_arr_update, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    file_put_contents(_DST_DATA_JSON, $data_json);

}
















