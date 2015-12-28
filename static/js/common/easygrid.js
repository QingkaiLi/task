/**
 * Author: Johnson Cheng
 * Date:2013-7-5
 * Mail:wb-chengwenliang@alibaba-inc.com chengwenliang@chinasofti.com
 */

(function ( $ ) {
	/** private data*/
	var NULL_TO_STRING = '--';
	
	var settings = null;
	
	/** public method*/
	/**
	 * Initialize an table to an easygrid control.
	 */
    $.fn.easygrid = function( options ) {
    	settings = $.extend( {}, $.fn.easygrid.defaults, options );
        
    	//TODO: replace with context path in the future
    	//settings.sOptionUrl = '/ceres/comm/resource/pub/getoptions.json';
		settings.sOptionUrl = null;
    	//settings.oOptionTypes = 'CITY,SUPPLIER_TYPE';
    	
        return this.each(function(){
        	var $this = $(this);
        	
        	//load and parse all options
        	var optionTypesArr = [];
        	$.each(settings.columnDefs,function(index,columnDef){
        		if('option' == columnDef.type){
        			optionTypesArr.push(columnDef.optionType);
        		}
        	});
			// 调试所用
			optionTypesArr.length = 0;

        	settings.oOptionTypes = optionTypesArr.join(',');
        	
        	if($.trim(settings.oOptionTypes) != ''){
	        	//var optionTypeRequestParam = {'optionTypes':'CITY,SUPPLIER_TYPE'};
        		var optionTypeRequestParam = {'optionTypes':settings.oOptionTypes};
	        	var optionResult = _ajaxLoadData(settings.sOptionUrl,optionTypeRequestParam);
	        	_parseOptionResult(optionResult);
        	}
        	
        	//load table data
        	var dataResult = _ajaxLoadData(settings.sUrl,settings.oRequestParam);
        	
        	//TODO: callback not implemented
        	_parseDataResult(dataResult);
        	
        	if(!settings.hasError){
        		if(settings.oData && settings.oData.length > 0){
		        	$this.append(_renderTableData(settings.columnDefs,settings.oData));
		        	
		        	if(settings.bPaginate){
		        		var pageSize = settings.nPageSize;
		        		var pageNo = settings.nPageNo;
		        		var rowCount = settings.nRowCount;
		        		
		        		$this.after(_renderPaginationBar(pageSize,rowCount,pageNo,$this));
		        	}
        		}
        		else{
        			_renderEmptyResult($this);
        		}
        	}
        	else{
        		_renderErrData($this);
        	}

            settings.callback.apply($this,[dataResult]);

        });
    };
    
 	$.fn.easygrid.defaults = {
			'bPaginate': true,			//true if need a pagination banner
			'bServer':true,				//not used
			'nPageSize': 50,			//default page size
			'nPageNo':1,				//default page no
			'oRequestParam':{},			//request parameters 
			'hasError':false,			//true if load data from server failed
			'hasOptionError':false,		//true if load options from server failed
			'sOptionUrl': null,			//url to load options from server
			'oOptionTypes':'',			//option types, get from oColumnDefs, separated by comma, eg {CITY,SUPPLIER_TYPE}
			'oOptionDatas':null,
            'callback' : function(){}   // 列表加载完毕之后的回调方法
   	};
    
    //refresh easygrid data
    $.fn.reload = function(requestParam){
    	return this.each(function(){
        	var $this = $(this);
        	if(requestParam){
        		$.extend(settings.oRequestParam,requestParam);
        	}
        	
        	//clear table data and pagination banner if has.
   	    	_clearTableData($this);
   	    	
   	    	if(settings.bPaginate){
   	    		_removePaginationBar($this);
   	    	}
   	    	
   	  		var dataResult = _ajaxLoadData(settings.sUrl,settings.oRequestParam);
	        //TODO: callback not implemented
	        _parseDataResult(dataResult);
	        
	        if(!settings.hasError){
	        	if(settings.oData && settings.oData.length > 0){
	        		$this.append(_renderTableData(settings.columnDefs,settings.oData));
                }else{
	        		_renderEmptyResult($this);
	        	}
		        
		        if(settings.bPaginate){
		        	var pageSize = settings.nPageSize;
	        		var rowCount = settings.nRowCount;
	        		
	        		//calculate if page no is valid
	        		var pageNo = settings.nPageNo;
	        		var pageCount=parseInt(rowCount/pageSize)+(rowCount%pageSize==0?0:1);
	        		
	        		//if exist request parameter, then need reset the table to page 1
	        		if(requestParam){
	        			pageNo = 1;
	        		}
	        		
	        		//if pageNo is out of scope then set page no to first page
	        		if(pageNo < 1 || pageNo > pageCount){
	        			pageNo = 1;
	        		}
	        		
	        		settings.nPageNo = pageNo;
	        		
		        	
		        	$this.after(_renderPaginationBar(pageSize,rowCount,pageNo,$this));
		        }
	        }
	        else{
	        	_renderErrData($this);
	        }

            settings.callback.apply($this,[dataResult]);

        });
    };
    
    //private methods
    
    /**
     *	create a td element.
     *
     *  rowIndex: the row index for this cell
     *  colIndex: the column index for this cell
     *  colDef: the definination of corresponding column
     */
    function _renderCell(rowIndex,colIndex,colDef,rowData){
    	 var td = $('<td></td>');
    	 
   		 td.attr('class',rowIndex%2 == 1?'eg_td_eve':'eg_td_odd');
   		 if(colIndex==0){td.addClass('tint5');}
   		 
    	 
    	 var val = NULL_TO_STRING;
    	 var type = colDef.type;
    	 
    	 if('number' == type || 'string' == type){
    		 val = eval('rowData.' + colDef.name);
    		 val = val || '';
    	 }else if('date' == type){
    		 var time = eval('rowData.' + colDef.name);
    		 if(time){
	    		 var d = new Date(eval('rowData.' + colDef.name));
	    		 val = d.getFullYear() + '-' + _getFs(d.getMonth()+1) + '-' + _getFs(d.getDate());
    		 }
    		 else{ val = '';}
    	 }else if('datetime' == type){
    		 var time = eval('rowData.' + colDef.name);
    		 if(time){
	    		 var d = new Date( eval('rowData.' + colDef.name) );
	    		 val = d.getFullYear() + '-' + _getFs(d.getMonth()+1) + '-' + _getFs(d.getDate());
	    		 val += ' ' + _getFs(d.getHours()) + ':' + _getFs(d.getMinutes()) + ':' + _getFs(d.getSeconds());
    		 }
    		 else{ val = '';}
    	 }
    	 else if('boolean' == type){
    		 val = eval('rowData.' + colDef.name)==true?'是':'否';
    	 }else if('callback' == type || 'control' == type){
    		 if(colDef.render && typeof colDef.render == 'function'){
    			 val = colDef.render(rowIndex,colIndex,colDef,rowData);
    		 }
    	 }else if('option' == type){
    		 var data = eval('rowData.' + colDef.name);
    		 var optionType = colDef.optionType;
    		 
    		 var mappedData = optionType ? eval('settings.oOptionDatas.' + optionType) : '';
	    	 if(mappedData){ 
			 	for(var i=0;i<mappedData.length;i++){
	    			 if(mappedData[i].key == data){
	    				 val = mappedData[i].value;
	    				 break;
	    			 }
	    		}
	    	 }
	    	 else{
	    		 val = data;
	    		 //console.log('Data not translated for column ' + colIndex);
	    	 }
    	 }
    	 else{val = 'N/A';}
    	 
    	 if(colDef.css){
			 td.css(colDef.css);
    	 }
    	 
         var oDiv = $('<div/>',{"class":"gridColTd"}).html( val );
         td.append( oDiv );
    	 
    	 return td;
    }
    
    function _getFs(n){
    	return n<10?'0'+n:n;
    }
    
    /**
     * Render row data
     * 
     */
     //TODO: callback not implemented
    function _renderRow(rowIndex,colDefs,rowData,callback){
    	var tr = $('<tr></tr>');
    	
    	tr.attr('class',rowIndex%2 == 1?'eg_tr_eve':'eg_tr_odd');
    	
    	if(callback != 'undefined' && typeof callback == 'function'){
    		callback(tr,rowIndex,colDefs,rowData);
    	}else{
    		$.each(colDefs,function(index,colDef){
    			var td = _renderCell(rowIndex,index,colDef,rowData);
    			tr.append(td);
    		});
    	}
    	
    	return tr;
    }
    
    function _renderTableData(colDefs,tableData){
    	var tbody = $('<tbody></tbody>');
    	
    	tbody.attr('class','eg_tbody');
    	
    	if(tableData){
    		$.each(tableData,function(rowIndex,rowData){
    			var tr = _renderRow(rowIndex,colDefs,rowData);
    			tbody.append(tr);
    		});
    		
    	}
    	
    	return tbody;
    }
    
 	/** remove tbody element */
	function _clearTableData(tblObj){
		var tbody = tblObj.find('tbody');
		if(tbody){tbody.remove();}
		
	}
    
    /**
     * A function the render the pagination banner.
     * pageSize: The pagesize,can use nPageSize during initialize.
     * rowCount: Total row count.
     * pageNo: The page no.
     */
    function _renderPaginationBar(pageSize,rowCount,pageNo,tblControl){
    	 
    	var pagers = [];
    	 
    	var paginateBar = $('<div></div>');
    	
    	paginateBar.attr('class','eg_pb_bar');
    	
    	var pageCount=parseInt(rowCount/pageSize)+(rowCount%pageSize==0?0:1);
    	
    	//if only one page, pagination bar is not needed.
    	if(pageCount <= 1){return;}
    	
    	//firstpage control
    	var firstPageControl = $('<a></a>').html('首页');
    	
    	var firstPageSpan = $('<span></span>')
    						.attr('class','eg_pb_first')
    						.append(firstPageControl);
    	//if selected page is not first page then attache click handler
    	if(1 < settings.nPageNo){
    		firstPageControl.attr('href','javascript:void(0)');
    		firstPageControl.click(function(){$.extend(settings,{'nPageNo':1});tblControl.reload();});
    	}
    	paginateBar.append(firstPageSpan);
    	
    	//previous page control
    	var previousPageControl = $('<a></a>').html('＜上一页');
    	var previousPageSpan = $('<span></span>')
    						.attr('class','eg_pb_first')
    						.append(previousPageControl);
    	//if selected page is not first page then attache click handler
    	if(1 < settings.nPageNo){
    		previousPageControl.attr('href','javascript:void(0)');
    		previousPageControl.click(function(){$.extend(settings,{'nPageNo':settings.nPageNo-1});tblControl.reload();});
    	}
    	paginateBar.append(previousPageSpan);
    	
    	//other page
    	for(var i=0;i<pageCount;i++){
    		var pageCtl = $('<a></a>')
    			.attr('href','javascript:void(0)')
    			.attr('class',(settings.nPageNo == i+1)?'eg_pb_arch_sel':'eg_pb_arch')
    			.html(i+1);
    		
    		if(settings.nPageNo == i+1){
    			pageCtl.removeAttr('href');
    		}else{
    			var k = i+1;
    			pageCtl.bind('click',
    					(function(k){
    						return function(){
    							$.extend(settings,{'nPageNo':k});
    							tblControl.reload();
    						};
    					})(k)
    			);
    		}
    		
    		var span = $('<span></span>')
    			.attr('class','eg_pb_pager')
    			.append(pageCtl);
    		
    		//paginateBar.append(span);
    		pagers[i] = span;
    	}
    	
    	var minIndex = 1;
    	var maxIndex = 10;
    	var currentPageNo = settings.nPageNo;
    	if(currentPageNo > 5){
    		maxIndex = (currentPageNo + 5 > pageCount)? pageCount:currentPageNo + 5;
    		minIndex = maxIndex - 10 >= 0? maxIndex-10+1:1;
    	}
    	
    	for(var i=minIndex;i<=maxIndex;i++){
    		paginateBar.append(pagers[i-1]);
    	}
    	
    	//next page control
    	var nextPageControl = $('<a></a>').html('下一页＞');
    	var nextPageSpan = $('<span></span>')
    						.attr('class','eg_pb_last')
    						.append(nextPageControl);
    	//if selected page is not last page then attache click handler
    	if(pageCount > settings.nPageNo){
    		nextPageControl.attr('href','javascript:void(0)');
    		nextPageControl.click(function(){$.extend(settings,{'nPageNo':settings.nPageNo+1});tblControl.reload();});
    	}
    	paginateBar.append(nextPageSpan);
    	
    	
    	//tail page control
   	    var lastPageControl = $('<a></a>').html('末页');
   	    				
   	    if(pageCount > settings.nPageNo){			
   	    	lastPageControl.attr('href','javascript:void(0)')
   	    	lastPageControl.click(function(){$.extend(settings,{'nPageNo':pageCount});tblControl.reload();});
   	    }
   	    var lastPageSpan = $('<span></span>')
   	    						.attr('class','eg_pb_last')
   	    						.append(lastPageControl);
   	    	
   	    paginateBar.append(lastPageSpan);
   	    
   	    
   	    var totalCountSpan = $('<span></span>').attr('class','eg_pb_last');
   	    totalCountSpan.html('共' + rowCount  + '条');
   	    paginateBar.append(totalCountSpan);
    	
    	return paginateBar;
    }
    
    function _removePaginationBar(tblObj){
    	//var paginationBar = tblObj.find('.eg_pb_bar');
    	var paginationBar = tblObj.next();
    	
		if(paginationBar){paginationBar.remove();}
    }
    
    /**
     * Ajax request to the server to retrive the table data.
     * url: the target url
     * requestParam: now will use POST method and submit all the params
     */
    function _ajaxLoadData(url,requestParam){
    	var dataResult = null;
    	//add an timestamp to url to prevent browser buffer
    	url = url || '';
    	var t = new Date();
    	url += url.lastIndexOf('?') < 0 ? '?_timestamp=' + t.getTime():'&_timestamp=' + t.getTime();
    	
    	
    	requestParam = $.extend(requestParam,_calcPagerParams());
    	$.ajax({
 	    	  type: 'POST',
 	    	  url: url,
 	    	  async: false,
 	    	  data: requestParam,
 	    	  //contentType: 'application/json; charset=utf-8',
 	    	  dataType: 'json',
 	    	  success: function(data){
 	    		  dataResult = data;
 	    	  }
 	    	});
    	return dataResult;
    }
    
    /** parse the result from ajax call
     *  
     *  dataResult: data retrived from ajax request
     *  callback: a result parse function, will give dataresult and setting object.
     */
	function _parseDataResult(dataResult,callback){
		if(callback && 'function' == typeof callback){
			return callback(dataResult,settings);
		}else{
			if( dataResult && dataResult.hasError){
				settings.hasError = true;
			}
			else{
				if( dataResult && dataResult.returnValue && dataResult.returnValue.list && dataResult.returnValue.list.length){
					settings.nRowCount = dataResult.returnValue.count;
					settings.oData = dataResult.returnValue.list;
				}
				else{
					settings.nRowCount = 0;
					settings.oData = null;
				}

				if(settings.hasError){
					settings.hasError = false;
				}
			}
		}
	}
	
	/**
	 * parse the option data from ajax call
	 * 
	 * optionResult: option list from ajax request,eg: {type1:[{'key1':'value1'},{'key2':'value2'}]}
	 * callback: a result parse function, will give dataresult and setting object.
	 */
	function _parseOptionResult(optionResult,callback){
		if(callback && 'function' == typeof callback){
			return callback(optionResult,settings);
		}else{
			if(optionResult.hasError){
				settings.hasOptionError = true;
			}
			else{
				//settings.oOptionDatas = $.extend(settings.oOptionDatas ,optionResult.dataResult);
				settings.oOptionDatas = optionResult.dataResult;
				
				if(settings.hasOptionError){
					settings.hasOptionError = false;
				}
			}
		}
	}
    
    /**
     * calculate page param
     */
    function _calcPagerParams(){
    	//return {'take':settings.nPageSize,'skip':(settings.nPageNo-1) * settings.nPageSize + 1};
        return {'take':settings.nPageSize,'pageIndex':settings.nPageNo};
    }
    
    /**
     * Render the error information while load data failed.
     *
     */
    function _renderErrData(tblObj){
    	_clearTableData(tblObj);
    	_removePaginationBar(tblObj);
    	
    	if(settings.columnDefs){
    		var errTd = $('<td></td>')
    			.attr('colspan',settings.columnDefs.length)
    			.html('Data load failed, please check network settings and try again!');
    		
    		var errTr = $('<tr></tr>').append(errTd);
    		
    		var errBody = $('<tbody></tbody>').append(errTr);
    		
    		tblObj.append(errBody);
    	}
    }
    
    function _renderEmptyResult(tblObj){
    	_clearTableData(tblObj);
    	_removePaginationBar(tblObj);
    	
    	if(settings.columnDefs){
    		var errTd = $('<td align="center"></td>')
    			.attr('colspan',settings.columnDefs.length)
    			.html('没有符合条件的数据!');
    		
    		var errTr = $('<tr></tr>').append(errTd);
    		
    		var errBody = $('<tbody class="gridEmpty"></tbody>').append(errTr);
    		
    		tblObj.append(errBody);
    	}
    }
    
}( jQuery ));