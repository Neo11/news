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

const feedUrl = generateUrl("/apps/news/feeds")
const folderUrl = generateUrl("/apps/news/folders")

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
        unreadCount: 0
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
        }
    },
    actions: {
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
            console.log('loading folders')
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
            console.log(feedReq)
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
