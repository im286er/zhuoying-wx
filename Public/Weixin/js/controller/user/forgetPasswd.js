define(function(require, exports, module){
	/**
	 * 加载其他模块
	 */
	var template = require('template'),
		server = require('server'),
		net = require('net');
	
	/**
	 * 定义模块内部方法
	 */
	module.exports = {
		/**
		 * 给服务器发送ajax请求，进行密码找回
		 * @param {Object} regInfo
		 * @param {Object} callback
		 */
		forgetPass : function (regInfo,success,error) {
			var phonenumber = regInfo.account;
			var password = regInfo.password;
			var code = regInfo.code;
			
			//发送ajax请求
			net.send({
				server: server.user.forgetPass,
				params: {
					phonenumber:phonenumber,
					password:password,
					code:code
				},
				success:success(phonenumber),
				failure:error
			});
		},
		/**
		 * 给服务器发送ajax请求，获取验证码
		 * @param {Object} regInfo
		 * @param {Object} callback
		 */
		getVertifycode : function  (regInfo,success,error,callback) {
			/** 前端校验手机号 **/
			var phonenumber = regInfo.account;
			if (phonenumber.length < 1) {
				return callback('手机号不能为空！');
			}
			if (phonenumber.length != 11) {
				return callback('请输入有效的手机号！');
			}
			var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
			if(!reg.test(phonenumber)){
				return callback('请输入有效的手机号！');
			}
			//发送ajax请求
			net.send({
				server: server.user.forgetCode,
				params: {
					phonenumber:phonenumber,
					operator:1
				},
				success:success,
				failure:error
			});
			
		},
		/**
		 * 看手机号是否已经存在
		 */
		existPhone :function  (phonenumber,error) {
			//发送ajax请求
			net.send({
				server: server.user.existPhone,
				params: {
					phonenumber:phonenumber
				},
				failure:error
			});
		}
	}
	
});
