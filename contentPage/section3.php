<?php 
    $postDirection = null;
    $postMsg = null;
    $disabled = "disabled";
?>

<?php 
    // 글쓰기 조건 검사
    if ($_SESSION["logined"] == true) {
        $postMsg = "게시하기";
        $postDirection = "../portfolio4/includes/inputPost.php";
        $disabled = "";
    } else {
        $postMsg = "비회원은 좋아요만 가능합니다.";
    }
?>

<div>
    <h2>오늘의 한마디</h2>
    <div class="login-bar">
        <p>상태 로딩중</p>
        <form action="" method="post">
            <input type="text" name="id" id="id" placeholder="ID 입력">
            <input type="password" name="pw" id="pw" placeholder="PW 입력">
            <input type="submit" id="submit" value="로그인">
        </form>
        <button id="signUpPopup">회원가입</button>
    </div>
    <div class="board">
        <div class="write">
            <form action="<?= $postDirection ?>" method="post">
                <label for="nameid">글쓴이 <input type="text" name="nameid" id="nameid" value="로딩중" disabled></label>
                <label for="title">제목 <input type="text" name="title" id="title"></label>
                <label for="para">내용 <textarea name="para" id="para" cols="30" rows="10"></textarea></label>
                <input type="submit" id="post_submit" value="<?= $postMsg ?>" <?= $disabled ?> >
            </form>
        </div>
        <div class="post-list" id="postList">
            <p class="load">로딩중입니다...</p>

        </div>
    </div>

    <div id="signUp">
        <form action="../portfolio4/includes/signUp.php" method="post" onsubmit="return validateForm()">
            <input type="text" name="id_r" id="id_r" placeholder="ID 입력" required>
            <input type="text" name="name_r" id="name_r" placeholder="닉네임 입력" required>
            <input type="password" name="pw_r" id="pw_r" placeholder="PW 입력" required>
            <input type="password" name="pw_rr" id="pw_rr" placeholder="PW 재입력" required>
            <input type="submit" id="submit_r" value="회원가입">
        </form>
        <button id="popupclose">닫기</button>
    </div>

    <script>
        // 폼 유효성 검사

        const validateForm = () => {
            let pw_r = $("#pw_r").val();
            let pw_rr = $("#pw_rr").val();
            
            if (pw_r != pw_rr) {
                alert("입력한 비밀번호와 비밀번호 확인이 일치하지 않습니다.");
                return false;

            } else if (pw_r == pw_rr) {
                return true;
            }
        }
    </script>



    <script>
        // 게시글용 테스트 더미데이터

        // let postList = [
        //     {
        //         "title": "안녕하세요",
        //         "id": "koko",
        //         "postdate": "2024-03-20",
        //         "para": "테스트입니다1."
        //     },
        //     {
        //         "title": "안녕히세요2",
        //         "id": "koko",
        //         "postdate": "2024-03-20",
        //         "para": "테스트입니다2."
        //     }
        // ];
    </script>

    <script>
        $("#signUp").hide();
        $("#signUpPopup").click(function(){
            $("#signUp").fadeIn();
        })
        $("#popupclose").click(function(){
            $("#signUp").fadeOut();
        })

        let anonId = `익명<?=$_SESSION['anonId']?>`;
        $("#nameid").val(anonId);
        $(".login-bar>p").html(anonId);

        // 이하 게시글 생성
        let postList = <?= $sendPostList ?>;
        console.log(postList);

        $(".load").hide();
        $("#postList").html("<ul class='postWrap'></ul>");

        for(var i = 0; i < postList.length; i++) {
            $("#postList>ul.postWrap").prepend(
                `<li>
                    <p id="postTitle">${postList[i]['title']} <span>작성자: ${postList[i]['id']}, 게시일: ${postList[i]['postdate']}</span></p>
                    <p>${postList[i]['para']}</p>
                </li>`
            );
        }

        // 게시글 게시 결과.

        // 밑에 방법 쓰기
        // echo '<script type="text/javascript">';
        // echo 'alert("알림 메시지");';
        // echo 'window.location.href = "새로운페이지.php";';
        // echo '</s1cript>';
    </script>    
</div>