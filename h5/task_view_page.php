<?php
require_once '../common/__html__.php';

require_once '../common/common.php';
require_once '../common/page.php';
$task = param('task');
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
            class="sr-only">Close</span></button>
</div>
<div class="modal-body">
    <div class="task-detail" id="taskDetail">
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

<script type="text/javascript" src="../static/js/common/common.js"></script>
<!--<script type="text/javascript" src="../static/js/bootstrap-validator.js"></script>
<script type="text/javascript" src="../static/js/common/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../static/js/common/jquery.validate.extends.js"></script>-->
<script type="text/javascript" src="../static/js/devoops.js"></script>
<script type="text/javascript" src="../static/js/jsrender.js"></script>

<!--<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=AqPA2oEsRSrNWkWfakAI84dP"></script>-->
<script id="taskDetailTemplate" type="text/x-jsrender">
   <div class="row border-bottom" style="padding:5px 0 0 0;">
        <div class="col-sm-6 col-xs-6" style="">
            <span>期望送达</span>
            <div class="text-success" style="padding: 10px 0;font-size: 18px;">{{:~dateFormat(data.end_time)}} </div>
	</div>
        <div class="col-sm-6 col-xs-6 border-left">
            <span>配送费用</span>
            <div style="padding: 5px 0;">现付<span class="text-danger" style="font-size:20px;">{{>data.reward/100}}</span>元</div>
        </div>
    </div>
    <div class="row border-bottom" style="background-color: #f8f8f8; height: 10px;">
    </div>
    <div class="row border-bottom" style="padding: 5px;">
        <div class="col-sm-2 col-xs-4">
            订单编号:
        </div>
        <div class="col-sm-10 col-xs-8">
            <time>{{>data.id}}</time>
        </div>
    </div>
    <div class="row border-bottom" style="background-color: #f8f8f8; height: 10px;">
    </div>
    <div class="row border-bottom" style="padding: 5px;">
        <div class="col-sm-2 col-xs-4">
           购买要求:
        </div>
        <div class="col-sm-10 col-xs-8">
            <time>{{>data.description}}<time>
        </div>
    </div>
    <div class="row border-bottom" style="padding: 5px;">
        <div class="col-sm-2 col-xs-4">
            购买地址:
        </div>
        <div class="col-sm-10 col-xs-8">
            <time>{{>data.from_address}}</time>
        </div>
    </div>
    <div class="row border-bottom" style="background-color: #f8f8f8; height: 10px;">
    </div>
    <div class="row border-bottom" style="padding: 5px;">
        <div class="col-sm-2 col-xs-4">
           联系电话:
        </div>
        <div class="col-sm-10 col-xs-8">
            <time>{{>data.publisher_phone}}</time>
        </div>
    </div>
    <div class="row border-bottom" style="padding: 5px;">
        <div class="col-sm-2 col-xs-4">
           联系人:
        </div>
        <div class="col-sm-10 col-xs-8">
            <time>{{>data.publisher_name}}</time>
        </div>
    </div>
    <div class="row border-bottom" style="padding: 5px;">
        <div class="col-sm-2 col-xs-4">
            收货地址:
        </div>
        <div class="col-sm-10 col-xs-8">
            <time>{{>data.address}}</time>
        </div>
    </div>
    {{if data.status >= 2}}
    <div class="row border-bottom" style="background-color: #f8f8f8; height: 10px;">
    </div>
    <div class="row border-bottom" style="padding: 5px;">
        <div class="col-sm-2 col-xs-4">
           接单人电话:
        </div>
        <div class="col-sm-10 col-xs-8">
            <time>{{>data.accepter_phone}}</time>
        </div>
    </div>
    {{/if}}
    <div class="row border-bottom" style="background-color: #f8f8f8; height: 20px;">
    </div>

</script>

<script type="text/javascript">
    var taskId = '<?php echo $task;?>';
    $.ajax({
        url: '../action/get_task.php?id=' + taskId, //后台处理程序
        type: 'GET',         //数据发送方式
        dataType: 'json',     //接受数据格式
        success: function (data) {
            if (data && data.task) {
                $("#taskDetail").append(
                    $("#taskDetailTemplate").render({
                            data: data.task
                        },
                        {
                            dateFormat: function (m) {
                                return moment(m).format('MM-DD HH:mm')
                            }
                        })
                )
            }
        }
    });
</script>

