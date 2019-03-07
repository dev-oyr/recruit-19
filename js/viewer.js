var APP = {};
var loader = null;

APP._dataCtrlProp = {
    getApplications: {
        val: "applicationList",
        url: "dbctr/get_applications",
        callback: function(resolve, reject, data) {
            if(data) {
                resolve(data);
            } else {
                reject(new Error("Failed to get application list"));
            }
        }
    }
}

APP.dataCtrl = async function (opt = {}) {
    if(opt.type === undefined) { console.error("type is undefined"); return; }

    APP[APP._dataCtrlProp[opt.type].val] = await new Promise(function(resolve, reject) {
        var _url = APP._dataCtrlProp[opt.type].url;
        opt.args !== undefined && (opt.args.forEach(e => {
            _url += "/" + e;
        }));
        $.post(_url, APP._dataCtrlProp[opt.type].callback.bind(this, resolve, reject));
    });
}

$(document).ready(() => {
    loader = $('.loader').children();
    init();
    toggleSidebar();
});

var init = async function() {
    loader.addClass('active');
    $('.ui.sidebar').sidebar({
        dimPage: false,
        transition: 'overlay',
        exclusive: false,
    });
    await APP.dataCtrl({type: 'getApplications', args: [0, 1]});
    const target = $('.ui.sidebar > .sidebar-items');
    
    APP.applicationList.forEach(e => {
        const _name = e.apply_name;
        const _timestamp = e.apply_date.substring(2, e.apply_date.length);
        const newItem = `<a class="item">${_name} <p>${_timestamp}</p></a>`
        target.append(newItem);
    });

    loader.removeClass('active');
}

var currentPage = undefined;

var itemClick = function() {
    $(this).addClass('active');
    $(this).siblings().removeClass('active');
    const _sidebar = $('.ui.sidebar');
    const _mobileInfo = $('.pusher > main > .app-contents > .info-mobile');
    const _table = $('.table-section');
    const _index = $(this).index();
    _sidebar.sidebar('hide');
    
    const selectedData = APP.applicationList[_index];
    currentPage = _index;

    _mobileInfo.removeClass('active');
    _table.addClass('active');

    Object.keys(selectedData).forEach((key, idx) => {
        let _value = selectedData[key];

        if(key === 'apply_gender') {
            _value = _value === '0' ? '남' : '여';

        } else if(key === 'apply_military') {
            _value = _value === '0' ? '미필' : '군필(면제 포함) 또는 해당없음';

        }

        $(`td[name="${key}"]`).html(_value);
    });

    const _url = `${encodeURIComponent(selectedData.apply_name)}/${encodeURIComponent(selectedData.apply_major)}/${encodeURIComponent(selectedData.phone_no)}`;
    const btnTarget = $('#select_recruit');

    btnTarget.addClass('loading');
    btnTarget.css({'pointer-events': 'none'});
    $.post(`dbctr/check_applicant_selected/${_url}`, data => {
        if(parseInt(data) > 0) {
            btnTarget.removeClass('primary');
            btnTarget.addClass('red');
            btnTarget.html('취소');
        
        } else {
            btnTarget.removeClass('red');
            btnTarget.addClass('primary');
            btnTarget.html('선발!');
        }
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
        btnTarget.removeClass('loading');
        btnTarget.css({'pointer-events': ''});
    });

}
$(document).on('click', '.ui.sidebar > .sidebar-items > .item', itemClick);

$(window).resize(function(){
    toggleSidebar();
});

var swipeFunc = {
    swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
        const _sidebar = $('.ui.sidebar');
        if(direction === 'right') {
            _sidebar.sidebar('show');
        } else {
            _sidebar.sidebar('hide');
        }
    },
    threshold: 128,
    allowPageScroll: 'vertical'
}

var toggleSidebar = function() {
    const _sidebar = $('.ui.sidebar');
    const _mobileInfo = $('.pusher > main > .app-contents > .info-mobile');
    const _table = $('.table-section');

    if ($(window).width() <= 600){
        _sidebar.removeClass('visible');
        _sidebar.addClass('left');
        $(".pusher").swipe(swipeFunc).swipe('enable');
        if(currentPage === undefined) {
            _mobileInfo.addClass('active');
            _table.removeClass('active');
        }

    } else {
        _sidebar.sidebar('hide');
        $(".pusher").swipe('disable');
        _sidebar.removeClass('left');
        _sidebar.addClass('visible');
        _mobileInfo.removeClass('active');
        _table.addClass('active');
    }
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

var closeModal = function() {
    $('.ui.modal').modal('hide');
}

var openModal = function() {
    $('.ui.modal').modal({
        inverted: true,
        blurring: true
    }).modal('show');
}

var downloadExcel = function() {
    const name = encodeURIComponent($('td[name="apply_name"]').html());
    const major = encodeURIComponent($('td[name="apply_major"]').html());
    const date = encodeURIComponent(APP.applicationList[currentPage].apply_date);
    
    location.href = `excel/download/${name}/${major}/${date}`;

    // $.post(`excel/download/${name}/${major}/${date}`, data => {
    //     console.log(data);
    // });
}
$(document).on('click', '#download_excel', downloadExcel);

var selectApplicant = function() {
    closeModal();

    let name = APP.applicationList[currentPage].apply_name ? APP.applicationList[currentPage].apply_name : 'null';
    let birthday = APP.applicationList[currentPage].apply_birthday ? APP.applicationList[currentPage].apply_birthday : 'null';
    let email = APP.applicationList[currentPage].apply_email ? APP.applicationList[currentPage].apply_email : 'null';
    let phone = APP.applicationList[currentPage].phone_no ? APP.applicationList[currentPage].phone_no : 'null';
    let gender = APP.applicationList[currentPage].apply_gender ? APP.applicationList[currentPage].apply_gender : 'null';
    let military = APP.applicationList[currentPage].apply_military ? APP.applicationList[currentPage].apply_military : 'null';
    let major = APP.applicationList[currentPage].apply_major ? APP.applicationList[currentPage].apply_major : 'null'; 

    const _url = `${encodeURIComponent(name)}/${encodeURIComponent(birthday)}/${encodeURIComponent(phone)}/${encodeURIComponent(gender)}/${encodeURIComponent(major)}`;
    const btnTarget = $('#select_recruit');

    btnTarget.addClass('loading');
    btnTarget.css({'pointer-events': 'none'});
    $.post(`dbctr/select_applicant/${_url}`, data => {
        if(data) {
            btnTarget.removeClass('primary');
            btnTarget.addClass('red');
            btnTarget.html('취소')

        } else {
            modalConfig("오류!", "변경되지 않았습니다.<br>잠시 후 다시 시도 해주세요.", {
                ok: {
                    text: "확인",
                    event: closeModal
                },
            });
            openModal();
        }
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
        $('#select_recruit').removeClass('loading');
        btnTarget.css({'pointer-events': ''});
    });
}

var unselectApplicant = function() {
    closeModal();

    let name = APP.applicationList[currentPage].apply_name ? APP.applicationList[currentPage].apply_name : 'null';
    let birthday = APP.applicationList[currentPage].apply_birthday ? APP.applicationList[currentPage].apply_birthday : 'null';
    let email = APP.applicationList[currentPage].apply_email ? APP.applicationList[currentPage].apply_email : 'null';
    let phone = APP.applicationList[currentPage].phone_no ? APP.applicationList[currentPage].phone_no : 'null';
    let gender = APP.applicationList[currentPage].apply_gender ? APP.applicationList[currentPage].apply_gender : 'null';
    let military = APP.applicationList[currentPage].apply_military ? APP.applicationList[currentPage].apply_military : 'null';
    let major = APP.applicationList[currentPage].apply_major ? APP.applicationList[currentPage].apply_major : 'null'; 

    const _url = `${encodeURIComponent(name)}/${encodeURIComponent(birthday)}/${encodeURIComponent(phone)}/${encodeURIComponent(gender)}/${encodeURIComponent(major)}`;
    const btnTarget = $('#select_recruit');
    

    btnTarget.addClass('loading');
    btnTarget.css({'pointer-events': 'none'});
    $.post(`dbctr/unselect_applicant/${_url}`, data => {
        if(data) {
            btnTarget.removeClass('red');
            btnTarget.addClass('primary');
            btnTarget.html('선발!')

        } else {
            modalConfig("오류!", "변경되지 않았습니다.<br>잠시 후 다시 시도 해주세요.", {
                ok: {
                    text: "확인",
                    event: closeModal
                },
            });
            openModal();
        }
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
        $('#select_recruit').removeClass('loading');
        btnTarget.css({'pointer-events': ''});
    });
}

var controlApplicant = function() {
    if($(this).hasClass('primary')) {
        modalConfig("경고", "이 지원자를 선발 하시겠습니까?", {
            ok: {
                text: "예",
                event: selectApplicant
            },
            cancel: {
                text: "아니오",
                event: closeModal
            }
        });
        openModal();

    } else {
        modalConfig("경고", "이 지원자를 선발 목록에서 제외 하시겠습니까?", {
            ok: {
                text: "예",
                event: unselectApplicant
            },
            cancel: {
                text: "아니오",
                event: closeModal
            }
        });
        openModal();
    }
}
$(document).on('click', '#select_recruit', controlApplicant);