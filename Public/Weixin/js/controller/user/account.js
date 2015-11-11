
define(function(require, exports, module) {
	/**
	 * 加载其他模块
	 */
	var server = require('server'),
		user = require('user'),
		net = require('net');

	/**
	 * 定义模块内部方法
	 */
	module.exports = {
		/**
		 * 资金账户对账单
		 */
		statement: function(index, size, success, callback) {
			net.send({
				server: server.account.statement,
				params: {
					uid: user.getUserData()['userid'],
					pageIndex: index,
					pageSize: size
				},
				success: success,
				callback: callback
			})
		}

	};

});