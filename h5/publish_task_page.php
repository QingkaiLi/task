<?php
require_once '../common/__html__.php';

require_once '../common/common.php';
require_once '../common/page.php';

$taskId = param('taskId', 0);
?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
      <div class="navbar-nav text-center">
	 <a style="float:left; margin-left:3px; color:#ec971f;" href="#"><i class="fa fa-angle-left"></i></a>
         <span>任务发布</span>
     </div>
  </div>
</nav>
<div class="container">
<!--test
<form action="../action/upload_file.php" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file" />
<br />
<input type="submit" name="submit" value="Submit" />
</form>
test--> 
 <form data-toggle="validator" class="form-horizontal task_form" role="form" name="myform" method="post" action="">
    <input hidden="hidden" type="text" id="lng" name="lng" />
    <input hidden="hidden" type="text" id="lat" name="lat" />
<!--
    <div class="form-group">
          <label class="col-sm-2 col-xs-4 control-label" for="title">商品名称:<span class="requiredIco pull-right">*</span></label>
          <div class="col-sm-10 col-xs-8">
            <input type="text" placeholder="" class="form-control" required id="title" name="title">
          </div>
    </div>
-->
    <div class="form-group">
          <!-- Text input-->
          <label class="col-sm-2 col-xs-4 control-label" for="desc">任务需求:<span class="requiredIco pull-right">*</span></label>
          <div class="col-sm-10 col-xs-8">
            <input type="text" placeholder="" required class="form-control" id="desc" name="desc">
          </div>
    </div>
<!--
    <div class="form-group">
          <label class="col-sm-2 col-xs-4 control-label" for="fromAddress">购买地:</label>
          <div class="col-xs-8 col-sm-10">
            <input class="form-control" size="16" type="text" value="" id="fromAddress" name="fromAddress">
          </div>
    </div>
-->
    <div class="form-group">
          <!-- Text input-->
          <label class="col-sm-2 col-xs-4 control-label" for="endTime">送达时间:<!--<span class="requiredIco pull-right">*</span>--></label>
          <div class="input-group date form_datetime col-xs-8 col-sm-10" data-link-field="endTime">
            <input class="form-control" size="16" type="text" value="" readonly id="endTime" name="endTime" />
	    <span class="input-group-addon" onclick='document.getElementById("endTime").click()'>
		<span class="glyphicon glyphicon-th"></span></span>
          </div>
    </div>

    <div class="form-group">
          <!-- Text input-->
          <label class="col-sm-2 col-xs-4 control-label" for="address">收货地:</label>
          <div class="col-sm-10 col-xs-8 input-group">
            <input type="text" placeholder="" class="form-control" id="address" name="address">
	     <a id="mapMarker" class="position input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></a>
          </div>
    </div>

    <div class="form-group">
          <label class="col-sm-2 col-xs-4 control-label" for="reward">愿付酬劳:</label>
	  <div class="col-xs-8 col-sm-10 input-group" style="width:120px;">
              <input type="text" placeholder="金额" class="form-control" id="tip" name="tip" pattern="^[0-9]+\.{0,1}[0-9]{0,2}$" required/>
		<span class="input-group-addon">元</span>
          </div>
          <!-- Text input-->
          <!--label class="col-sm-2 control-label" for="phone"><span class="requiredIco">*</span>联系手机</label>
          <div class="col-sm-10">
            <input type="text" placeholder="" class="input-xlarge easyui-validatebox" data-options="required:true,validType:'mobile'" id="phone" name="phone">
          </div-->
    

    <!--div class="form-group">
          <label class="col-sm-2 control-label" for="verifyCode"><span class="requiredIco">*</span>手机验证码</label>
          <div class="col-sm-10">
            <input type="text" placeholder="" class="input-xlarge easyui-validatebox" data-options="required:true" id="verifyCode" name="verifyCode">
            <input type="button" class="btn btn-default" id="getVerifyCode" value="获取验证码">
          </div>
    </div--><!--
    <div class="form-group" style="margin-top:35px;">-->
          <!--<div class="col-sm-10 col-xs-12" style="margin-left:15px; padding-right:30px; margin-top:40px;">
		<input id="tip" name="tip" data-slider-id='ex1Slider' type="text"/>
	  </div>-->
    </div>
    <div class="form-group">
          <!-- Text input-->
          <label class="col-sm-2 col-xs-4 control-label" for="address">联系方式:<span class="requiredIco pull-right">*</span></label>
          <div class="col-xs-8 col-sm-10">
	    <input type="text" placeholder="" class="form-control" id="phone" name="phone" pattern="^1\d{10}$" required/>
          </div>
    </div>

    <div class="form-group">
          <!-- Text input-->
          <label class="col-sm-2 col-xs-4 control-label" for="user">联系人:<span class="requiredIco pull-right">*</span></label>
          <div class="col-sm-10 col-xs-8">
            <input type="text" placeholder="" required class="form-control" id="user" name="user">
          </div>
    </div>
<!--<div class="form-group">
          <label class="col-sm-2 col-xs-4 control-label" for="code"><span class="requiredIco">*</span>验证码:</label>
          <div class="col-sm-10 col-xs-8">
            <input type="text" placeholder="" class="input-xlarge easyui-validatebox" id="code" name="code">
            <img id="imgCode" src="../action/verify_code.php" onclick="javascript:this.src='../action/verify_code.php?tm='+Math.random()">
            <a href="#" onclick="document.getElementById('imgCode').src='../action/verify_code.php?tm='+Math.random()">看不清楚，刷新</a>
          </div>
    </div>
-->
    <div class="form-group">
          <!-- Button -->
          <div class="col-sm-8 col-xs-10 col-sm-offset-2 col-xs-offset-1">
            <!--button class="btn btn-primary" id="publishTask">发布任务</button-->
            <input type="button" class="btn btn-primary" id="publishTask" value="立即发布"/>
          </div>
    </div>

    </div>
  </form>
</div>
<div id="calenderModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<div id="datePlugin"></div>
<?php
require_once '../common/footer.php';
?>
<script type="text/javascript" src="../static/js/common/common.js"></script>
<script type="text/javascript" src="../static/js/bootstrap-validator.js"></script>
<!--<script type="text/javascript" src="../static/js/common/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../static/js/common/jquery.validate.extends.js"></script>-->
<script type="text/javascript" src="../static/js/devoops.js"></script>
<script type="text/javascript" src="../static/js/iscroll.js"></script>
<script type="text/javascript" src="../static/js/calendar_picker.js"></script>

<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=AqPA2oEsRSrNWkWfakAI84dP"></script>
<script type="text/javascript">
  var taskId = '<?php echo $taskId;?>';
  if (taskId != 0) {
        $.getJSON('../action/get_task.php?id='+taskId, function(data) {
	    var task = data.task;
	   $('#desc').val(task.description);
	   $('#endTime').val(task.end_time);
	   $('#address').val(task.address);
	   $('#tip').val(task.reward/100);
	   $('#phone').val(task.publisher_phone); 
	});
  }
        
/*
  var myScrollD, myScrollH, myScrollM;
  function loaded() {
	setTimeout(function() {
	     myScrollD = new iScroll('scrollerD', {vScrollbar: false});
	     myScrollH = new iScroll('scrollerH', {vScrollbar: false});
	     myScrollM = new iScroll('scrollerM', {vScrollbar: false});
	}, 100);
  }
  window.addEventListener('load', loaded, false);
*/
  $('#mapMarker').on("click", function(){
    	// 百度地图API功能
	var geolocation = new BMap.Geolocation();
	var geoc = new BMap.Geocoder();
	
	geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
			//alert('您的位置：'+r.point.lng+','+r.point.lat);
			geoc.getLocation(r.point, function(rs){
				var addComp = rs.addressComponents;
				//alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
				$('#address').val(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
			});
		}
		else {
			alert('failed'+this.getStatus());
		}        
	},{enableHighAccuracy: true})
  });
  /*
  $('.form_datetime').datetimepicker({
    language:  'zh-CN',
    format: "dd MM yyyy - hh:ii",
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 0,
    forceParse: 0,
    showMeridian: 1
  });*/
$('#endTime').date();
/*  $('#tip').slider({
    formatter: function(value) {
        return value+'元';
    },
    value: 20,
    tooltip: 'always',
    ticks: [0, 20, 40, 60, 80, 100]
  });
*/
//$(document).ready(function() {
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
			alert("验证码: " + json.code);
			$('#getVerifyCode').val('发送中('+ seconds+')');
			$('#getVerifyCode').attr('disabled', true);
			timerID = setInterval(countDownTimer, 1000);  //单位毫秒
		}
	}

  // 发布任务
  $('#publishTask').on("click", function(){
    // 创建地址解析器实例
    var myGeo = new BMap.Geocoder();
    setTimeout(function(){
      var params = common.Get_parameter({form:'.form-horizontal'});
     
      if ( $('.form-horizontal').validator() ){
        //params = common.Get_parameter({form:'.F_table'});
      } else {
        alert('数据不符合要求');
        return;
      }
      var url = '../action/publish_task.php';
      if (taskId != 0) {
	  params.taskId = taskId;
          url = '../action/update_task.php';
      }
      $.ajax({
          url: url, //后台处理程序
          type:'post',         //数据发送方式
          dataType:'json',     //接受数据格式
          data:params,         //要传递的数据
          success:publishTaskCb  //回传函数(这里是函数名)
      });
    }, 400);
    // 将地址解析结果显示在地图上,并调整地图视野
    myGeo.getPoint($('#address').val(), function(point){
      if (point) {
        $('#lng').val(point.lng);
        $('#lat').val(point.lat);
        //alert("您的位置："+point.lng+","+point.lat);
      }else{
        alert("您选择地址没有解析到结果!");
      }
    }, "上海市");

    //setTimeout(function(){$("form").submit()}, 555);
  });
  
  function publishTaskCb (json)  //回传函数实体，参数为XMLhttpRequest.responseText
  {
    if (json.error != 0) {
      alert(json.message);
    } else {
      // 页面需要<body></body>标签，这个函数实际上是触发一个A标签事件
      ajaxGoToPage("my_task_page.php?phone="+$('#phone').val()+"&type=1");
    }
  }
  
//});
</script>

