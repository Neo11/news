import Vue from 'vue'
import App from './App'
import VueRouter from 'vue-router'
import Explore from './components/Explore'
import Content from './components/Content'
import {generateUrl} from "@nextcloud/router";
import Vuex from 'vuex'
import axios from "@nextcloud/axios";

Vue.prototype.t = t
Vue.prototype.n = n
Vue.prototype.OC = OC
Vue.prototype.OCA = OCA

Vue.use(Vuex)
Vue.use(VueRouter)

import { Tooltip } from '@nextcloud/vue'

Vue.directive('tooltip', Tooltip)

const defaultLanguageCode = 'en';
const supportedLanguageCodes = [
    'ar-ma', 'ar', 'bg', 'ca', 'cs', 'cv', 'da', 'de', 'el', 'en', 'en-ca',
    'en-gb', 'eo', 'es', 'et', 'eu', 'fi', 'fr-ca', 'fr', 'gl', 'he', 'hi',
    'hu', 'id', 'is', 'it', 'ja', 'ka', 'ko', 'lv', 'ms-my', 'nb', 'ne',
    'nl', 'pl', 'pt-br', 'pt', 'ro', 'ru', 'sk', 'sl', 'sv', 'th', 'tr',
    'tzm-la', 'tzm', 'uk', 'zh-cn', 'zh-tw'
];

function processLanguageCode(languageCode) {
    languageCode = languageCode.replace('_', '-').toLowerCase();

    if (supportedLanguageCodes.indexOf(languageCode) < 0) {
        languageCode = languageCode.split('-')[0];
    }

    if (supportedLanguageCodes.indexOf(languageCode) < 0) {
        languageCode = defaultLanguageCode;
    }

    return languageCode;
}

const feedUrl = generateUrl("/apps/news/feeds")
const folderUrl = generateUrl("/apps/news/folders")
const settingsUrl = generateUrl("/apps/news/settings")

const routes = [
    {
        name: 'explore',
        path: '#explore',
        component: Explore
    },
    {
        name: 'content',
        path: '#content',
        component: Content
    },
    {
        name: 'content_unread',
        path: '#content/unread',
        component: Content
    },
]

const router = new VueRouter({
    mode: 'history',
    base: generateUrl('apps/news'),
    routes
});

const store = new Vuex.Store({
    state: {
        folders: [],
        feeds: [],
        unreadCount: 0,
        settings: {
            language: 'en',
            showAll: false,
            compact: false,
            oldestFirst: false,
            preventReadOnScroll: false,
            compactExpand: false,
            exploreUrl: ''
        }
    },
    mutations: {
        addFolders(state, folders) {
            folders.forEach(it => {
                it.unreadCount = 0
                it.feeds = [];
                state.folders.push(it)
            })
        },
        deleteFolder(state, folder) {
            const index = state.folders.indexOf(folder)
            state.folders.splice(index, 1);
        },
        deleteFeed(state, feed) {
            const index = state.feeds.indexOf(feed)
            state.feeds.splice(index, 1);
        },
        addFeeds(state, feeds) {
            feeds.forEach(it => {
                state.feeds.push(it)
                const folder = state.folders.find(folder => folder.id === it.folderId)
                if (!!folder) {
                    folder.feeds.push(it)
                    folder.unreadCount += it.unreadCount
                } else {
                    it.folderId = null
                }
                state.unreadCount += it.unreadCount
            })
        },
        setOption(state, {key, value}) {
            state.settings[key] = value;
        }
    },
    actions: {
        loadOptions({commit}) {
            axios.get(settingsUrl).then(
                response => {
                    for (let property in response.data.settings) {
                        let value = response.data.settings[property];
                        if (property === 'language') {
                            value = processLanguageCode(value);
                        }
                        commit('setOption', {key: property, value});
                    }
                }
            );
        },
        updateOption({commit}, {key}) {
            commit('setOption', {key, value: this.state.settings[key]})

            axios.put(settingsUrl, {
                    language: this.state.settings.language,
                    showAll: this.state.settings.showAll,
                    compact: this.state.settings.compact,
                    oldestFirst: this.state.settings.oldestFirst,
                    compactExpand: this.state.settings.compactExpand,
                    preventReadOnScroll: this.state.settings.preventReadOnScroll
            }).then();
        },
        addFolder({commit}, {folder}) {
            axios.post(folderUrl, {folderName: folder.name}).then(
                response => commit('addFolders', response.data.folders)
            );
        },
        deleteFolder({commit}, {folder}) {
            /**
            this.getByFolderId(folderId).forEach(function (feed) {
                promises.push(self.reversiblyDelete(feed.id, false, true));
            });

            this.updateUnreadCache();
             */
            axios.delete(folderUrl + '/' + folder.id).then(
                response => commit('deleteFolder', folder)
            )
        },
        loadFolder({commit}) {
            axios.get(folderUrl).then(
                response => {
                    commit('addFolders', response.data.folders);
                    axios.get(feedUrl).then(
                        response => commit('addFeeds', response.data.feeds)
                    )
                }
            )
        },
        async addFeed({commit}, {feedReq}) {
            let url = feedReq.url.trim();
            if (!url.startsWith('http')) {
                url = 'https://' + url;
            }

            if (feedReq.createNewFolder) {
                const response = await axios.post(folderUrl, {folderName: feedReq.newFolderName});
                commit('addFolders', response.data.folders);
                feedReq.folder = response.data.folders[0];
            }

            /**
            if (title !== undefined) {
                title = title.trim();
            }
             */

            let feed = {
                url: url,
                folderId: feedReq.folder.id || 0,
                title: null,
                unreadCount: 0
            };

            axios.post(feedUrl, {
                url: feed.url,
                parentFolderId: feed.folderId,
                title: null,
                user: null,
                password: null,
                fullDiscover: feed.autoDiscover
            }).then(
                response => commit('addFeeds', response.data.feeds)
            );
        },
        deleteFeed({commit}, {feed}) {
            axios.delete(feedUrl + '/' + feed.id).then(
                response => commit('deleteFeed', feed)
            )
        }
    }
});

export default new Vue({
    router,
    store,
    el: '#content',
    render: h => h(App),
})
