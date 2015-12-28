<?php
require_once '../common/__html__.php';

require_once '../common/common.php';
require_once '../common/page.php';

$id = param('id');
if ($id == null) {
	echo "<span><strong><b>任务不存在</b></strong></span>";
	exit();
} else {
	$r = TaskModel::getTaskById($id);
	$now = date("Y-m-d H:i:s", time());
	if ($r == null || $r['status'] != 1 || strtotime($now) >= strtotime($r['end_time'])) {
		echo "<span><strong><b>任务不存在或者已失效</b></strong></span>";
		exit();
	}
}
?>
<div class="container">
  <form class="form-horizontal" role="form" name="myform" method="post" action="">
    <div id="legend" class="col-sm-12">
        <legend class="">接受任务</legend>
    </div>
    <input type="hidden" name="taskId" value="<?php echo $id;?>">
    <div class="form-group">
          <!-- Text input-->
          <label class="col-sm-2 control-label">任务标题</label>
          <div class="col-sm-10">
            <input type="text" class="input-xlarge" value="<?php echo $r['title'];?>" readonly>
          </div>
    </div>
    <div class="form-group">
          <!-- Text input-->
          <label class="col-sm-2 control-label">任务奖励</label>
          <div class="col-sm-10">
            <input type="text" class="input-xlarge" value="<?php echo ($r['reward']/100);?>" readonly>
            <span>元</span>
          </div>
    </div>
    <div class="form-group">
          <!-- Text input-->
          <label class="col-sm-2 control-label">开始时间</label>
          <div class="col-sm-10">
            <input type="text" class="input-xlarge" value="<?php echo $r['start_time'];?>" readonly>
          </div>
    </div>
    <div class="form-group">
          <!-- Text input-->
          <label class="col-sm-2 control-label">过期时间</label>
          <div class="col-sm-10">
            <input type="text" class="input-xlarge" value="<?php echo $r['end_time'];?>" readonly>
          </div>
    </div>
    <div class="form-group">
          <!-- Text input-->
          <label class="col-sm-2 control-label">完成地点</label>
          <div class="col-sm-10">
            <input type="text" class="input-xlarge" value="<?php echo $r['address'];?>" readonly>
          </div>
    </div>
    <div class="form-group">
          <!-- Text input-->
          <label class="col-sm-2 control-label">任务描述</label>
          <div class="col-sm-10 textarea">
            <textarea type="" class="" value="" readonly><?php echo $r['description'];?></textarea>
          </div>
    </div>
    <div class="form-group">
          <!-- Text input-->
          <label class="col-sm-2 control-label" for="acceptPhone"><span class="requiredIco">*</span>联系手机</label>
          <div class="col-sm-10">
            <input type="text" class="ui-input easyui-validatebox" data-options="required:true,validType:'mobile'" id="acceptPhone" name="acceptPhone"/>
          </div>
    </div>
    <div class="form-group">
          <!-- Text input-->
          <label class="col-sm-2 control-label" for="verifyCode"><span class="requiredIco">*</span>验证号码</label>
          <div class="col-sm-10">
            <input type="text" class="ui-input easyui-validatebox" data-options="required:true" id="verifyCode" name="verifyCode"/>
            <input type="button" class="btn btn-default" id="getVerifyCode" value="获取验证码">
          </div>
    </div>
    <div class="form-group">
          <!-- Button -->
          <div class="col-sm-10 col-sm-offset-2">
          	<input type="button" class="btn btn-primary" id="acceptTask" value="接受任务" />
          </div>
    </div>
  </form>
</div>
<?php
require_once '../common/footer.php';
?>

<script type="text/javascript" src="../static/js/common/common.js"></script>
<script type="text/javascript" src="../static/js/common/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../static/js/common/jquery.validate.extends.js"></script>
<script type="text/javascript" src="../static/js/devoops.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	// 发布任务
	$('#acceptTask').on("click", function(){
		var params = common.Get_parameter({form:'.form-horizontal'});
		if ( $('.form-horizontal').form('validate') ){
			//params = common.Get_parameter({form:'.F_table'});
		} else {
			alert('数据不符合要求');
			return;
		}
		$.ajax({
      		url:'../action/accept_task.php', //后台处理程序
        	type:'post',         //数据发送方式
       		dataType:'json',     //接受数据格式
       		data:params,         //要传递的数据
       		success:acceptTaskCb  //回传函数(这里是函数名)
     	});
	});
	function acceptTaskCb (json)  //回传函数实体，参数为XMLhttpRequest.responseText
	{
		if (json.error != 0) {
			alert(json.message);
		} else {
			// 页面需要<body></body>标签，这个函数实际上是触发一个A标签事件
			ajaxGoToPage("list_tasks_page.php");
		}
	}
	
	$('#getVerifyCode').on("click", function(){
		var params = {};
		params['phone'] = $('#acceptPhone').val();
		$.ajax({
      		url:'../action/get_verify_code.php', //后台处理程序
        	type:'post',         //数据发送方式
       		dataType:'json',     //接受数据格式
       		data:params,         //要传递的数据
       		success:getVerifyCodeCb  //回传函数(这里是函数名)
     	});
	});
	
	var timerID = null;
	var seconds = 60;
	function countDownTimer() {
		seconds --;
		if (seconds < 1) {
			$('#getVerifyCode').val('获取验证码');
			clearInterval(timerID);
			$('#getVerifyCode').attr('disabled', false);
		} else {
			$('#getVerifyCode').val('发送中('+ seconds+')');		
		}
	}
	function getVerifyCodeCb (json)  //回传函数实体，参数为XMLhttpRequest.responseText
	{
		seconds = 60;
		if (json.error != 0) {
			alert(json.message);
		} else {
			$('#getVerifyCode').val('发送中('+ seconds+')');
			$('#getVerifyCode').attr('disabled', true);
			timerID = setInterval(countDownTimer, 1000);  //单位毫秒
		}
	}

});
  
</script>