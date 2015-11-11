define(function(require, exports, module) {
	/**引入其他模块文件**/
	var user = require("config/user");

	/**
	 * 注意:common属于基础支持模块，其他模块都可能调用common，所以common只可以引入config下数据存储的非逻辑模块，避免耦合引用
	 */
	module.exports = {
		/**
		 * 检查网络连接状态
		 */
		checkNet: function(returnType) {
			//验证是否手机登录
			var types = {};

			return true;
		},
		/**
		 * 将字符串转换为dom元素
		 * @param {Object} arg
		 */
		parseDom: function(arg) {
			var objE = document.createElement("div");
			objE.innerHTML = arg;
			var nodes_all = objE.childNodes
			var nodes_list = mui.map(nodes_all, function(item) {
				if (item.nodeName == "#text" && item.nodeValue && item.nodeValue.trim() == "") {
					return;
				} else {
					return item;
				}

			})
			return nodes_list;
		},
		/**
		 * 截取字符串
		 * @param {Object} str
		 * @param {Object} len
		 * @param {Object} sign
		 */
		truncate: function(str, len, sign) {
			var content = "";

			if (str.length < len) {
				content = str;
			} else {
				var context = str.substring(len);
				content += sign ? sign : "...";
			}

			return content;
		},
		/**
		 * 获取当前时间的时间戳(s为单位)
		 */
		timeTostamp: function() {
			var timestamp = Date.parse(new Date());
			timestamp = timestamp / 1000;
			return timestamp;
		},

		/**
		 * 将时间戳转为日期字符串，如2014年6月18日 上午10:33:24
		 * @param {Object} stampStr
		 */
		stampTotime: function(stampStr, format) {
			return dateFormat(stampStr, format);
		},
		dateFormat: function(stampStr, format) {
			var date = new Date();
			date.setTime(stampStr * 1000);

			var map = {
				"M": date.getMonth() + 1, //月份 
				"d": date.getDate(), //日 
				"h": date.getHours(), //小时 
				"m": date.getMinutes(), //分 
				"s": date.getSeconds(), //秒 
				"q": Math.floor((date.getMonth() + 3) / 3), //季度 
				"S": date.getMilliseconds() //毫秒 
			};
			format = format.replace(/([yMdhmsqS])+/g, function(all, t) {
				var v = map[t];
				if (v !== undefined) {
					if (all.length > 1) {
						v = '0' + v;
						v = v.substr(v.length - 2);
					}
					return v;
				} else if (t === 'y') {
					return (date.getFullYear() + '').substr(4 - all.length);
				}
				return all;
			});
			return format;
		},
		moneyFormat: function(number, places, symbol, thousand, decimal) {
			number = number || 0;
			places = !isNaN(places = Math.abs(places)) ? places : 2;
			symbol = symbol !== undefined ? symbol : "";
			thousand = thousand || ",";
			decimal = decimal || ".";
			var negative = number < 0 ? "-" : "",
				i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
				j = (j = i.length) > 3 ? j % 3 : 0;
			return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
		},
		/**
		 * 登录检测
		 */
		checkLogin: function() {
			var id = user.getUserData(user.userid);
			return (!!id);
		},
		checkPhone: function() {
			var phone = user.getUserData(user.userphone);
			return (!!phone);
		},
		/** 看是否绑定了手机**/
		isExistPhone: function() {
			var phonenumber = user.getUserData(user.userphone)

			if (!phonenumber) {
				var userBind = mui.preload({
					id: "userBind.html",
					url: "../../views/user/userBind.html"
				});

				userBind.show("slide-in-right", 150);

			}

			return (!!phonenumber);
		},
		/**
		 * 检查用户信息
		 */
		checkUserInfo: function() {
			var id = user.getUserData(user.userid);
			var phone = user.getUserData(user.userphone);
			var password = user.getUserData(user.password);

			return !!id && !!phone && !!password;
		},
		/**
		 * 获取当前位置
		 * @returns {}
		 */
		getCurrentPosition: function() {
			if (!this.checkNet()) {
				console.log("请检查网络状态.");
				return;
			}

			plus.geolocation.getCurrentPosition(function(p) {
				user.setUserData(user.country, p.address.country);
				user.setUserData(user.province, p.address.province);
				user.setUserData(user.city, p.address.city.substr(0, p.address.city.length - 1));
				user.setUserData(user.localcity, p.address.city.substr(0, p.address.city.length - 1));
				user.setUserData(user.longitude, p.coords.longitude.toString());
				user.setUserData(user.latitude, p.coords.latitude.toString());

				console.log("所在城市:" + p.address.city.substr(0, p.address.city.length - 1));
			}, function(e) {
				//手机定位失败改用网络定位
				mui.ajax(
					"http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js", {
						dataType: 'text',
						type: 'get', //HTTP请求类型
						timeout: 30000,
						success: function(data, status, xhr) {
							eval(data);

							var country = remote_ip_info.country;
							var province = remote_ip_info.province;
							var city = remote_ip_info.city;

							user.setUserData(user.country, country);
							user.setUserData(user.province, province);
							user.setUserData(user.city, city);
							user.setUserData(user.localcity, city);
							console.log("所在城市:" + city);
						},
						error: function(e) {
							console.log('位置获取失败');
							console.log(e);
						}
					});
			}, {
				provider: 'baidu',
				enableHighAccuracy: true,
				geocode: true
			});


		},
		/**
		 * 获取地图图标
		 * @param {Object} option
		 */
		getMapIcon: function(option) {
			var device = plus.os.name.toLowerCase();
			var map_icons = [];
			option = option || {};
			map_icons = new Array();
			map_icons["android"] = new Array();
			map_icons["ios"] = new Array();
			map_icons["android"][true] = new Array();
			map_icons["android"][false] = new Array();
			map_icons["ios"][true] = new Array();
			map_icons["ios"][false] = new Array();
			map_icons["android"][false][false] = "../../images/map/mark_uncheck_single_96.png";
			map_icons["android"][true][false] = "../../images/map/mark_check_single_96.png";
			map_icons["android"][false][true] = "../../images/map/mark_uncheck_many_96.png";
			map_icons["android"][true][true] = "../../images/map/mark_check_many_96.png";
			map_icons["ios"][false][false] = "../../images/map//mark_uncheck_single_32.png";
			map_icons["ios"][true][false] = "../../images/map//mark_check_single_32.png";
			map_icons["ios"][false][true] = "../../images/map//mark_uncheck_many_32.png";
			map_icons["ios"][true][true] = "../../images/map//mark_check_many_32.png";
			return map_icons[device][!!option.ischeck][!!option.hasmany];
		},
		/**
		 * 根据经纬度获取point
		 * @param {Object} point
		 */
		getPoint: function(point) {
			var longitude = parseFloat(point.longitude)
			var latitude = parseFloat(point.latitude)
			return new plus.maps.Point(longitude, latitude)
		},
		/**
		 * 监听前后台改变
		 */
		listenAppChange: function() {
			plus.storage.setItem("app_state", true)
			document.addEventListener("pause", function() {
				plus.storage.setItem("app_state", false)
			}, false);
			document.addEventListener("resume", function() {
				plus.storage.setItem("app_state", true)
			}, false);
		},
		getAppState: function() {
			return plus.storage.getItem("app_state")
		}
	}
});