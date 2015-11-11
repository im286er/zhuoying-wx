define({
	//测试获取变量
	aa: 'xinlai aa',
	//测试传入参数，并使用参数
	test1: function(aa) {
		console.log(aa);
	},
	//测试传入参数，根据参数发送ajax请求或判断，并执行回调方法
	test2 : function(loginInfo, callback){
		console.log(loginInfo.name);
		if(loginInfo.name == "xinlai"){
			return callback("ok");
		}else{
			return callback("false");
		}
	}
	
});