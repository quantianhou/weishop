define(['core', 'tpl'], function(core, tpl) {
    var modal = {
        params: {
            needrealname: 0,
            needmobile: 0,
            needsmscode: 0,
            card_id: "",
            encrypt_code: "",
        }
    };
    modal.init = function(params) {
        console.log(params);
        modal.params = params;
        $('#btnSubmit').click(function() {
            if ($('#btnSubmit').attr('stop')) {
                return
            }
            if (!$('#mobile').isMobile() && modal.params.needmobile == 1) {
                FoxUI.toast.show('请输入11位手机号码');
                return
            }
            core.json('member/activation/submit', {
                card_id: modal.params.card_id,
                encrypt_code: modal.params.encrypt_code,
                realname: $('#realname').val(),
                mobile: $('#mobile').val(),
                birth: $('#birth').val(),
                sms_code: $('#sms_code').val()
            }, function(ret) {
                if (ret.status != 1) {
                    FoxUI.toast.show(ret.result.message);
                    $('#btnSubmit').html('立即激活').removeAttr('stop');
                    return
                } else {
                    location.href = core.getUrl('member/activation/success')
                }
            }, false, true)
        });
        $('#btnSubmit1').click(function() {
            if ($('#btnSubmit1').attr('stop')) {
                return
            }
            if (!$('#mobile').isMobile() && modal.params.needmobile == 1) {
                FoxUI.toast.show('请输入11位手机号码');
                return
            }
            core.json('member/activation/submit1', {
                realname: $('#realname').val(),
                mobile: $('#mobile').val(),
                birth: $('#birth').val(),
                sms_code: $('#sms_code').val()
            }, function(ret) {
                if (ret.status != 1) {
                    FoxUI.toast.show(ret.result.message);
                    $('#btnSubmit1').html('立即绑定').removeAttr('stop');
                    return
                } else {
                    location.href = core.getUrl('member/activation/success1')
                }
            }, false, true)
        });
        $('#btnCode').click(function() {
            alert('11');
            if ($('#btnCode').hasClass('disabled')) {
                return
            }
            if (!$('#mobile').isMobile()) {
                FoxUI.toast.show('请输入11位手机号码');
                return
            }
            modal.seconds = 60;
            core.json('member/activation/verifycode', {
                mobile: $('#mobile').val(),
            }, function(ret) {
                FoxUI.toast.show(ret.result.message);
                if (ret.status != 1) {
                    $('#btnCode').html('获取验证码').removeClass('disabled').removeAttr('disabled')
                }
                if (ret.status == 1) {
                    modal.verifycode()
                }
            }, false, true)
        })
    };
    modal.verifycode = function() {
        modal.seconds--;
        if (modal.seconds > 0) {
            $('#btnCode').html(modal.seconds + '秒后重发').addClass('disabled').attr('disabled', 'disabled');
            setTimeout(function() {
                modal.verifycode()
            }, 1000)
        } else {
            $('#btnCode').html('获取验证码').removeClass('disabled').removeAttr('disabled')
        }
    };
    return modal
});