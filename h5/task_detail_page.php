<?php
require_once '../common/__html__.php';

require_once '../common/common.php';
require_once '../common/page.php';
$task = param('task');
$type = param('type');
$extra = $_SESSION['account_extra'];
$phone = $extra['contact_phone'];
?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
      <div class="navbar-nav text-center">
	 <a class="pull-left nav-title-left" href="javascript:history.go(-1)">返回</i></a>
         <span>订单详情</span>
         <a class="pull-right nav-title-right">&nbsp;</a>
     </div>
  </div>
</nav>
<div class="container task-detail" id="taskDetail">
</div>
<?php
require_once '../common/footer.php';
?>

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
    {{if data.type!=1 && data.status == 1}}
     <div class="row border-bottom">
        <div class="task-box-bottom text-center">
            <button type="button" class="btn btn-warning acceptTask"
                    style="margin:10px auto; background-color: #fff;color: #ec971f; width: 200px"><strong>确认接单</strong></button>
        </div>
     </div>
    {{else data.type == 1 && data.status == 1}}
     <div class="row border-bottom">
        <div class="task-box-bottom text-center">
            <button type="button" class="btn btn-warning cancelTask"
                    style="margin:10px auto; background-color: #fff;color: #ec971f; width: 200px"><strong>取消订单</strong></button>
        </div>
    </div>
    {{else data.type==1 && data.status == 2}}
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
     <div class="row border-bottom">
        <div class="task-box-bottom text-center">
            <button type="button" class="btn btn-warning finishTask"
                    style="margin:10px auto; background-color: #fff;color: #ec971f; width: 200px"><strong>完成</strong></button>
        </div>
    </div>
    {{/if}}
    <div class="row border-bottom" style="background-color: #f8f8f8; height: 20px;">
    </div>
</script>

<script type="text/javascript">
   var taskId = '<?php echo $task;?>';
   var phone = '<?php echo $phone;?>';
   var type = '<?php echo $type;?>';
   $.ajax({
       url:'../action/get_task.php?id='+taskId, //后台处理程序
       type:'GET',         //数据发送方式
       dataType:'json',     //接受数据格式
       success:function(data) {
          if (data && data.task) {
              data.task.type = type;
              $("#taskDetail").append(
                  $("#taskDetailTemplate").render({
			data: data.task},
			{dateFormat: function(m) {
			  return moment(m).format('MM-DD HH:mm') 
			}
		  })
               )
	      $('.acceptTask').click(function() {
		   $.ajax({
	                url:'../action/accept_task.php?taskId='+data.task.id, //后台处理程序
        	        //url:'../action/accept_task.php?taskId=2', //后台处理程序
			type:'POST',         //数据发送方式
            	        dataType:'json',     //接受数据格式
            		success:function(res) {
			   ajaxGoToPage("my_task_page.php?type=0&phone="+phone);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			   alert("mm")
			}
		   });
	      });
	      $('.cancelTask').click(function() {
		   $.post('../action/close_task.php', {taskId: data.task.id}, function() {
			ajaxGoToPage("publish_task_page.php?taskId="+data.task.id);	
		   })
  	      });
	      $('.finishTask').click(function() {
                   $.post('../action/finish_task.php', {taskId: data.task.id}, function() {
                        //ajaxGoToPage("publish_task_page.php?taskId="+data.task.id);
			location.reload();
                   })
              })

          }
        }
   });
</script>

