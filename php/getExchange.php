<?php 
    $key = "";

    // 환율 키 가져오기
    $sql_2 = "SELECT authkeys FROM keyslist WHERE call_id = 'exchage';";
    // 인증키 가져오기
    $key = $pdo->query($sql_2);
    $key = $key->fetch();

    // var_dump($key);
    
    const url = "https://www.koreaexim.go.kr/site/program/financial/exchangeJSON";
    $searchdate = "";

    // 날짜구하기 시작
    $today = new DateTime();
    $print_today = $today->format("Y-m-d");

    $interval_lastday = new DateInterval("P1D"); // 어제 인터벌

    $weekInterval = ["P1D", "P2D", "P3D", "P4D"];

    $todayLast1D = [];
    $todayLast2D = [];
    $todayLast3D = [];
    $todayLast4D = [];

    $todayLastList = [$todayLast1D, $todayLast2D, $todayLast3D, $todayLast4D];

    // 오늘이 휴일이면 직전휴일로 되돌리기
    $dayCheck = $today->format("D");
    while ($dayCheck == "Sat" || $dayCheck == "Sun") {
        // 어제가 토요일이나 일요일일 경우
        $today->sub($interval_lastday); // 일단 하루 빼
        $dayCheck = $today->format("D");

        if ($dayCheck != "Sat" && $dayCheck != "Sun") {
            // 오늘이 토요일, 일요일이 아닐 경우
            break;
        }
    }
    $print_today = $today->format("Y-m-d");

    for ($i = 0; $i < 4; $i++) { // 결과 확인 후 clone 쓰든가 해야함.
        $yesterday = clone $today;
        $interval = new DateInterval($weekInterval[$i]);
        $todayLastList[$i] = ($yesterday->sub($interval))->format("Y-m-d");
        // var_dump($todayLastList[$i]);
    }
    // 여기까지

    $request_url = url . "?authkey=" . $key["authkeys"] . "&data=AP01&searchdate=" . $print_today;
    $request_url_lastDay = url . "?authkey=" . $key["authkeys"] . "&data=AP01&searchdate=";

    // 오늘자료 요청
    $exchangeData = file_get_contents($request_url);
    $exchangeData = json_decode($exchangeData, true);

    $exData1D=[];
    $exData2D=[];
    $exData3D=[];
    $exData4D=[];

    // 어제부터의 자료가 담길 변수
    $exchangeDataLastDay = [$exData1D, $exData2D, $exData3D, $exData4D];

    // 어제날짜부터 4일전까지 자료 요청
    for ($i = 0; $i < 4; $i++) {
        $send_url = $request_url_lastDay . $todayLastList[$i];

        $tempData = file_get_contents($send_url);
        $tempData = json_decode($tempData, true);

        $exchangeDataLastDay[$i] = $tempData;
    }

    //cur_unit      통화코드
    //cur_nm        국가,통화명
    //ttb           송금받을때
    //tts           송금보낼때
    //deal_bas_r    매매기준율

    $targetCountury_usd = "USD";
    $targetCountury_jpn = "JPY(100)";
    $targetCountury_chn = "CNH";

    $exchangeRateTTB_usd = [];
    $exchangeRateTTB_jpn = [];
    $exchangeRateTTB_chn = [];

    $exchangeRateTTS_usd = [];
    $exchangeRateTTS_jpn = [];
    $exchangeRateTTS_chn = [];

    $exchangeRateRef_usd = [];
    $exchangeRateRef_jpn = [];
    $exchangeRateRef_chn = [];

    function findData (String $country, String $what, array $exchangeData, array $exDataLastDay): ?array {
        // 나라코드와 어떤자료를 원하는지 넣으면 배열에서 그것을 리턴함.

        $send = [];

        if (empty($exchangeData)) {

            $send[] = "0";

        } else {
            // 일단 오늘꺼 찾아서 저장
            foreach ($exchangeData as $item) {
                // item은 excangeDate의 원소 하나하나가 된다.
                // 그 원소 하나가 국가이름이랑 환율정보가 담긴 덩어리임.
                if ($item["cur_unit"] === "$country") {
                    // item[국가] === 찾을국가가 일치하면, 그 item덩어리를 가져옴.
                    // 그 배열에서 찾을 것을 검색
                    $send[] = $item[$what];
                    break;
                }
            }
        }

        if (empty($exDataLastDay)) {

            $send[] = "0";

        } else {
            // 어제부터 데이터 저장
            for ($i = 0; $i < 4; $i++) {
                foreach($exDataLastDay[$i] as $item) {
                    if ($item["cur_unit"] === "$country") {
                        $send[] = $item[$what];
                        break;
                    }
                }
            }
        }

        return $send;
    }

    // 데이터 얻기
    $exchangeRateRef_usd = findData($targetCountury_usd, "deal_bas_r", $exchangeData, $exchangeDataLastDay);
    $exchangeRateRef_jpn = findData($targetCountury_jpn, "deal_bas_r", $exchangeData, $exchangeDataLastDay);
    $exchangeRateRef_chn = findData($targetCountury_chn, "deal_bas_r", $exchangeData, $exchangeDataLastDay);

    $exchangeRateRef_usd = json_encode($exchangeRateRef_usd);
    $exchangeRateRef_jpn = json_encode($exchangeRateRef_jpn);
    $exchangeRateRef_chn = json_encode($exchangeRateRef_chn);

?>
