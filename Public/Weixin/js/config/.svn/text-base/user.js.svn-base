/**
 * 系统初始化逻辑
 */
define(function(require, exports, module) {
	/**
	 * 定义模块内部方法
	 */
	module.exports = {
		/**
		 * 用户数据列表
		 */
		username: "username",
		userid: "userid",
		usersex: "usersex",
		userphone: "userphone",
		country: "country",
		province: "province",
		city: "city",
		localcity:"localcity",
		avatar: 'avatar',
		clientid: "clientid",
		latitude:"latitude",
		longitude:"longitude",
		userrole:'userrole',
		usersign:'usersign',
		/**
		 * 获取用户数据
		 */
		getUserData: function(data) {
			return getCookie(data);
		},
		/**
		 * 设置用户数据
		 */
		setUserData: function(key, value) {
			setCookie(key, value);
		},
		/**
		 * 清除用户信息
		 */
		clearUserData: function() {
			delCookie(this.username);
			delCookie(this.userid);
			delCookie(this.usersex);
			delCookie(this.userphone);
			delCookie(this.avatar);
			delCookie(this.usersign);
		}
	};

	//写cookies 
	function setCookie(name,value) {
	    var Days = 30; 
	    var exp = new Date(); 
	    exp.setTime(exp.getTime() + Days*24*60*60*1000); 
	    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString(); 
	} 

	//读取cookies 
	function getCookie(name) {
	    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
	 
	    if(arr=document.cookie.match(reg))
	        return unescape(arr[2]); 
	    else 
	        return null; 
	} 

	//删除cookies 
	function delCookie(name) {
	    var exp = new Date(); 
	    exp.setTime(exp.getTime() - 1); 
	    var cval=getCookie(name); 
	    if(cval!=null) 
	        document.cookie= name + "="+cval+";expires="+exp.toGMTString(); 
	} 
});