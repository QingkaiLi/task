<?php
//define('CHECK_SESSION', 0);
//
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
      <a class="navbar-brand" style="color:black;" href="#">筋斗云<small style="color:#337ab7;">用户管理</small></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="admin_applier_page.php" style="color:black;">快递员认证</a></li>
	 <li class="active"><a href="admin_user_page.php" style="color:black;">任务管理</a></li>
	<li class="active"><a href="#" style="color:black;">用户管理</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<div class="container" style="margin-top:20px;">
    <form >
  	<div class="row">
	    <div class="col-xs-8">
    		<label for="search" class="sr-only">Search</label>
    		<input type="text" class="form-control" id="search" placeholder="请输入用户名">
	    </div>
	    <div class="col-xs-4"><button type="button" id="searchBtn" class="btn btn-default">搜索</button></div>
  	</div>
    </form>
    <div class="table-responsive" style="border:0;">
    <table class="table table-hover adminTable">
  	<thead><tr><th>账号</th><th>联系方式</th><th>加入时间</th></tr></thead>
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
{{for}}
<tr><td><a data-toggle="modal" href="user_view_page.php?openid={{>openid}}" data-target="#detailModal">{{>nickname}}</a></td>
	<td>{{>phone}}</td><td>{{:~dateFormat(create_time)}}</td>
</tr>
{{/for}}
</script>
<script type="text/javascript">
    $('#searchBtn').click(function() {
	var content = $("#search").val();
	if (content != "") {
	    $.ajax({
               url:'../admin/get_appliers.php?nickname='+content, //后台处理程序
      	       type:'GET',         //数据发送方式
               dataType:'json',     //接受数据格式
               success:function(data) {
                  if (data && data.result && data.result.length > 0) {
                     $(".adminTable tbody").html(
                          $("#adminTemplate").render(
				[data.result],
				{dateFormat: function(m) {
                         	    return moment(m).format('YYYY-MM-DD')}
                            })
                      )
                   }else  $(".adminTable tbody").html('');
                }
  	    });
	 }	
    });
</script>


<div class="modal fade" role="dialog" id="detailModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    </div>
  </div>
</div>

