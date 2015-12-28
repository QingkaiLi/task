<?php
require_once '../common/__html__.php';
require_once '../common/common.php';
require_once '../common/page.php';

$page = param("page", 1);

$tasks = TaskModel::getValidTasks($page);

$tasksTotalCount = TaskModel::getValidTasksCount();
?>

<div class="container">
  <div class="row">
	<div class="col-md-10 col-sm-10 col-md-offset-1 col-sm-offset-1">
		<table class="table table-striped">
			<thead>
				<tr>
					<!--th>标题</th>
					<th>描述</th>
					<th>开始时间</th>
					<th>结束时间</th>
					<th>地址</th>
					<th>奖励</th-->
				</tr>
			</thead>
			<tbody>
				<?php
				$index = 1;
				foreach($tasks as $key => $value)
				{
					$id = $value['id'];
					$title = $value["title"];
					$startTime = $value["start_time"];
					$endTime = $value["end_time"];
					$desc = $value["description"];
					$reward = $value["reward"];
					$reward = round($reward/100.0, 2);
					echo "<tr><td>";
					echo "<div>任务标题：$title</div>";
					echo "<div>有效时间：$startTime 至 $endTime</div>";
					echo "<div>任务描述：$desc</div>";
					echo "<div>奖励(元): $reward</div>";
					echo "<div><a href='accept_task_page.php?id=$id'>接受</a></div>";
					echo "</td></tr>";
					$index++;
				}
				?>
			</tbody>
		</table>
	</div>
  </div>
</div>

<?php
require_once '../common/footer.php';
?>
