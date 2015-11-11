define(function(require, exports, module){
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
		/**
		 * 给服务器发送ajax请求，修改密码
		 * @param {Object} regInfo
		 * @param {Object} callback
		 */
		modifyPass : function (params,success,error) {
			console.log(params);
			net.send({
				server: server.user.forgetPass,
				params: params,
				success: success,
				failure:error  
			})
		}
		
	}
	
});
