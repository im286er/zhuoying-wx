
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
		 * 获取心愿列表
		 */
		getSite: function(city, success, callback) {
			net.send({
				server: server.activity.getsite,
				params: {
					city:city
				},
				success: success,
				callback: callback
			})
		}

	};

});