import { defineStore } from "pinia";
import cachev2 from "@/utils/cachev2";
import cache from "@/utils/cache";
import type { RouteRecordRaw } from "vue-router";
import { getUserInfo, login, register, logout } from "@/api/user";
import router, { filterAsyncRoutes } from "@/router";
import { TOKEN_KEY } from "@/enums/cacheEnums";
import { PageEnum } from "@/enums/pageEnum";
import { clearAuthInfo, getToken } from "@/utils/auth";

const USER_INFO_KEY = "user_info_cache";

export interface UserState {
	token : string;
	userInfo : Record<string, any>;
	routes : RouteRecordRaw[];
	perms : string[];
	getSignStatus : number;
}

const useUserStore = defineStore({
	id: "user",
	state: () : UserState => ({
		token: getToken() || "",
		userInfo: {},
		routes: [],
		perms: [],
		getSignStatus: 0 as number,
	}),
	getters: {},
	actions: {
		getSignStatus() {

			if (this.userInfo.role_id.includes(1)) {
				return (
					this.userInfo.enterpriseVerification != null &&
					this.userInfo.enterpriseVerification?.realname_status
				);
			}else{
				return 6;
			}

		},
		resetState() {
			this.token = "";
			this.userInfo = {};
			this.perms = [];
		},
		login(payload : any) {
			const { account, password } = payload;
			return new Promise((resolve, reject) => {
				login({
					account: account.trim(),
					password: password,
				})
					.then((data) => {
						this.token = data.token;
						cache.set(TOKEN_KEY, data.token);
						resolve(data);
					})
					.catch((error) => {
						reject(error);
					});
			});
		},
		register(payload : any) {
			const { account, password, password_confirm, code, role_id, invitecode } = payload;
			return new Promise((resolve, reject) => {
				register({
					account: account.trim(),
					password: password,
					password_confirm: password_confirm,
					code: code,
					role_id: role_id,
					invitecode: invitecode,
				})
					.then((data) => {
						this.token = data.token;
						cache.set(TOKEN_KEY, data.token);
						resolve(data);
					})
					.catch((error) => {
						reject(error);
					});
			});
		},
		logout() {
			return new Promise((resolve, reject) => {
				logout()
					.then(async (data) => {
						this.token = "";
						await router.push(PageEnum.LOGIN);
						clearAuthInfo();
						resolve(data);
					})
					.catch((error) => {
						reject(error);
					});
			});
		},
		getUserInfo() {
			return new Promise((resolve, reject) => {
				const cachedUserInfo = cachev2.get(USER_INFO_KEY);
				if (cachedUserInfo) {
					this.userInfo = cachedUserInfo.user;
					this.perms = cachedUserInfo.permissions;
					this.routes = filterAsyncRoutes(cachedUserInfo.menu);
					resolve(cachedUserInfo);
				} else {
					getUserInfo()
						.then((data) => {
							this.userInfo = data.user;
							this.perms = data.permissions;
							this.routes = filterAsyncRoutes(data.menu);
							cachev2.set(USER_INFO_KEY, data, 300000); // 缓存 5 分钟
							resolve(data);
						})
						.catch((error) => {
							reject(error);
						});
				}
			});
		},
		updateUserInfo() {
			return new Promise((resolve, reject) => {
				getUserInfo()
					.then((data) => {
						this.userInfo = data.user;
						this.perms = data.permissions;
						this.routes = filterAsyncRoutes(data.menu);
						cachev2.set(USER_INFO_KEY, data, 300000); // 缓存 5 分钟
						resolve(data);
					})
					.catch((error) => {
						reject(error);
					});
			});
		},
	},
});

export default useUserStore;