<?php
require_once '../common/__html__.php';

require_once '../common/common.php';
require_once '../common/page.php';
session_start();
$account = $_SESSION['account'];

?>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-nav text-center">
            <a style="float:left; margin-left:3px; color:#ec971f;" href="#"><i class="fa fa-angle-left"></i></a>
            <span>加入自由快递人</span>
        </div>
    </div>
</nav>
<div class="bg-warning declaration">
    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
    以下内容请按照要求填写或上传，避免审核不通过再次提交！
</div>
<div class="container">
    <div class="row guideWrapper">
        <div class="col-xs-6 col-sm-6 text-center">
            <span class="circle">1</span>
            在线学习
            <span class="pull-right">>></span>
        </div>
        <div class="col-xs-6 col-sm-6 text-center">
            <span class="circle">2</span>
            提交审核材料
        </div>
    </div>
    <form enctype="multipart/form-data" data-toggle="validator" class="form-horizontal task_form" id="myForm"
          role="form" name="myForm" method="post">
        <div class="row AvatarWrapper">
            <div class="col-xs-7 col-sm-6 text-center label-control">
                半身免冠工作照<span class="required">*</span>

                <p class="supplementary">(请用手机拍摄本人)</p>
            </div>
            <div class="col-xs-5 col-sm-6 text-center">
                <img id="uploadHead" src="../static/img/avatar.jpg" class="avatarImg" alt="User Avatar"/>
                <input id="headerMediaId" type="file" name="file" class="form-control hide" required/>
                <input type="hidden" name="icon" value=""/>
            </div>
        </div>
        <div class="row split"></div>
        <div class="avatarInfoWrapper">
            <div class="form-group avatarInfoItem">
                <label class="col-xs-4 control-label" for="address">
                    所在地区:<span class="required">*</span>
                </label>

                <div class="col-xs-8 input-group">
                    <input id="province" class="form-control" type="text" name="address" required placeholder="省市区">
                    <a href="./province_page.php" class="input-group-addon"
                       data-toggle="modal" data-target="#provinceModal">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true" style="color:#999;"></span>
                    </a>
                </div>
            </div>
            <div class="form-group avatarInfoItem">
                <label class="col-xs-4 control-label" for="fullname">
                    真实姓名:<span class="required">*</span>
                </label>

                <div class="col-xs-8">
                    <input class="form-control" type="text" id="fullname" name="fullname" required
                           placeholder="请输入真实姓名，如：张三">
                </div>
            </div>
            <div class="form-group avatarInfoItem">
                <label class="col-xs-4 control-label" for="idcard">
                    身份证号:<span class="required">*</span>
                </label>

                <div class="col-xs-8">
                    <input class="form-control" type="text" id="idcard" name="idcard" required
                           pattern="(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)" placeholder="请输入身份证号">
                </div>
            </div>
            <!--        <div class="form-group avatarInfoItem">
                        <label class="col-xs-4 control-label" for="contact">
                            紧急联络人:<span class="required">*</span>
                        </label>
                        <div class="col-xs-8">
                            <input class="form-control" type="text" name="contact" required placeholder="请输入真实姓名，如：李四">
                        </div>
                    </div>-->
            <div class="form-group avatarInfoItem" style="border-bottom:0;">
                <label class="col-xs-4 control-label" for="contactPhone">
                    手机:
                </label>

                <div class="col-xs-8">
                    <?php echo $account['phone']; ?>
                    <input class="form-control" type="hidden" name="contactPhone"
                           value="<?php echo $account['phone']; ?>">
                </div>
            </div>
        </div>
        <div class="row uploadText" style="margin:0 -25px;">
            上传身份证照
        </div>
        <p class="intro">
            手持二代正式身份证靠近镜头，正面拍摄胸部以上，对焦使身份证上面字体和照片清晰可见。
        </p>

        <div class="row photoWrapper">
            <div class="col-xs-6 col-sm-6">
                <img src="../static/resource/id_front.png" width="100%"/>
            </div>
            <div class="col-xs-6 col-sm-6">
                <img id="uploadIdCard1" src="../static/img/UpPicDefault.png" width="100%"/>
                <input type="file" id="idCardMediaId1" name="file" class="hide"/>
                <input type="hidden" name="cardPic1" value=""/>
            </div>
        </div>
        <div class="row photoWrapper">
            <div class="col-xs-6 col-sm-6">
                <img src="../static/resource/id_back.png" width="100%"/>
            </div>
            <div class="col-xs-6 col-sm-6">
                <img id="uploadIdCard2" src="../static/img/UpPicDefault.png" width="100%"/>
                <input type="file" id="idCardMediaId2" name="file" class="hide"/>
                <input type="hidden" name="cardPic2" value=""/>
            </div>
        </div>
        <div class="row photoWrapper">
            <div class="col-xs-6 col-sm-6">
                <img src="../static/resource/id_full.png" width="100%"/>
            </div>
            <div class="col-xs-6 col-sm-6">
                <img id="uploadIdCard3" src="../static/img/UpPicDefault.png" width="100%"/>
                <input type="file" id="idCardMediaId3" name="file" class="hide"/>
                <input type="hidden" name="cardPic3" value=""/>
            </div>
        </div>
        <!--
             <div class="row uploadText" style="margin:0 -25px;">
                    以下信息为选填
              </div>
              <div class="form-group avatarInfoItem">
                    <label class="col-xs-4">
                        邀请人:
                    </label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" placeholder="请输入邀请码或邀请人手机号">
                    </div>
              </div>
              <div class="form-group avatarInfoItem">
                    <label class="col-xs-4">
                        可接单时间:
                    </label>
                    <div class="col-xs-8 input-group">
                        <input type="text" required placeholder="请选择可接单时间" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
              </div>
              <div class="form-group avatarInfoItem">
                  <label class="col-xs-4">
                        可接单区域:
                    </label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" placeholder="如：高升桥、骡马市">
                    </div>
              </div>
              <div class="form-group avatarInfoItem">
                    <label class="col-xs-4">
                        学历:
                    </label>
                    <div class="col-xs-8 input-group">
                         <input class="form-control" type="text" placeholder="请选择学历">
                         <span class="glyphicon glyphicon-chevron-right input-group-addon" aria-hidden="true" style="color:#999;"></span>
                    </div>
              </div>
              <div class="form-group avatarInfoItem">
                    <label class="col-xs-4">
                        职业:
                    </label>
                    <span class="col-xs-8 input-group">
                        <input class="form-control" type="text" placeholder="请选择职业">
                        <span class="glyphicon glyphicon-chevron-right input-group-addon" aria-hidden="true" style="color:#999;"></span>
                    </span>
              </div>
              <div class="form-group avatarInfoItem">
                    <label class="col-xs-4">
                        交通工具:
                    </label>
                    <span class="col-xs-8 input-group">
                        <input class="form-control" type="text" placeholder="请选择交通工具">
                        <span class="glyphicon glyphicon-chevron-right input-group-addon" aria-hidden="true" style="color:#999;"></span>
                    </span>
              </div>-->
        <div class="row split" style="height:80px">
            <div class="col-xs-12"><i class="fa fa-check-square-o"></i>
                <span style="font-size:12px">同意并接受<a>
                        <自由快递人注册协议>
                    </a></span></div>
            <div class="col-xs-12 text-center" style="padding:8px 0;">
                <input id="submitForm" style="width: 250px;" type="submit" class="btn btn-warning" value="立即提交"/></div>
        </div>
    </form>
</div>

<!-- Modal -->
<div id="provinceModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" src="../static/js/common/common.js"></script>
<script type="text/javascript" src="../static/js/bootstrap-validator.js"></script>
<script type="text/javascript" src="../static/js/devoops.js"></script>
<!--
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="../static/js/uploadify/jquery.uploadify.min.js"></script>-->
<script type="text/javascript" src="../static/js/ajaxfileupload.js"></script>
<script type="text/javascript">
    $('#uploadHead, #uploadIdCard1, #uploadIdCard2, #uploadIdCard3').click(function () {
        $(this).next().click();
    })
    $('#headerMediaId, #idCardMediaId1, #idCardMediaId2, #idCardMediaId3').change(function (e) {
        /*   var prev = $(this).prev();
         var file = e.target.files[0]
         var reader = new FileReader()
         reader.onload = function(e) {
         prev.attr('src', e.target.result);
         }
         reader.readAsDataURL(file);
         */
        preview($(this), e);
        var name = $(this).attr('id');
        upload($(this).attr('id'), name);
    });

    $('#submitForm').on('click', function () {
        //if($("input[name='icon']").val() == '') {
        if (false) {
            alert("头像不能为空");
            return false;
        } else {
            var params = common.Get_parameter({form: '.form-horizontal'});
            $.ajax({
                url: '../action/apply_delivery.php', //后台处理程序
                type: 'post',         //数据发送方式
                dataType: 'json',     //接受数据格式
                data: params,         //要传递的数据
                success: function (data) {
                    if (data.error == 0) {
                        ajaxGoToPage("../index.php");
                    } else
                        alert(data.message);
                },
                error: function (xhr, status, err) {
                    alert('error');
                }
            });
        }
        return false;
    });
    $('#provinceModal').on('shown.bs.modal', function () {
        $('#provinceModal .list-group-item span').click(function () {
            $('#province').val($(this).text());
        })
    })
    function preview($this, e) {
        var prev = $this.prev();
        var file = e.target.files[0]
        var reader = new FileReader()
        reader.onload = function (e) {
            prev.attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
    }
    function upload(src, type) {
        $.ajaxFileUpload({
            url: '/action/upload_file.php?type=' + type,
            type: "POST",
            secureuri: false,
            fileElementId: src,
            dataType: 'json',
            //data:{type: type},
            success: function (data, status) {
                if (data.error == 0) {
                    $('#' + src).next().val(data.file);
                } else {
                    alert(data.message);
                }
            },
            error: function (data, status, e) {
                alert("error");
            }
        });
        $('#' + src).change(function (e) {
            preview($(this), e);
            upload();
        });
    }

</script>
