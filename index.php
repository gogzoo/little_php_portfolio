<!-- db연결 리콰이어 자리 -->
<?php require "./includes/dbConnect_localhost.php" ?>
<?php require "./includes/session.php" ?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="./globalStyle.css">
    <link rel="stylesheet" href="./globalStyleMobile.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="kk">
        <button id="kk">레이아웃</button>
        <script>
            $("#kk").click(function(){
                $("*").toggleClass("view-lay");
            })
        </script>
    </div>

    <header>
        <h1><a href="./index.php">MyLittleMoney</a></h1>
        <ul id="funcBtn_pc" class="funcbtn">
            <li id="win1" class="actived-win"><i class="xi-chart-bar-square"></i><span class="mini">오늘의 환율</span></li>
            <li id="win2"><i class="xi-emoticon-cool"></i><span>BITO 알아보기</span></li>
            <li id="win3"><i class="xi-forum"></i><span>한마디</span></li>
        </ul>
    </header>

    <ul id="funcBtn_mb" class="funcbtn">
        <li id="win4" class="actived-win"><i class="xi-chart-bar-square"></i><span>오늘의 환율</span></li>
        <li id="win5"><i class="xi-emoticon-cool"></i><span>BITO 알아보기</span></li>
        <li id="win6"><i class="xi-forum"></i><span>한마디</span></li>
    </ul>

    <div class="head-void"></div>

    <main id="mainWrapper"> <!--메인컨텐츠-->
        <section class="sec-1"> <?php require "./contentPage/section1.php"; ?> </section>
        <section class="sec-2"> <?php require "./contentPage/section2.php"; ?> </section>
        <section class="sec-3"> <?php require "./contentPage/section3.php"; ?> </section>
    </main>

    <div class="void"></div>

    <script> // 화면 이동용 기능
        // -----------------------------------------------
        $(".funcbtn>li").click(function(){
            $(this).addClass("actived-win");
            $(this).siblings().removeClass("actived-win");
        })

        let recognizeWinNum = 1;
        // 현재 화면이 뭔지 기억하는 변수

        const toWin1 = document.getElementById("win1");
        const toWin2 = document.getElementById("win2");
        const toWin3 = document.getElementById("win3");

        const toWin4 = document.getElementById("win4");
        const toWin5 = document.getElementById("win5");
        const toWin6 = document.getElementById("win6");

        const mainWrapper = document.getElementById("mainWrapper");

        const changeWindow = (clickWinNum) => {

            if(clickWinNum != recognizeWinNum) {
                var marginMultiply = clickWinNum - 1;
                // 화면이동용 마진에 얼마나 곱할건지

                let select = document.getElementById(`win${clickWinNum}`);
                // 현재 선택한 화면의 요소

                let innerWidth = window.innerWidth;
                // 현재 뷰포트너비
                
                mainWrapper.style.marginLeft = `-${innerWidth * marginMultiply}px`;
                // wrapper를 얼마나 움직일지

                recognizeWinNum = clickWinNum;
                // 현재 화면이 뭔지 클릭한걸로 바꿈.

            }
        };

        toWin1.addEventListener("click", () => changeWindow(1));
        toWin2.addEventListener("click", () => changeWindow(2));
        toWin3.addEventListener("click", () => changeWindow(3));

        toWin4.addEventListener("click", () => changeWindow(1));
        toWin5.addEventListener("click", () => changeWindow(2));
        toWin6.addEventListener("click", () => changeWindow(3));

        // -----------------------------------------------
    </script>
</body>
</html>