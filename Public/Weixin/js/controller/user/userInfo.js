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
		/**
		 * 获取心愿列表
		 */
		editUser: function(params,success,error) {
			console.log(params);
			net.send({
				server: server.user.editUser,
				params: params,
				success: success,
				failure:error
			})
		}

	};

});