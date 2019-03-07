<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="images/logo_round.png" type="img/png">
    <link rel="stylesheet" type="text/css" href="css/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="css/default.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <script src="libs/jquery-deprecate-ie.js"></script>
    <script>deprecateIE.alert.forcible({title:"지원되지 않는 브라우저", msg:"이 페이지는 Internet Explorer를 지원하지 않습니다. 다른 브라우저로 접속해 주십시오.", btn:"닫기"})</script>
    <script src="libs/semantic.min.js"></script>
    <script src="libs/jquery-ui.min.js"></script>
    <script src="js/default.js"></script>
    <title>2019 Open Year Round 지원서</title>
</head>
<body>
    <div class="loader"> 
        <div class="ui inverted dimmer">
            <div class="ui text loader">Loading</div>
        </div>
    </div>
    
    <header></header>

    <main>
        <div class="ui segment">
            

            <div class="secondary-header">
                <h2>2019년도 OpenYearRound 신입부원 지원서</h2>
            </div>
            <form class="ui form" method="POST" name="main-form-apply">
                <div class="sections">
                    <div class="form-section" data-no="0">
                        <div class="title-logo">
                            <img src="images/logo_round.png" alt="" height="192px">
                        </div>
                        <p>&nbsp;OpenYearRound는 스터디를 통해 웹 서비스 개발에 중점을 두고 있고, 현재 각종 공모전 및 학술제에 참여하고 있습니다.</p>
                    </div>

                    <div class="form-section" data-no="1">
                        <h4 class="ui dividing header">기본 정보</h4>
                        <div class="field">
                            <label>이름 *</label>
                            <div class="field">
                                <input type="text" name="apply-name" placeholder="">
                            </div>
                        </div>

                        <div class="field">
                            <label>생년월일 *</label>
                            <div class="three fields">
                                <div class="field">
                                    <input type="number" min="1970" max="2019" name="apply-birth-year" placeholder="년" regex-type="date-year">
                                </div>
                                <div class="field">
                                    <select class="ui fluid search dropdown" name="apply-birth-month" regex-type="date-month">
                                        <option value="1">1월</option>
                                        <option value="2">2월</option>
                                        <option value="3">3월</option>
                                        <option value="4">4월</option>
                                        <option value="5">5월</option>
                                        <option value="6">6월</option>
                                        <option value="7">7월</option>
                                        <option value="8">8월</option>
                                        <option value="9">9월</option>
                                        <option value="10">10월</option>
                                        <option value="11">11월</option>
                                        <option value="12">12월</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <input type="number" min="1" max="31" name="apply-birth-day" placeholder="일" regex-type="date-day">
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label>성별 *</label>
                            <div class="field">
                                <select class="ui fluid search dropdown" name="apply-gender">
                                    <option value="0">남자</option>
                                    <option value="1">여자</option>
                                </select>
                            </div>
                        </div>

                        <div class="field">
                            <label>연락처 *</label>
                            <div class="field">
                                <input type="text" name="apply-phone-no" placeholder="" regex-type="phone-no" regex-minlength="10" regex-maxlength="11">
                            </div>
                        </div>

                        <div class="field">
                            <label>이메일</label>
                            <div class="field">
                                <input type="email" name="apply-email" placeholder="" regex-type="email">
                            </div>
                        </div>

                        <div class="field">
                            <label>군필여부 *</label>
                            <div class="field">
                                <select class="ui fluid search dropdown" name="apply-military">
                                    <option value="0">미필</option>
                                    <option value="1">군필 또는 면제(해당 없음)</option>
                                </select>
                            </div>
                        </div>

                        <h4 class="ui dividing header">학사 정보</h4>
                        <div class="field">
                            <label>학과 *</label>
                            <div class="field">
                                <input type="text" name="apply-major" placeholder="">
                            </div>
                        </div>

                        <div class="field">
                            <label>학년, 학기 *</label>
                            <div class="field">
                                <input type="text" name="apply-grade" placeholder="예) 1학년 1학기">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section" data-no="2">
                        <h4 class="ui dividing header">지원 사항</h4>

                        <input class="hidden-form-data" type="text" name="apply-known-data">
                        <div class="grouped fields">
                            <label for="fruit">이 동아리를 어떻게 알게 되었나요? *</label>
                            
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="apply-known" checked class="hidden">
                                    <label>홍보 포스터를 보고</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="apply-known" class="hidden">
                                    <label>친구 또는 지인의 소개 및 추천으로</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="apply-known" class="hidden">
                                    <label>SNS를 통해</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="apply-known" class="hidden">
                                    <label>기타</label> 
                                </div>
                                <div class="field">
                                    <input class="radio input other" type="text" name="apply-known-oth" class="hidden">
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label>지원 동기를 적어주세요. *</label>
                            <div class="field">
                                <textarea style="margin-top: 0px; margin-bottom: 0px; height: 144px;" name="apply-motivation"></textarea>
                            </div>
                        </div>

                        <div class="field">
                            <label>프로젝트 경험이 있으신가요?<br>개인이든 팀이든 상관없습니다!</label>
                            <div class="field">
                                <textarea style="margin-top: 0px; margin-bottom: 0px; height: 170px;" name="apply-project-exp"></textarea>
                            </div>
                        </div>

                        <div class="field">
                            <label>평소에 만들고 싶었던 웹 서비스가 있나요?</label>
                            <div class="field">
                                <textarea style="margin-top: 0px; margin-bottom: 0px; height: 170px;" name="apply-project-wish"></textarea>
                            </div>
                        </div>

                        <div class="field">
                            <label>앞으로 나의 다짐을 적어주세요! *</label>
                            <div class="field">
                                <textarea style="margin-top: 0px; margin-bottom: 0px; height: 144px;" name="apply-my-promise"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-controller">
                    <button class="ui button prev" type="button">뒤로</button>
                    <div class="spacer"></div>
                    <button class="ui button next" type="button">다음</button>
                    <button class="ui primary button submit" type="button">제출</button>
                </div>
                
            </form>
        </div>

        <div class="content-result">
            <div class="wrapper-result"><h3>성공적으로 제출되었습니다.<br>지원해 주셔서 감사합니다!</h3></div>
        </div>
    </main>

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

    <footer><p>© 2019 OpenYearRound. All rights reserved.</p></footer>
</body>
</html>