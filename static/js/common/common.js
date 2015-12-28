/*
	description:公用方法
	author:tanshenghu, wb-tanshenghu
	update:2014-08-20
*/
/*<-------------------------公用方法----------------------------->*/
var common = common || {};
// 获取表单字段
common.Get_parameter   =	function( param ){
	if ( !(param instanceof Object) ) throw 'parameter error';

		var form = checkJqObject( param.form ),
			selectEle = param.selector ? checkJqObject( param.selector ) : false,
			way = 'way' in param ? param.way : true;

		var	selector = null,
			resultParam = {};

		function checkJqObject( obj ){
			var newObj = obj;
			if ( !(obj instanceof jQuery) ){
				newObj = $(newObj);
			}
			return newObj;
		};
		var FormatHtml = function( val ){
			return $('<div/>').text( val ).html();
		};
		var Encode  = function( value ){

			if ( param.Encode ){
				value = param.Encode( value );
			}
			return value;
		};

		if ( selectEle && way ){
			selector = form.find( selectEle );
		}else if ( selectEle && (!way) ){
			selector = form.find('input[name],textarea[name]').not( selectEle );
		}else{
			selector = form.find('input[name],textarea[name],select[name]');
		}

		selector.each(function(eid, ele){
			var thisObj = $(ele),
				iName   = thisObj.attr('name'),
				type    = thisObj.attr('type') && thisObj.attr('type').toLowerCase();
	
			if ( iName && type && type==='radio' ){

				if ( thisObj.prop('checked') ){
					resultParam[iName] = FormatHtml( Encode( thisObj.val() ) );
				}

			}else if( iName && type && type==='checkbox' ){
				
				if ( thisObj.prop('checked') ){
					
					if ( resultParam[iName] ){
						resultParam[iName].push( FormatHtml( Encode( thisObj.val() ) ) );
					}else{
						var itemArr = [ FormatHtml( Encode( thisObj.val() ) ) ];
						resultParam[iName] = itemArr;
					}
					
				}

			}else if ( iName ){
			
				resultParam[iName] = FormatHtml( Encode( thisObj.val() ) );
				
			}

		});

		if( param.split ){
			for(var i in resultParam){
				if( resultParam[i] instanceof Array ){
					resultParam[i] = resultParam[i].join( param.split );
				}
			}
		};


		return resultParam;
	
};
common.Get_parameter.Get_checkbox = function(form, checkName){
		var resultObj = {},
			getVal    = [],
			decoll    = arguments[2];

		form = common.isjQ( form );

		form.find('[name="'+checkName+'"]').each(function(i, ele){
			var thisObj = $(ele),
				type    = thisObj.attr('type') && thisObj.attr('type').toLowerCase();

			if ( type && (type === 'radio' || type === 'checkbox') ){
				if ( thisObj.prop('checked') ){
					getVal.push( $(ele).val() );
				}		
			}else{
				if ( thisObj.val() ){
					getVal.push( $(ele).val() );
				}				
			}
		});

		if ( decoll ){
			resultObj[checkName] = getVal.join( decoll );
		}else{
			resultObj[checkName] = getVal;
		}
		
		return resultObj;
};
// 选项卡切换
common.checkTab = function(hand, box){
	var callback = arguments[2];
	$(hand).bind('click', function(){
		var thisObj = $(this),
			Index 	= thisObj.index(hand);
		thisObj.addClass('active').siblings(hand).removeClass('active');	
		$(box).eq(Index).show().siblings(box).hide();
		if( typeof callback === 'function' ){
			callback.apply(this,[]);
		};	
	});
};
// 取url参数
common.UrlPara = function(parText){
		var url = arguments[1] || document.URL;		/* 获取参数方法，该方法共提供两个参数，第一个必选参数。第二个可选参数具体的URL地址 */
		var parameter = url.substring(url.lastIndexOf('?')+1);
		var aPar = parameter.split('&');
		//var result = {};
		for (var i=0; i<aPar.length; i++)
		{
			if (aPar[i].split('=')[0]==parText)
			{
				return aPar[i].split('=')[1];
			}
		}
		return false;
};
// ajax加载vm页面
common.ajaxLoadPage = function(){

	$('#content .ajax-link').on('click', function(ev){
		ev.preventDefault();
		var thisObj = $(this);
			url     = thisObj.attr('href');

		if( url==='#' || url==='javascript:;' ){
			// 推荐使用,eg: <a href="javascript:;" data-goto="your Url">加载页面</a>
			url = thisObj.data('goto');
		}
		if ( !url ) return;

		ajaxGoToPage( url );
	});

};
// 分页列表排序
common.gridSort = function(){
	$('#ajax-content').delegate('thead .sortBtn', 'click', function(){
		if ( !gridObj ) throw 'not find gridObj';

		var param = $(this).data('sort');
		gridObj.reload( param );
	});
};
// json select 下拉框
common.getJsonSelect = function( param ){
	var option = param.deVal || '';
	if ( option ){
		option = '<option value="0">'+option+'</option>';
	}
	$.each(param.data, function(key, val){
		option += '<option value="'+(val[param.name])+'" data-id="'+(val[param.value])+'">'+(val[param.name])+'</option>';
	});
	return option;
};
common.isjQ = function( obj ){
	return (obj instanceof jQuery) ? obj : $(obj);
}
// 简单的写一个浮层效果
common.popupbox = function( param ){
	var hand= this.isjQ( param.hand ),
		evet= param.evet || 'click',
		delegateEle = param.delegateEle,
		box = this.isjQ( param.box ),
		CW  = 0,
		CH  = 0,
		boxW= parseInt( param.width ),
		boxH= parseInt( param.height ),
		sclT= 0,
		callback = param.callback;

	if ( !box.length ){
		console.log( "error:not "+param.box );
		return;
	}
	// 是否外层判断并处理
	if ( box.parent().prop('nodeName').toLowerCase()!='body' ){
		$('body').append( box );
	}

	if ( !(param.width) && !(param.height) ){
		box.css('display','inline');
		boxW = box.width();
		boxH = box.height();
	}

	var bgDiv = $('#coverPop').length ? $('#coverPop') : $('<div id="coverPop"></div>');

	var posFn = function(){
		CW  = document.documentElement.clientWidth;
		CH  = document.documentElement.clientHeight;
		sclT= $(document).scrollTop() || 0;
	
		var left = ( ( CW - boxW )*0.5 < 0 ) ? 0 : ( ( CW - boxW )*0.5 > (CW-boxW) ) ? CW-boxW : ( CW - boxW )*0.5;
		var top = ( ( CH - boxH )*0.5 < 0 ) ? 0 : ( ( CH - boxH )*0.5 > (CH-boxH) ) ? CH-boxH : ( CH - boxH )*0.5;

		var css = {
			"position":"absolute",
			"z-index":"1000",
			"display":"none",
			"left":left,
			"background-color":"white",
			"top":top+sclT
		};

		var DH = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
		bgDiv.css({"position":"absolute","z-index":"998","left":"0","top":"0","width":"100%","display":"none","background":"black","opacity":"0.5","height":DH});

		return css;
	};

	var css = posFn();

	if ( param.width && param.height ){
		css.height = param.height;
		css.width = param.width;
	}else{
		css.height = 'auto';
		css.width = 'auto';
	}

		
	

	box.css( css ).before( bgDiv );

	if ( evet === 'delegate' ){
		hand.delegate(delegateEle,'click', function(){
			var DH    = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight),
				thisObj = $(this);
			box.css( posFn() );
			bgDiv.height( DH ).fadeIn();
			box.fadeIn();
			if ( typeof callback === 'function' ){
				callback.apply(this, [thisObj,box]);
			}
		});
		
	}else{
		hand.on('click', function(){
			var DH    = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
			box.css( posFn() );
			bgDiv.height( DH ).fadeIn();
			box.fadeIn();
			if ( typeof callback === 'function' ){
				callback.apply(this, [$(this),box]);
			}
		});
	}
	

	box.find('.closeMe').on('click', function(){
		bgDiv.fadeOut();
		box.fadeOut();
	});
};
// 文件上传方法
common.uploader = function( ids ){
	
	var Control = function( id, param ){
			var callback = function(fileObj, data, response){
					data = (typeof data == 'object') ? data : $.parseJSON(data);
		        	
		        	if( data.success ){
		        		var resultData = data.result[0];
		        		if( resultData.success ){

				        	var upResult = $('#'+resultData.uploadId).closest('.up_resultBox').find('.up_result');
				        	upResult.each(function(i, ele){
				        		var upResult = $(ele);
					        	if( upResult.prop('nodeName').toLowerCase()==='img' ){
					        		upResult.attr('src', resultData.absoPath);
					        	}else if( upResult.prop('nodeName').toLowerCase()==='input' ){
					        		var picPath = upResult.val();
					        		if ( !picPath ){
					        			picPath += resultData.relaPath
					        		}else{
					        			picPath += ','+resultData.relaPath;
					        		}
					        		upResult.val( picPath ).removeClass('validatebox-invalid');
					        	}else{
					        		upResult.text( resultData.relaPath )
					        	}
				        	});

				        }else{
				        	alert( resultData.msg );
				        }

		        	}else{
		        		alert( data.msg );
		        	}
			};
			$('#'+id).uploadify({
				height        : 30,
				width         : 120,
				'swf' : '/static/flash/uploadify.swf',
				'formData' : {"action":"../../../action/upload_file.php","event_submit_doUploadFile":"true","uploadId":id},
				'uploader': '/index.json', 
				'folder': 'upload',
				'queueID': 'fileQueue',
				'auto': true,
				'buttonText':'文件上传',
				'sizeLimit':'102400000000000000',
				'fileTypeDesc': '支持的格式：',
                'fileTypeExts': '*.jpg;*.jpge;*.gif;*.png',
				'multi': param.multi || false,
				'onFallback': function () {
                    alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
                },
				'onSelectError': function (file, errorCode, errorMsg){
					alert(errorMsg);
				},
		        onUploadSuccess : function(fileObj, data, response){
		        	
		        	if ( param.onUploadSuccess && typeof param.onUploadSuccess == 'function'){
		        		param.onUploadSuccess(fileObj, data, response);
		        	}else{
		        		callback(fileObj, data, response);
		        	}

		        }
		    });
	};

	for(var i in ids){
		Control( ids[i].id, ids[i].param || {} );
	}

};

// 清空节点内容操作
common.emptyele = function( box ){
	var Box   = this.isjQ(box),
		deEle = arguments[1] || '.jq-empty';

	Box.find( deEle ).each(function(i, ele){
		var curEle = $(ele);
		if( curEle.prop('nodeName').toLowerCase()==='input' || curEle.prop('nodeName').toLowerCase()==='textarea' ){
			curEle.val('');
		}else{
			curEle.html('');
		};

	});
};

// 拆分并取值 日期时间
common.splitDate = function( d ){
	if( !d ) return false;
	var mydate = new Date(d),
		result;
	if( mydate.getTime() ){
		result = mydate;
	}else{
		var dateArr = d.match(/\d+/g),
			Month   = dateArr[1]-1 < 0 ? 12 : dateArr[1]-1;
		switch (dateArr.length){
			case 3 : result = new Date(dateArr[0],Month,dateArr[2]);break;
			case 5 : result = new Date(dateArr[0],Month,dateArr[2],dateArr[3],dateArr[4]);break;
			case 6 : result = new Date(dateArr[0],Month,dateArr[2],dateArr[3],dateArr[4],dateArr[5]);break;
		}
	}
	return result;
};

String.format = function() {
    if( arguments.length == 0 )
        return null;

    var str = arguments[0]; 
    for(var i=1;i<arguments.length;i++) {
        var re = new RegExp('\\{' + (i-1) + '\\}','gm');
        str = str.replace(re, arguments[i]);
    }
    return str;
}
// 根据权限等条件,修改所有A标签的url
common.modifyUrl = function(){
	var range = arguments[0] || '#ajax-content'; // A标签的范围，指某个区域内的A标签需要重置
	var pathname = location.pathname;
	var Aobj = $(range).find('a[href]');
	var getHash = function( href ){
		var new_href = pathname;
		new_href = new_href + href.substring( href.indexOf('#') );
		return new_href;
	};
	Aobj.each(function(i, ele){
		var href = $(ele).attr('href');
		var script = /javascript/ig.test(href);
		if( href && href!='javascript:;' && !script ){
			$(ele).attr( 'href',getHash(href) );
		}
	});
};

$(function(){

	// a标签跳转ajax加载页面
	common.ajaxLoadPage();
	
	// 分页排序
	common.gridSort();

	// 根据权限等条件,配置A标签href
	//common.modifyUrl();

});
