var choseAll = true;
// 当前选择的门店数量
var selectedBranch = 0;
$("#branchName").bind("keydown", function(e) {
    e = e || event;
    if (e.keyCode == 13) {
        var name = $("#branchName").val();
        var flagMshop = '';
        if ($("#flagMshop")) {
            flagMshop = $("#flagMshop").val();
        }
        // 查找门店
        getBranch(false,name,flagMshop);
    }
});
function searchBranch() {
    var name = $("#branchName").val();
    var flagMshop = '';
    // if ($("#flagMshop")) {
    //     flagMshop = $("#flagMshop").val();
    // }
    console.log(name);
    getBranch(false,name,flagMshop);
}
function getBranch(loadRight,name,flagMshop) {
    // name = name.replace(/[ ]/g, "");
    // console.log(name);
    var url = $("#allBranchUrl").val();
    $.ajax({
        url : url,
        type : "GET",
        data : {
            branchName : name,
            flagMshop : flagMshop
        },
        success : function(data) {
            console.log(data);
            $("#ulId").html("");
            data = eval("("+data+")");
            if(data.status)
            {
                var data2 = data.result.childBranchList;
                $("#searchInfoId").remove();
                if(data2.length <= 0 || !data2)
                {
                    $("#ulId").append(
                        "<li title='未查询到任何数据' id='searchInfoId' style='color:gray;padding-left: 30px;padding-top:25px;'>未查询到任何数据</li>"
                    );
                    return;
                }else{
                    fillLeftBranchData(loadRight,data);
                }
            }

        }
    });
}

// 标记第几次刷新门店信息 1.第一次 2.第二次
var op = 1;
var currentTotle;

// 左侧选中门店
function selectBranch($this) {
    var $next = $this.next();
    var data = $next.attr("data");
    var $li = $("#selectUlId").find("li");
    for ( var t = 0; t < $li.length; t++) {
        var $liHtml = $($li[t]);
        var selectedData = $liHtml.find("span").attr("data");
        if (data == selectedData) { // 发现已经选中这个门店
            return;
        }
    }
    var name = $next.attr("title");
    var showName = "";
    if (name.length > 18) {
        showName = name.substr(0, 18) + "...";
    } else {
        showName = name;
    }
    var vHtml = '<li class="showLiClass"><input type="hidden" value="'+data+'" name="storeid[]"><span title="'
        + name
        + '" data="'
        + data
        + '">'
        + showName
        + '</span>'
        + '<a style="float:right;margin-right:8px;cursor:pointer;"><img src="../../addons/ewei_shopv2/static/images/del.png"></a></li>';
    $("#selectUlId").append(vHtml);
    selectedBranch = selectedBranch + 1;
    changeBranchInfo();
}

// 左侧子checkbox选中事件
$("#ulId").on("click", "[name='checkBoxInputName']", function() {
    var thisValue = $(this).is(':checked');
    if (thisValue) { // 选中当前门店
        selectBranch($(this));
    } else { // 取消当前门店
        cancelSelectedBranch($(this));
        choseAll = true;// 全部选中
    }
});

// 左侧取消门店操作
function cancelSelectedBranch($this) {
    var $next = $this.next();
    var data = $next.attr("data");
    var $li = $("#selectUlId").find("li");
    for ( var t = 0; t < $li.length; t++) {
        var $liHtml = $($li[t]);
        var selectedData = $liHtml.find("span").attr("data");
        if (data == selectedData) { // 发现已经选中这个门店,则取消
            $liHtml.remove();
        }
    }
    selectedBranch = selectedBranch - 1;
    changeBranchInfo();
}

// 设置门店时，取消所选门店
$("#selectUlId").on("click", 'a', function() {
    removeSelectedBranch($(this));
    $(this).parent().remove();
    choseAll = true;// 全部选中
});

// 取消所选门店
function removeSelectedBranch($this) {
    var selectedData = $this.parent().find("span").attr("data");
    var $checkBox = $("[name='checkBoxInputName']");
    for ( var t = 0; t < $checkBox.length; t++) {
        var $span = $($checkBox[t]).next();
        var data = $span.attr("data");
        if (selectedData == data) {
            $($checkBox[t]).prop("checked", false);
        }
    }
    selectedBranch = selectedBranch - 1;
    changeBranchInfo();
}

function changeBranchInfo() {
    var flag = true;
    var childLiClass = $(".childLiClass");
    for ( var i = 0; i < childLiClass.length; i++) {
        var isSel = $(childLiClass[i]).find(".checkBoxInputClass").prop("checked");
        if (!isSel) {
            flag = false;
            break;
        }
    }
    if (flag) {
        $("#parentCheckBoxId").prop("checked", true);
        choseAll = false;
    } else {
        $("#parentCheckBoxId").prop("checked", false);
    }
}

// 不管第几次请求数据，先把左侧checkbox数据填充满，方法通用
function fillLeftBranchData(loadRight,data) {
    data = data.result;
    var data2 = data.childBranchList;
    var vHtml = "";
    if (data.parentBranchList == null) { return; }
    var parent = data.parentBranchList[0];
    var name = parent.name;
    var showName = "";
    if (name.length > 18) {
        showName = name.substr(0, 18) + "...";
    } else {
        showName = name;
    }
    vHtml += '<li class="parentLiClass">' + '<input id="parentCheckBoxId" type="checkbox" class="checkBoxInputClass">'
        + '<span title="' + showName + '" style="margin-left:2px;">' + name + '</span></li>';
    for ( var i = 0; i < data2.length; i++) {
        var branch = data2[i];
        var name = branch.storename;
        var showName = "";
        if (name.length > 18) {
            showName = name.substr(0, 18) + "...";
        } else {
            showName = name;
        }
        vHtml += '<li class="childLiClass">'
            + '<input name="checkBoxInputName" type="checkbox" class="checkBoxInputClass">' + '<span title="'
            + name + '" data="' + branch.id + '" style="margin-left:2px;">' + showName + '</span>' + '</li>';
    }
    $("#ulId").append(vHtml);
    choseAll = true;
    console.log(loadRight);
    if(loadRight)
    {
        console.log( 'item_id:'+$("#item_id").val());
        $.ajax({
            url : $("#selectedBranchUrl").val(),
            type : "GET",
            data : {
                id :  $("#item_id").val(),
                // id :  15,
            },
            success : function(data) {
                console.log('1'+data);
                data = eval("("+data+")");
                console.log(data);
                if (data.status == 'OK') {
                    var vHtml = '';
                    var ids2 = [];
                    var length = data.result.branchList.length;
                    console.log(length);
                    // for ( var t = 0; t < length; t++) {
                    //     var a = data.branchList[t];
                    //     console.log(a);
                    //
                    //     vHtml += '<li class="showLiClass"><span title="' + name + '" data="' + a.id + '">'
                    //         + showName + '</span>'
                    //         + '<a style="float:right;margin-right:28px;cursor:pointer;" href="javascript:void(0)"><img src="../../addons/ewei_shopv2/static/images/del.png"></a></li>';
                    //     ids2.push(a.id);
                    // }
                    // $("#selectUlId").append(vHtml);
                    selectedBranch =length;

                    //左侧已选门店勾上
                    var $childLi = $(".childLiClass");
                    for ( var i = 0; i < length; i++) {
                        var a = data.result.branchList[i];
                        console.log(a);
                        for ( var j = 0; j < $childLi.length; j++) {
                            var spanData = $($childLi[j]).find("span").attr("data");
                            console.log(spanData);
                            if (spanData == a) {
                                $($childLi[j]).find(".checkBoxInputClass").prop("checked", true);
                                selectBranch($($childLi[j]).find(".checkBoxInputClass"));
                            }
                        }
                    }

                    changeBranchInfo();
                }
            }
        });
    }
}
// 左侧全选按钮的事件
$("#ulId")
    .on(
        "click",
        "#parentCheckBoxId",
        function() {

            if (choseAll) {

                var childLiClass = $("#ulId").find(".childLiClass");
                for ( var t = 0; t < childLiClass.length; t++) {
                    var $childClass = $(childLiClass[t]);
                    $childClass.find(".checkBoxInputClass").prop("checked", true);
                    var data = $childClass.find("span").attr("data");
                    var showLiClass = $(".showLiClass");
                    var flag = true;
                    for ( var i = 0; i < showLiClass.length; i++) {
                        var showData = $(showLiClass[i]).find("span").attr("data");
                        if (data == showData) { // 选中左侧一个checkbox，右侧如果存在，直接过滤掉
                            flag = false;
                        }
                    }
                    if (flag) {
                        var a = $childClass.find("span").attr("data");
                        var name = $childClass.find("span").attr("title");
                        var showName = "";
                        if (name.length > 18) {
                            showName = name.substr(0, 18) + "...";
                        } else {
                            showName = name;
                        }
                        //<input type='hidden' name='storeid[]' value='"+a+"'/>
                        //<!--cmlove-->
                        var vHtml = "<input type='hidden' name='storeid[]' value='"+a+"' /><span style='float: left; display: inline-block;' title='" + name + "' value='"+ a +"' data='" + a + "' name=storeid[]>" + showName + "</span>";
                        $("#selectUlId")
                            .append(
                                "<li class='showLiClass' style='clear: both'>"
                                + vHtml
                                + "<a style='float:right;margin-right:8px;cursor:pointer;'><img src=\"../../addons/ewei_shopv2/static/images/del.png\"></a><li>");
                        selectedBranch = parseInt(selectedBranch) + 1;

                    }
                }
                choseAll = false;// 全部非选中
            } else { // 全部非选中

                var childLiClass = $("#ulId").find(".childLiClass");
                for ( var t = 0; t < childLiClass.length; t++) {
                    var $childClass = $(childLiClass[t]);
                    $childClass.find(".checkBoxInputClass").prop("checked", false);
                    var data = $childClass.find("span").attr("data");
                    var showLiClass = $(".showLiClass");
                    for ( var i = 0; i < showLiClass.length; i++) {
                        var showData = $(showLiClass[i]).find("span").attr("data");
                        if (data == showData) { // 反选左侧一个checkbox，右侧如果存在，则去掉
                            $(showLiClass[i]).remove();
                            selectedBranch = parseInt(selectedBranch) - 1;

                        }
                    }
                }
                choseAll = true;// 全部选中
            }
        });
function cleanSelected() {

    $("#ulId").find(".checkBoxInputClass").prop("checked", false);
    $("#selectUlId").html("");
    choseAll = true;// 全部选中
}