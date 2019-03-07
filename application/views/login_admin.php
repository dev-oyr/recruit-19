<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="images/logo_black.png" type="img/png">
    <link rel="stylesheet" type="text/css" href="../css/semantic.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <script src="../libs/jquery-deprecate-ie.js"></script>
    <script>deprecateIE.alert.forcible({title:"지원되지 않는 브라우저", msg:"이 페이지는 Internet Explorer를 지원하지 않습니다. 다른 브라우저로 접속해 주십시오.", btn:"닫기"})</script>
    <script src="../libs/semantic.min.js"></script>
    <script src="../libs/jquery-ui.min.js"></script>
    <title>관리자 로그인</title>

    <style>
        .floater {
            float: left; 
            height: 50%; 
            margin-bottom: -192px;
        }
        .container {
            clear: both; 
            position: relative;
        }
        .container > h4 {
            margin: 0 auto 0 auto;
            max-width: 360px;
        }
        .ui.segment {
            margin: 8px auto 0 auto;
            max-width: 360px; 
        }
        .logo-container {
            display: flex;
            justify-content: center;
        }
        .field.hidden {
            display: none;
        }
        .form-controller > .ui.button {
            width: 100%;
        }
        .toggle {
            margin-top: 8px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="loader"> 
        <div class="ui inverted dimmer">
            <div class="ui text loader">Loading</div>
        </div>
    </div>
    <div class="floater"></div>
    <div class="container">
        <h4>지원자 관리</h4>
        <div class="ui segment">
            <header>
                <div class="logo-container">
                    <img src="../images/CI.png" alt="" height="92px">
                </div>  
            </header>

            <main>
                <form class="ui form" method="POST" name="main-form">
                    <div class="field">
                        <label>아이디</label>
                        <div class="field">
                            <input type="text" name="admin-id" regex-type="id">
                        </div>
                    </div>
                    <div class="field">
                        <label>암호</label>
                        <div class="field">
                            <input type="password" name="admin-pw" regex-type="password" regex-minlength="4" regex-maxlength="32">
                        </div>
                    </div>
                    <div id="field_check_pw" class="field hidden">
                        <label>암호 확인</label>
                        <div class="field">
                            <input id="check_pw" type="password" regex-type="password" regex-minlength="4" regex-maxlength="32">
                        </div>
                    </div>
                    <div class="form-controller">
                        <button class="ui primary button" type="button">로그인</button>
                    </div>
                </form>
            </main>

            <footer>
                <div class="toggle" status="login">
                    <a>등록</a>
                </div>
            </footer>
        </div>
    </div>

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

    <script>
        var toggleRegister = function() {
            const target = $('.toggle');
            const actionBtn = $('.form-controller > .ui.primary.button');
            
            if(target.attr('status') === 'login') {
                target.children().html("로그인");
                target.attr('status', 'register');
                actionBtn.html("등록");
                $('#field_check_pw').removeClass('hidden');
                $('#field_check_pw').siblings('.field').find('input[type="password"]').attr('placeholder', '영문 숫자 조합 4자 이상');
                $('.floater').css({'margin-bottom': '-240px'});

            } else if(target.attr('status') === 'register') {
                target.children().html("등록");
                target.attr('status', 'login');
                actionBtn.html("로그인");
                $('#field_check_pw').addClass('hidden');
                $('#field_check_pw').siblings('.field').find('input[type="password"]').attr('placeholder', '');
                $('.floater').css({'margin-bottom': '-192px'});

            } else {
                modalConfig("오류", "내부 오류입니다.", {
                    ok: {
                        text: "확인",
                        event: refresh
                    }
                });
                openModal();
            }
        }
        $(document).on('click', '.toggle', toggleRegister);

        var modalConfig = function(title, message, opt = {}) {
            const btnOk = $('.ui.modal > .actions > .ui.primary.button');
            const btnCancel = $('.ui.modal > .actions > .ui.cancel.button')
            const content = $('.ui.modal > .content > p');
            const header = $('.ui.modal > .header');

            $(document).off('click', '.ui.modal > .actions > .ui.primary.button');
            $(document).off('click', '.ui.modal > .actions > .ui.cancel.button');

            header.html(title);
            content.html(message);

            if(opt.cancel === undefined) { // Ok only
                btnOk.css({display:'none'});
                btnCancel.html(opt.ok.text);
                $(document).on('click', '.ui.modal > .actions > .ui.cancel.button', opt.ok.event);
            
            } else {
                btnOk.css({display:''});
                btnOk.html(opt.ok.text);
                $(document).on('click', '.ui.modal > .actions > .ui.primary.button', opt.ok.event);
                btnCancel.html(opt.cancel.text);
                $(document).on('click', '.ui.modal > .actions > .ui.cancel.button', opt.cancel.event);
            }
        }

        var closeModal = function() {
            $('.ui.modal').modal('hide');
        }

        var openModal = function() {
            $('.ui.modal').modal({
                inverted: true,
                blurring: true
            }).modal('show');
        }

        var refresh = function() {
            closeModal();
            location.reload();
        }

        var checkFieldRequired = function() {
            const section = $('.field > label');
            let passed = 0;
            
            section.each((idx, e) => {
                !checkRegEx($(e), true) && passed++;
            });

            return !passed;
        }

        var checkRegEx = function(element, required) {
            const target = element.next();
            let i = true;

            if(target.hasClass('fields')) {
                target.children().each((idx, e) => {
                    if(!regex($(e).find('input, select, textarea'), required)) i = false;
                });

            } else {
                if(!regex(target.find('input, select, textarea'), required)) i = false;
            }

            i ? element.parent($('.field')).removeClass('error') : element.parent($('.field')).addClass('error');

            return i;
        }

        var regex = function(element, required) {
            const value = element.val();
            const type = element.attr('regex-type');
            const minLength = parseInt(element.attr('regex-minlength'));
            const maxLength = parseInt(element.attr('regex-maxlength'));

            const statusRegister = $('.toggle').attr('status') === 'register' ? true : false;

            let passed = true;

            if(!statusRegister && element.attr('id') === 'check_pw') {
                return true;
                
            } else if(required && !value) {
                return false;
            }

            if(type === 'id' && value) {
                
            } else if(type === 'password' && value) {
                if(element.attr('id') !== 'check_pw' && statusRegister) {
                    const matched = (value === $('#check_pw').val());
                    !matched && ($('#check_pw').attr('placeholder', '비밀번호를 확인 해주세요'));
                    passed = matched && new RegExp(`^.{${minLength},${maxLength}}$`).test(value);

                } else {
                    passed = new RegExp(`^.{${minLength},${maxLength}}$`).test(value);
                }
            }

            return passed;
        }

        var dismissFieldError = function() {
            const target = $(this);
            target.closest($('.field.error')).removeClass('error');
        }
        $(document).on('change', '.field > input, select, textarea', dismissFieldError);

        var enterPressed = function(e) {
            if(e.keyCode == 13) $('.form-controller > .ui.primary.button').click();
        }
        $(document).on('keypress', 'input[name="admin-pw"]', enterPressed);

        var actionCore = function() {
            const statusRegister = $('.toggle').attr('status') === 'register' ? true : false;
            const loader = $('.loader').children();

            if(!checkFieldRequired()) return;

            if(statusRegister) {
                loader.addClass('active');
                $.post('../dbctr/check_admin_duplication', $('form[name="main-form"]').serialize(), data => {
                    if(parseInt(data) < 1) {
                        loader.addClass('active');
                        $.post('../dbctr/register_admin', $('form[name="main-form"]').serialize(), data => {
                            if(data) {
                                modalConfig("알림", "성공적으로 등록되었습니다.", {
                                    ok: {
                                        text: "확인",
                                        event: refresh
                                    },
                                });
                                openModal();
                                loader.removeClass('active');

                            } else {
                                modalConfig("오류!", "내부 오류가 발생했습니다.<br>잠시 후 다시 시도 해주세요.", {
                                    ok: {
                                        text: "확인",
                                        event: closeModal
                                    },
                                });
                                openModal();
                            }
                        }, 'json')
                        .fail(data => {
                            modalConfig("오류!", "내부 오류가 발생했습니다.<br>잠시 후 다시 시도 해주세요.", {
                                    ok: {
                                        text: "확인",
                                        event: closeModal
                                    },
                                });
                            openModal();
                        })
                        .always(data => {loader.removeClass('active');});

                    } else {
                        modalConfig("알림", "이미 등록된 관리자입니다.", {
                            ok: {
                                text: "확인",
                                event: closeModal
                            },
                        });
                        openModal();
                    }

                }, 'json')
                .fail(data => {
                    modalConfig("오류!", "내부 오류가 발생했습니다.<br>잠시 후 다시 시도 해주세요.", {
                        ok: {
                            text: "확인",
                            event: closeModal
                        },
                    });
                    openModal();
                })
                .always(data => {
                    loader.removeClass('active');
                });
            } else {
                loader.addClass('active');
                $.post('../dbctr/login_admin', $('form[name="main-form"]').serialize(), data => {
                    if(data) {
                        console.info("로그인 성공!");
                        loader.removeClass('active');
                        location.href = '../viewer';

                    } else {
                        console.info("아이디나 비밀번호를 다시 확인 해주세요.");
                        modalConfig("알림", "아이디나 비밀번호를 다시 확인 해주세요.<br>또는 승인되지 않은 관리자 입니다.", {
                            ok: {
                                text: "확인",
                                event: closeModal
                            },
                        });
                        openModal();
                    }
                }, 'json')
                .fail(data => {
                    modalConfig("오류!", "내부 오류가 발생했습니다.<br>잠시 후 다시 시도 해주세요.", {
                            ok: {
                                text: "확인",
                                event: closeModal
                            },
                        });
                    openModal();
                })
                .always(data => {loader.removeClass('active');});
            }
        }
        $(document).on('click', '.form-controller > .ui.primary.button', actionCore);
    </script>
</body>
</html>