define(function(require, exports, module) {
	/**
	 * 加载其他模块
	 */
	var app = require('../config/app');
	var server = require('../config/server');
	var common = require('../util/common');

	module.exports = {
		send: function(options) {

			//参数模版
			//options={
			//  server:...,
			//  params:...,
			//  success:fn,
			//  scope:...,
			//  failure:fn
			//}
			//获取默认服务端地址
			var url = options.url ? options.url : app.serverUrl;

			//生成链接地址
			if (!options.url&&options.server) {
				mui.each(options.server, function(index, item) {
					url += "/" + item;
				})
			}
			console.log("开始请求:"+url);
			//进行网络通讯
			mui.ajax(url, {
				data: options.params,
				dataType: 'json', //服务器返回json格式数据
				type: 'post', //HTTP请求类型
				timeout: app.timeout,
				scope: options.scope,
				successfn: options.success,
				failurefn: options.failure,
				callbackfn: options.callback,
				// crossDomain: true,
				beforeSend: function(xhr, setting) {
					xhr.successfn = setting.successfn;
					xhr.failurefn = setting.failurefn;
					xhr.callbackfn = setting.callbackfn;
					xhr.scope = setting.scope;
				},
				/**
				 * 请求成功回调
				 * @param {Object} result
				 * @param {Object} status
				 * @param {Object} xhr
				 */
				success: function(result, status, xhr) {
					//服务器返回响应，根据响应结果
					var scope = xhr.scope ? xhr.scope : window;
					var successfn = xhr.successfn;
					var failurefn = xhr.failurefn;
					if (result.status === "true") {
						//业务逻辑返回成功
						//执行成功处理回调
						try {
							successfn && successfn.call(scope, result.data);
						} catch (ex) {
							console.log(ex);
						}

					} else {
						//业务逻辑返回错误
						//打印错误信息
						console.group('失败提示');
						console.error('失败信息:' + result.errcode);
						console.groupEnd();

						try {
							//执行错误处理回调
							failurefn && failurefn.call(this, {
								errmsg: result.errcode
							});
						} catch (ex) {
							console.log(ex);
						}

					}
				},
				/**
				 * 请求错误回调
				 * @param {Object} xhr
				 * @param {Object} type
				 * @param {Object} errorThrown
				 */
				error: function(xhr, type, errorThrown) {
					//业务逻辑返回错误
					//打印错误信息
					console.group('错误提示');
					console.error('错误类型:' + type);
					console.error('错误内容:' + errorThrown);
					console.error(xhr);
					
					console.groupEnd();
				},
				/**
				 * 请求完成回调
				 * @param {Object} xhr
				 * @param {Object} status
				 */
				complete: function(xhr, status) {
					//服务器返回响应，根据响应结果
					var scope = xhr.scope ? xhr.scope : window;
					var callbackfn = xhr.callbackfn;

					try {
						//执行错误处理回调
						callbackfn && callbackfn.call(scope, status);
					} catch (ex) {
						console.log(ex);
					}
				}
			});

		}
	}
});