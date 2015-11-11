/**
 * 系统初始化逻辑
 */
define(function(require, exports, module) {
	/**
	 * 加载其他模块
	 */
	var template = require('template'),
		server = require('config/server'),
		net = require('util/net');

	/**
	 * 定义模块内部方法
	 */
	module.exports = {
		launchActivity: function(params,success,error) {
			net.send({
				server: server.activity.launch,
				params: params,
				success: success,
				failure:error
			})
		},
		activityDetail: function(sid,success,error) {
			net.send({
				server: server.activity.activitydetail,
				params: {
				    aid: sid,
				},
				success: success,
				failure:error
			})
		}
		

	}

});