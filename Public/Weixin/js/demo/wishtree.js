/**
 * 系统初始化逻辑
 */
define(function(require, exports, module) {
	/**
	 * 加载其他模块
	 */
	var template = require('template'),
		server = require('./config/server'),
		net = require('./util/net');

	/**
	 * 定义模块内部方法
	 */
	module.exports = {
		/**
		 * 获取心愿列表
		 */
		getWishList: function(type, index, size, success, callback) {
			net.send({
				server: server.wish.getWishList,
				params: {
					order: type,
					pageIndex: index,
					pageSize: size
				},
				success: success,
				callback: callback
			})
		}

	};

});