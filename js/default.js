var headerHeight = 32;
var headerShrinked = 24;
var headerTitleSize = 28;
var headerTitleShrinked = 24;
var titleSizeOffset = 6;
var animEasing = 'easeInOutQuint'

$(document).ready(() => {
    console.info("Apply page loaded.");

    $('.form-section').each((idx, e) => {
        const sectionNo = parseInt($(e).attr('data-no'));
        if(sectionNo !== idx) { console.error("폼 섹션 구성이 잘못되었습니다. 섹션 번호는 0부터 1씩 증가해야 합니다."); return; }
        if(sectionNo < 1) {
            $(e).addClass('active').css({opacity: 1});
        }
    });

    $('.form-controller > button.submit').addClass('hide');

    if(getCurrentFormSection() === 0) {
        $('.form-controller > button.prev').addClass('hide');
    }

    if(isEndOfFormSection()) {
        const btnPrev = $('.form-controller > button.prev');
        const btnNext = $('.form-controller > button.next');
        const btnSend = $('.form-controller > button.submit');
        btnSend.removeClass('hide');
        btnNext.addClass('hide');
        btnPrev.addClass('hide');
    }

    $('.ui.dropdown').dropdown();

    $('.hidden-form-data').val($('.hidden-form-data').siblings('.grouped.fields').children('.field').find('label').html());

    $('.ui.radio.checkbox').checkbox({
        onChange: function() {
            let value = $(this).siblings('label').html();
            const othval = $(this).parent().siblings().find('.radio.input.other').val();
            if(othval) value = othval;
            $('.hidden-form-data').val(value);
        }
    });

    $('.radio.input.other').keyup(e => {
        $(e.target).parents('.grouped.fields').siblings('.hidden-form-data').val($(e.target).val());
    });

    $(window).on('beforeunload', function() {
        return '이 페이지를 벗어나면 현재 작성중인 내용이 지워집니다.';
    });
});

var formNext = function() {
    const no = getCurrentFormSection();
    const target = $(`.form-section[data-no="${no}"]`);
    const length = $('.form-section').length;
    const btnPrev = $('.form-controller > button.prev');
    const btnNext = $(this);
    const btnSend = $('.form-controller > button.submit');
    const btnSpacer = $('.form-controller > .spacer');
    
    if(!checkFieldRequired(no)) return;

    $('form').parent($('.ui.segment')).css({height: $('.secondary-header')[0].clientHeight + $('form')[0].clientHeight});

    $(document).off('click', '.form-controller > button.prev', formPrev);
    $(document).off('click', '.form-controller > button.next', formNext);
    $(document).off('click', '.form-controller > button.submit', confirmApply);

    if(!isEndOfFormSection()) {
        console.info(`Go to next form ${no + 1}`);
        const next = $(`.form-section[data-no="${no + 1}"]`);
        $('.form-controller').animate({opacity: 0}, 250);
        target.animate({opacity: 0}, 250, () => {
            target.removeClass('active');
            next.addClass('active');
            if(no === 0) {
                const secHeader = $('.secondary-header');
                offset = 22;
                $('.secondary-header').animate({padding: headerShrinked}, 500, animEasing);
                $('.secondary-header > h2').animate({fontSize: headerTitleShrinked}, 500, animEasing);
            }
            $('form').parent($('.ui.segment')).animate({height: $('.secondary-header')[0].clientHeight + $('form')[0].clientHeight - offset}, 500, animEasing, () => {
                next.animate({"opacity": 1}, 250, () => {
                    $(document).on('click', '.form-controller > button.prev', formPrev);
                    $(document).on('click', '.form-controller > button.next', formNext);
                    $(document).on('click', '.form-controller > button.submit', confirmApply);
                    $('.form-controller').animate({opacity: 1}, 250);
                });
                $('form').parent($('.ui.segment')).css({height: "inherit"});
            });
        });

    } else {
        console.warn("End of form section.");
    }

    if(no + 1 > 0) {
        btnPrev.removeClass('hide');
        btnSpacer.addClass('active');

    } else {
        btnPrev.addClass('hide');
        btnSpacer.removeClass('active');
    }

    if(no + 1 >= length - 1) {
        btnSend.removeClass('hide');
        btnNext.addClass('hide');

    } else {
        btnSend.addClass('hide');
        btnNext.removeClass('hide');
    }
}
$(document).on('click', '.form-controller > button.next', formNext);

var formPrev = function() {
    const no = getCurrentFormSection();
    const target = $(`.form-section[data-no="${no}"]`);
    const length = $('.form-section').length;
    const btnPrev = $(this);
    const btnNext = $('.form-controller > button.next');
    const btnSend = $('.form-controller > button.submit');
    const btnSpacer = $('.form-controller > .spacer');

    $('form').parent($('.ui.segment')).css({height: $('.secondary-header')[0].clientHeight + $('form')[0].clientHeight});

    $(document).off('click', '.form-controller > button.prev', formPrev);
    $(document).off('click', '.form-controller > button.next', formNext);
    $(document).off('click', '.form-controller > button.submit', confirmApply);

    if(no > 0) {
        console.info(`Go to next form ${no - 1}`);
        const prev = $(`.form-section[data-no="${no - 1}"]`);
        $('.form-controller').animate({opacity: 0}, 250);
        target.animate({opacity: 0}, 250, () => {
            target.removeClass('active');
            prev.addClass('active');
            let offset = 0;
            if(no === 1) {
                const secHeader = $('.secondary-header');
                offset = headerHeight * 2 + $('.secondary-header').find('h2')[0].clientHeight + titleSizeOffset - secHeader[0].clientHeight;
                $('.secondary-header > h2').animate({fontSize: headerTitleSize}, 500, animEasing);
                secHeader.animate({padding: headerHeight}, 500, animEasing);
            }
            $('form').parent($('.ui.segment')).animate({height: $('.secondary-header')[0].clientHeight + $('form')[0].clientHeight + offset}, 500, animEasing, () => {
                prev.animate({"opacity": 1}, 250, () => {
                    $(document).on('click', '.form-controller > button.prev', formPrev);
                    $(document).on('click', '.form-controller > button.next', formNext);
                    $(document).on('click', '.form-controller > button.submit', confirmApply);
                    $('.form-controller').animate({opacity: 1}, 250);
                });
                $('form').parent($('.ui.segment')).css({height: "inherit"});
            });
        });
        
    } else {
        console.warn("First of form section.");
    }
    
    if(no - 1 > 0) {
        btnPrev.removeClass('hide');
        btnSpacer.addClass('active');

    } else { 
        btnPrev.addClass('hide');
        btnSpacer.removeClass('active');
    }

    if(no - 1 >= length - 1) {
        btnSend.removeClass('hide');
        btnNext.addClass('hide');

    } else {
        btnSend.addClass('hide');
        btnNext.removeClass('hide');
    }
}
$(document).on('click', '.form-controller > button.prev', formPrev);

var formSend = function() {
    const loader = $('.loader').children();
    loader.addClass('active');
    console.warn("Trying to send form datas.");
    $.post('dbctr/check_duplicate', $('form[name=main-form-apply]').serialize(), data => {
        if(parseInt(data) < 1) {
            console.info(`No duplicate applicated :: ${data}`);
            loader.addClass('active');
            $.post('dbctr/send_apply', $('form[name=main-form-apply]').serialize(), data => {
                if(data) {
                    setResultPage();
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
            .done(data => {
            })
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
            console.info(`You have alread applied this recruitment!`);
            modalConfig("알림", "이미 지원하셨습니다..!", {
                ok: {
                    text: "확인",
                    event: closeModal
                },
            });
            openModal();
        }
    }, 'json')
    .done(data => {
    })
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
}

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

var confirmApply = function() {
    if(checkFieldRequired('all')) {
        console.info("All requirements are passed!");
        modalConfig("알림", "지원서를 제출하시겠습니까?", {
            ok: {
                text: "예",
                event: formSend
            },
            cancel: {
                text: "아니오",
                event: closeModal
            }
        });
        openModal();

    } else {
        console.warn("One or more requirements are not passed.");
    }
}
$(document).on('click', '.form-controller > button.submit', confirmApply);

var closeModal = function() {
    $('.ui.modal').modal('hide');
}

var openModal = function() {
    $('.ui.modal').modal({
        inverted: true,
        blurring: true
    }).modal('show');
}

var getCurrentFormSection = function() {
    const target = $('.form-section.active');
    if(target.length > 1) { console.error("활성 폼 섹션은 하나여야 합니다."); return; }
    return parseInt(target.attr('data-no'));
}

var isEndOfFormSection = function() {
    const target = $('.form-section');
    if((target.length - 1) === getCurrentFormSection()) return true;
    else return false;
}

var checkFieldRequired = function(arg) {
    const section = arg === 'all' ? $('.form-section > .field > label, .form-section > .fields > label') : $(`.form-section[data-no=${arg}] > .field > label, .form-section[data-no=${arg}] > .fields > label`);
    let passed = 0;
    
    section.each((idx, e) => {
        if($(e).html().includes(" *")) {
            !checkRegEx($(e), true) && passed++;
        } else {
            !checkRegEx($(e), false) && passed++;
        }
    });

    return !passed;
}

var checkRegEx = function(element, required) {
    const target = element.next();
    const radioInputOth = element.siblings().find('.radio.input.other');
    const radioOth = radioInputOth.parent().siblings('.ui.radio.checkbox');
    let i = true;

    if(target.hasClass('fields')) {
        target.children().each((idx, e) => {
            if(!regex($(e).find('input, select, textarea'), required)) i = false;
        });

    } else {
        if(!regex(target.find('input, select, textarea'), required)) i = false;
    }
    
    
    if(radioOth.hasClass('checked') && !radioInputOth.val()) i = false;

    i ? element.parent($('.field')).removeClass('error') : element.parent($('.field')).addClass('error');

    return i;
}

var dismissFieldError = function() {
    const target = $(this);
    target.closest($('.field.error')).removeClass('error');
}
$(document).on('change', '.field > input, select, textarea', dismissFieldError);

let thisYear = new Date().getFullYear;
let thisMonth = new Date().getMonth + 1;

var regex = function(element, required) {
    const value = element.val();
    const type = element.attr('regex-type');
    const minLength = parseInt(element.attr('regex-minlength'));
    const maxLength = parseInt(element.attr('regex-maxlength'));

    let passed = true;

    if(required && !value) {
        return false;
    }

    if(type === 'email' && value) {
        passed = (/^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/i).test(value);
    
    } else if(type === 'phone-no' && value) {
        const trimmed = value.replace(/-/gi, '');
        passed = new RegExp(`^[0-9]{${minLength},${maxLength}}$`).test(trimmed);

    } else if(type === 'date-year' && value) {
        if(parseInt(value) && parseInt(value) >= 1970 && parseInt(value) <= 2019) { thisYear = parseInt(value); passed = true; }
        else passed = false;

    } else if(type === 'date-month' && value) {
        if(parseInt(value) && parseInt(value) >= 1 && parseInt(value) <= 12) { thisMonth = parseInt(value); passed = true; }
        else passed = false;

    } else if(type === 'date-day' && value) {
        const lastday = new Date(thisYear, thisMonth, 0).getDate();
        if(parseInt(value) && parseInt(value) >= 1 && parseInt(value) <= lastday) passed = true;
        else passed = false;
    }

    return passed;
}

var setResultPage = function() {
    const result = $('.content-result').children();
    closeModal();
    $('form').detach();
    $('.secondary-header').after(result);
    $(window).off('beforeunload');
}

var setRadioOther = function() {
    const target = $(this).parent().siblings('.ui.radio.checkbox');
    target.checkbox('check');
}
$(document).on('focus', '.radio.input.other', setRadioOther);