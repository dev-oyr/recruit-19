<?php
    # 세션 검증
	if(!$this->session->userdata('authorized')){
		echo '세션이 만료되었습니다. 다시 로그인 해주세요.';
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="../images/logo_black.png" type="img/png">
    <link rel="stylesheet" type="text/css" href="css/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="css/viewer.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <script src="libs/jquery-deprecate-ie.js"></script>
    <script>deprecateIE.alert.forcible({title:"지원되지 않는 브라우저", msg:"이 페이지는 Internet Explorer를 지원하지 않습니다. 다른 브라우저로 접속해 주십시오.", btn:"닫기"})</script>
    <script src="libs/semantic.min.js"></script>
    <script src="libs/jquery-ui.min.js"></script>
    <script src="libs/jquery.touchSwipe.min.js"></script>
    <script src="js/viewer.js"></script>
    <title>지원서 뷰어</title>
</head>
<body>
    <div class="ui mini modal">
        <div class="header">타이틀</div>
        <div class="content">
            <p>메시지</p>
        </div>
        <div class="actions">
            <div class="ui cancel button">아니오</div>
            <div class="ui primary button">예</div>
        </div>
    </div>

    <div class="ui visible sidebar vertical menu cto">
        <div class="loader"> 
            <div class="ui inverted dimmer">
                <div class="ui text loader">Loading</div>
            </div>
        </div>
        <h4 class="ui dividing header">지원자 목록</h4>
        <div class="sidebar-items">
        </div>
        <div class="logout" onclick="location.href='admin/logout'">
            <a><strong>로그아웃</strong></a>
        </div>
    </div>
    <div class="pusher">
        <header>
            <h2>지원서</h2>
            <div>
                <button id="download_excel" class="ui button">지원서 Excel다운로드</button>
                <button id="select_recruit" class="ui primary button">선발!</button>
            </div>
        </header>
        <main>
            <div class="app-contents">
                <div class="info-mobile">
                    <div class="floater"></div>
                    <div class="container">
                        <h4>우측으로 스와이프해서<br>지원자 목록을 조회하세요.</h4>
                    </div>
                </div>

                <div class="table-section">
                    <table>
                        <tr>
                            <td class="td-strong narrow">이름</td>
                            <td name="apply_name" colspan="3"></td>
                        </tr>
                        <tr>
                            <td class="td-strong narrow">생년월일</td>
                            <td name="apply_birthday"></td>
                            <td class="td-strong narrow">성별</td>
                            <td name="apply_gender"></td>
                        </tr>
                        <tr>
                            <td class="td-strong narrow">연락처</td>
                            <td name="phone_no"></td>
                            <td class="td-strong narrow">이메일</td>
                            <td name="apply_email"></td>
                        </tr>
                        <tr>
                            <td class="td-strong narrow">군필여부</td>
                            <td name="apply_military" colspan="3"></td>
                        </tr>
                    </table>

                    <table>
                        <tr>
                            <td class="td-strong narrow" rowspan="2">학사정보</td>
                            <td class="td-strong">학과</td>
                            <td class="td-strong">현재 학년</td>
                        </tr>
                        <tr>
                            <td name="apply_major"></td>
                            <td name="apply_grade"></td>
                        </tr>
                    </table>

                    <table>
                        <tr>
                            <td class="td-strong narrow">유입경로</td>
                            <td name="apply_known"></td>
                        </tr>
                    </table>

                    <table>
                        <tr style="min-height: 400px">
                            <td class="td-strong narrow" style="padding-top:48px;padding-bottom:48px">지원동기</td>
                            <td name="apply_motivation"></td>
                        </tr>
                    </table>

                    <table>
                        <tr>
                            <td class="td-strong narrow" style="padding-top:54px;padding-bottom:54px">프로젝트<br>경험</td>
                            <td name="proj_exp"></td>
                        </tr>
                    </table>

                    <table>
                        <tr>
                            <td class="td-strong narrow" style="padding-top:54px;padding-bottom:54px">제작하고 싶은<br>웹서비스</td>
                            <td name="proj_wish"></td>
                        </tr>
                    </table>

                    <table>
                        <tr>
                            <td class="td-strong narrow" style="padding-top:42px;padding-bottom:42px">앞으로의<br>각오</td>
                            <td name="my_promise"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </main>
        <footer><p>© 2019 OpenYearRound. All rights reserved.</p></footer>
    </div>
</body>
</html>