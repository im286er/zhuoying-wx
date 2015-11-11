define(function(require, exports, module) {
	/**
	 * 加载其他模块
	 */
	var common = require("util/common");
	var views = require("config/views");
	var navigate = require("util/navigate");
	var linq = require("../../js/linq");
	//消息监听列表
	var listenerList = [];
	module.exports = {
		/**
		 * 存储消息容器作用域
		 * @param {Object} scope
		 */
		init: function() {
			window.message = this;

			this.clickMsgDeal();
			this.receiveMsgDeal();
			console.log('消息中心初始化完成');
		},
		/**
		 * 点击消息处理
		 */
		clickMsgDeal: function() {
			plus.push.addEventListener("click", function(msg) {
				console.log("接收到点击消息:");
				console.log(msg);

				var data = mui.parseJSON(msg.payload);

				var message_types = {
					'0': views.message.messageCenter,
					'3': views.activity.activityDetail,
					'4': views.movie.movieCenter
				}

				var view_types = {
					'1': views.site.siteCenter,
					'2': views.activity.activityUserLaunch,
					'3': views.activity.activityUserJoin,
					'4': views.activity.activityUserLaunch,
					'5': views.activity.activityUserJoin,
					'6': views.movie.activityUserLaunch
				}

				if (message_types[data.t]) {
					var page = plus.webview.getWebviewById(view.id);

					if (page) {
						page.close();
					}

					navigate.redirectTo({
						view: message_types[data.t],
						extras: data.d
					})
				}

				if (data.t == "2") {
					var view = view_types[data.v]
					var page = plus.webview.getWebviewById(view.id);

					if (page) {
						page.show();
					} else {
						view.url = view.id + ".html";
						navigate.redirectTo({
							view: view
						});
					}
				}
			}, false);
		},
		/**
		 * 接收消息处理
		 */
		receiveMsgDeal: function() {
			// 监听在线消息事件
			plus.push.addEventListener("receive", function(msg) {
				console.log("接收到推送消息:");
				console.log(msg);

				var data = mui.parseJSON(msg.payload);

				//非用户通讯消息
				if (data.t != 1) {
					var options = {
						cover: false,
						title: msg.title
					};

					plus.push.createMessage(data.d.content, data, options);
					return;
				}

				console.log("处理用户消息");
				console.log(data);
				//处理用户消息
				message.dealMessage(data);

				navigate.update({
					view: views.message.messageCenter
				})

			}, false);
		},
		/**
		 * 消息集中处理
		 */
		dealMessage: function(data) {
			mui.each(listenerList, function(index, item) {
				var webview = plus.webview.getWebviewById(item.id)
				console.log("检测到监听");
				console.log(item);
				if (item.type == data.t) {
					if ((item.uid && item.uid == data.d.uid) || !item.uid) {
						mui.fire(webview, "message", {
							data: data.d
						})
					}
				}
			});
		},
		/**
		 * 添加消息监听处理
		 * @param {Object} event
		 */
		addListenerHandler: function(option) {
			window.addEventListener("message", option.event);
			var index = plus.webview.getLaunchWebview();
			var id = plus.webview.currentWebview().id;
			index.evalJS("message.addListenerItem('" + id + "','" + option.type + "','" + option.uid + "')");
			//退出页面取消监听
			navigate.beforeBack(function() {
				index.evalJS('message.removeListenerItem("'+id+'");'); 
			})
		},
		/**
		 * 移除消息监听
		 */
		removeListenerHandler: function() {
			index.evalJS("message.removeListenerItem('" + id + "');");
		},
		/**
		 * 添加消息监听对象
		 * @param {Object} id
		 */
		addListenerItem: function(id, type, uid) {
			listenerList.push({
				id: id,
				type: type,
				uid: uid
			});
		},
		/**
		 * 添加消息监听对象
		 * @param {Object} id
		 */
		removeListenerItem: function(id) {
			listenerList = linq.From(listenerList).SkipWhile("c=>c.id=='" + id + "'").ToArray();
		},
		/**
		 * 创建用户接收的本地消息
		 * @param {Object} title
		 * @param {Object} content
		 * @param {Object} data
		 */
		createLocalPushMsg: function(title, content, data) {
			var options = {
				cover: false,
				title: title
			};

			plus.push.createMessage(content, data, options);
		}

	}
});