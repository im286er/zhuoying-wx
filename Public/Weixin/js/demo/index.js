/**
 * 系统初始化逻辑
 */
define(function(require, exports, module) {
	/**
	 * 加载其他模块
	 */
	var template = require('template');
	/**
	 * 定义模块内部方法
	 */
	module.exports = {
		foo: 'bar',
		/**
		 * asdasdssadasd
		 */
		init: function() {
			console.log('gogo');
			var data = {
				title: '标签',
				list: ['文艺', '博客', '摄影', '电影', '民谣', '旅行', '吉他']
			};
			var html = template('test', data);
			console.log(html);
			document.getElementsByClassName('mui-content')[0].innerHTML = html;

		}

	};

});