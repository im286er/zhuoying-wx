define(function(require, exports, module) {
	/**
	 * 加载其他模块
	 */
		var server = require('server'),
		net = require('net');
	/**
	 * 定义模块内部方法
	 */
	module.exports = {
		/**
		 * 登录 
		 */
		login : function(loginInfo,success,error,callback) {
			var phonenumber = loginInfo.phonenumber;
			var password = loginInfo.password;
			var city = loginInfo.city;
			//前端校验手机号和密码
			if (phonenumber.length != 11) {
				return callback('请输入有效的手机号！');
			}
			var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
			if(!reg.test(phonenumber)){
				return callback('请输入有效的手机号！');
			}
			if (password == "" || password.length < 6 || password.length>20) {
				return callback('请输入6-20位用户密码！');
			}
			
			//发送ajax请求
			net.send({
				server: server.user.login,
				params:loginInfo,
				success:success,
				failure:error
			})
		},
		/*
		 * 第三方登录
		 */
		loginBythird : function(params,success,error){
			console.log(params);
			//发送ajax请求
			net.send({
				server: server.user.loginBythird,
				params: params,
				success:success,
				failure:error
			});
			
		}

	}
		
	
	
});