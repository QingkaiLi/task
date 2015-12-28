$.fn.validatebox.defaults.missingMessage = '该输入项为必输项!';
$.fn.validatebox.defaults.missingRadioMessage = '请至少选择一项!';
$.fn.validatebox.defaults.rules.email.message = '请输入有效的邮件地址!';
$.fn.validatebox.defaults.rules.url.message = '请输入有效的URL地址!';
$.fn.validatebox.defaults.rules.length.message = '允许最大长度为 {1} 个字符!';
$.extend($.fn.validatebox.methods, {  
    remove: function(jq, newposition){  
        return jq.each(function(){  
            $(this).removeClass("validatebox-text validatebox-invalid").unbind('focus').unbind('blur');
        });  
    },
    reduce: function(jq, newposition){  
        return jq.each(function(){  
           var opt = $(this).data().validatebox.options;
           $(this).validatebox(opt);
        });  
    },
    sourcingRemove: function(jq, newposition){ 
        return jq.each(function(){    
            $(this).removeClass("validatebox-text validatebox-invalid").unbind('focus.validatebox').unbind('blur.validatebox');  
        });    
    },  
    sourcingReduce: function(jq, newposition){    
        return jq.each(function(){    
           $(this).addClass("validatebox-text").validatebox();  
        });    
    }        
});

$.extend($.fn.validatebox.defaults.rules, {
		radioRequired:{  
	        validator: function(value,param){  
	            return value.trim() != "";  
	        },  
	        message: '请至少选择一项！'  
	    },
		equals: {  
	        validator: function(value,param){  
	            return value == $(param[0]).val();  
	        },  
	        message: '输入密码不一致！'  
	    },
	    radio: {
            validator: function (value, param) {
                var frm = $(param[0]), groupname = param[1], ok = false,
                	pBox= $('input[name="' + groupname + '"]');
                $(pBox, frm).each(function () { //查找表单中所有此名称的radio

                    if (this.checked) { 
                    	ok = true;
                    }

                });
                if(ok){
                	pBox.closest('.PvalidateBox').removeClass('validatebox-invalid');
                }else{
                	pBox.closest('.PvalidateBox').addClass('validatebox-invalid');
                };
                return ok;
            },
            message: '至少选择一项！'
        },
		select : {
        	validator: function (value, param) {
        		if( value!==param[0] ){
        			return true;
        		}else{
        			return false;
        		}

        	},
        	message: '请选择一个有效的选项'
        },
        spacial_character: {
            validator: function(value){
                return !isSafe(value);  
            },
            message: '输入项存在非法字符！'
        },
        stringCH:{
        	validator: function(value,element){
                return /^[\u4e00-\u9fa5]+$/.test(value);    
            },
            message: '只允许输入汉字!'
        },
        stringEN:{
        	validator: function(value,element){
                return /^[A-Za-z]+$/g.test(value);    
            },
            message: '只允许输入字母!'
        },
        maxValue: {   
	        validator: function(value, param){   
	            return value <= param[0];   
	        },   
	        message: '首付款比例不超过{0}%!'  
	    },
	    minValue: {   
	        validator: function(value, param){   
	            return value >= param[0];   
	        },   
	        message: '剩余货款比例不得小于{0}%!'  
	    },
        minLength: {   
	        validator: function(value, param){   
	            return value.length >= param[0];   
	        },   
	        message: '最小长度为 {0} 字符.'  
	    },
	    ip:{
	    	validator: function(value, param){   
	            return (/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/.test(value) && (RegExp.$1 < 256 && RegExp.$2 < 256 && RegExp.$3 < 256 && RegExp.$4 < 256));   
	        },   
	        message: '请输入正确的IP地址!'  
	    },
	    port:{
	    	validator: function(value, param){   
	            return (value < 65536 && value > 0);   
	        },   
	        message: '请输入正确的端口号!'  
	    },
	    postalCode:{
	    	validator: function(value, param){   
	            var tel = /^[0-9]{6}$/;
				return (tel.test(value));
	        },   
	        message: '邮政编码格式不正确!'  
	    },
	    mobile:{
	    	validator: function(value, param){   
	            var length = value.length;
				//长度为11，以13，15，18开头的
				return (length == 11 && /^(((1[0-9]{1}))+\d{9})$/.test(value));
	        },   
	        message: '手机号码格式不正确!'  
	    },
	    phone:{
	    	validator: function(value, param){   
				//return (/^[+]{0,1}(\d){1,4}[ ]{0,1}([-]{0,1}((\d)|[ ]){1,12})+$/.test(value));
				return (/^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/.test(value));
	        },   
	        message: '电话号码格式不正确!'  
	    },
	    fax:{
	    	validator: function(value, param){   
				return (/^(\d{3,4}-)?\d{7,8}$/.test(value));
	        },   
	        message: '传真号码格式不正确!'  
	    },
	    alnum:{
	    	validator: function(value, param){   
				return /^[a-zA-Z0-9]+$/.test(value);;
	        },   
	        message: '只能包括英文字母和数字!'  
	    },
	    naturalnum:{
	    	validator: function(value, param){   
	    		var strRegex = "^[0-9]*$";
				var re=new RegExp(strRegex);  
	    		return re.test(value);
	        },   
	        message: '只能输入自然数!' 
	    },
	    positiveInteger:{
	    	validator: function(value, param){   
	    		var strRegex = "^[1-9][0-9]*$";
				var re=new RegExp(strRegex);  
	    		return re.test(value);
	        },   
	        message: '只能输入正整数!' 
	    },
	    decimal:{
	    	validator:function(value,param){
	    		return /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value);
	    	},
	    	message: '只能输入数字或小数!' 
	    },
	    decimalD:{
	    	validator:function(value,param){
	    		return /^\d+\.?\d{0,2}$/.test(value);
	    	},
	    	message: '只能输入正数数字（可保留小数点后两位）!' 
	    },
	    validateNumberLimit : {
			 validator: function(value,param){  
				 var result = /^(([1-9]\d{0,11})|0)(\.\d{1,2})?$/.test(value);
		         return  result;  
		     },  
		     message: '数字格式不正确：整数部分不能超过12位，小数部分不能超过2位！'
		},
	    decimalMoney:{
	    	validator:function(value,param){
	    		return /^([1-9]([0-9,])*(\.[0-9]+)?)$/.test(value);
	    	},
	    	message: '金额输入有误!' 
	    },
	    greaterThan:{
	    	validator:function(value,param){
	    		if(value>0.0){
	    			return true;
	    		}
	    	},
	    	message: '数值必须大于 0'
	    },
	    letterThan:{
	    	validator:function(value,param){
	    		if(value <= 1){
	    			return true;
	    		}
	    	},
	    	message: '数值必须小于等于1'
	    },
	    space:{
	    	validator:function(value,param){
	    		if(value.indexOf(" ")>=0){
	    			return false;
	    		}
	    		return true;
	    	},
	    	message: '不允许输入空格！' 
	    },
	    idcardno:{
	    	validator: function(value, param){   
				return isIdCardNo(value);
	        },   
	        message: '请正确输入身份证号码!' 
	    },
	    equalTo:{
	    	validator: function(value, param){   
	    		if($(param).val() == value){
	    			return true;
	    		}
				return false;
	        },   
	        message: '两次输入不一致!' 
	    },
	    percent:{
	    	validator: function(value, param){   
	    		var reg = /^(?:[1-9][0-9]?|100)$/;
	    		return reg.test(value);
	        },   
	        message: '非法百分比数值!' 
	    },
	    phone_tel:{
	    	validator:function(value,param){
	    		var _partten = "^(?=[0-9-]*$)";
				var partten=new RegExp(_partten); 
	    		return partten.test(value);
	    	},
	    	message:'联系电话格式不正确!'
	    },
	    validatePartyInfoIsExist: { 
	        validator: function(value, params){
	        	var name=params[1];
	        	var url = params[0];
	        	
	        	var ret = false;
	        	$.ajax({
	        		url : url,
	        		dataType : "json",
	        		data : {"value":value},
	        		async : false,
	        		type : "post",
	        		success: function(result) {
	        			if(result.content== "success"){
	        				ret = true;
	    	        	}
        		    }
	        	}); 
	        	
	        	return ret;
	        },
	        message: '乙方信息不存在！'
	    },
	    validateContractCodeIsExist: { 
	        validator: function(value, params){
	        	var name=params[1];
	        	var url = params[0];
	        	var ret = false;
	        	$.ajax({
	        		url : url,
	        		dataType : "json",
	        		data : {"value":value},
	        		async : false,
	        		type : "post",
	        		success: function(result) {
	        			if(result.content== "success"){
	        				ret = true;
	    	        	}
        		    }
	        	}); 
	        	
	        	return ret;
	        },
	        message: '编号有误,合同信息不存在！'
	    },
	    valiDate:{
	    	validator:function(value,param){
	    		var partten=/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/;
	    		var res = value.match(partten);
	    		if(res==null){
	    			return false;
	    		}
	    		var r = res;
	    		if(r[3]>12){
	    			return false;
	    		}
	    		 //对日期进行判定
	            if(r[3]==2&&r[1]%4==0){//闰年二月的日期的判定
	                if(r[4]<0||r[4]>29){
	                	//alert(r[1]+"年2月的日期必须介于1和29之间，请您重新输入！");
	                	return false;
	                }
	            }
	            if(r[3]==2&&r[1]%4!=0){//平年二月的日期的判定
	                if(r[4]<0||r[4]>28){
//	                	alert(r[1]+"年2月的日期必须介于1和28之间，请您重新输入！");
	                	return false;
	                }
	            }
	            if(r[3]<8&&r[3]%2==0){//4，6月日期的判定
	                if(r[4]<0||r[4]>30){
//	                	alert(r[3]+"月的日期必须介于1和30之间，请您重新输入！");
	                	return false;
	                }
	            }
	            if(r[3]<8&&r[3]%2!=0) {//1、3、5、7月日期的判定
	                if(r[4]<0||r[4]>31){
//	                	alert(r[3]+"月的日期必须介于1和31之间，请您重新输入！");
	                	return false;
	                }
	            }
	            if(r[3]>=8&&r[3]%2==0){//8、10、12月日期的判定
	                if(r[4]<0||r[4]>31){
//	                	alert(r[3]+"月的日期必须介于1和31之间，请您重新输入！");
	                	return false;
	                }
	            }
	            if(r[3]>=8&&r[3]%2!=0){//9、11月日期的判定
	                if(r[4]<0||r[4]>30){
	                	//alert(r[3]+"月的日期必须介于1和30之间，请您重新输入！");
	                	return false;
	                }
	            }
	    		return true;
	    	},
	    	message:'日期格式不正确!'
	    },
	    valiStartDate:{
	    	validator:function(value,param){
	    		var partten=/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/;
	    		var endDate = $("#"+param).datebox("getValue");
	    		var res = endDate.match(partten);
	    		if(res == null){return true;}
	    		if(res.length == 5){
	    			var arr1 = value.split("-");
	    			var arr2 = endDate.split("-");
	    			if(arr1[0]<arr2[0]){
	    				return true;
	    			}else if(arr1[0] == arr2[0]){
	    				if(arr1[1]<arr2[1]){
	    					return true;
	    				}else if(arr1[1] == arr2[1]){
	    					if(arr1[2] <arr2[2]){
	    						return true;
	    					}
	    				}
	    			}
	    		}
	    		return false;
	    	},
	    	message:'开始时间必须小于结束时间'
	    },
	    valiEndDate:{
	    	validator:function(value,param){
	    		var partten=/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/;
	    		var startDate = $("#"+param).datebox("getValue");
	    		var res = startDate.match(partten);
	    		if(res == null){return true;}
	    		if(res.length == 5){
	    			var arr1 = startDate.split("-");
	    			var arr2 = value.split("-");
	    			if(arr1[0]<arr2[0]){
	    				return true;
	    			}else if(arr1[0] == arr2[0]){
	    				if(arr1[1]<arr2[1]){
	    					return true;
	    				}else if(arr1[1] == arr2[1]){
	    					if(arr1[2] <arr2[2]){
	    						return true;
	    					}
	    				}
	    			}
	    		}
	    		return false;
	    	},
	    	message:'结束时间必须大于开始时间'
	    },
	    md:{
			validator: function(value, param){
				var d1 = $.fn.datebox.defaults.parser(myformatter(new Date()));
				var d2 = $.fn.datebox.defaults.parser(value);
				return d2>=d1;
			},
			message: '选择日期不能小于当前日期！'
		},
		validatePersent:{
			validator: function(value, param){
				var validateLength = param.length;
				var total = 0;
				if(validateLength > 0){
					for ( var int = 0; int < validateLength; int++) {
						var paramValue = $("#"+param[int]).val();
						if(paramValue){
							total+=new Number(paramValue);
						}
						
					}
				}
				var flag = total == 100?true:false;
				if(flag){
					if(validateLength > 0){
						for ( var int = 0; int < validateLength; int++) {
							$("#"+param[int]).removeClass("validatebox-invalid");
						}
					}
				}
				return flag;
			},
			message: '百分比总和不等于100%!'
		},
	    dropListDown:{
			validator: function(value, param){
				var result;
				 if(value=='\u8BF7\u9009\u62E9'){result=false;}else{result=true;}
				 return result;
			},
			message: '请选择一个有效的选项！'
		},
		searchAttrRequied:{
			validator: function(value, param){
				var attrArray = param.length;
				var _id = param[0];
				var flag = true;
				if(attrArray > 1){
					for ( var int = 1; int < attrArray; int++) {
						if($("#"+_id).attr(param[int]) == "" || !$("#"+_id).attr(param[int])){
							flag = false;
						}
					}
				}
				return flag;
			},
			message: '请选择查询列表中的选项值！'
		},
		
		cityNodefault:{
	    	validator: function(value, param){   
	    		if(value==param[0]){
	    			return false;
	    		}
				return true;
	        },   
	        message: '请选择{0}!' 
	    }
});
/** 
 * @Description: 验证非法字符
 * @author lvf 
 */
function isSafe(str) {  
    var filterString = "'~`·!#$%^*+/";  
    var ch;  
    var i;  
    var temp;  
    var error = false; // 当包含非法字符时，返回True     
    for (i = 0; i <= (filterString.length - 1); i++) {  
        ch = filterString.charAt(i);  
        temp = str.indexOf(ch);  
        if (temp != -1) {  
            error = true;  
            break;  
        }  
    }  
    return error;  
}
/**
 * @Description: 验证身份证号码格式
 * @author lvf 
 */
function isIdCardNo(num) {
	var factorArr = new Array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2,1);
	var parityBit=new Array("1","0","X","9","8","7","6","5","4","3","2");
	var varArray = new Array();
	var intValue;
	var lngProduct = 0;
	var intCheckDigit;
	var intStrLen = num.length;
	var idNumber = num;
  // initialize
    if ((intStrLen != 15) && (intStrLen != 18)) {
        return false;
    }
    // check and set value
    for(i=0;i<intStrLen;i++) {
        varArray[i] = idNumber.charAt(i);
        if ((varArray[i] < '0' || varArray[i] > '9') && (i != 17)) {
            return false;
        } else if (i < 17) {
            varArray[i] = varArray[i] * factorArr[i];
        }
    }
    
    if (intStrLen == 18) {
        //check date
        var date8 = idNumber.substring(6,14);
        if (isDate8(date8) == false) {
           return false;
        }
        // calculate the sum of the products
        for(i=0;i<17;i++) {
            lngProduct = lngProduct + varArray[i];
        }
        // calculate the check digit
        intCheckDigit = parityBit[lngProduct % 11];
        // check last digit
        if (varArray[17] != intCheckDigit) {
            return false;
        }
    }
    else{        //length is 15
        //check date
        var date6 = idNumber.substring(6,12);
        if (isDate6(date6) == false) {
            return false;
        }
    }
    return true;
}