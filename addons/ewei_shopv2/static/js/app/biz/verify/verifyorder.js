define(['core'], function (core) {
    var modal = {
        page: 1,
        type: 0,
        offset: 0,
        keywords: ''
    };
    modal.initList = function (params) {
        modal.keywords = params.keywords;
        modal.initClick();
        if (window.orderid) {
            var elm = $(document).find(".fui-list-group[data-order='" + window.orderid + "']");
            if (window.remarksaler == 1) {
                elm.find(".icon-pin").show()
            } else if (window.remarksaler == 2) {
                elm.find(".icon-pin").hide()
            }
        }
        var leng = $.trim($('.container').html());
        if (leng == '') {
            modal.page = 1;
            modal.getList();
            modal.getList2();
        }
        $('.fui-content').infinite({
            onLoading: function () {
                modal.getList();
                modal.getList2();
            }
        })
    };
    modal.initClick = function () {
        $("#tab a").unbind('click').click(function () {
            var tab = $(this).data("tab");
            $(this).addClass("active").siblings().removeClass("active");
            $(".tab-content").hide();
            $("#tab_" + tab).show()
        });
        $(".fui-search-btn").unbind('click').click(function () {
            var keywords = $.trim($("#keywords").val());
            if (keywords == '') {
                FoxUI.toast.show("请输入搜索关键字");
                return
            }
            modal.keywords = keywords;
            modal.page = 1;
            $(".container").empty();
            modal.getList()
        });
        $("#keywords").bind('input propertychange', function () {
            var keywords = $.trim($(this).val());
            if (keywords == '') {
                modal.keywords = '';
                modal.page = 1;
                modal.offset = 0;
                $(".container").empty();
                modal.getList()
            }
        })
    };
    modal.getList2 = function () {

        core.json('verify/verifyorder/orderData2', {}, function (json) {
            if (json.status != 1) {
                return
            }
            var result = json.result;
            if (result.total < 1) {
                $('#content-empty2').show();
                $('#content-nomore2').hide();
                $('#content-more2').hide();
                $('.fui-content').infinite('stop')
            } else {
                $('#content-empty').hide();
                $('.fui-content').infinite('init');
                if (result.list.length <= 0 || result.list.length < result.pagesize) {
                    $('#content-more2').hide();
                    $("#content-nomore2").show();
                    $("#content-empty2").hide();
                    $('.fui-content').infinite('stop')
                } else {
                    $("#content-nomore2").hide()
                }
            }
            modal.page++;
            core.tpl('.coupon_container', 'tpl_order_coupon', result, modal.page > 1);
            FoxUI.loader.hide()
        }, false, true)

    };
    modal.getList = function () {
        var obj = {
            page: modal.page,
            keyword: modal.keywords,
            offset: modal.offset
        };
        if (obj.keyword != '') {
            obj.searchfield = modal.selectVal('searchfieid', true)
        }
        core.json('verify/verifyorder/orderData', obj, function (json) {
            if (json.status != 1) {
                return
            }
            var result = json.result;
            if (result.total < 1) {
                $('#content-empty').show();
                $('#content-nomore').hide();
                $('#content-more').hide();
                $('.fui-content').infinite('stop')
            } else {
                $('#content-empty').hide();
                $('.fui-content').infinite('init');
                if (result.list.length <= 0 || result.list.length < result.pagesize) {
                    $('#content-more').hide();
                    $("#content-nomore").show();
                    $("#content-empty").hide();
                    $('.fui-content').infinite('stop')
                } else {
                    $("#content-nomore").hide()
                }
            }
            modal.page++;
            core.tpl('.container', 'tpl_order', result, modal.page > 1);
            FoxUI.loader.hide()
        }, false, true)
    };
    modal.selectVal = function (elm, isVal) {
        if (isVal) {
            return $("#" + elm).find('option:selected').val()
        }
        return $("#" + elm).find('option:selected').text()
    };
    return modal
});