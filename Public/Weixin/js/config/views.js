/**
 * 页面加载集中控制
 */
define(function(require, exports, module) {
		/**
		 * 定义模块内部方法
		 */
		module.exports = {
			activity: {
				//活动分类目录
				activityCatalog: {
					url: "views/activity/activityCatalog.html",
					id: "views/activity/activityCatalog",
					proload: true,
					checklogin:false
				},
				//活动详情
				activityDetail: {
					url: "../../views/activity/activityDetail.html",
					id: "views/activity/activityDetail",
					proload: false,
					checklogin:false,
					autoWaiting:true
				},
				//用户发起活动列表
				activityLaunch: {
					url: "../../views/activity/activityLaunch.html",
					id: "views/activity/activityLaunch",
					proload: false,
					checklogin:true,
					checkphone:true
				},
				//用户活动列表
				activityList: {
					url: "../../views/activity/activityList.html",
					id: "views/activity/activityList",
					proload: false,
					checklogin:false,
					autoWaiting:true
				},
				//用户发起活动列表
				activityUserHistory: {
					url: "../../views/activity/activityUserHistory.html",
					id: "views/activity/activityUserHistory",
					proload: false,
					checklogin:true
				},
				activityUserJoin: {
					url: "../../views/activity/activityUserJoin.html",
					id: "views/activity/activityUserJoin",
					proload: false,
					checklogin:true,
					autoWaiting:true
				},
				activityUserLaunch: {
					url: "../../views/activity/activityUserLaunch.html",
					id: "views/activity/activityUserLaunch",
					proload: false,
					checklogin:true,
					autoWaiting:true
				},
				activityMap: {
					url: "../../views/activity/activityMap.html",
					id: "views/activity/activityMap",
					proload: false,
					checklogin:true
				},
				activityUsers: {
					url: "../../views/activity/activityUsers.html",
					id: "views/activity/activityUsers",
					proload: false,
					checklogin:true
				},
				activityShare: {
					url: "../../views/activity/activityShare.html",
					id: "views/activity/activityShare",
					proload: false,
					checklogin:true
				},
				search: {
					url: "../../views/activity/search.html",
					id: "views/activity/search",
					proload: true,
					checklogin:false
				},
				signUp: {
					url: "../../views/activity/signUp.html",
					id: "views/activity/signUp",
					proload: true,
					checklogin:true,
					checkphone:true
				},
				signUpSuccess: {
					url: "../../views/activity/signUpSuccess.html",
					id: "views/activity/signUpSuccess",
					proload: false,
					checklogin:false
				}
			},
			app: {
				barcode: {
					url: "../../views/app/barcode.html",
					id: "views/activity/barcode",
					proload: false,
					checklogin:true
				},
				city: {
					url: "../../views/app/city.html",
					id: "views/app/city",
					proload: false,
					checklogin:false
				},
				edit: {
					url: "../../views/app/edit.html",
					id: "views/app/edit",
					proload: false,
					checklogin:false
				},
				guide: {
					url: "../../views/app/guide.html",
					id: "views/app/guide",
					proload: false,
					checklogin:false
				},
				menu: {
					url: "views/app/menu.html",
					id: "views/app/menu",
					proload: true,
					checklogin:false
				},
				qrcode: {
					url: "../../views/app/qrcode.html",
					id: "views/app/qrcode",
					proload: false,
					checklogin:true
				},
				terms: {
					url: "../../views/app/terms.html",
					id: "views/app/terms",
					proload: false,
					checklogin:false
				},
				feedback:{
					url: "../../views/app/feedback.html",
					id: "views/app/feedback",
					proload: false,
					checklogin:false
				},
				map:{
					url: "../../views/app/map.html",
					id: "views/app/map",
					proload: false,
					checklogin:false
				}
			},
			message: {
				messageCenter: {
					url: "../../views/message/messageCenter.html",
					id: "../../views/message/messageCenter",
					proload: false,
					checklogin:false
				},
				systemMsg: {
					url: "../../views/message/systemMsg.html",
					id: "views/message/systemMsg",
					proload: false,
					checklogin:false
				},
				userMsg: {
					url: "../../views/message/userMsg.html",
					id: "views/message/userMsg",
					proload: false,
					checklogin:true
				}
			},
			movie: {
				movieList: {
					url: "views/movie/movieList.html",
					id: "views/movie/movieList",
					proload: false,
					checklogin:false,
					autoWaiting:true
				},
				movieComment: {
					url: "../../views/movie/movieComment.html",
					id: "views/movie/movieComment",
					proload: false,
					checklogin:false
				},
				movieCenter: {
					url: "../../views/movie/movieCenter.html",
					id: "views/movie/movieCenter",
					proload: false,
					checklogin:false,
					autoWaiting:true
				},
				movieSearch:{
					url: "../../views/movie/movieSearch.html",
					id: "views/movie/movieSearch",
					proload: false,
					checklogin:false
				}
			},
			site: {
				chooseSite: {
					url: "../../views/site/chooseSite.html",
					id: "views/site/chooseSite",
					proload: false,
					checklogin:true
				},
				provideSite: {
					url: "../../views/site/provideSite.html",
					id: "views/site/provideSite",
					proload: false,
					checklogin:true,
					autoWaiting:true
				},
				siteCenter: {
					url: "../../views/site/siteCenter.html",
					id: "views/site/siteCenter",
					proload: false,
					checklogin:true,
					autoWaiting:true
				},
				siteDetail: {
					url: "../../views/site/siteDetail.html",
					id: "views/site/siteDetail",
					proload: false,
					checklogin:true,
					autoWaiting:true
				},
				siteList: {
					url: "../../views/site/siteList.html",
					id: "views/site/siteList",
					proload: false,
					checklogin:true,
					autoWaiting:true
				},
				siteMap: {
					url: "../../views/site/siteMap.html",
					id: "views/site/siteMap",
					proload: false,
					checklogin:true,
					autoWaiting:true
				},
				siteRecord: {
					url: "../../views/site/siteRecord.html",
					id: "views/site/siteRecord",
					proload: false,
					checklogin:true
				}
			},
			user: {
				editUser: {
					url: "../../views/user/editUser.html",
					id: "views/user/editUser",
					proload: false,
					checklogin:true
				},
				forgetPassword: {
					url: "../../views/user/forgetPasswd.html",
					id: "views/user/forgetPassword",
					proload: false,
					checklogin:false
				},
				login: {
					url: "../../views/user/login.html",
					id: "views/user/login",
					proload: true,
					checklogin:false,
					autoWaiting:false
				},
				modifyPasswd: {
					url: "../../views/user/modifyPasswd.html",
					id: "views/user/modifyPasswd",
					proload: false,
					checklogin:true
				},
				register: {
					url: "../../views/user/register.html",
					id: "views/user/register",
					proload: false,
					checklogin:false
				},
				userBind: {
					url: "../../views/user/userBind.html",
					id: "views/user/userBind",
					proload: false,
					checklogin:false
				},
				userInfo: {
					url: "../../views/user/userInfo.html",
					id: "views/user/userInfo",
					proload: false,
					checklogin:true
				},
				userNickname: {
					url: "../../views/user/userNickname.html",
					id: "views/user/userNickname",
					proload: false,
					checklogin:true
				}
			},
			wallet: {
				addCard: {
					url: "../../views/wallet/addCard.html",
					id: "views/wallet/addCard",
					proload: false,
					checklogin:true
				},
				income: {
					url: "../../views/wallet/income.html",
					id: "views/wallet/income",
					proload: false,
					checklogin:true
				},
				userCard: {
					url: "../../views/wallet/userCard.html",
					id: "views/wallet/userCard",
					proload: false,
					checklogin:true
				},
				walletCenter: {
					url: "../../views/wallet/walletCenter.html",
					id: "views/wallet/walletCenter",
					proload: false,
					checklogin:true,
					autoWaiting:true
				},
				withdrawal: {
					url: "../../views/wallet/withdrawal.html",
					id: "views/wallet/withdrawal",
					proload: false,
					checklogin:true
				},
				withdrawalRecord: {
					url: "../../views/wallet/withdrawalRecord.html",
					id: "views/wallet/withdrawalRecord",
					proload: false,
					checklogin:true
				}
			}
		}
	}

);