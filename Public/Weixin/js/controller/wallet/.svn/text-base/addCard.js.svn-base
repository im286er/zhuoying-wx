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
		
		addbank : function (params,success,error) {
			//发送ajax请求
			net.send({
				server: server.account.purse,
				params: params,
				success:success,
				failure:error
			});
		}
		
		
	}
	
});