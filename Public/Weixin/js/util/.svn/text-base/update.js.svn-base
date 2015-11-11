define(function(require, exports, module) {
	/**
	 * 加载其他模块
	 */
	var server = require('server'),
		net = require('net'),
		me = module.exports;
	/**
	 * 定义模块内部方法
	 */
	module.exports = {
		/**
		 * 更新检查
		 */
		checkUpdate: function() {
			// 获取本地应用资源版本号
			plus.runtime.getProperty(plus.runtime.appid, function(inf) {
				if(inf.appid=="HBuilder"){
					console.log("开发版本已经关闭自动更新");
					return;
				}
				var version = inf.version;
				var me = module.exports;
				var device = plus.os.name.toLowerCase();
				net.send({
					server: server.app.checkUpdate,
					params: {
						device: device
					},
					success: function(data) {
						var temp1 = data.version.split('.');
						var temp2 = version.split('.');
						var flag = false;
						for (var i = 0; i <= 2; i++) {
							var v1 = parseInt(temp1[i]);
							var v2 = parseInt(temp2[i]);
							if (v1 > v2) {
								flag = true;
								break;
							}
						}

						if (flag) {
							me.downUpdate(data.url);
						} else {
							//不需要更新
							console.log('已经是最新版本。');
						}
					}
				})
			});
		},
		/**
		 * 下载更新
		 * @param {Object} url
		 */
		downUpdate: function(url) {
			plus.nativeUI.showWaiting("应用更新...");
			var download = plus.downloader.createDownload(url, {
				filename: "_doc/update/"
			}, function(d, status) {
				if (status == 200) {
					console.log("下载wgt成功：" + d.filename);
					module.exports.installUpdate(d.filename); // 安装wgt包
				} else {
					console.log("下载wgt失败!");
					plus.nativeUI.alert("更新失败!");
				}
				plus.nativeUI.closeWaiting();
			});

			download.start();
		},
		/**
		 * 安装更新
		 */
		installUpdate: function(filename) {
			plus.nativeUI.showWaiting("安装安装更新...");
			plus.runtime.install(filename, {}, function() {
				plus.nativeUI.closeWaiting();
				console.log("安装wgt文件成功！");
				plus.nativeUI.alert("应用资源更新完成！", function() {
					plus.runtime.restart();
				});
			}, function(e) {
				plus.nativeUI.closeWaiting();
				console.log("安装wgt文件失败[" + e.code + "]：" + e.message);
				plus.nativeUI.alert("安装更新文件失败[" + e.code + "]：" + e.message);
			});
		}
	}
});