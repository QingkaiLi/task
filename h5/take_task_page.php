<?php
require_once '../common/__html__.php';

require_once '../common/common.php';
require_once '../common/page.php';
session_start();
$sessionId = session_id();
$account = $_SESSION['account_extra'];
$phone = $account['contact_phone'];
?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-nav text-center">
            <a class="pull-left nav-title-left" href="javascript:history.go(-1)">返回主页</a>
            <span>接单</span>
	 <span class="pull-right nav-title-right"><a href="my_task_page.php?type=0&phone=<?php echo $phone; ?>"
                                                 style="margin-right:5px;">我的接单</a>
		<a href="javascript:location.reload()"><i class="fa fa-refresh"></i></a></span>
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
<!--<script type="text/javascript" src="../static/js/bootstrap-validator.js"></script>-->
<script type="text/javascript" src="../static/js/devoops.js"></script>
<script type="text/javascript" src="../static/js/jsrender.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=AqPA2oEsRSrNWkWfakAI84dP"></script>

<script id="taskTemplate" type="text/x-jsrender">
{{for}}
<div class="task-box">
        <div class="task-box-item row">
            <div class="col-sm-2 col-xs-2 task-item-first" style="padding-left:25px; padding-top:10px;">
                <!--<div onclick="popup" class="circle" style="background-color:#8BEF43;">预约</div>-->
		<img src='../static/img/task.ico'></img>
	    </div>
            <div class="col-sm-10 col-xs-10" style="padding-top: 15px;">
                <div style="padding-bottom: 12px;" class="task-border">
                    距您{{>distance}}千米<div class="pull-right"><span class="grade">{{>reward/100}}<small>元</small></span></div>
                </div>
            </div>
        </div>
        <div class="task-box-item row" style="padding: 7px;">
            <div class="col-sm-2 col-xs-2 task-item-first" style="text-align: right;"></div>
            <div class="col-sm-10 col-xs-10"><span style="font-size:15px;color:#ff8800;">期望送达时间:{{:~dateFormat(end_time)}}</span></div>
        </div>
        <div class="task-box-item row" style="padding: 7px;">
            <div class="col-sm-2 col-xs-2 task-item-first" style="text-align: right;">起:</div>
            <div class="col-sm-10 col-xs-10"><span><time>{{>from_address}}</time></span></div>
        </div>
        <div class="task-box-item row" style="padding: 7px;">
            <div class="col-sm-2 col-xs-2 task-item-first" style="text-align: right;">到:</div>
            <div class="col-sm-10 col-xs-10"><span><time>{{>address}}</time></span></div>
        </div>
        <div class="task-box-item">
             <div class="task-box-bottom text-center">
                <span style="font-size:15px;color:#ff8800;"><strong>重要说明:{{>description}}</strong></span>
             </div>
        </div>
        <div class="task-box-item">
             <div class="task-box-bottom text-center">
                <button type="button" class="btn btn-warning acceptTask" data={{>id}}
			 style="background-color: #fff;color: #ec971f;width: 200px"><strong>接单</strong></button>
             </div>
        </div>
   </div>
{{/for}}

</script>
<script type="text/javascript">
    var page = 1;
    var phone = '<?php echo $phone;?>';
    var lng = 0;
    var lat = 0;
    var geolocation = new BMap.Geolocation();
    var geoc = new BMap.Geocoder();

    geolocation.getCurrentPosition(function (r) {
        if (this.getStatus() == BMAP_STATUS_SUCCESS) {
            lng = r.point.lng;
            lat = r.point.lat;
            $('#loadingMore').click();
        }
        else {
            alert('failed' + this.getStatus());
        }
    }, {enableHighAccuracy: true})

    $("#loadingMore").click(function () {
        if (lng == 0 || lat == 0) return;
        $.ajax({//lng=121.427677&lat=31.231177
            url: '../action/get_valid_task.php?lng=' + lng + '&lat=' + lat + '&page=' + page, //后台处理程序
            type: 'GET',         //数据发送方式
            dataType: 'json',     //接受数据格式
            success: function (data) {
                if (data && data.tasks && data.tasks.length > 0) {
                    $("#myTaskList").append(
                        $("#taskTemplate").render([data.tasks],
                            {
                                dateFormat: function (m) {
                                    return moment(m).format('MM-DD HH:mm')
                                }
                            })
                    )


                    $('.acceptTask').click(function () {
                        ajaxGoToPage("task_detail_page.php?task=" + $(this).attr('data'));
                    })
                    page++;
                    if (data.tasks.length < 20)
                        $("#loadingMore").hide();
                } else
                    $("#loadingMore").hide();
            }
        });
    })

</script>
<script type="text/javascript">/*
     var geolocation = new BMap.Geolocation();
     geolocation.getCurrentPosition(function(r){
     if(this.getStatus() == BMAP_STATUS_SUCCESS){
     alert('您的位置：'+r.point.lng+','+r.point.lat);
     } else {
     alert('failed'+this.getStatus());
     }
     },{enableHighAccuracy: true})*/
</script>
