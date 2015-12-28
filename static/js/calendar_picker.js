(function($) {
    $.fn.date = function(options, Ycallback, Ncallback) {
        var that = $(this);
        var docType = $(this).is('input');
        var datetime = false;
        var nowdate = new Date();
        var indexD = 1, indexH = 0, indexM = 0;
        var initD = parseInt(nowdate.getDate()+"");
        var initH = parseInt(nowdate.getHours());
        var initI = parseInt(nowdate.getMinutes());
        var daysList = ['今天', '明天', '后天'];
        var hoursList = getHoursList();
        var minsList = ['0分', '10分', '20分', '30分', '40分', '50分'];
        var dayScroll = null, hourScroll = null, minScroll = null;
        var beginHour = 0, beginMin = 0;
        $.fn.date.defaultOptions = {
            curdate: true,
            event: 'click',
            show: true
        }
        var opts = $.extend(true, {}, $.fn.date.defaultOptions, options);
        if (!opts.show) {
            that.unbind('click');
        } else {
            that.bind(opts.event, function() {
                resetIndex();
                createUI();
                init_iScroll();
                extendOptions();
                that.blur();
                refreshDate();
                bindButton();
            })
        }
        function strToDate() {
            //dateStr = daysList[indexD - 1] + '-' + hoursList[indexH] + '-' + minsList[indexM -1];
            var dt = new Date();
            var str = '';
            var hr = hoursList[indexH].substring(0, hoursList[indexH].length - 1);
            var mi = minsList[indexM-1].substring(0, minsList[indexM-1].length - 1);
            if (indexD == 2) {
                dt.setDate(dt.getDate()+1);
            } else if (indexD == 3) {
                dt.setDate(dt.getDate()+2);
            }
            return dt.getFullYear()+'-'+add0Prefix(dt.getMonth()+1)+'-'+add0Prefix(dt.getDate())+' '+add0Prefix(hr)+':'+add0Prefix(mi);
        }
        function add0Prefix(dd) {
            if (dd < 10) return '0'+dd;
            return dd;
        }
        function bindButton() {

            $("#dateconfirm").unbind('click').click(function () {
                var dateStr = '';
                dateStr = strToDate();//daysList[indexD - 1] + '-' + hoursList[indexH] + '-' + minsList[indexM -1];
                //alert(dateStr);
                if (Ycallback === undefined) {
                    if (docType) {
                        that.val(dateStr);
                    } else {
                        that.html(dateStr);
                    }
                } else {
                    Ycallback(dateStr);
                }
                $("#datePage").hide();
                $("#dateshadow").hide();
            });
            $("#datecancle").click(function () {
                $("#datePage").hide();
                $("#dateshadow").hide();
                //Ncallback(false);
            });
        }
        function resetIndex() {
            indexD = 1;
            indexH = 0;
            indexM = 0;
        }
        function refreshDate() {
            dayScroll.refresh();
            hourScroll.refresh();
            minScroll.refresh();

            resetInitDate();

            dayScroll.scrollTo(0, indexD *40, 100, true);
            hourScroll.scrollTo(0, indexH *40, 100, true);
            minScroll.scrollTo(0, indexM *40, 100, true);
        }
        function resetInitDate() {
            if (opts.curdate) {return false;}
            else if (that.val() === ""){ return false;}
            initD = parseInt(that.val().substr(2, 2));
            initH = parseInt(that.val().substr(5, 2));
            initI = parseInt(that.val().substr(8, 2));
        }
        function extendOptions() {
            $("#datePage").show();
            $("#dateshadow").show();
        }
        function init_iScroll() {
            dayScroll = new iScroll('yearwrapper', {snap:'li', vScrollbar: false,
                onScrollEnd: function() {
                    indexD = (this.y / 40) * (-1) + 1;
                    beginHour = checkHours(indexD);
                    $('#monthwrapper ul').html(createHOUR_UI());
                    hourScroll.refresh();
                    hourScroll.scrollTo(0, hourScroll.y, 100, true);
                    indexH = beginHour;
                    triggerMinScroll(indexH);
                }});
            hourScroll = new iScroll('monthwrapper', {snap: 'li', vScrollbar: false,
                onScrollEnd: function() {
                    indexH = (this.y / 40) * (-1) + 1 + beginHour - 1;
                    triggerMinScroll(indexH);
                }});
            minScroll = new iScroll('daywrapper', {snap: 'li', vScrollbar: false,
                onScrollEnd: function() {
                    indexM = (this.y / 40) * (-1) + 1 + beginMin;
                }});
        }
        function triggerMinScroll(h) {
            beginMin = checkMins(indexD, h);
            indexM = beginMin;
            $('#daywrapper ul').html(createMIN_UI());
            minScroll.refresh();
            minScroll.scrollTo(0, minScroll.y, 100, true);
        }
        function checkMins(day, hour) {
            if (day > 1) return 0;
            else if (hour > initH) return 0;
            else {
                return Math.ceil(((initI+1)/10)%6);
            }
        }
        function checkHours(day) {
            if (day == 1) {
                //var newList = hoursList.slice(initH);
                if (initI > 45) return initH + 1;
                return initH;
            }
            else return 0;// hoursList;
        }
        function getHoursList() {
            var arr = [];
            for (var i = 0; i < 24; i++) arr.push(i+'点');
            return arr;
        };
        function createUI() {
            createDateUI();
            $('#yearwrapper ul').html(createDAY_UI());
            $('#monthwrapper ul').html(createHOUR_UI());
            $('#daywrapper ul').html(createMIN_UI());
        }
        function createDAY_UI() {
            var str = "<li>&nbsp;</li>";
            $.each(daysList, function(k, v) {
                str += '<li value="'+k+'">'+v+'</li>';
            })
            return str+"<li>&nbsp;</li>";
        }
        function createHOUR_UI() {
            var str = "<li>&nbsp;</li>";
            for(var i = beginHour; i < hoursList.length; i++)
                str += '<li value="'+i+'">'+hoursList[i]+'</li>';
            return str+"<li>&nbsp;</li>";
        }
        function createMIN_UI() {
            var str = "<li>&nbsp;</li>";
            for (var i = beginMin; i < minsList.length; i++)
                str += '<li value="'+i+'">'+minsList[i]+'</li>';
            return str+"<li>&nbsp;</li>";
        }
        function createDateUI() {
            var str = ''+
                '<div id="dateshadow"></div>'+
                '<div id="datePage" class="page">'+
                '<section>'+
                '<div id="datetitle"><h1>请选择时间</h1></div>'+
                '<div id="datemark"><a id="markyear"></a><a id="markmonth"></a><a id="markday"></a></div>'+
                '<div id="datescroll">'+
                '<div id="yearwrapper">'+
                '<ul></ul>'+
                '</div>'+
                '<div id="monthwrapper">'+
                '<ul></ul>'+
                '</div>'+
                '<div id="daywrapper">'+
                '<ul></ul>'+
                '</div>'+
                '</div>'+
                '</section>'+
                '<footer id="dateFooter">'+
                '<div id="setcancle">'+
                '<ul>'+
                '<li id="dateconfirm">确定</li>'+
                '<li id="datecancle">取消</li>'+
                '</ul>'+
                '</div>'+
                '</footer>'+
                '</div>'
            $("#datePlugin").html(str);
        }
    }
})(jQuery)
