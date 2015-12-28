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
      <a class="navbar-brand" style="color:black;" href="#">筋斗云<small style="color:#337ab7;">任务管理</small></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="admin_applier_page.php" style="color:black;">快递员认证</a></li>
	 <li class="active"><a href="#" style="color:black;">任务管理<span class="sr-only">(current)</span></a></li>
	<li class="active"><a href="admin_user_page.php" style="color:black;">用户管理</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<div class="container" style="margin-top:20px;">
    <form >
  	<div class="row">
	    <div class="col-xs-8">
    		<label for="search" class="sr-only">Search</label>
    		<input type="text" class="form-control" id="search" placeholder="请输入搜索内容">
	    </div>
	    <div class="col-xs-4"><button type="button" id="searchBtn" class="btn btn-default">搜索</button></div>
  	</div>
    </form>
    <div class="table-responsive" style="border:0;">
    <table class="table table-hover adminTable">
  	<thead><tr><th>删除</th><th>标题</th><th>发布时间</th><th>状态</th></tr></thead>
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
<tr><td><button class="btn btn-primary" data={{>id}} type="button" style="height:20px;padding-top:2px;"><i class="fa fa-remove"></i></button></td>
	<td><a data-toggle="modal" href="task_view_page.php?task={{>id}}?type=-1" data-target="#detailModal">{{>title}}</a></td>
	<td>{{:~dateFormat(create_time)}}</td><td>{{:~statusFormat(status)}}</td>
</tr>
{{/for}}
</script>
<script type="text/javascript">
    $('#searchBtn').click(function() {
	var content = $("#search").val();
	if (content != "") {
	    $.ajax({
               url:'../admin/get_tasks.php?taskName='+content, //后台处理程序
      	       type:'GET',         //数据发送方式
               dataType:'json',     //接受数据格式
               success:function(data) {
                  if (data && data.result && data.result.length > 0) {
                     $(".adminTable tbody").html(
                          $("#adminTemplate").render(
				[data.result],
				{dateFormat: function(m) {
                         	    return moment(m).format('YYYY-MM-DD')},
				statusFormat:function(m) {
				    if (m==0) return '关闭';
				    if(m==1)return '发布';
				    if (m==2) return '接单'; 
				    return '完成';
				}
                            })
                      )
                     $(".adminTable button").click(function() {
                          $("#myModal").modal('show');
                          var taskId = $(this).attr('data');
                          var $this = this;
                          $('#myModal .btn-primary').click(function() {
                             $.post('../admin/delete_task.php',{taskId: taskId},  function(data) {
                                 $("#myModal").modal('hide');
                                 $('#searchBtn').click();
                             })
                          })
                     })
                   }else  $(".adminTable tbody").html('');
                }
  	    });
	 }	
    });
</script>

<div class="modal fade" role="dialog" id="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="gridSystemModalLabel">确定删除?</h4>
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

