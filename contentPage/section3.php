<?php 
    $postDirection = null;
    $postMsg = null;
    $disabled = "disabled";
?>

<?php 
    // 로그인일 경우, 아닐 경우 검사 
    if ($logined == true) {
        // 글쓰기 가능
        $postMsg = "게시하기";
        $postDirection = "../portfolio4/includes/inputPost.php";
        $disabled = "";

        // 익명용 아이디 교체
        $username = $_SESSION["username"];

        echo "<script>";
        echo "  let username = ' ". $username . " '; ";
        echo "  let anonId = undefined;";
        echo "</script>";

    } else if ($logined == false) {
        // 글쓰기 불가
        $postMsg = "비회원은 열람만 가능합니다.";

        // 익명 아이디 세팅
        $anonId = "익명".$_SESSION["anonId"];

        echo "<script>";
        echo "  let username = undefined;";
        echo "  let anonId = ' ". $anonId ." '; ";
        echo "</script>";
    }
?>

<script>
    $("#logoutForm").hide();
</script>

<div>
    <h2>오늘의 한마디</h2>
    <div class="login-bar">
        <p>상태 로딩중</p>
        <form action="../portfolio4/includes/loginPrcs.php" method="post" id="loginForm">
            <input type="text" name="id" id="id" placeholder="ID 입력" required>
            <input type="password" name="pw" id="pw" placeholder="PW 입력" required>
            <input type="submit" id="submit" value="로그인">
        </form>
        <button id="loginMobile">로그인</button>
        <button id="signUpPopup">회원가입</button>
        <button id="writeMobile">글쓰기</button>
        <form id="logoutForm" action="../portfolio4/includes/logoutPrcs.php" method="post" onsubmit="return questionLogout()">
            <input type="submit" value="로그아웃">
        </form>
    </div>
    <div class="board">
        <div class="write write_pc">
            <form action="<?= $postDirection ?>" method="post">
                <label for="nameid">글쓴이 <input type="text" name="nameid" id="nameid" value="로딩중" disabled></label>
                <label for="title">제목 <input type="text" name="title" id="title" required></label>
                <label for="para">내용 <textarea name="para" id="para" cols="30" rows="10" required></textarea></label>
                <input type="submit" id="post_submit" value="<?= $postMsg ?>" <?= $disabled ?> >
            </form>
        </div>
        <div class="post-list" id="postList">
            <p class="load">로딩중입니다...</p>
        </div>
    </div>

    <script> // 로그아웃 물어보기
        function questionLogout() {
            return confirm("로그아웃 하시겠습니까?");
        }
    </script>

    <script> // 로그인 시 위의 화면 내용 바꿔치기
        if (username != undefined) { // 로그인해서 유저네임에 뭐라도 있을 경우

            $("#nameid").val(username); // 폼 요소 밸류 건드리기
            $("#nameid_m").val(username);
            $(".login-bar>p").html(`${username}님 접속중`);

            $("#loginForm").hide(); // 로그인 폼 감추기
            $("#signUpPopup").hide(); // 회원가입 버튼 감추기
            $("#logoutForm").show();  // 로그아웃 버튼 보이기
            $("#loginMobile").hide(); // 모바일 로그인 감추기

        } else if (anonId != undefined) { // 로그인 안해서 익명아이디에 뭐라도 있을 경우

            $("#nameid").val(anonId); // 폼 요소 밸류 건드리기
            $(".login-bar>p").html(anonId);
            $("#logoutForm").hide(); // 로그아웃 버튼 감추기
            $("#writeMobile").hide(); // 모바일 글쓰기 버튼 감추기
        }
    </script>

    <div id="signUp" class="mobile-popup">
        <form action="../portfolio4/includes/signUp.php" method="post" onsubmit="return validateForm()">
            <label for="id_r">ID/PW는 영대소문자 및 숫자로만 이루어져야 합니다.</label>
            <input type="text" name="id_r" id="id_r" placeholder="ID 입력" required>
            <input type="text" name="name_r" id="name_r" placeholder="닉네임 입력" required>
            <input type="password" name="pw_r" id="pw_r" placeholder="PW 입력" required>
            <input type="password" name="pw_rr" id="pw_rr" placeholder="PW 재입력" required>
            <input type="submit" id="submit_r" value="회원가입">
        </form>
        <button id="popupclose" class="mobile-popup-close">닫기</button>
    </div>

    <div id="loginMobilePopup" class="mobile-popup">
        <form action="../portfolio4/includes/loginPrcs.php" method="post" id="loginForm_m">
            <input type="text" name="id" id="id_m" placeholder="ID 입력" required>
            <input type="password" name="pw" id="pw_m" placeholder="PW 입력" required>
            <input type="submit" id="submit_m" value="로그인">
        </form>
        <button id="popupclose_login_m" class="mobile-popup-close">닫기</button>
    </div>

    <div class="write write_m">
        <form action="<?= $postDirection ?>" method="post">
            <label for="nameid">글쓴이 <input type="text" name="nameid" id="nameid_m" value="로딩중" disabled></label>
            <label for="title">제목 <input type="text" name="title" id="title_m" required></label>
            <label for="para">내용 <textarea name="para" id="para_m" cols="30" rows="10" required></textarea></label>
            <input type="submit" id="post_submit_m" value="<?= $postMsg ?>" <?= $disabled ?> >
        </form>
        <button id="write_m_close">닫기</button>
    </div>
    
    <?php // 다른사람의 게시글에 수정 및 삭제가 나타나지 않게 하기 
        if(isset($_SESSION["username"])) {
            $postModifyBtn = "<li><button id='postMoreModify'>수정</button></li>";
            $postDeleteBtn = "<li><button id='postMoreDelete'>삭제</button></li>";
            $postModifyCompleteBtn = "<li><button id='postMoreModifyComplete'>수정 적용</button></li>";
        }else if(!isset($_SESSION["username"])) {
            $postModifyBtn = '';
            $postDeleteBtn = '';
            $postModifyCompleteBtn = '';
        }
    ?>

    <div class="post-more-bg">
        <div class="post-more">
            <input type="text" id="post_more_title" value="제목" disabled>
            <span>작성정보</span>
            <input type="text" id="post_more_para" value="작성내용" disabled>
            <ul class="postMoreBtns">
                <li><button id="postMoreClose">닫기</button></li>
                <?= $postModifyBtn ?>
                <?= $postDeleteBtn ?>
                <?= $postModifyCompleteBtn ?>
            </ul>
        </div>
    </div>

    <script> // 폼 유효성 검사

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

    <script> // 게시글용 테스트 더미데이터
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

    <script> // 창 숨겼다 나타냈다 관련
        // 회원가입창 관련
        $("#signUp").hide(); 
        $("#signUpPopup").click(function(){
            $("#signUp").fadeIn();
            $("#loginMobilePopup").fadeOut();
        })
        $("#popupclose").click(function(){
            $("#signUp").fadeOut();
        })

        // 모바일 로그인창 관련
        $("#loginMobilePopup").hide();
        $("#loginMobile").click(function(){
            $("#loginMobilePopup").fadeIn();
            $("#signUp").fadeOut();
        })
        $("#popupclose_login_m").click(function(){
            $("#loginMobilePopup").fadeOut();
        })

        // 모바일 글쓰기 버튼 관련
        $(".write_m").hide();
        $("#writeMobile").click(function(){
            $("#nameid_m").val(username);
            $(".write_m").fadeIn();
        })
        $("#write_m_close").click(function(){
            $(".write_m").fadeOut();
        })
    </script>

    <script> // 게시글 생성 관련

        // 이하 게시글 생성
        let postList = <?= $sendPostList ?>;
        console.log(postList);

        $(".load").hide();
        $("#postList").html("<ul class='postWrap'></ul>");

        for(var i = 0; i < postList.length; i++) {
            $("#postList>ul.postWrap").prepend(
                `<li>
                    <p class="postTitle" id=SN${postList[i]['sn']}>${postList[i]['title']} <span>작성자: ${postList[i]['id']}, 게시일: ${postList[i]['postdate']}</span></p>
                    <p>${postList[i]['para']}</p>
                </li>`
            );
        }

        // 게시글을 클릭했을 때 더보기 생성
        $(".post-more-bg").hide();

        $("#postList>ul.postWrap>li").click(function(){
            // 수정, 적용, 삭제 버튼 일단 숨기기
            $("#postMoreModifyComplete").hide();
            $("#postMoreModify").hide();
            $("#postMoreDelete").hide();

            // 클릭한 게시글의 아이디를 받아옴
            let clickTitleOrder = $(this).find("p:first-child").attr("id");
            // 아이디가 SN88 이렇게 되어있는데, 숫자만 빼옴.
            clickTitleOrder = parseInt(clickTitleOrder.substring(2));

            // 시리얼넘버로 게시글 객체에서 몇번째인지 찾기
            var postIndex = postList.findIndex(function(post){
                return post["sn"] == clickTitleOrder;
            })

            //console.log(postIndex);

            // 게시글 더보기에 내용 담기
            $("#post_more_title").val(postList[postIndex]["title"]);
            $(".post-more>span").html(`작성자: ${postList[postIndex]["id"]}, 게시일: ${postList[postIndex]["postdate"]}`);
            $("#post_more_para").val(postList[postIndex]["para"]);

            // 좌우공백 없애기
            username = username.trim();
            postuser = (postList[postIndex]["id"]).trim();

            // 이게 내 게시글이면 수정, 삭제버튼 보이기
            if(username == postuser) {
                $("#postMoreModify").show();
                $("#postMoreDelete").show();
            }

            // 게시글 더보기 보여주기
            $(".post-more-bg").fadeIn();

            // 게시글 수정 버튼을 누르면
            $("#postMoreModify").click(function(){
                // 삭제버튼 숨기고, 수정적용 버튼 보이기
                $("#postMoreDelete").hide();
                $("#postMoreModify").hide();
                $("#postMoreModifyComplete").show();
                $("#postMoreClose").html("취소 및 닫기");

                // 제목과 내용의 disabled 속성 제거
                $("#post_more_title").prop("disabled", false);
                $("#post_more_para").prop("disabled", false);

                $("#post_more_title").addClass("actived-input");
                $("#post_more_para").addClass("actived-input");

                // 게시글 수정 적용 버튼을 누르면
                $("#postMoreModifyComplete").click(function(){
                    let sendData = {
                        "modifyTitle": $("#post_more_title").val(),
                        "modifyPara": $("#post_more_para").val(),
                        "sn": clickTitleOrder
                    }

                    $.ajax({
                        url: "../portfolio4/includes/modifyPost.php",
                        method: "POST",
                        data: sendData,
                        success: function(response) {
                            let responseStr = response;
                            // 리스폰스 안에 성공 여부가 있는지 없는지 확인
                            if(responseStr.includes("success") == true) {
                                alert("게시글을 수정했습니다.");
                                // 에이젝스 요청은 php문서로 이동하지 않고 처리함.
                                // 그래서 여기서 바로 새로고침해줘야됨.
                                window.location.reload();
                            } else {
                                alert("서버에 접근하지 못했습니다. 나중에 시도해주세요.");
                            }
                        },
                        error: function(error) {
                            alert("서버에 접근하지 못했습니다. 나중에 시도해주세요.");
                        }
                    })
                })
            })

            //게시글 삭제 버튼 누르면
            $("#postMoreDelete").click(function(){
                if(confirm("게시글을 삭제할까요?")) {
                    let sendData = {
                        "delete": true,
                        "sn": clickTitleOrder
                    };

                    $.ajax({
                        url: "../portfolio4/includes/deletePost.php",
                        method: "POST",
                        data: sendData,
                        success: function(response) {
                            let responseStr = response;
                            // 리스폰스 안에 성공 여부가 있는지 없는지 확인
                            if(responseStr.includes("success") == true) {
                                alert("게시글을 삭제했습니다.");
                                // 에이젝스 요청은 php문서로 이동하지 않고 처리함.
                                // 그래서 여기서 바로 새로고침해줘야됨.
                                window.location.reload();
                            } else {
                                alert("서버에 접근하지 못했습니다. 나중에 시도해주세요.");
                            }
                        },
                        error: function(error) {
                            alert("서버에 접근하지 못했습니다. 나중에 시도해주세요.");
                        }
                    })
                }
            })
        })

        // 닫기 버튼 누르면
        $("#postMoreClose").click(function(){
            $(".post-more-bg").fadeOut(300);
            $("#postMoreDelete").hide();
            $("#postMoreModify").hide();
            $("#postMoreModifyComplete").hide();
            $("#postMoreClose").html("닫기");

            $("#post_more_title").prop("disabled", true);
            $("#post_more_para").prop("disabled", true);

            $("#post_more_title").removeClass("actived-input");
            $("#post_more_para").removeClass("actived-input");
        })


    </script>
</div>