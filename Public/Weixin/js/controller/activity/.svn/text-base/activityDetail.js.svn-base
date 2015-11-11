/**
 * 系统初始化逻辑
 */
define(function(require, exports, module) {
	/**
	 * 加载其他模块
	 */
	var template = require('template'),
		server = require('server'),
		net = require('util/net');

	/**
	 * 定义模块内部方法
	 */
	module.exports = {
		activityDetail: function(params,success,error) {
			net.send({
				server: server.activity.activitydetail,
				params: params,
				success: success,
				failure:error
			})
		},
		zeropay: function(params,success,error) {
			console.log(params);
			net.send({
				server: server.activity.join,
				params: params,
				success: success,
				failure:error
			})
		}
		

	}

});