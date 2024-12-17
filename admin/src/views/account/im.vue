<template>
	<PermissionWrapper>
		<button @click="handleSelectConversation">切换聊天</button>
		<div :id="isPC ? 'preloadedImages' : ''" :class="['home', isH5 && 'home-h5']">
			<div v-show="isMenuShow" class="home-menu">
				<Menu @closeMenu="toggleMenu(false)" />
			</div>
			<div :class="['home-container', isMenuShow && 'menu-expand']">
				<!-- <div v-if="isPC" class="home-header">
				<Header :class="[isMenuShow && 'header-menu-show']" showType="menu" :defaultLanguage="locale"
					@toggleMenu="toggleMenu(!isMenuShow)" @changeLanguage="changeLanguage" />
			</div> -->
				<div class="home-main">
					<div class="home-TUIKit">
						<div v-if="isPC || !currentConversationID" class="home-TUIKit-navbar">
							<NavBar v-model:currentNavBar="currentNavBar" v-model:isSettingShow="isSettingShow">
								<template #profile>
									<Profile display-type="profile" />
								</template>
								<template #setting>
									<Profile v-model:showSetting="isSettingShow" display-type="setting" />
								</template>
							</NavBar>
						</div>
						<div v-if="isPC" class="home-TUIKit-main">
							<div v-show="currentNavBar === 'message'" class="home-TUIKit-main">
								<div class="home-conversation">
									<TUISearch searchType="global" />
									<TUIConversation @selectConversation="handleSelectConversation" />
								</div>
								<div class="home-chat">
									<TUIChat>
										<ChatDefaultContent />
									</TUIChat>
									<TUIGroup class="chat-aside" />
									<TUISearch class="chat-aside" searchType="conversation" />
								</div>
								<TUIContact display-type="selectFriend" />
							</div>
							<div v-show="currentNavBar === 'relation'" class="home-TUIKit-main">
								<TUIContact display-type="contactList" @switchConversation="currentNavBar = 'message'" />
							</div>
						</div>
						<div v-else-if="isH5" class="home-TUIKit-main">
							<div v-if="!currentConversationID" class="home-TUIKit-main">
								<div v-show="currentNavBar === 'message'" class="home-TUIKit-main">
									<TUISearch searchType="global" />
									<TUIConversation @selectConversation="handleSelectConversation" />
									<TUIContact display-type="selectFriend" />
								</div>
								<div v-show="currentNavBar === 'relation'" class="home-TUIKit-main">
									<TUIContact display-type="contactList" @switchConversation="currentNavBar = 'message'" />
								</div>
								<div v-show="currentNavBar === 'profile'" class="home-TUIKit-main">
									<Profile display-type="all" />
								</div>
							</div>
							<TUIChat v-else />
							<TUIGroup class="chat-popup" />
							<TUISearch class="chat-popup" searchType="conversation" />
						</div>
						<Drag ref="dragRef" :show="isCalling" domClassName="callkit-drag-container">
							<!-- <TUICallKit :class="[
		'callkit-drag-container',
		`callkit-drag-container-${isMinimized ? 'mini' : isH5 ? 'h5' : 'pc'}`
	]" :allowedMinimized="true" :allowedFullScreen="false" :beforeCalling="beforeCalling" :afterCalling="afterCalling"
							:onMinimized="onMinimized" /> -->
						</Drag>
					</div>
				</div>
			</div>
		</div>
	</PermissionWrapper>

</template>


<script setup lang="ts" name="userSetting">
import { ref } from '../../TUIKit/adapter-vue';
import { TUIStore, StoreName, TUITranslateService, TUIConversationService } from '@tencentcloud/chat-uikit-engine';
// import { TUICallKit } from '@tencentcloud/call-uikit-vue';
import { TUIChat, TUIConversation, TUIContact, TUIGroup, TUISearch } from '../../TUIKit';
import NavBar from './components/NavBar.vue';
import Profile from './Profile.vue';
import ChatDefaultContent from './components/ChatDefaultContent.vue';
import Drag from '../../TUIKit/components/common/Drag';
import { isPC, isH5 } from '../../TUIKit/utils/env';
import { enableSampleTaskStatus } from '../../TUIKit/utils/enableSampleTaskStatus';
import { framework } from '../../TUIKit/adapter-vue';
import { showConfirm } from "@/utils/util";
import TUINotification from "./TUIKit/components/TUINotification/index";
import PermissionWrapper from '@/components/auth.vue';
import { TUILogin } from '@tencentcloud/tui-core';

const props = withDefaults(
	defineProps<{
		language: string;
	}>(),
	{
		language: 'zh',
	},
);
const emits = defineEmits(['changeLanguage', 'handleSwitchConversation']);
// 定义 emits 事件


const locale = ref<string>(props.language);
const isMenuShow = ref<boolean>(false);
const currentNavBar = ref<string>('message');
const currentConversationID = ref<string>('');
const isCalling = ref<boolean>(false);
const isMinimized = ref<boolean>(false);
const dragRef = ref<typeof Drag>();
const isSettingShow = ref<boolean>(false);
import useUserStore from '@/stores/modules/user';
const userStore = useUserStore();

// 从URL获取和监控currentConversationID
const route = useRoute();
const router = useRouter();
// watch(() => currentConversationID.value, (newConversationId) => {
// 	if (newConversationId) {
// 		alert(111)
// 		console.log("currentConversationID changed:", newConversationId);
// 		handleSelectChange(newConversationId);
// 		try {
// 			emits('handleSwitchConversation', newConversationId);
// 			console.log("emit handleSwitchConversation:", newConversationId);
// 		} catch (error) {
// 			console.error("emit error:", error);
// 		}
// 	}
// });

function changeLanguage(language: string) {
	emits('changeLanguage', language);
}

TUIStore.watch(StoreName.CONV, {
	currentConversationID: (id: string) => {
		currentConversationID.value = id;

	},
});
function toggleMenu(value: boolean) {
	isMenuShow.value = value;
}

function beforeCalling() {
	isCalling.value = true;
	isMinimized.value = false;
	enableSampleTaskStatus('call');
}

function afterCalling() {
	isCalling.value = false;
	isMinimized.value = false;
}

function onMinimized(oldMinimizedStatus: boolean, newMinimizedStatus: boolean) {
	isMinimized.value = newMinimizedStatus;
	dragRef?.value?.positionReset();
}
import { locales } from "./locales";
onMounted(() => {

	TUITranslateService.provideLanguages(locales);
	TUITranslateService.useI18n();
	TUITranslateService.changeLanguage("zh")
	handleSelectConversation()


})


function handleSelectConversation(conversationId: string) {
	conversationId = route.query.conversationId
	if (route.query.conversationId) {
		TUIConversationService.switchConversation(conversationId)
			.then(() => {
				console.log('Switched conversation successfully');
				console.log(route.query.conversationId);
			})
			.catch((error) => {
				console.error('Failed to switch conversation:', error);
			});
	}
	router.push({ query: { ...route.query, conversationId: conversationId } });
}
//获取项目名称
const handleSelectChange = (val) => {
	router.push({ query: { ...route.query, conversationId: val } });
}
</script>

<style lang="scss" scoped>
// :deep .TUIKit {
// 	display: flex;
// 	width: 95%;
// 	height: calc(100vh - 100px);
// 	overflow: hidden;
// 	text-align: left;
// 	border: 1px;
// 	background: var(--el-color-primary-light-9);

// 	.TUIKit-navbar {
// 		overflow: hidden;
// 	}
// }
:deep .tui-conversation-item .left {
	max-width: 36px;
}

:deep .home-TUIKit-navbar .navbar {
	height: 100%;
}

:deep .home .home-container .home-main .home-TUIKit {
	height: calc(100vh - 100px);
}

:deep .navbar .header .user-info .user-info-main {
	min-width: 200px;
}

@import "./styles/home";
</style>
