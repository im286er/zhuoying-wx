define(function(require, exports, module) {
	/**引入其他模块文件**/
	var net = require("../util/net");
	var server = require("../config/server");
	var navigate = require("../util/navigate");
	var views = require("../config/views")
		/**
		 * 获取相机照片
		 */
	var captureCamera = function(option) {
		var cmr = plus.camera.getCamera();
		cmr.captureImage(function(p) {
			plus.io.resolveLocalFileSystemURL(p, function(entry) {
				var localurl = entry.toLocalURL(); //把拍照的目录路径，变成本地url路径，例如file:///........之类的。
				getBase64(localurl, option);
			});
		}, function(e) {
			console.log("相机获取失败");
			option.failure && option.failure(e);
		});
	};

	/**
	 * 获取相册照片
	 */
	var capturePhoto = function(option) {
		plus.gallery.pick(function(e) {
			mui.each(e.files, function(index, item) {
				getBase64(item, option);
			});
		}, function(e) {
			console.log("取消选择图片");
			option.failure(e);
		}, {
			filter: "image",
			multiple: true
		});
	};

	/**
	 * 上传文件
	 * @param {Object} data
	 */
	var upload = function(data, option, now) {
		net.send({
			server: server.app.uploadImg,
			params: {
				file: data,
				timestamp: now
			},
			success: option.success,
			failure: option.failure
		})
	};

	/**
	 * 获取base64编码
	 * @param {Object} url
	 */
	var getBase64 = function(url, option) {
		// 兼容以“file:”开头的情况
		if (0 != url.toString().indexOf("file://")) {
			url = "file://" + url;
		}

		var img = new Image();
		img.src = url; // 传过来的图片路径在这里用。
		img.onload = function() {
			var that = this;
			//生成比例 
			var w = that.width,
				h = that.height,
				scale = w / h;

			//480  你想压缩到多大，改这里
			w = 480 || w;
			h = w / scale;

			//生成canvas
			var canvas = document.createElement('canvas');

			var ctx = canvas.getContext('2d');
			canvas.setAttribute("width", w);
			canvas.setAttribute("height", h);

			ctx.drawImage(that, 0, 0, w, h);

			var base64 = canvas.toDataURL('image/jpeg', 1 || 0.8); //1最清晰，越低越模糊。

			var now = mui.now();

			if (option.dataready) {
				option.dataready(base64, now);
			}

			var temp = base64.split(',');

			if (temp.length > 1) {
				upload(temp[1], option, now);
			} else {
				var msg = 'base64转换出错';
				option.failure(msg)
			}

		}
	}

	module.exports = {
		imageSource: {
			camera: 0,
			photo: 1
		},
		/**
		 * 获取相册或图片
		 * @param {Object} source
		 */
		captureImage: function(option) {
			if (!option) {
				console.log("参数错误");
				return;
			}
			if (option.source) {
				switch (source) {
					case 0:
						captureCamera(option);
						break;
					case 1:
						capturePhoto(option);
						break;
				}
			} else {
				plus.nativeUI.actionSheet({
					title: "选择照片",
					cancel: "取消",
					buttons: [{
						title: "拍照"
					}, {
						title: "相册"
					}]
				}, function(e) {
					switch (e.index) {
						case 1:
							captureCamera(option);
							break;
						case 2:
							capturePhoto(option);
							break;
						default:
							break;
					}
				});
			}
		},
		/**
		 * 扫描二维码
		 * @param {Object} success
		 * @param {Object} error
		 */
		scanBarCode: function(success, error) {
			navigate.redirectTo({
				view: views.app.barcode,
				reback: success
			});
		}
	}
});