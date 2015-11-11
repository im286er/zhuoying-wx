define(function(require, exports, module) {
	/**引入其他模块文件**/
	var update = require("update");
	var common = require("common");
	var user = require("user");
	var message = require("message");
	/**
	 * 注意:common属于基础支持模块，其他模块都可能调用common，所以common只可以引入config下数据存储的非逻辑模块，避免耦合引用
	 */
	var getClientID = function() {
		var client_info = plus.push.getClientInfo();
		//存储客户端id
		if (client_info && client_info.clientid) {
			user.setUserData(user.clientid, client_info.clientid);
			console.log("AppID:" + client_info.appid);
		} else {
			setTimeout(getClientID, 100);
		}
	};

	module.exports = {
		init: function() {
			//系统更新检测
			update.checkUpdate();
			//用户位置检测
			common.getCurrentPosition();

			if (!plus.storage.getItem(user.clientid)) {
				getClientID();
			} else {
				var clientid = user.getUserData(user.clientid);
			}
			
			//监听app前后台切换
			common.listenAppChange();
			
			//初始化消息中心
			message.init();
			
			user.setUserData(user.userrole,"0");
		}
	};
});