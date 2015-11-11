define({
	/**
	 * 系统模块
	 */
	app: {
		checkUpdate: {
			controller: 'app',
			action: 'update'
		},
		uploadImg: {
			controller: 'app',
			action: 'uploadImage'
		},
		feedback: {
			controller: 'app',
			action: 'feedback'
		},
	},
	/**
	 * 电影模块
	 */
	movie: {
		getMovieList: {
			controller: 'movie',
			action: 'getmovielist'
		},
		getMovieDetail: {
			controller: 'movie',
			action: 'get_info'
		},
		getComment: {
			controller: 'comment',
			action: 'get_list'
		},
		addComment: {
			controller: 'comment',
			action: 'add'
		}
	},
	/**
	 * 活动模块
	 */
	activity: {
		//发起活动
		launch: {
			controller: 'activity',
			action: 'launch'
		},
		//发起活动
		join: {
			controller: 'activity',
			action: 'join'
		},
		//获得活动详情
		activitydetail: {
			controller: 'activity',
			action: 'activitydetail'
		},
		getSubjectList: {
			controller: 'subject',
			action: 'get_list'
		},
		get_list_by_city: {
			controller: 'activity',
			action: 'get_list_by_city'
		},
		get_list_by_weixin: {
			controller: 'activity',
			action: 'get_list_by_weixin'
		},
		//获取活动场地
		getsite: {
			controller: 'activity',
			action: 'getsite'
		},
		get_list_applied: {
			controller: 'activity',
			action: 'get_list_applied'
		},
		get_list_applying: {
			controller: 'activity',
			action: 'get_list_applying'
		},
		get_list_history: {
			controller: 'activity',
			action: 'get_list_history'
		},
		getsitedetail: {
			controller: 'activity',
			action: 'getsitedetail'
		},
		getactivitylist: {
			controller: 'activity',
			action: 'getactivitylist'
		},
		addsite: {
			controller: 'activity',
			action: 'addsite'
		},
		editSite: {
			controller: 'activity',
			action: 'editsite'
		},
		get_list_my_host_progress: {
			controller: 'activity',
			action: 'get_list_my_host_progress'
		},
		get_list_my_host_complete: {
			controller: 'activity',
			action: 'get_list_my_host_complete'
		},
		get_list_my_join_progress: {
			controller: 'activity',
			action: 'get_list_my_join_progress'
		},
		get_list_my_join_complete: {
			controller: 'activity',
			action: 'get_list_my_join_complete'
		},
		getmyhost: {
			controller: 'activity',
			action: 'getmyhost'
		},
		getmyjoin: {
			controller: 'activity',
			action: 'getmyjoin'
		},
		getactivitybymovie: {
			controller: 'activity',
			action: 'get_list_by_movie'
		},
		getactivitybysubject: {
			controller: 'activity',
			action: 'get_list_by_subject'
		},
		site_apply_consent: {
			controller: 'activity',
			action: 'site_apply_consent'
		},
		site_apply_refusal: {
			controller: 'activity',
			action: 'site_apply_refusal'
		},
		get_list_by_movie_title: {
			controller: 'activity',
			action: 'get_list_by_movie_title'
		},
		checkin: {
			controller: 'activity',
			action: 'checkin'
		},
		exists_my_progress_activity:{
			controller: 'activity',
			action: 'exists_my_progress_activity'
		}
	},
	/**
	 * 心愿模块
	 */
	wish: {
		getwishlist: {
			controller: 'wish',
			action: 'get_list'
		},
		addwish: {
			controller: 'wish',
			action: 'addwish'
		}
	},
	/**
	 * 用户模块
	 */
	user: {
		getUserWish: {
			controller: 'wish',
			action: 'getmywishlist'
		},
		login: {
			controller: 'user',
			action: 'login'
		},
		loginBythird: {
			controller: 'user',
			action: 'loginBythird'
		},
		forgetPass: {
			controller: 'user',
			action: 'modifyPass'
		},
		forgetCode: {
			controller: 'user',
			action: 'getvertifycode'
		},
		register: {
			controller: 'user',
			action: 'register'
		},
		registerCode: {
			controller: 'user',
			action: 'sendvertifycode'
		},
		existPhone: {
			controller: 'user',
			action: 'existPhone'
		},
		editUser: {
			controller: 'user',
			action: 'editUser'
		},
		userBind: {
			controller: 'user',
			action: 'userBind'
		},
		modifyUser: {
			controller: 'user',
			action: 'modifyUser'
		},
		modifySign: {
			controller: 'user',
			action: 'modifySign'
		},

	},
	//资金账户模块
	account: {
		statement: {
			controller: 'account',
			action: 'statement'
		},
		getBank: {
			controller: 'account',
			action: 'getBank'
		},
		purse: {
			controller: 'account',
			action: 'purse'
		},
		banklist: {
			controller: 'account',
			action: 'bank_list'
		},
		addbank: {
			controller: 'account',
			action: 'add_bank'
		},
		cash_list: {
			controller: 'account',
			action: 'cash_list'
		},
		delete_bank: {
			controller: 'account',
			action: 'delete_bank'
		},
		cash: {
			controller: 'account',
			action: 'cash'
		}
	},
	message: {
		getSysmsg: {
			controller: 'message',
			action: 'getSysmsg'
		},
		getUsermsg: {
			controller: 'message',
			action: 'getUsermsg'
		},
		gethistorymsg: {
			controller: 'message',
			action: 'gethistorymsg'
		},
		sendmsg: {
			controller: 'message',
			action: 'send_message'
		}
	},
	/** 支付模块**/
	pay: {
		payorder: {
			controller: 'app',
			action: 'payment'
		}
	}
});