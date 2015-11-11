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
		/**通过mid获取电影详情，因电影详情后台接口暂时未提供，暂且用心愿详情接口**/
		loadMovieInfo: function(mid, success, failure, callback) {
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
		/**通过mid获取电影详情，因电影详情后台接口暂时未提供，暂且用心愿详情接口**/
		loadActivityList: function(mid, city, success, failure, callback) {
			net.send({
				server: server.activity.getactivitybymovie,
				params: { 
					mid: mid,
					city: city
				},
				success: success,
				failure: failure,
				callback: callback
			})
		}
	}

});