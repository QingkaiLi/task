<?php
require_once '../common/__html__.php';

require_once '../common/common.php';
require_once '../common/page.php';

$openid = param("openid");
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
</div>
<div class="modal-body">
    <form class="form-horizontal task_form">
    <div class="row">
        <div class="col-xs-7 col-sm-6 text-center label-control">
            半身免冠工作照<span class="required">*</span>
        </div>
        <div class="col-xs-5 col-sm-6 text-center">
	    <img id="uploadHead" src="../static/img/loading.gif" class="avatarImg" alt="User Avatar" />
        </div>
    </div>
    <div class="row"></div>
    <div class="avatarInfoWrapper">   
        <div class="form-group avatarInfoItem">
            <label class="col-xs-4 control-label" for="address">
                所在地区:
            </label>
            <div class="col-xs-8">
                <span id="province"></span>
		</a>
            </div>
        </div>
        <div class="form-group avatarInfoItem">
            <label class="col-xs-4 control-label" for="fullname">
                真实姓名:
            </label>
            <div class="col-xs-8">
                <span id="fullname"></span>
            </div>
        </div>
        <div class="form-group avatarInfoItem">
            <label class="col-xs-4 control-label" for="idcard">
                身份证号:
            </label>
            <div class="col-xs-8">
                <span id="idcard"></span>
            </div>
        </div>
        <div class="form-group avatarInfoItem" style="border-bottom:0;">
            <label class="col-xs-4 control-label" for="contactPhone">
                手机:
            </label>
            <div class="col-xs-8">
                <span id="phone"></span>
            </div>
        </div>
    </div>
    <div class="row uploadText" style="margin:0 -25px;padding-top:5px;">
            身份证照
    </div>
    <div class="row photoWrapper">
            <div class="col-xs-8 col-xs-offset-2">
                <img id="uploadIdCard1" src="../static/img/loading.gif" width="100%" />
            </div>
    </div>
    <div class="row photoWrapper">
            <div class="col-xs-8 col-xs-offset-2">
		<img id="uploadIdCard2" src="../static/img/loading.gif" width="100%" />
            </div>
    </div>
    <div class="row photoWrapper">
            <div class="col-xs-8 col-xs-offset-2">
                <img id="uploadIdCard3" src="../static/img/loading.gif" width="100%" />
            </div>
     </div>
     </form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<script>
var openid = '<?php echo $openid;?>';
$.getJSON('../admin/get_applier.php?openid='+openid, function(data) {
    if (data.error == 0) {
	var res = data.result;
	$('#uploadHead').attr('src', '/'+res.icon);
	var pics = res.card_pic.split(',');
	$('#uploadIdCard1').attr('src','/'+ pics[0]);
	$('#uploadIdCard2').attr('src','/'+ pics[1]);
	$('#uploadIdCard3').attr('src','/'+ pics[2]);
	$('#province').text(res.address);
	$('#fullname').text(res.fullname);
	$('#idcard').text(res.idcard);
	$('#phone').text(res.contact_phone);
    }
})
</script>
