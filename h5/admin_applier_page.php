<?php
//define('CHECK_SESSION', 0);

require_once '../common/__html__.php';
require_once '../common/common.php';
require_once '../common/page.php';
?>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" style="color:black;" href="#">筋斗云<small style="color:#337ab7;">快递员认证</small></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#" style="color:black;">快递员认证<span class="sr-only">(current)</span></a></li>
	<li class="active"><a href="admin_task_page.php" style="color:black;">任务管理</a></li>
	<li class="active"><a href="admin_user_page.php" style="color:black;">用户管理</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<div class="container">
    <div class="table-responsive" style="border:0;">
    <table class="table table-hover adminTable">
  	<thead><tr><th>审核</th><th>姓名</th><th>地区</th><th>手机</th></tr></thead>
	<tbody></tbody>
    </table>
    </div>
</div>


<?php
require_once '../common/footer.php';
?>
<script type="text/javascript" src="../static/js/common/common.js"></script><!--
<script type="text/javascript" src="../static/js/bootstrap-validator.js"></script>-->
<script type="text/javascript" src="../static/js/devoops.js"></script>
<script type="text/javascript" src="../static/js/jsrender.js"></script>

<script id="adminTemplate" type="text/x-jsrender">
<tr><td><button class="btn btn-primary" data={{>openid}} type="button" style="height:20px;padding-top:2px;"><i class="fa fa-check"></i></button></td>
	<td><a data-toggle="modal" href="applier_detail_page.php?openid={{>openid}}" data-target="#detailModal">{{>fullname}}</a></td>
	<td>{{>address}}</td><td>{{>contact_phone}}</td>
</tr>
</script>
<script type="text/javascript">
  $.ajax({
        url:'../admin/get_appliers.php', //后台处理程序
        type:'GET',         //数据发送方式
        dataType:'json',     //接受数据格式
        success:function(data) {
	     if (data && data.result && data.result.length > 0) {
	          $(".adminTable tbody").append(
                          $("#adminTemplate").render(data.result)
                      )
		  $(".adminTable button").click(function() {
			$("#myModal").modal('show');
			var openid = $(this).attr('data');
			var $this = this;
			$('#myModal .btn-primary').click(function() {
			     $.post('../admin/approve_delivery.php',{applier: openid},  function(data) {
				 $("#myModal").modal('hide');
				location.reload();                            	
                             })
			})
				
		  })
	     }
	}
  }); 
</script>

<div class="modal fade" role="dialog" id="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="gridSystemModalLabel">审核通过?</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary">确定</button>
      </div>
   </div>
  </div>
</div>
<div class="modal fade" role="dialog" id="detailModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    </div>
  </div>
</div>


