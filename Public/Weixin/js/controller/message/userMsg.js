/**
 * 系统初始化逻辑
 */
define(function(require, exports, module) {
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
		gethistorymsg: function(params, success,error) {
			net.send({
				server: server.message.gethistorymsg,
				params: params,
				success: success,
				failure:error
			})
		},
		sendmsg : function  (params,error) {
			net.send({
				server: server.message.sendmsg,
				params: params,
				failure:error
			})
		}

	}

});