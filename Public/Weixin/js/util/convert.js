define(function(require, exports, module) {
	/**引入其他模块文件**/
	var common = require("util/common");
	var template = require("../../js/template");
	
	/**
	 * 事件戳转换
	 */
	template.helper('dateFormat', function(date,format) {
		return common.dateFormat(date,format);
	});

	/**
	 * 时间日期转换
	 */
	template.helper('convert_usetime', function(data) {
		var str = data.split(",");
		if(!str[0]||!str[1]||!str[2]){
			return "";
		}
		var startTime = str[0].substring(0, 2) + ":" + str[0].substring(2, 4);
		var endTime = str[1].substring(0, 2) + ":" + str[1].substring(2, 4);
		var day = "";
		s = str[2].split("");
		for (var i = 0; i < s.length; i++) {
			switch (s[i]) {
				case "0":
					day += "周日,";
					break;
				case "1":
					day += "周一,";
					break;
				case "2":
					day += "周二,";
					break;
				case "3":
					day += "周三,";
					break;
				case "4":
					day += "周四,";
					break;
				case "5":
					day += "周五,";
					break;
				case "6":
					day += "周六,";
					break;
			}
		}
		var len = day.length;
		day = day.substr(0, len - 1);

		var useTime = startTime + "~" + endTime + "  " + day;

		return useTime;
	});

	
	template.helper('usetime_to_time', function(data) {
		var str = data.split(",");
		if(!str[0]||!str[1]||!str[2]){
			return "";
		}
		var startTime = str[0].substring(0, 2) + ":" + str[0].substring(2, 4);
		var endTime = str[1].substring(0, 2) + ":" + str[1].substring(2, 4);
		var useTime = startTime + "~" + endTime;
		return useTime;
	});

	template.helper('usetime_to_weekday', function(data) {
		var str = data.split(",");
		if(!str[0]||!str[1]||!str[2]){
			return "";
		}
		var day = "";
		s = str[2].split("");
		for (var i = 0; i < s.length; i++) {
			switch (s[i]) {
				case "0":
					day += "周日,";
					break;
				case "1":
					day += "周一,";
					break;
				case "2":
					day += "周二,";
					break;
				case "3":
					day += "周三,";
					break;
				case "4":
					day += "周四,";
					break;
				case "5":
					day += "周五,";
					break;
				case "6":
					day += "周六,";
					break;
			}
		}
		var len = day.length;
		day = day.substr(0, len - 1);
		var useTime = day;
		return useTime;
	});
	
	template.helper('convert_money', function(data) {
		return common.moneyFormat((data / 100), 2);
	});

	template.helper('convert_sendtime', function(data) {
		if(!data){
			return;
		}
		var timestamp = parseInt((new Date()).valueOf()/1000);
		
		var daynow=parseInt(timestamp/86400);
		var daymsg=parseInt(data/86400);
		
		if(daynow==daymsg){
			return common.dateFormat(data,"hh:mm");
		}else if((daynow-daymsg)==1){
			return ("昨天 "+common.dateFormat(data,"hh:mm").toString());
		}else{
			return common.dateFormat(data,"MM月dd日");
		}
	});

	template.helper('convert_cash_status', function(data) {
		switch(data){
			case "1":return "已到账";
			case "0":return "未到账";
		}
	});

	template.helper('convert_account', function(data) {
		var len=data.length;
		var s1 = data.substr(0,4);
		var s2 = "****";
		var s3 = data.substr(data.length-4,4);
		
		return s1+s2+s3;
	});

	template.helper('convert_bank_icon', function(data) {
		switch(data){
			case "北京银行":return "../../images/wallet/bj.png";
			case "中国工商银行":return "../../images/wallet/gs.png";
			case "中国建设银行":return "../../images/wallet/js.png";
			case "中国民生银行":return "../../images/wallet/ms.png";
			case "中国农业银行":return "../../images/wallet/ny.png";
			case "兴业银行":return "../../images/wallet/xy.png";
			case "中国邮政银行":return "../../images/wallet/yz.png";
			case "中国银行":return "../../images/wallet/zg.png";
			case "招商银行":return "../../images/wallet/zs.png";
			case "中信实业银行":return "../../images/wallet/zx.png";
		}
	});

	module.exports = {}
});