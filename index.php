<?php
define('CHECK_SESSION', 0);

require_once 'common/__html__.php';
require_once 'common/common.php';
require_once 'common/page.php';
require_once 'common/util.php';
session_start();
$account = $_SESSION['account'];
$accountExtra = $_SESSION['account_extra'];
$admin = checkAdminPermisson();
?>
<div class="container" style="margin-top:-45px;">
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="margin-right:-15px;margin-left:-15px;">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="./static/resource/1.jpg" alt="...">
      <div class="carousel-caption">
        ...
      </div>
    </div>
    <div class="item">
      <img src="./static/resource/2.jpg" alt="...">
	<div class="carousel-caption">
	...
	</div>
    </div>
    <div class="item">
       <img src="./static/resource/3.jpg" alt="...">
        <div class="carousel-caption">
          ...
        </div>
    </div>

  </div>
</div>

<nav class="navbar navbar-inverse navbar-fixed-bottom" role="navigation">
  <div class="container">
      <div class="navbar-nav text-center">
	 <a class="pull-left" style="margin-left:15px; color:rgb(86, 84, 84);"
	<?php
	     if ($account['user_scheme'] == 1) {echo 'href="h5/take_task_page.php"';}
	     elseif ($accountExtra == null || $accountExtra['status']!=0) {echo 'href="h5/apply_join_page.php"';}
	     else {echo 'href="h5/apply_approving.php" data-toggle="modal" data-target="#modal"';}
	?>
	><i class="fa fa-paw" style="display:block;"></i><span style="font-size:12px;">接单</span></a>
	<?php
	if ($admin) {
		echo '<a class="pull-left" style="width:24px;margin-left:15px;">&nbsp;</a>';
	}
	?>
         <a href="h5/publish_task_page.php"><div class="circle orange" style="margin-top:-15px; width:40px; height:40px;padding-top:6px;">发布</div></a>
	  <a class="pull-right" style="margin-right:15px; color:rgb(86, 84, 84);" href="h5/my_task_page.php?type=1">
		<i class="fa fa-user" style="display:block;"></i><span style="font-size:12px;">我的</span></a>
	<?php
	     if ($admin){ 
		echo
	  '<a class="pull-right" href="h5/admin_applier_page.php" style="margin-right:15px; color:rgb(86, 84, 84);">'.
		'<i class="fa fa-wrench" style="display:block;"></i><span style="font-size:12px;">管理</span></a>';}
	?>
     </div>
  </div>
</nav>

<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


<div id="authModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false"
	role="dialog" aria-labelledby="authModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
		<div class="container" style="padding: 20px 10px;">
		     
  		         <div class="form-group">
                              <label for="phone"> 请输入手机号码</label>
                              <input type="text" class="form-control" id="phone" pattern="^1\d{10}$" placeholder="请输入手机号码">
                         </div>
                         <div class="form-group">
                              <label for="code">请填写验证码</label>
			      <div class="input-group">
                 		    <input type="text" class="form-control" id="code" pattern="^1\d{10}$" placeholder="请输入验证码" >
			            <a id="getVerifyCode" class="position input-group-addon">获取验证码</a>
          		      </div>
                         </div>
			<div class="row" style="height:80px">
            		    <div class="col-xs-12 text-center" style="padding:8px 0;">
                		<input id="submitForm" style="width: 250px;" type="button" class="btn btn-warning" value="提交"/>
			</div>
		    
      </div>

        	</div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<script type="text/javascript">
      var account = <?php echo $account==null?'null':$account;?>;
      var accPhone = <?php echo $account==null? 'null':($account['phone']==null?'null':$account['phone'])?>;
      if (account && !accPhone)
      	$('#authModal').modal('show');
      $('#getVerifyCode').on("click", function(){
                var params = {};
                var code = $('#code').val();
                params['code'] = code;

                if ($('#phone').val() == undefined || $('#phone').val() == '') {
                        params['phone'] = $('#phone').attr('placeholder');
                } else {
                        params['phone'] = $('#phone').val();
                } 
                $.ajax({
        	        url:'./action/get_verify_code.php', //后台处理程序
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
                        $('#getVerifyCode').html('获取验证码');
                        clearInterval(timerID);
                        $('#getVerifyCode').attr('disabled', false);
                } else {
                        $('#getVerifyCode').html('发送中('+ seconds+')');
                }
        }
        function getVerifyCodeCb (json)  //回传函数实体，参数为XMLhttpRequest.responseText
        {
                seconds = 60;
                if (json.error != 0) {
                        alert(json.message);
                } else {
                        console.log("验证码: " + json.code);
                        $('#getVerifyCode').html('发送中('+ seconds+')');
                        $('#getVerifyCode').attr('disabled', true);
                        timerID = setInterval(countDownTimer, 1000);  //单位毫秒
                }
        }
	$('#submitForm').on('click', function(e){
		var param = {
			phone: $('#phone').val(),
			code: $('#code').val()
		}
		if ($.trim(param.phone) == ''|| $.trim(param.code) == '') return;
		$.ajax({
			url: './action/verify_phone.php',
			data: param,
			dataType: 'json',
			type: 'post',
			success: function(data) {
				alert(data.result);
				$('#authModal').modal('hide');
			},
			error: function(err) {
				alert('验证码有误，请重新输入');
			}
		})
	})
</script>

<style>
.mainbar {
margin: 40px 0;
}
.mainbar div {
border: 1px solid red;
    height: 60px;
    width: 100%;
margin:10px 0;
}
.mainbar div i {
  font-size: 4em;
}
.mainbar div span {
padding: 4px 40px;
    font-size: 20px;
    color: green;
}
.item2 {
background:url(./static/resource/2.jpg) no-repeat;
background-size:cover;
width:100%;
height:100%;
}

</style>
