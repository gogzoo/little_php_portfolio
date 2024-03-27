<?php
    $key = "";
    $sql_3 = "SELECT authkeys FROM keyslist WHERE call_id = 'oversea_stocks';";

    // 인증키 가져오기
    $key = $pdo->query($sql_3);
    $key = $key->fetch();

    const urlGeneral = "";

    $today = new DateTime();
    $print_today = $today->format("Y-m-d");

    // var_dump($print_today);

    $interval_lastday = new DateInterval("P1D"); // 어제 인터벌

    $lastday = clone $today;
    $lastday->sub($interval_lastday); // 어제 날짜 생성
    $print_lastday = $lastday->format("Y-m-d"); // 어제 날짜를 문자열로 변경

    // var_dump($print_lastday);

    $dayCheck = $lastday->format("D"); // 어제가 무슨요일인지

    //var_dump($dayCheck);

    while ($dayCheck == "Sat" || $dayCheck == "Sun") {
        // 어제가 토요일이나 일요일일 경우
        $lastday->sub($interval_lastday); // 일단 하루 빼
        $dayCheck = $lastday->format("D");

        if ($dayCheck != "Sat" && $dayCheck != "Sun") {
            // 오늘이 토요일, 일요일이 아닐 경우
            break;
        }
    }

    //var_dump($dayCheck);

    $print_lastday = new DateTime($dayCheck); // 휴일 조정 후, 예를 들어 금요일이 나왔는데 이번주 금요일이(미래)가 나옴.
    $print_lastday->sub(new DateInterval("P7D")); // 저번주로 바꿈
    $print_lastday = $print_lastday->format("Y-m-d");

    //var_dump($print_lastday);

    // 예: ttps://api.polygon.io/v2/aggs/ticker/BITO/range/1/day/2024-03-11/2024-03-18?adjusted=true&sort=asc&limit=120&apiKey=인증키
    // 예: ttps://api.polygon.io/v1/open-close/AAPL/2023-01-09?adjusted=true&apiKey=인증키
    // 분배금정보 예: ttps://api.polygon.io/v3/reference/dividends?ticker=BITO&apiKey=인증키

    $sendURL_2 = "https://api.polygon.io/v1/open-close/BITO/" . $print_lastday . "?adjusted=true&apiKey=" . $key["authkeys"];
    // 특정일의 시작, 종가, 최고, 최저가등을 조회

    // 리턴 자료 예제
    //{"status":"OK","from":"2024-03-20","symbol":"BITO","open":29.03,"high":30.255,"low":28.41,"close":30.11,"volume":2.3632179e+07,"afterHours":31.07,"preMarket":28.76}

    //요청 보내기

    try { 
        $BITOData = @file_get_contents($sendURL_2);
        // @는 오류보고를 하지 말라는 뜻
        // 어차피 file_get_content는 실패 시 예외를 던지는게 아니라 오류를 던져서 catch 이하를 실행하지 않음.

        if ($BITOData == false) {
            throw new Exception("데이터 수신 실패");
            // 내가 대신 예외 던져서 catch이하로 유도하기.
        }

    }catch (Exception $e) {
        // 어제날짜로 조회했는데, 아직 장이 안끝나서 오류가 생긴 경우

        $print_lastday = new DateTime($print_lastday);
        $print_lastday->sub(new DateInterval("P1D"));
        $print_lastday = $print_lastday->format("Y-m-d");
        // 어제에서 또 어제를 만듬.

        $sendURL_2 = "https://api.polygon.io/v1/open-close/BITO/" . $print_lastday . "?adjusted=true&apiKey=" . $key["authkeys"];

        $BITOData = file_get_contents($sendURL_2);
    }   

    // 이하 분배금정보 조회
    $sendURL_3 = "https://api.polygon.io/v3/reference/dividends?ticker=BITO&apiKey=" . $key["authkeys"];

    // 요청 보내기
    $BITOdividens = file_get_contents($sendURL_3);
    $BITOdividens = json_decode($BITOdividens, true);

    $BITOdivDate = [];
    $BITOdivAmount = [];

    // 현재 error_reporting 값을 저장해둡니다.
    $previousErrorReporting = error_reporting();

    // 원하는 범위에서는 워닝을 숨김
    error_reporting($previousErrorReporting & ~E_WARNING);

    foreach ($BITOdividens as $items) {
        try {
            foreach ($items as $item) {
                $BITOdivDate[] = $item["pay_date"];
                $BITOdivAmount[] = $item["cash_amount"];
                }
        } catch (Exception $e) {
        }
    }

    // 이전 error_reporting 값으로 복원
    error_reporting($previousErrorReporting);

    // var_dump($BITOdivDate);
    // var_dump($BITOdivAmount);

    $BITOdivDate = json_encode($BITOdivDate, true);
    $BITOdivAmount = json_encode($BITOdivAmount, true);

?>