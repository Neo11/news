<template>
    <div>
        <EmptyContent icon="icon-empty" v-if="items.length === 0">
            <span v-if="isShowAll">{{ t('news', 'No articles available') }}</span>
            <span v-if="!isShowAll">{{ t('news', 'No unread articles available') }}</span>
        </EmptyContent>

        <div id="articles"
             :class="{ compact: settings.compact, 'feed-view': isFeed }"
             class="app-content-detail">
            <ul>
                <li class="item"
                    :class="[getFeed(item.feedId).cssClass, {read: !item.unread, open: item.show, active: isItemActive(item.id)}]"
                    v-for="item in items" :key="item.id"
                    @click.self="markRead(item);toggleItem(item)"
                    @mouseup="markRead(item)">
                    <div class="utils">
                        <ul>
                            <li class="util-spacer"></li>
                            <li class="util only-in-compact">
                                <a class="external icon-link"
                                   @click.self="markRead(item)"
                                   target="_blank"
                                   rel="noreferrer"
                                   :href="item.url"
                                   :title="t('news','Open website')">
                                </a>
                            </li>
                            <li class="title only-in-compact"
                                @click="markRead(item);toggleItem(item)"
                                :class="{ 'icon-rss': !getFeed(item.feedId).faviconLink }"
                                :style="{ backgroundImage: 'url(' + getFeed(item.feedId).faviconLink + ')' }">
                                <h1 ng-attr-dir="item.rtl && 'rtl'"><a>{{ item.title }} <span
                                    class="intro" v-html="item.intro"></span></a></h1>
                            </li>
                            <li class="only-in-compact">
                                <time class="date">
                                    {{ item.pubDate * 1000 | formatDate(settings.language) }}
                                </time>
                            </li>
                            <li @click="toggleStar(item.id)"
                                class="util">
                                <button class="icon-star"
                                        v-if="!item.starred"
                                        :title="t('news','Star article')">
                                </button>
                                <button class="icon-starred"
                                        v-if="item.starred"
                                        :title="t('news','Unstar article')">
                                </button>
                            </li>
                            <li @click="toggleKeepUnread(item)"
                                class="util toggle-keep-unread">
                                <button class="icon-toggle"
                                        v-if="!item.keepUnread"
                                        :title="t('news','Keep article unread')">
                                </button>
                                <button class="icon-toggle keep-unread"
                                        v-if="item.keepUnread"
                                        :title="t('news','Remove keep article unread')">
                                </button>
                            </li>

                            <li class="util">
                                <Actions :forceMenu="true" default-icon="icon-share" >
                                    <ActionInput icon="icon-user"
                                                 type="multiselect"
                                                 :options="formattedOptions"
                                                 label="displayName" track-by="user"
                                                 :user-select="true"
                                                 style="width: 250px">
                                        <template #singleLabel="{ option }">
                                            <ListItemIcon v-bind="option" :title="option.displayName" :avatar-size="24" :no-margin="true" />
                                        </template>
                                    </ActionInput>
                                </Actions>
                            </li>


                            <li class="util more" v-if="false">
                                <button class="icon-more"
                                        news-toggle-show="#actions-item.id"></button>
                                <div class="article-actions" id="actions-item.id">
                                    <ul news-article-actions="item" no-plugins="noPlugins"></ul>
                                </div>
                            </li>
                        </ul>
                        <!-- TODO integrate logik in new actions modal -->
                        <!--                    <template click-outside="Content.hide()" news-stop-propagation>-->
                        <!--                        <div-->
                        <!--                            ng-controller="ShareController as Share"-->
                        <!--                            class="dropdown-content"-->
                        <!--                            news-stop-propagation>-->
                        <!--                            &lt;!&ndash; Share with users &ndash;&gt;-->
                        <!--                            <p class="label-group">{{t('news','Share with users')}}</p>-->
                        <!--                            <form ng-submit=""-->
                        <!--                                  name="contactForm"-->
                        <!--                                  autocomplete="off">-->
                        <!--                                <fieldset class="contact-input">-->
                        <!--                                    <input-->
                        <!--                                        ng-model="nameQuery"-->
                        <!--                                        ng-model-options="{debounce: 400}"-->
                        <!--                                        ng-change="Share.searchUsers(nameQuery)"-->
                        <!--                                        type="text"-->
                        <!--                                        :placeholder="t('news','Username')"-->
                        <!--                                        :title="t('news','Username')"-->
                        <!--                                        name="contactName"-->
                        <!--                                        required-->
                        <!--                                        style="width: 200px">-->
                        <!--                                    <div ng-if="App.loading.isLoading('user')"-->
                        <!--                                         ng-class="{'icon-loading-small': App.loading.isLoading('user') }">-->
                        <!--                                    </div>-->
                        <!--                                </fieldset>-->
                        <!--                            </form>-->

                        <!--                            <div class="dropdown-search-message"-->
                        <!--                                 ng-if="Share.userList.length === 0 && nameQuery && !App.loading.isLoading('user') && !Share.searchUsersFailed">-->
                        <!--                                {{t('news','No users found')}}-->
                        <!--                            </div>-->
                        <!--                            <div class="dropdown-search-message"-->
                        <!--                                 ng-if="Share.userList.length === 0 && Share.searchUsersFailed">-->
                        <!--                                {{t('news','Error while searching for users')}}-->
                        <!--                            </div>-->
                        <!--                            <a-->
                        <!--                                ng-repeat="user in Share.userList"-->
                        <!--                                class="icon-category-installed pr-3"-->
                        <!--                                ng-click="Share.shareItem(item.id, user.value.shareWith)">-->
                        <!--                                {{ user.label }}-->
                        <!--                                <span class="share-status-icon"-->
                        <!--                                      ng-class="{'icon-loading-small': Share.isLoading(user.value.shareWith), 'icon-checkmark': Share.isStatus(item.id, user.value.shareWith, true), 'icon-close': Share.isStatus(item.id, user.value.shareWith, false)}">-->
                        <!--                            </span>-->
                        <!--                            </a>-->

                        <!--                            <div ng-if="Share.isAnySocialAppEnabled()">-->
                        <!--                                <p class="label-group"> {{t('news','Share on social media')}} </p>-->
                        <!--                                <div class="row">-->
                        <!--                                    <div ng-if="Share.isSocialAppEnabled('facebook')" class="col-4">-->
                        <!--                                        <a target="_blank"-->
                        <!--                                           class="icon-dropdown icon-facebook pr-5"-->
                        <!--                                           ng-href="Share.getFacebookUrl(item.url)"></a>-->
                        <!--                                    </div>-->
                        <!--                                    <div ng-if="Share.isSocialAppEnabled('twitter')" class="col-4">-->
                        <!--                                        <a target="_blank"-->
                        <!--                                           class="icon-dropdown icon-twitter pr-5"-->
                        <!--                                           ng-href="Share.getTwitterUrl(item.url)"></a>-->
                        <!--                                    </div>-->
                        <!--                                    <div ng-if="Share.isSocialAppEnabled('email')" class="col-4">-->
                        <!--                                        <a class="icon-dropdown icon-mail pr-5"-->
                        <!--                                           ng-href="Share.getEmailUrl(item.url, 't('news','I wanted you to see this article')', 't('news','Check out this article')')"></a>-->
                        <!--                                    </div>-->
                        <!--                                </div>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <!--                    </template>-->
                        <!-- End share dropdown -->
                    </div>

                    <div class="article" v-if="!settings.compact || item.show">

                        <div class="heading only-in-expanded">

<!--                            TODO title and date attributs -->
                            <time class="date">
                                {{ item.pubDate * 1000 | formatDate(settings.language) }}
                            </time>

                            <h1 ng-attr-dir="item.rtl && 'rtl'">
                                <a class="external"
                                   target="_blank"
                                   rel="noreferrer"
                                   :href="item.url"
                                   title="item.title">
                                    {{ item.title }}
                                </a>
                            </h1>
                        </div>

                        <div class="subtitle" ng-attr-dir="item.rtl && 'rtl'">
                    <span class="author" v-if="item.author">
                        {{ t('news', 'by') }} {{ item.author }}
                    </span>
                            <span v-if="!item.sharedBy" class="source">{{ t('news', 'from') }}
                            <a href="#/items/feeds/item.feedId/">
                                {{ getFeed(item.feedId).title }}
                                <img v-if="getFeed(item.feedId).faviconLink && !settings.compact" :src="getFeed(item.feedId).faviconLink" alt="favicon">
                            </a>
                    </span>
                            <span v-if="item.sharedBy">
                        <span v-if="item.author">-</span>
                            {{ t('news', 'shared by') }}
                        {{ item.sharedByDisplayName }}
                    </span>
                        </div>

                        <div class="enclosure" v-if="getMediaType(item.enclosureMime) === 'audio'">
                            <button @click="play(item)">{{ t('news', 'Play audio') }}</button>
                            <a class="button" :href="item.enclosureLink" target="_blank"
                               rel="noreferrer">
                                {{ t('news', 'Download audio') }}
                            </a>
                        </div>
                        <div class="enclosure" v-if="getMediaType(item.enclosureMime) === 'video'">
                            <video controls preload="none" news-play-one :src="item.enclosureLink"
                                   :typeof="item.enclosureMime">
                            </video>
                            <a class="button" :href="item.enclosureLink" target="_blank"
                               rel="noreferrer">
                                {{ t('news', 'Download video') }}
                            </a>
                        </div>

                        <div class="enclosure thumbnail" v-if="item.mediaThumbnail">
                            <a :href="item.enclosureLink"><img :src="item.mediaThumbnail|trustUrl"
                                                               alt=""/></a>
                        </div>

                        <div class="enclosure description" v-if="item.mediaDescription"
                             news-bind-html-unsafe="item.mediaDescription"></div>

                        <div class="body" v-html="item.body" ng-attr-dir="item.rtl && 'rtl'"></div>

                    </div>
                </li>
            </ul>
        </div>
    </div>

</template>

<script>
import EmptyContent from '@nextcloud/vue/dist/Components/EmptyContent'
import Actions from '@nextcloud/vue/dist/Components/Actions'
import ActionInput from '@nextcloud/vue/dist/Components/ActionInput'
import ActionButton from '@nextcloud/vue/dist/Components/ActionButton'
import ListItemIcon from '@nextcloud/vue/dist/Components/ListItemIcon'
import axios from "@nextcloud/axios";
import {itemsUrl} from "../main";


const maxRelDistance = 90*86400*1000;
const relLimits = [
    [ 7*86400*1000, 'week'   ],
    [   86400*1000, 'day'    ],
    [    3600*1000, 'hour'   ],
    [      60*1000, 'minute' ],
    [       1*1000, 'second' ]
];
const absLimits = [
    [ 7*86400*1000, { hour: '2-digit', minute: '2-digit', dayPeriod: 'narrow',
        year: 'numeric', month: 'short', day: 'numeric' } ],
    [   43200*1000, { hour: '2-digit', minute: '2-digit', dayPeriod: 'narrow',
        weekday: 'long' } ],
    [            0, { hour: '2-digit', minute: '2-digit', dayPeriod: 'narrow' } ]
];

const options = ['admin', 'user1', 'user2', 'guest', 'group1']
const formattedOptions = options.map(item => {
    return {
        user: item,
        displayName: item,
        subtitle: `This is the ${item.startsWith('group') ? 'group' : 'user'} ${item}`,
        icon: item.startsWith('group') ? 'icon-group' : 'icon-user',
        isNoUser: item.startsWith('group')
    }
})

export default {
    components: {
        EmptyContent,
        Actions,
        ActionButton,
        ActionInput,
        ListItemIcon
    },
    computed: {
        isShowAll() {
            // TODO
            return true;
        },
        isFeed() {
            return true
        },
        settings() {
            return this.$store.state.settings
        },
    },
    methods: {
        toggleItem(item) {
            if (this.settings.compact) {
                this.$set(item, 'show', !item.show);
            }
        },
        markRead(item) {

            this.activeItem = item.id;

            if (item.keepUnread || item.unread === false) {
                return;
            }

            this.$set(item, 'unread', false);
            axios.post(itemsUrl + '/' + item.id + '/read', { isRead: true });

            this.$store.commit('markRead', item.feedId);
        },
        toggleStar(itemId) {
            // TODO
        },
        toggleKeepUnread(item) {
            if (!item.unread) {
                this.$store.commit('markUnread', item.feedId);
                this.$set(item, 'unread', true);
                axios.post(itemsUrl + '/' + item.id + '/read', { isRead: false });
            }

            this.$set(item, 'keepUnread', !item.keepUnread);
        },
        isItemActive(itemId) {
            return this.activeItem === itemId;
        },
        getMediaType(type) {
            if (type && type.indexOf('audio') === 0) {
                return 'audio';
            } else if (type && type.indexOf('video') === 0) {
                return 'video';
            } else {
                return undefined;
            }
        },
        play(type) {
            // TODO
        },
        getFeed(feedId) {
            return this.$store.state.feeds.filter(feed => feedId = feed.id)[0]
        }
    },
    data() {
        return {
            items: [],
            value: formattedOptions[0],
            formattedOptions,
            activeItem: null
        }
    },
    beforeRouteUpdate(to, from, next) {
        console.log('Navigating');
        const params = {
            type: to.params.type,
            id: to.params.id == -1 ? null : to.params.id,
            offset: 0,
            limit: 40,
            oldestFirst: this.settings.oldestFirst,
            showAll: this.settings.showAll
        }
        axios.get(itemsUrl, { params }).then(result => {
            let compareFn = (i1, i2) => i2.pubDate - i1.pubDate;
            let compareFnReverse = (i1, i2) => i1.pubDate - i2.pubDate;
            this.items = result.data.items.sort(this.settings.oldestFirst ? compareFnReverse : compareFn);
            next();
        })
    },
    filters: {
        formatDate(timestamp, languageCode) {
            const relFormat = Intl.RelativeTimeFormat ?
                new Intl.RelativeTimeFormat(languageCode, { numeric: 'auto' }) : null;

            if (!Number.isFinite(timestamp)) {
                return timestamp;
            }
            const ts = new Date(timestamp);
            const dist = ts.getTime() - Date.now();
            const absDist = Math.abs(dist);
            if (relFormat && absDist < maxRelDistance) {
                for (const [ scale, unit ] of relLimits) {
                    const value = Math.trunc(dist / scale);
                    if (value !== 0) {
                        return relFormat.format(value, unit);
                    }
                }
                // We arrive here only if distance from now is less than 1 second
                return relFormat.format(0, 'second');
            } else {
                for (const [ limit, options ] of absLimits) {
                    if (absDist >= limit) {
                        return ts.toLocaleString(languageCode, options);
                    }
                }
                // We shouldn't be here
                return ts.toLocaleString(languageCode, absLimits[absLimits.length - 1][1]);
            }
        }
    }
}
</script>

<style>
/**
 * Nextcloud - News
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Bernhard Posselt <dev@bernhard-posselt.com>
 * @copyright Bernhard Posselt 2014
 */

#app-navigation:not(.hidden) + {
    width: calc(100% - 300px);
}

#searchresults {
    display: none;
}

#text-content {
    padding: 20px;
}

#text-content h1 {
    font-size: 18pt;
    margin-bottom: 15px;
}

#text-content p {
    font-size: 11pt;
}

.no-feeds-available {
    text-align: center;
    color: var(--color-text-lighter);
    font-size: 16pt;
    min-height: 100%;
    height: 100%;
    width: 100%;
    display: table;
}

.no-feeds-available p {
    vertical-align: middle;
    display: table-cell;
}

#articles {
    width: 100%;
}

/**
 * Podcast settings
 */
.podcast {
    height: 50px;
    z-index: 10000;
    background-color: #111;
    display: flex;
    position: sticky;
    top: 50px;
    align-items: center;
}

.podcast audio {
    display: block;
    width: calc(100% - 60px);
    border-radius: 0;
    height: inherit;
}

.podcast button,
.podcast a {
    display: block;
    float: left;
    height: 30px;
    width: 30px;
    border: 0;
    border-radius: 0;
    margin: 0;
    background-color: transparent;
    background-repeat: no-repeat;
    background-position: center;
}

.podcast-close {
    background-size: 25px;
    /*background-image: url('../img/close.svg');*/
}

.podcast-download {
    transform: rotate(270deg);
    background-size: 20px;
    /*background-image: url('../img/arrow-white.svg');*/
}

.podcast audio::-webkit-media-controls-enclosure {
    max-width: inherit;
}

.podcast.fixed {
    position: absolute;
    right: 0;
    left: 0;
}

#notification a {
    text-decoration: underline;
    color: blue;
}

#notification li {
    display: block;
}

/**
 * Content styles
 */
#app-content.loading-content {
    /*background: url('../img/loading.gif') no-repeat;*/
    background-color: var(--color-main-background);
    background-position: calc(50% - 16px) calc(50% - 16px);
    /* Overrides the snap.js animation making the loading icon to fly in app-content. */
    transition: none !important;
    -webkit-transition: none !important;
    -moz-webkit-transition: none !important;
}

#app-content.loading-content #app-content-wrapper {
    display: none;
}

#app-content:not(.explore) #articles:after {
    content: '';
    display: block;
    height: 100%;
    background-repeat: no-repeat;
}

.autopaging: #articles:after {
    content: '';
    /*background-image: url('../img/loading.gif');*/
    background-position: calc(50% - 16px) calc(50% - 16px);
    /* Overrides the snap.js animation making the loading icon to fly in app-content. */
    transition: none !important;
    -webkit-transition: none !important;
    -moz-webkit-transition: none !important;
}

.finished-auto-paging #articles:after {
    background: var(--color-main-background);
}

/**
 *
 */
.pull-to-refresh {
    height: 0;
    overflow: hidden;
    text-align: center;
    background-image: -moz-linear-gradient(top, #eee 0%, #fefefe 100%);
    background-image: -webkit-linear-gradient(top, #eee 0%, #fefefe 100%);
    background-image: -ms-linear-gradient(top, #eee 0%, #fefefe 100%);
    background-image: linear-gradient(top, #eee 0%, #fefefe 100%);
}

.pull-to-refresh.show-pull-to-refresh {
    transition: all 0.5s ease;
    padding-top: 10px;
    height: 44px;
}


/**
 * Rules for a single feed item
 */
.only-in-expanded {
    display: block;
}

.compact .only-in-expanded {
    display: none;
}

.only-in-compact {
    display: none;
}

.compact .only-in-compact {
    display: block;
}

.item {
    cursor: default;
    border-bottom: 1px solid var(--color-border);
}

.item.active {
    background-image: linear-gradient(to right, orange 0, orange 2px, transparent 2px);
}

.compact li.item div.utils:hover {
    background-color: var(--color-background-hover);
}

:not(.compact) .item {
}

.item:last-child {
    border-bottom: 0;
}

/**
 * Utils panel
 */
.utils {
    width: 100%;
    line-height: 52px;
    box-sizing: border-box;
    padding: 15px 50px 0 15px;
    position: relative;
}

.compact .utils {
    cursor: pointer;
    padding: 0 15px 0 0;
    -webkit-position: sticky;
    position: sticky;
    top: 50px;
    background-color: var(--color-main-background);
    min-height: 43px;
}

/* Only apply opacity to the util bar, not the dropdown */
.compact .utils ul {
    opacity: 0.9;
}

.utils ul {
    height: 43px;
    list-style-type: none;
    display: flex;
    margin: 0;
    padding: 0;
}

.compact .util-spacer,
.compact .util,
.compact .only-in-compact {
    flex: 1 0 auto;
}

.compact .title {
    flex: 1 1 auto;
}

.open .utils ul {
    height: auto;
}

.utils ul > li {
    min-width: 0;
}

.util-spacer {
    width: 25px;
}

.compact .util-spacer {
    width: 5px;
}

@media screen and (max-width: 1024px) {
    .compact .util-spacer {
        width: 0;
        flex: 0 0 auto;
    }

    .item:first-of-type .util-spacer {
        width: 28px;
    }
}

.utils li {
    line-height: 43px;
    vertical-align: middle;
}

.utils > ul > li:last-child {
    padding-right: 0;
}

.utils > ul > li:first-child {
    padding-left: 0;
}

.utils .util {
    padding: 0;
}

.utils .article-action-plugins {

}

.utils button,
.utils .external {
    padding: 0;
    margin: 0;
    height: 43px;
    width: 40px;
    border: 0;
    float: left;
    background-color: transparent;
    background-size: 100%;
    background-repeat: no-repeat;
    background-size: 25px;
    background-position: center;
}

.utils .external,
.utils .menu button,
.utils .icon-toggle {
    display: inline-block;
    opacity: 0.3;
}

.utils .external:hover,
.utils .menu button:hover,
.utils .icon-toggle:hover,
.utils .icon-toggle.keep-unread {
    opacity: 1;
}

.utils .share {
    opacity: .3;
}

.utils .share:hover {
    opacity: 1;
}

.utils .more button {
    opacity: .3;
}

.utils .more button:hover {
    opacity: 1;
}

.utils .more {
    position: relative;
}

.article-actions {
    display: none;
    filter: drop-shadow(0 0 5px rgba(150, 150, 150, 0.75));
    -webkit-filter: drop-shadow(0 0 5px rgba(150, 150, 150, 0.75));
    -moz-filter: drop-shadow(0 0 5px rgba(150, 150, 150, 0.75));
    position: absolute;
    top: 40px;
    border-radius: 3px;
    z-index: 1000;
    border: 1px solid transparent;
    margin: 0;
    padding: 0;
}

.article-actions ul {
    display: flex;
    float: left;
}

.article-actions li {
    float: left;
    width: 40px !important;
}

#articles:not(.compact) .article-actions {
    left: 6px;
}

#articles:not(.compact) .article-actions:after,
#articles:not(.compact) .article-actions:before {
    left: 20px;
}

#articles.compact .article-actions {
    right: -10px;
}

#articles.compact .article-actions:after,
#articles.compact .article-actions:before {
    right: 9px;
}

.article-actions:after,
.article-actions:before {
    bottom: 100%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
}

.article-actions:after {
    border-color: rgba(255, 255, 255, 0);
    /*border-bottom-color: var(--color-border);*/
    border-width: 5px;
    margin-left: -5px;
}

.article-actions:before {
    border-color: rgba(255, 255, 255, 0);
    /*border-bottom-color: var(--color-border);*/
    border-width: 9px;
    margin-left: -9px;
}

/**
 * Article title
 */
.utils .title {
    width: 100%;
    padding-left: 35px;
    background-position: 6px 10px;
    background-size: 20px 20px;
    background-repeat: no-repeat;
}

.compact .utils .title h1 {
    line-height: 1;
}

.feed-view .utils .title {
    background: none !important;
    padding-left: 10px;
}

.utils .title h1 a {
    /*text-overflow: ellipsis;*/
    white-space: nowrap;
    overflow: hidden;
    width: 100%;
    display: inline-block;
    /*color: var(--color-main-text);*/
    font-weight: bold;
    font-size: 16px;
    line-height: 1.5;
    padding-top: 8px;
    min-height: 40px;
}

.open .title .intro {
    display: none;
}

.title .intro {
    /*color: var(--color-text-lighter);*/
    font-size: 10pt;
    font-weight: normal;
    margin-left: 20px;
}

.open .utils .title h1 a {
    overflow: auto;
    white-space: normal;
}

.read .utils .title h1 a {
    font-weight: normal;
    /*color: var(--color-text-lighter);*/
}

.utils .date {
    padding: 0 15px;
}


/**
 * Expanded content
 */
.heading {
    padding: 25px 0 10px 0;
}

.heading h1 {
    font-size: 19px;
    max-width: 770px;
    word-wrap: break-word;
}

.heading a {
    font-weight: bold;
    /*color: var(--color-main-text);*/
    text-decoration: none;
    margin: 0;
}

.heading a:hover {
    text-decoration: underline;
}

.heading .date {
    float: right;
    display: inline-block;
    font-size: 15px;
    padding: 0 10px;
}

.read .heading a {
    font-weight: normal !important;
    /*color: var(--color-text-lighter);*/
}

.feed-view .source {
    display: none;
}

.source img {
    height: 16px;
    display: inline-block;
    margin: 0 0 -2px 2px;
}

/**
 * Actual content
 */
.article {
    min-height: 69px;
    padding: 0 50px 50px 50px;
}

.compact .article {
    display: none;
}

.open .article {
    display: block;
}

/**
 * Timestamp
 */
.subtitle {
    /*color: var(--color-text-lighter);*/
    font-size: 15px;
    max-width: 770px;
    padding: 25px 0;
}

.subtitle a {
    /*color: var(--color-text-lighter);*/
}

.subtitle a:hover {
    text-decoration: underline;
}

.date {
    /*color: var(--color-text-lighter);*/
    white-space: nowrap;
}

/**
 * Author
 */
.author {
    /*color: var(--color-text-lighter);*/
    font-size: 15px;
}

.author a {
    color: inherit;
}

.author a:hover {
    text-decoration: underline;
}

/**
 * Enclosure
 */
.enclosure {
    padding: 20px 0;
    max-width: 770px;
    margin-bottom: 15px;
}

.enclosure audio,
.enclosure video {
    width: 100%;
}

.enclosure video {
    background-color: #000;
    margin-bottom: 20px;
}

.enclosure-error {
    line-height: 1.5;
    height: 40px;
}

/**
 * Article body
 */
.body {
    max-width: 770px;
    font-size: 14px;
    clear: both;
    overflow-x: auto;
}

.body p {
    line-height: 1.5;
    margin: 7px 0 14px 0;
}

.body img,
.body table {
    max-width: 100%;
    height: auto;
    overflow: auto;
    word-wrap: break-word;
    white-space: normal;
}

.body iframe {
    max-width: 100%;
}

.body > div > img:first-child,
.body > div > :first-child > img:first-child {
    padding: 0 14px 0 0;
    float: left;
}

.body h1,
.body h2 {
    font-size: 17px;
    font-weight: bold;
    /*color: var(--color-main-text);*/
    margin: 21px 0 5px 0;
}

.body h3 {
    font-size: 15px;
    font-weight: bold;
    /*color: var(--color-main-text);*/
    margin: 21px 0 0 0;
    text-decoration: underline;
}

.body h4,
.body h5,
.body h6 {
    font-weight: bold;
}

.body > div {
    margin: 7px 0;
}

.body a {
    color: #0c76ff;
    text-decoration: underline;
}

.body ul {
    margin: 7px 0;
    padding-left: 14px;
    list-style-type: disc;
}

.body ol {
    margin: 7px 0;
    padding-left: 21px;
}

.body ul li {
    cursor: default;
    line-height: 21px;
}

.body pre {
    padding: 7px 7px 7px 14px;
    /*background-color: var(--color-background-darker);*/
    /*border: 1px solid var(--color-border-dark);*/
    margin: 7px 0 14px 0;
    overflow-y: auto;
}

.body pre::-webkit-scrollbar {
    height: 10px;
}

.body pre::-webkit-scrollbar-thumb {
    background: #333;
}

.body pre::-webkit-scrollbar-track-piece {
    background: #ddd;
}

.body code,
.body pre {
    font-family: monospace;
    font-size: 16px;
}

.body blockquote {
    border-left: 1px solid rgb(210, 210, 210);
    margin: 25px 0;
    padding: 0 0 0 15px;
    font-style: italic;
}

.body em {
    font-style: italic;
    opacity: 1;
}

*[dir=rtl] {
    text-align: right;
}


</style>
