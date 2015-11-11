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

	// 正确写法
	module.exports = {
		/**
		 * 获取影库列表 
		 */
		getMovies: function(params, success, callback) {
			var order = params.order;
			var pageIndex = params.pageIndex;
			var pageSize = params.pageSize;
			var title = params.title;
			var tag = params.tag;
			net.send({
				server: server.movie.getMovieList,
				params: {
					order: order,
					pageIndex: pageIndex,
					pageSize: title,
					tag:tag
				},
				success: success,
				callback: callback
			})
		}
	};

});