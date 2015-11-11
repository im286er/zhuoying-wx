define(function(require, exports, module) {
	//	var views = require("config/views");
	var common = require("util/common");
	var views = require("config/views");

	module.exports = {
		/**
		 * 页面跳转
		 * @param {Object} option
		 * ｛
		 * 		view：...,
		 * 		binds:[
		 * 				event:...,
		 * 				extras:...
		 * 		],
		 * 		style:...,
		 * 		extras:...,
		 * 		autoClose:...,
		 *		beforeOpen:...,
		 * 		autoWaiting:true
		 * 	}
		 */
		redirectTo: function(option) {
			var default_option = {
				autoClose: false,
				autoWaiting: false
			}
			
			if (!option.view) {
				console.log("创建页面参数错误.");
				return;
			}

			//准备打开的页面
			var view = option.view;

			option = mui.extend(default_option, {
				autoWaiting: view.autoWaiting
			}, option);

			//页面登录检测
			if (view.checklogin) {
				var loginflag = common.checkLogin();
				if (!loginflag) {
					this.redirectTo({
						view: views.user.login,
						beforeOpen: option.beforeOpen
					})
					return;
				}
			}

			//页面登录检测
			if (view.checkphone) {
				var phoneflag = common.checkPhone();
				if (!phoneflag) {
					this.redirectTo({
						view: views.user.userBind,
						beforeOpen: option.beforeOpen
					})
					return;
				}
			}

			//页面打开前触发事件
			if (option.beforeOpen) {
				var result = option.beforeOpen();
				//是否终止打开新页面
				if (result === false) {
					return;
				}
			}

			if (option.reback) {
				window.addEventListener("page_reback", option.reback)
			}
			

			setTimeout(function() {
				//页面显示事件
				window.location.href = view.url;
			}, 0);


			//页面检测自动关闭
			if (option.autoClose) {
				setTimeout(function() {
					
				}, 3000);
			}
		},
		back: function(data) {
			var page = plus.webview.currentWebview().opener();
			mui.fire(page, "page_reback", data);
			mui.back();
		},
		backTo: function(option) {},
		/**
		 * 回到启动页面
		 */
		backToLaunch: function(option) {
			var index = plus.webview.getLaunchWebview();
			var current = plus.webview.currentWebview();

			if (option.autoClose) {
				//关闭原先界面
				setTimeout(function() {
					if (current.preload) {
						current.hide();
					} else {
						current.close();
					}
				}, 200);
			}

			if (option.beforeClose) {
				option.beforeClose();
			}

			//			if(option.reset){
			//				index.eval("fun.activeCatalog()");
			//			}

			index.show("slide-in-right", 150);

		},
		close: function() {

		},
		beforeBack: function(handle) {
			mui.options.beforeback = handle;
		},
		/**
		 * 监听页面刷新
		 * @param {Object} handle
		 */
		whenUpdate: function(handle) {
			window.addEventListener("update", handle);
		},
		/**
		 * 主动刷新页面
		 * @param {Object} options
		 */
		update: function(option) {
			//方案1
			//			if (!view) {
			//				console.log("传入参数错误.");
			//				return;
			//			}
			//
			//			var page = plus.webview.getWebviewById(view.id);
			//
			//			if (page) {
			//				mui.fire(page, "update", extra);
			//			}
			//采用方案2
			if (!option.view) {
				console.log('参数传递错误');
				return;
			}

			var page = plus.webview.getWebviewById(option.view.id);

			if (page) {
				mui.fire(page, "openpage", option.extras);

				//默认滚动顶部
				if (option.autoScroll !== false) {
					page.evalJS("mui.scrollTo(0,1000)");
				}
			}
		},
		/**
		 * 页面打开事件
		 * @param {Object} handle
		 */
		pageReady: function(handle) {
			window.addEventListener("openpage", handle);
			var page = plus.webview.currentWebview().opener();
			mui.fire(page, "listener_ready");
		},
		/**
		 * 进行页面预加载
		 * @param {Object} option
		 */
		proload: function(option) {
			var view = option.view;
			page = mui.preload({
				id: view.id,
				url: view.url,
				styles: option.styles,
				extras: option.extras
			});
		},
		showWaiting: function(content) {
			var waiting = plus.nativeUI.showWaiting(content, {
				width: "100px",
				height: "100px",
				padding: "1%",
				modal: true
			});

			setTimeout(function() {
				if (!waiting.hasclose) {
					waiting.close();
					plus.nativeUI.toast("哎~加载失败了,心好累...");
				}
			}, 10000)

			waiting.onclose = function() {
				waiting.hasclose = true
			};
		}
	}
});