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
const _SRC_LASTUPDATE_JSON          = 'data.json';
const _SRC_DISTRICT_MAP_JSON        = 'data.json';
const _SRC_DISTRICT_RANK_JSON       = 'data.json';
const _SRC_DISTRICT_POPULATION_JSON = 'data.json';
const _SRC_DISTRICT_STACK_JSON      = 'agency.json';
const _SRC_PATIENT_NUM_TREND_JSON   = 'agency2.json';
const _SRC_PATIENT_AGE_JSON         = 'agency3.json';
const _SRC_PCR_JSON                 = 'agency4.json';
const _UPDATE_AWARE_FILE            = 'update.txt';
const _SRC_PATIENT_PER_DAY_JSON     = 'agency5.json';
const _SRC_PATIENT_7DAYS_JSON       = 'agency6.json';
// const _PATIENT_PER_DAY_DISP_NUM     = 20;

# 2020-05-01
$yokohama_popuration_arr =
    [
        '中区' => 151604,
        '保土ケ谷区' => 205957,
        '南区' => 196340,
        '戸塚区' => 281078,
        '旭区' => 245170,
        '栄区' => 119810,
        '泉区' => 152005,
        '港北区' => 355840,
        '港南区' => 213860,
        '瀬谷区' => 121744,
        '磯子区' => 166752,
        '神奈川区' => 246275,
        '緑区' => 182957,
        '西区' => 104607,
        '都筑区' => 213257,
        '金沢区' => 197892,
        '青葉区' => 311361,
        '鶴見区' => 293958
    ];


# global
$twitter_comment = '';
$html = file_get_contents(_SRC_URL);
$web_csv_arr = get_patient_arr_from_web_csv();


# update json
update_patients_per_day_bar();
update_patients_num_trend();
update_district_population_ratio();
update_patients_age_bar();
update_district_rank_bar();
update_district_stack_bar();
update_district_map();
update_pcr_num();
update_patients_7days();


# make tweet txt
make_tweet_txt();


exit;



#
# update_patients_7days ave
#

function update_patients_7days()
{
    # get_patient_arr_from_web_csv
    $patient_arr = $GLOBALS['web_csv_arr'];

    # get array from json url
    $data_json_arr = jsonUrl2array(_SRC_PATIENT_7DAYS_JSON);

    # if unmatch last update
    $latest_date = end($patient_arr['公表日']);

    if($latest_date == $data_json_arr['date'])
    {
        echo "___no update agency7.json: 7days\n";
        return;
    }
    else
    {
        echo "update 7days\n";
    }

    # get patient_count_key_date_arr
    $patient_count_key_date_arr_s = get_patient_count_key_date_arr();

    $patient_count_key_date_arr = $patient_count_key_date_arr_s[0];
    $ymd_key_arr =  $patient_count_key_date_arr_s[1];

    # make 7 days ave
    foreach( $ymd_key_arr as $key_num => $val_ymd )
    {
        # skip for 7 days
        if($key_num<6) continue;

        $seven_days_ave_arr[$val_ymd] =
              $patient_count_key_date_arr[$ymd_key_arr[$key_num]]
            + $patient_count_key_date_arr[$ymd_key_arr[$key_num-1]]
            + $patient_count_key_date_arr[$ymd_key_arr[$key_num-2]]
            + $patient_count_key_date_arr[$ymd_key_arr[$key_num-3]]
            + $patient_count_key_date_arr[$ymd_key_arr[$key_num-4]]
            + $patient_count_key_date_arr[$ymd_key_arr[$key_num-5]]
            + $patient_count_key_date_arr[$ymd_key_arr[$key_num-6]];

        $seven_days_ave_arr[$val_ymd] = round($seven_days_ave_arr[$val_ymd] / 7); # 四捨五入

    }


    # make json
    foreach( $seven_days_ave_arr as $key_date => $val_num )
    {
        $arr_for_json['datasets'][0]['data'][] = $val_num;

        list(,$key_m,$key_d) = explode('-',$key_date);
        $arr_for_json['labels'][] = "$key_m/$key_d";
    }

    # format Y-m-d to Y/n/j
    $arr_for_json['date'] = date('Y/n/j', strtotime($key_date));


    # write to json file
    arr2writeJson($arr_for_json, _SRC_PATIENT_7DAYS_JSON);

}



#
# get_patient_count_key_date_arr
#

function get_patient_count_key_date_arr()
{
    # get_patient_arr_from_web_csv
    $patient_arr = $GLOBALS['web_csv_arr'];

    # count patient per day
    $patient_count_key_date_arr = array_count_values($patient_arr['公表日']);

    # stop flag
    $today_Ymd = date("Y-m-d");

    # input zero if empty in arr
    for($i=0;true;$i++)
    {
        $Ymd = date("Y-m-d", mktime(0, 0, 0, 2, 18+$i, 2020));

        # inputo 0, if emputy
        if($patient_count_key_date_arr[$Ymd]=='') $patient_count_key_date_arr[$Ymd] = 0;

        # use 7week ave
        $ymd_key_arr[] = $Ymd;

        if($today_Ymd==$Ymd) break;
    }
    ksort($patient_count_key_date_arr);

    return array($patient_count_key_date_arr, $ymd_key_arr);
}




#
# update_district_population_ratio
#

function update_district_population_ratio()
{
    # get_patient_district_arr_from_web
    $patient_district = get_patient_district_arr_from_web();

    # get array from json url
    $data_json_arr = jsonUrl2array(_SRC_DISTRICT_POPULATION_JSON);

    # if unmatch last update
    if($patient_district['ymd'] != $data_json_arr['cities2']['date'])
    {
        # date update for data.json->cities
        $data_json_arr['cities2']['date'] = $patient_district['ymd'];
        foreach( $patient_district as $k => $v )
        {
            # if district
            if(is_numeric($v))
                if($k != '市外')
                    $only_district_arr[$k] = (int)$v;
        }

        # calc positive numvber / 100k
        foreach( $only_district_arr as $ku => $positive_num)
        {
            foreach( $GLOBALS['yokohama_popuration_arr'] as $ku2 => $population )
            {
                if($ku == $ku2)
                {
                    $a = $positive_num/$population*100000;
                    $positive_per_popuration_arr[$ku] = (int)$a;
                    break;
                }
            }
        }

        arsort($positive_per_popuration_arr);

        $data_json_arr['cities2']['data']['labels'] = [];
        $data_json_arr['cities2']['data']['datasets'][0]['data'] = [];
        foreach( $positive_per_popuration_arr as $k => $v )
        {
            $data_json_arr['cities2']['data']['labels'][] = $k;
            $data_json_arr['cities2']['data']['datasets'][0]['data'][] = $v;
        }

        # write to json file
        arr2writeJson($data_json_arr, _SRC_DISTRICT_POPULATION_JSON);

        # echo
        echo "update data.json: district population rank\n";
    }
    else
    {
        echo "___no update data.json: district population rank\n";
    }
}






#
# update_patients_per_day_bar
#

function update_patients_per_day_bar()
{
    # get_patient_arr_from_web_csv
    $patient_arr = $GLOBALS['web_csv_arr'];


    # count patient age, status
    foreach( $patient_arr['公表日'] as $arr_num => $pub_date )
    {
        # patient status
        $patient_status = $patient_arr['患者_状態'][$arr_num];

        # change label
        if( $patient_status == '無症状' ) $patient_status = '無症状-中等症';
        elseif( $patient_status == '軽症' ) $patient_status = '無症状-中等症';
        elseif( $patient_status == '中等症' ) $patient_status = '無症状-中等症';

        # count patient status
        $pub_date_arr[$pub_date][$patient_status]++;
    }


    # data construction cange for output
    $counter_pub_date_arr2 = 0;

    $pub_date_arr2['datasets'][]['label'] = '無症状-中等症';
    $pub_date_arr2['datasets'][]['label'] = '重症';
    $pub_date_arr2['datasets'][]['label'] = '死亡';
    $pub_date_arr2['datasets'][]['label'] = '退院';
    $pub_date_arr2['datasets'][]['label'] = '調査中';

    foreach( $pub_date_arr as $pub_date_key => $pub_date_val)
    {
        $pub_date_arr2['labels'][] = date('m/d', strtotime($pub_date_key));

        foreach( $pub_date_val as $pub_date_val_key => $pub_date_val_val)
            foreach( $pub_date_arr2['datasets'] as $pub_date_arr2_k => $pub_date_arr2_val)
                if($pub_date_val_key == $pub_date_arr2_val['label'])
                    $pub_date_arr2['datasets'][$pub_date_arr2_k]['data'][$counter_pub_date_arr2] = $pub_date_val_val;

        $counter_pub_date_arr2++;
    }

    foreach( $pub_date_arr2['labels'] as $key => $date)
        foreach( $pub_date_arr2['datasets'] as $dataset_key => $dataset)
            if( $dataset['data'][$key]=='' )
                $pub_date_arr2['datasets'][$dataset_key]['data'][$key] = 0;

    ksort($pub_date_arr2['datasets'][0]['data']);
    ksort($pub_date_arr2['datasets'][1]['data']);
    ksort($pub_date_arr2['datasets'][2]['data']);
    ksort($pub_date_arr2['datasets'][3]['data']);
    ksort($pub_date_arr2['datasets'][4]['data']);


    # get_patient_num_arr_from_web : want to use date
    $positive_patiant = get_patient_num_arr_from_web();

    # get array from json url
    $data_json_arr = jsonUrl2array(_SRC_PATIENT_PER_DAY_JSON);

    # if unmatch last update betweem json and web
    if($positive_patiant['ymd'] != $data_json_arr['date'])
    {
        $pub_date_arr2['date'] = $positive_patiant['ymd'];

        # write to json file
        arr2writeJson($pub_date_arr2, _SRC_PATIENT_PER_DAY_JSON);

        # echo
        echo "update agency5.json: per day positive\n";
    }
    else
    {
        echo "___no update agency5.json: per day positive\n";
    }
}




//
// update_pcr_num
//

function update_pcr_num()
{
    # Get PCR html
    $html = $GLOBALS['html'];
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

    # extract number
    //     <td colspan="2"><p>検査実施者総数（人）　<br>（※１）＋（※２）</p></td>
    //     <td style="text-align: right">2,781</td>
    preg_match("|検査実施者総数(.*?)</table>|us",$html_pcr,$match);
    preg_match("|right\">(.*?)<|us",$match[1],$match2);
    $pcr_num = str_replace(",","",$match2[1]);

    # Get last update date
    preg_match("|（([0-9]+)月([0-9]+)日時点）|us",$html_pcr,$match);
    $lastUpDate['y'] = date('Y');
    $lastUpDate['m'] = $match[1];
    $lastUpDate['d'] = $match[2];

    $lastUpDate['ymd'] = $lastUpDate['y'].'/'.$lastUpDate['m'].'/'.$lastUpDate['d'];
    $lastUpDate['md'] = $lastUpDate['m'].'/'.$lastUpDate['d'];



    # get array from json url
    $data_json_arr = jsonUrl2array(_SRC_PCR_JSON);
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

    #
    # get positive num
    #
    # get array from json url
    $patient_num_json_arr = jsonUrl2array(_SRC_PATIENT_NUM_TREND_JSON);
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
    # get arr key x/x
    foreach( $patient_num_json_arr['labels'] as $key_of_pcr_date => $patient_status_date)
        if($lastUpDate['md'] == $patient_status_date)
            break;

    # sum from dataset using key
    foreach( $patient_num_json_arr['datasets'] as $patient_status_date)
        $pcr_date_positive_sum += $patient_status_date["data"][$key_of_pcr_date];



    # if unmatch last update
    if($data_json_arr['date'] != $lastUpDate['ymd'])
    {
        # add twitter comment
        $GLOBALS['twitter_comment'] .= "・PCR検査数({$lastUpDate['md']})\n";

        # date update for json
        $data_json_arr['date']      = $lastUpDate['ymd'];
        $data_json_arr['labels'][]  = date('m/d', strtotime($lastUpDate['md']));
        $data_json_arr['datasets'][0]['data'][] = $pcr_num - $pcr_date_positive_sum;
        $data_json_arr['datasets'][1]['data'][] = $pcr_date_positive_sum;

        # write to json file
        arr2writeJson($data_json_arr, _SRC_PCR_JSON);

        # echo
        echo "update agency4.json: pcr\n";
    }
    else
    {
        echo "___no update agency4.json: pcr\n";
    }
}



//
// make_tweet_txt
//

function make_tweet_txt()
{
    if($GLOBALS['twitter_comment'] != ''){

        $tweet_txt = "更新：
{$GLOBALS['twitter_comment']}
#横浜市 #新型コロナ
#COVID19 #yokohama

https://covid19.yokohama";

        file_put_contents(_UPDATE_AWARE_FILE, $tweet_txt);

        update_lastupdate();
    }
}


//
// update_lastupdate
//

function update_lastupdate()
{
    $data_json_arr = jsonUrl2array(_SRC_LASTUPDATE_JSON);
    $data_json_arr['lastUpdate'] = date("Y/m/d H:i");
    arr2writeJson($data_json_arr, _SRC_LASTUPDATE_JSON);
}






#
# update_district_map
#

function update_district_map()
{
    # get_patient_district_arr_from_web
    $patient_district = get_patient_district_arr_from_web();

    # get array from json url
    $data_json_arr = jsonUrl2array(_SRC_DISTRICT_MAP_JSON);

    # if unmatch last update
    if($patient_district['ymd'] != $data_json_arr['patients']['date'])
    {

        # date update for data.json->patients
        $data_json_arr['patients']['date'] = $patient_district['ymd'];
        $data_json_arr['patients']['data'] = [];
        foreach( $patient_district as $k => $v )
        {
            # if district
            if(is_numeric($v))
            {
                # calc positive numvber / 100k
                foreach( $GLOBALS['yokohama_popuration_arr'] as $ku2 => $population )
                {
                    if($k == $ku2)
                    {
                        $a = $v/$population*100000;
                        $v = (int)$a;
                        break;
                    }
                }

                # loop patients time
                for($i=1;$i<=$v;$i++)
                {
                    $data_json_arr['patients']['data'][] = array('居住地' => $k);
                }
            }
        }

        # write to json file
        arr2writeJson($data_json_arr, _SRC_DISTRICT_MAP_JSON);

        # echo
        echo "update data.json: map\n";
    }
    else
    {
        echo "___no update data.json: map\n";
    }
}



#
# update_patients_age_bar
#

function update_patients_age_bar()
{
    # get_patient_arr_from_web_csv
    $patient_arr = $GLOBALS['web_csv_arr'];

    # count patient age, status
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

    # merge label 無症状-中等症
    foreach( $patient_status as $status_label => $status_arr)
    {
        if($status_label == '調査中')     continue;
        elseif($status_label == '重症')   continue;
        elseif($status_label == '死亡')   continue;
        elseif($status_label == '退院')   continue;

        foreach( $status_arr as $label => $status_num)
            $patient_status['無症状-中等症'][$label] += $status_num;
    }
    //
    // Array
    // (
    //     [退院] => Array
    //         (
    //             [60代] => 10
    //             [30代] => 7
    //             [50代] => 14
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

    # get_patient_num_arr_from_web : want to use date
    $positive_patiant = get_patient_num_arr_from_web();

    # get array from json url
    $data_json_arr = jsonUrl2array(_SRC_PATIENT_AGE_JSON);

    # if unmatch last update betweem json and web
    if($positive_patiant['ymd'] != $data_json_arr['date'])
    {
        # add twitter comment
//         $GLOBALS['twitter_comment'] .= "・患者年代別 陽性確定時の症状({$positive_patiant['md']}時点)\n";

        # update date
        $data_json_arr['date'] = $positive_patiant['ymd'];

        # update json
        foreach( $data_json_arr['datasets'] as $key => $datasets_arr)
        {
            # data reset
            $data_json_arr['datasets'][$key]['data'] = [];

            # 退院
            $status_label = $datasets_arr['label'];

            foreach( $data_json_arr['labels'] as $age_label)
            {
                if(isset($patient_status[$status_label][$age_label]))
                    $data_json_arr['datasets'][$key]['data'][] = $patient_status[$status_label][$age_label];
                else
                    $data_json_arr['datasets'][$key]['data'][] = 0;
            }
        }

        # write to json file
        arr2writeJson($data_json_arr, _SRC_PATIENT_AGE_JSON);

        # echo
        echo "update agency3.json: age\n";
    }
    else
    {
        echo "___no update agency3.json: age\n";
    }
}




//
// get_patient_arr_from_web_csv
//

function get_patient_arr_from_web_csv()
{
    # get html of yokohama web site
    $html = $GLOBALS['html'];

    # get patients csv
    preg_match("|<a class=\"csv\" href=\"(.*?)\">陽性患者の発生状況のオープンデータ|us",$html,$match);
    $_SRC_CSV_URL = "https://www.city.yokohama.lg.jp/city-info/koho-kocho/koho/topics/".$match[1];


//     # last update day
//     foreach( $patient_arr['公表日'] as $v )
//         $ymd = $v;
//
//     $ymd = str_replace("-0", "-", $ymd);
//     $ymd = str_replace("-", "/", $ymd);
//
//     $patient_detail_arr_update['date'] = $ymd;


    # csv to arr
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

    return $patient_arr;
}





#
# update_district_rank_bar
#

function update_district_rank_bar()
{
    # get_patient_district_arr_from_web
    $patient_district = get_patient_district_arr_from_web();

    # get array from json url
    $data_json_arr = jsonUrl2array(_SRC_DISTRICT_RANK_JSON);

    # if unmatch last update
    if($patient_district['ymd'] != $data_json_arr['cities']['date'])
    {
        # add twitter comment
        $GLOBALS['twitter_comment'] .= "・区別 陽性数({$patient_district['md']})\n";

        # date update for data.json->cities
        $data_json_arr['cities']['date'] = $patient_district['ymd'];
        foreach( $patient_district as $k => $v )
        {
            # if district
            if(is_numeric($v))
            {
                $only_district_arr[$k] = (int)$v;
            }
        }
        arsort($only_district_arr);
        $data_json_arr['cities']['data']['labels'] = [];
        $data_json_arr['cities']['data']['datasets'][0]['data'] = [];
        foreach( $only_district_arr as $k => $v )
        {
            $data_json_arr['cities']['data']['labels'][] = $k;
            $data_json_arr['cities']['data']['datasets'][0]['data'][] = $v;
        }

        # write to json file
        arr2writeJson($data_json_arr, _SRC_DISTRICT_RANK_JSON);

        # echo
        echo "update agency.json: district rank\n";
    }
    else
    {
        echo "___no update agency.json: district rank\n";
    }
}





#
# update_district_stack_bar
#

function update_district_stack_bar()
{
    # get_patient_district_arr_from_web
    $patient_district = get_patient_district_arr_from_web();

    # get array from json url
    $data_json_arr = jsonUrl2array(_SRC_DISTRICT_STACK_JSON);

    # if unmatch last update
    if($patient_district['ymd'] != $data_json_arr['date'])
    {

        # date update for agency.json
        $data_json_arr['date']     = $patient_district['ymd'];
        $data_json_arr['labels'][] = date('m/d', strtotime($patient_district['md']));
        $data_json_arr['datasets'][0]['data'][] = $patient_district['鶴見区'];
        $data_json_arr['datasets'][1]['data'][] = $patient_district['神奈川区'];
        $data_json_arr['datasets'][2]['data'][] = $patient_district['西区'];
        $data_json_arr['datasets'][3]['data'][] = $patient_district['中区'];
        $data_json_arr['datasets'][4]['data'][] = $patient_district['南区'];
        $data_json_arr['datasets'][5]['data'][] = $patient_district['港南区'];
        $data_json_arr['datasets'][6]['data'][] = $patient_district['保土ケ谷区'];
        $data_json_arr['datasets'][7]['data'][] = $patient_district['旭区'];
        $data_json_arr['datasets'][8]['data'][] = $patient_district['磯子区'];
        $data_json_arr['datasets'][9]['data'][] = $patient_district['金沢区'];
        $data_json_arr['datasets'][10]['data'][] = $patient_district['港北区'];
        $data_json_arr['datasets'][11]['data'][] = $patient_district['緑区'];
        $data_json_arr['datasets'][12]['data'][] = $patient_district['青葉区'];
        $data_json_arr['datasets'][13]['data'][] = $patient_district['都筑区'];
        $data_json_arr['datasets'][14]['data'][] = $patient_district['戸塚区'];
        $data_json_arr['datasets'][15]['data'][] = $patient_district['栄区'];
        $data_json_arr['datasets'][16]['data'][] = $patient_district['泉区'];
        $data_json_arr['datasets'][17]['data'][] = $patient_district['瀬谷区'];
        $data_json_arr['datasets'][18]['data'][] = $patient_district['市外'];

        # write to json file
        arr2writeJson($data_json_arr, _SRC_DISTRICT_STACK_JSON);

        # echo
        echo "update agency.json: district stack\n";
    }
    else
    {
        echo "___no update agency.json: district stack\n";
    }
}




#
#   get_patient_district_arr_from_web()
#

function get_patient_district_arr_from_web()
{
    # get html of yokohama web site
    $html = $GLOBALS['html'];

    # extract date
    preg_match("|区別発生状況（患者住所地）（([0-9０-９]+)月([0-9０-９]+)日時点）|us",$html,$match_district);
    # 全角2半角
    $match_district[1] = mb_convert_kana($match_district[1], "n");
    $match_district[2] = mb_convert_kana($match_district[2], "n");
    $patient_district['ymd'] = date("Y").'/'.$match_district[1].'/'.$match_district[2];  // 2020/4/28
    $patient_district['md']   = $match_district[1].'/'.$match_district[2];  // 4/28

    # extract number
    preg_match("|<span>区別発生状況（患者住所地）（(.*?)<th scope=\"row\">合計</th>|us",$html,$match_district);
    # 全角2半角
    $match_district[1] = mb_convert_kana($match_district[1], "n");
    preg_match_all("|>(\d+?)人|us",$match_district[1],$match_district2);
    preg_match_all("|row\">(.+?)<|us",$match_district[1],$match_district3);
    foreach( $match_district3[1] as $key => $val)
        $patient_district[$val] = (int)$match_district2[1][$key];

    return $patient_district;
}






#
# patients status trend
#

function update_patients_num_trend()
{
    # get_patient_num_arr_from_web
    $positive_patiant = get_patient_num_arr_from_web();

    # get array from json url
    $data_json_arr = jsonUrl2array(_SRC_PATIENT_NUM_TREND_JSON);

    # if unmatch last update betweem json and web
    if($positive_patiant['ymd'] == $data_json_arr['date'])
    {
        echo "___no update agency2.json: positive number\n";
        return;
    }

    # date update for agency2.json
    $data_json_arr['date'] = $positive_patiant['ymd'];
    $data_json_arr['labels'][] = date('m/d', strtotime($positive_patiant['md']));
    $data_json_arr['datasets'][0]['data'][] = $positive_patiant['無症状から中等症'];
    $data_json_arr['datasets'][1]['data'][] = $positive_patiant['重症'];
    $data_json_arr['datasets'][2]['data'][] = $positive_patiant['死亡'];
    $data_json_arr['datasets'][3]['data'][] = $positive_patiant['退院等'];
    $data_json_arr['datasets'][4]['data'][] = $positive_patiant['調査中'];

    # write to json file
    arr2writeJson($data_json_arr, _SRC_PATIENT_NUM_TREND_JSON);

    # get patient_count_key_date_arr
    $patient_count_key_date_arr_s = get_patient_count_key_date_arr();
    $patient_count_key_date_arr = $patient_count_key_date_arr_s[0];

    # make array: day of the week, sunday: 0, saturday: 6, 2/18=2
    $today_week_num = date("w");
    $counter = 2; # start 2/18 is 2(tues.)
    foreach( $patient_count_key_date_arr as $k => $v)
    {
        # if it is day of the week
        if($today_week_num == $counter)
            $patient_count_key_date_week_arr[$k] = $v;

        # counter up
        $counter++;

        # reset counter
        if($counter == 7)
            $counter = 0;
    }
    $today_key = $k;
    $today_positive_num = $v;

    # 値ソート、降順、キー維持
    arsort($patient_count_key_date_week_arr);


    # make rank
    $rank_num = 1;
    $rank_num_serial_count = 1;
    foreach( $patient_count_key_date_week_arr as $k => $v )
    {
        if($rank_num==1)
            $rank_arr[$k] = 1;
        else
        {
            if($patient_count_key_date_week_arr[$k]==$previous_positive_num)
                $rank_num--;
            else
                $rank_num = $rank_num_serial_count;

             $rank_arr[$k] = $rank_num;
        }

        $previous_positive_num = $patient_count_key_date_week_arr[$k];
        $rank_num++;
        $rank_num_serial_count++;
    }

    $total_rank_count = $rank_num_serial_count-1;
    $today_rank = $rank_arr[$today_key];


    $week_arr = ['日曜', '月曜', '火曜', '水曜', '木曜', '金曜', '土曜'];

    $today_week_str = $week_arr[$today_week_num];


    # add twitter comment
    $GLOBALS['twitter_comment'] .= "・陽性数({$positive_patiant['md']}, {$today_positive_num}人, {$today_week_str}{$today_rank}/{$total_rank_count}位)\n";


    # echo
    echo "update agency2.json: positive number\n";

}




#
#   get_patient_district_arr_from_web()
#

function get_patient_num_arr_from_web()
{
    # get html of yokohama web site
    $html = $GLOBALS['html'];

    # last update
    preg_match("|陽性患者の状況（([0-9]+)月([0-9]+)日時点）|us",$html,$match);
    $positive_patiant['ymd'] = date("Y").'/'.$match[1].'/'.$match[2];  // 2020/4/28
    $positive_patiant['md']   = $match[1].'/'.$match[2];  // 4/28

    # make number
    preg_match("|<caption>陽性患者の状況</caption>(.*?)</table><br>|us",$html,$match);
    preg_match_all("|>(\d+?)人|us",$match[1],$match2);
    preg_match_all("|row\">(.+?)<|us",$match[1],$match3);
    foreach( $match3[1] as $key => $val)
        $positive_patiant[$val] = (int)$match2[1][$key];
    $positive_patiant['無症状から中等症'] = $positive_patiant['無症状'] + $positive_patiant['軽症'] + $positive_patiant['中等症'];

    return $positive_patiant;
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

