<div class="modal-header" style="padding: 10px;">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="myModalLabel">城市选择<span id="selectdProvince"></span></h4>
 </div>
                <div class="modal-body" style="padding:0;">
                     <div id="provinceList" class="list-group">
<!--			<a href="#" class="list-group-item">北京</a>
<a href="#" class="list-group-item">重庆</a>
<a href="#" class="list-group-item">成都</a>
<a href="#" class="list-group-item">常州</a>
<a href="#" class="list-group-item">东莞</a>
<a href="#" class="list-group-item">大连</a>
<a href="#" class="list-group-item">佛山</a>
<a href="#" class="list-group-item">福州</a>
<a href="#" class="list-group-item">广州</a>
<a href="#" class="list-group-item">杭州</a>
<a href="#" class="list-group-item">哈尔滨</a>
<a href="#" class="list-group-item">合肥</a>
<a href="#" class="list-group-item">济南</a>
<a href="#" class="list-group-item">昆明</a>
<a href="#" class="list-group-item">兰州</a>
<a href="#" class="list-group-item">南京</a>
<a href="#" class="list-group-item">南昌</a>
<a href="#" class="list-group-item">南宁</a>
<a href="#" class="list-group-item">宁波</a>
<a href="#" class="list-group-item">青岛</a>-->
<a href="#" class="list-group-item">上海 <span>虹口</span><span>长宁</span><span>青浦</span><span>杨浦</span></a>
<!--<a href="#" class="list-group-item">深圳</a>
<a href="#" class="list-group-item">沈阳</a>
<a href="#" class="list-group-item">石家庄</a>
<a href="#" class="list-group-item">苏州</a>
<a href="#" class="list-group-item">厦门</a>
<a href="#" class="list-group-item">天津</a>
<a href="#" class="list-group-item">太原</a> 
<a href="#" class="list-group-item">武汉</a>
<a href="#" class="list-group-item">无锡</a>
<a href="#" class="list-group-item">西安</a>
<a href="#" class="list-group-item">郑州</a>-->
                     </div>
                </div>
                <div class="modal-footer" style="border-top:0;">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
<style>
.list-group-item:first-child {
border-top-left-radius: 0;
border-top-right-radius: 0;
border-top:0;
}
#selectdProvince, .list-group-item span {
   /* font-size: 11px;*/
    color: green;
    padding: 2px 4px;
}
</style>
<script type="text/javascript">
  $("#provinceList .list-group-item span").click(function() {
     $("#selectdProvince").text($(this).text());
 })
</script>
