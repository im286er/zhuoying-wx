define(function(require, exports, module) {
	/**引入其他模块文件**/
	var net = require("../util/net");
	var server = require("../config/server");
	var alipayurl = 'http://comerphptest.sinaapp.com/app/pay/payorder?total=';
	var wxurl = 'http://comerphp.sinaapp.com/weixin/index.php';
	var channel;
	var payment_success;
	var payment_error;
	/** 给服务器发送请求，获取经过加密的支付字符串信息**/
	var getPaystring = function(params, success, error) {
		net.send({
			server: server.pay.payorder,
			params: params,
			success: success,
			failure: error
		})
	}

	var paySuccess = function(data) {
		console.log(data.errmsg);
		//判断是否安装支付通道依赖的服务
		if (!channel.serviceReady) {
			channel.installService();
		}
		plus.payment.request(channel, data, function(result) {
			if (payment_success) {
				payment_success('支付成功！');
			}
			payment_success = null;
		}, function(error) {
			if (payment_error) {
				if (error.code == "62001") {
					payment_error('取消支付！');
				} else {
					payment_error('支付失败！');
				}

			}

			payment_error = null;
		});
	}

	var payError = function(data) {
		if (payment_error) {
			payment_error(data.errmsg);
		}
		payment_error = null;
	}

	/**
	 * 对外公开模块
	 */
	module.exports = {

		payment: function(params, paymentsuccess, paymenterror) {
			plus.payment.getChannels(function(channels) {
				for (var i in channels) {
					if (channels[i].id == params.paymethod) {
						channel = channels[i];
					}
				}
				//保存定义的回调方法
				payment_success = paymentsuccess;
				payment_error = paymenterror;
				getPaystring(params, paySuccess, payError);

			}, function(e) {
				console.log("获取支付通道失败：" + e.message);
			});
		}
	}
});