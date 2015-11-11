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
		mm:'ss',
		/**通过mid获取电影详情，因电影详情后台接口暂时未提供，暂且用心愿详情接口**/
		getMovieDetail: function(mid, success, failure, callback) {
			net.send({
				server: server.movie.getMovieDetail,
				params: {
					mid: mid
				},
				success: success,
				failure: failure,
				callback: callback
			})
		},
		wantSee: function(params, success,error) {
			console.log(params);
			net.send({
				server: server.wish.addwish,
				params: params,
				success: success,
				failure:error
			})
		}

	}

});