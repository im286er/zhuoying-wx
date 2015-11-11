
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
		getMyWishList: function(type, index, size, success, callback) {
			net.send({
				server: server.wish.getmywish,
				params: {
					order: type,
					uid:1,
					pageIndex: index,
					pageSize: size
				},
				success: success,
				callback: callback
			})
		}

	};

});