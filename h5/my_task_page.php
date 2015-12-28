<?php
require_once '../common/__html__.php';

require_once '../common/common.php';
require_once '../common/page.php';
session_start();
$sessionId = session_id();
$phone = param('phone');
$type = param('type');
?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-nav text-center">
            <a class="pull-left nav-title-left" href="../index.php">返回主页</a>
            <span>我的<?php echo $type == 1 ? '订单' : '接单'; ?></span>
        </div>
    </div>
</nav>

<div style="margin:0; padding:0;">
    <div class="container" id="myTaskList">
    </div>
    <div class="text-center">
        <a type="button" id="loadingMore" class="btn btn-default" style="width:200px; height:25px; padding:2px 12px;"
           data-toggle="button" aria-pressed="false" autocomplete="off">
            查看更多
        </a>
    </div>
</div>

<?php
require_once '../common/footer.php';
?>

<script type="text/javascript" src="../static/js/common/common.js"></script>
<script type="text/javascript" src="../static/js/bootstrap-validator.js"></script>
<script type="text/javascript" src="../static/js/devoops.js"></script>
<script type="text/javascript" src="../static/js/jsrender.js"></script>

<script id="taskTemplate" type="text/x-jsrender">
   <div class="task-box" data={{>id}}>
        <div class="task-box-item row">
            <div class="col-sm-2 col-xs-2 task-item-first" style="padding-left:25px; padding-top:10px;">
                <!--<div onclick="popup" class="circle orange">代购</div>-->
		<img src='../static/img/task.ico'></img>
	    </div>
            <div class="col-sm-10 col-xs-10" style="padding-top: 15px;">
                <div style="padding-bottom: 12px;" class="task-border">
                    {{if status==1}}[已发布]{{else status==2}}[已接单]{{else status==3}}[完成]{{else}}[已关闭]{{/if}}<div class="pull-right"><span class="grade">{{>reward/100}}<small>元</small></span></div>
                </div>
            </div>
        </div>
        <div class="task-box-item row" style="padding: 7px;">
            <div class="col-sm-2 col-xs-2 task-item-first" style="text-align: right;">买:</div>
            <div class="col-sm-10 col-xs-10"><span title="{{>description}}"><time>{{>title}}</time></span></div>
        </div>
        <div class="task-box-item row" style="padding: 7px;">
            <div class="col-sm-2 col-xs-2 task-item-first" style="text-align: right;">到:</div>
            <div class="col-sm-10 col-xs-10"><span><time>{{>address}}</time></span></div>
        </div>
        <div class="task-box-item">
             <div class="task-box-bottom text-center">
               {{if type==1}} <span style="font-size:15px;color:#ff8800;"><strong>请耐心等带自由快递人接单</strong></span>
                <time>发布时间 {{>create_time}}</time>{{else}}
		<time>期望送达时间 {{>end_time}}</time>
		{{/if}}
             </div>
        </div>
   </div>

</script>
<script type="text/javascript">
    var page = 1;
    var phone = '<?php echo $phone;?>';
    var type = '<?php echo $type;?>';
    $("#loadingMore").click(function () {
        $.ajax({
            url: '../action/get_my_task.php?phone=' + phone + "&type=" + type + "&page=" + page, //后台处理程序
            type: 'GET',         //数据发送方式
            dataType: 'json',     //接受数据格式
            success: function (data) {
                if (data && data.result && data.result.length > 0) {
                    $.each(data.result, function (k, v) {
                        v.type = type;
                    });
                    $("#myTaskList").append(
                        $("#taskTemplate").render(data.result)
                    )
                    $('.task-box').click(function () {
                        ajaxGoToPage("task_detail_page.php?task=" + $(this).attr('data') + '&type=' + type);
                    })
                    page++;
                    if (data.result.length < 20)
                        $("#loadingMore").hide();
                } else
                    $("#loadingMore").hide();
            }
        });
    }).click();

</script>

