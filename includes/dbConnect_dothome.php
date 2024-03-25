<!-- dbConnect.php -->

<?php 

    $type = "mysql"; // 데이터베이스 소프트웨어
    $server = "localhost"; // 호스트 이름
    $db = "mucooko12"; // 데이터베이스 이름
    $charset = "utf8mb4"; // UTF-8 인코딩에 4바이트 데이터
    
    $dsn = "$type:host=$server;dbname=$db;charset=$charset";
    // 그냥 콜론과 세미콜론 위치 주의.

    $username = "mucooko12";
    $password = "bouno96357!";
    $options = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // DB에서 가져온 행을 알아서 배열로 만들어라라는 뜻.
        
        PDO::ATTR_EMULATE_PREPARES => false,
        // false일 경우 DB에서 가져온 데이터 타입이
        // php로 가져와도 그대로 유지됨.
        // true 일 경우 전부 문자열로 저장.
    ];


    try { // 데이터베이스 연결 시도

        $pdo = new PDO($dsn, $username, $password, $options);
        // PDO 객체 생성

    }catch (PDOException $e) {

        throw new PDOException($e->getMessage(), $e->getCode());
        // 예외를 한번 더 던져서 브라우저에 예외 내용이 노출되지 않게(db 아디나 비번이) 함.
        // 해당 메세지와 코드가 로그에 저장되며, 그 이후 예외처리를 한번 더 수행 가능하게 함.

    }

    // 게시글 가져오기
    $postList = $pdo->query("SELECT * FROM board"); // 쿼리문 실행
    $postList = $postList->fetchAll(); // 쿼리 실행 결과를 배열로 만들기

    $sendPostList = json_encode($postList, true);
?>