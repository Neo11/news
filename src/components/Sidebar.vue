<template>
    <AppNavigation>
        <AddFeed v-if="showAddFeed" @close="closeShowAddFeed()"></AddFeed>
        <AppNavigationNew
            :text="t('news','Subscribe')"
            button-id="new-feed-button"
            button-class="icon-add"
            @click="showShowAddFeed()"/>

        <AppNavigationNewItem :title="t('news','New folder')"
                              icon="icon-add-folder"
                              @new-item="newFolder">
        </AppNavigationNewItem>

        <AppNavigationItem :title="t('news','Unread articles')"
                           icon="icon-rss"
                           :to="{ name: 'content_unread' }">
            <template #actions>
                <ActionButton icon="icon-checkmark" @click="alert('Edit')">
                    t('news','Mark read')
                </ActionButton>
            </template>
            <template #counter>
                <CounterBubble>{{ totalUnreadCount }}</CounterBubble>
            </template>
        </AppNavigationItem>
        <AppNavigationItem :title="t('news','All articles')" icon="icon-rss">
            <template #actions>
                <ActionButton icon="icon-checkmark" @click="alert('Edit')">
                    t('news','Mark read')
                </ActionButton>
            </template>
        </AppNavigationItem>
        <AppNavigationItem :title="t('news','Starred')" icon="icon-starred">
            <template #counter>
                <CounterBubble>?</CounterBubble>
            </template>
        </AppNavigationItem>
        <Feed :feed="feed" v-for="feed in feedsWithoutFolders" :key="feed.id"></Feed>
        <AppNavigationItem v-for="folder in folders" :title="folder.name" icon="icon-folder"
                           :allowCollapse="true" :key="folder.id">
            <template #default>
                <Feed :feed="feed" v-for="feed in folder.feeds" :key="feed.id"></Feed>
            </template>
            <template #counter v-if="folder.unreadCount > 0">
                <CounterBubble>{{ folder.unreadCount }}</CounterBubble>
            </template>
            <template #actions>
                <ActionButton icon="icon-checkmark" @click="alert('Mark read')">
                    {{ t('news', 'Mark read') }}
                </ActionButton>
                <ActionButton icon="icon-rename" @click="alert('Rename')">
                    {{ t('news', 'Rename') }}
                </ActionButton>
                <ActionButton icon="icon-delete" @click="deleteFolder(folder)">
                    {{ t('news', 'Delete') }}
                </ActionButton>
            </template>
        </AppNavigationItem>

        <AppNavigationItem :title="t('news','Explore')"
                           icon="icon-link"
                           :to="{ name: 'explore' }">
        </AppNavigationItem>


        <template #footer>
            <AppNavigationSettings>
                <div id="app-settings">
                    <fieldset>
                        <ul>
                            <li>
                                <CheckboxRadioSwitch :checked.sync="settings.preventReadOnScroll"
                                                     @update:checked="toggleSetting('preventReadOnScroll')">
                                    {{ t("news", "Disable mark read through scrolling") }}
                                </CheckboxRadioSwitch>
                            </li>
                            <li>
                                <CheckboxRadioSwitch :checked.sync="settings.compact"
                                                     @update:checked="toggleSetting('compact')">
                                    {{ t("news", "Compact view") }}
                                </CheckboxRadioSwitch>
                            </li>
                            <li v-if="settings.compact">
                                <CheckboxRadioSwitch :checked.sync="settings.compactExpand"
                                                     @update:checked="toggleSetting('compactExpand')">
                                    {{ t("news", "Expand articles on key navigation") }}
                                </CheckboxRadioSwitch>
                            </li>
                            <li>
                                <CheckboxRadioSwitch :checked.sync="settings.showAll"
                                                     @update:checked="toggleSetting('showAll')">
                                    {{ t("news", "Show all articles") }}
                                </CheckboxRadioSwitch>
                            </li>
                            <li>
                                <CheckboxRadioSwitch :checked.sync="settings.oldestFirst"
                                                     @update:checked="toggleSetting('oldestFirst')">
                                    {{ t("news", "Reverse ordering (oldest on top)") }}
                                </CheckboxRadioSwitch>
                            </li>
                        </ul>
                    </fieldset>

                    <div class="import-export">

                        <AppSettingsSection :title="t('news', 'Subscriptions (OPML)')">

                            <Button :tooltip="t('news', 'Import')" style="width: 100%; margin: 6px">
<!--                                <span class="icon-upload svg button-icon-label"></span>-->
                                {{ t('news', 'Import') }}
                            </Button>

                            <Button :tooltip="t('news', 'Export')" style="width: 100%; margin: 6px">
                                {{ t('news', 'Export') }}
                            </Button>

<!--                        <input type="file"-->
<!--                               id="opml-upload"-->
<!--                               name="import"-->
<!--                               news-read-file="Settings.importOPML($fileContent)"/>-->

<!--                        <button title="<?php p($l->t('Import')); ?>"-->
<!--                                class="icon-upload svg button-icon-label"-->
<!--                                news-trigger-click="#opml-upload"-->
<!--                                ng-class="{'entry-loading': Settings.isOPMLImporting}"-->
<!--                                ng-disabled=-->
<!--                                    "Settings.isOPMLImporting || Settings.isArticlesImporting">-->
<!--                        </button>-->

<!--                        <a title="<?php p($l->t('Export')); ?>"-->
<!--                           class="button icon-download svg button-icon-label"-->
<!--                           href="<?php p($_['url_generator']->linkToRoute('news.export.opml')); ?>"-->
<!--                           target="_blank"-->
<!--                           rel="noreferrer"-->
<!--                           ng-hide="App.isFirstRun()">-->
<!--                        </a>-->

<!--                        <button-->
<!--                            class="icon-download svg button-icon-label"-->
<!--                            title="<?php p($l->t('Export')); ?>"-->
<!--                            ng-show="App.isFirstRun()"-->
<!--                            disabled>-->
<!--                        </button>-->

<!--                        <p class="error" ng-show="Settings.opmlImportError">-->
<!--                            {{ t('news', 'Error when importing: File does not contain valid OPML') }}
<!--                        </p>-->
<!--                        <p class="error" ng-show="Settings.opmlImportEmptyError">-->
<!--                            <?php p(-->
<!--                $l->t('Error when importing: OPML is does neither contain ' .-->
<!--                            'feeds nor folders')-->
<!--                            ); ?>-->
<!--                        </p>-->

                        </AppSettingsSection>
                        <AppSettingsSection :title="t('news', 'Unread/Starred Articles')">

<!--                        <input-->
<!--                            type="file"-->
<!--                            id="article-upload"-->
<!--                            name="importarticle"-->
<!--                            news-read-file="Settings.importArticles($fileContent)"/>-->

<!--                        <button title="<?php p($l->t('Import')); ?>"-->
<!--                                class="icon-upload svg button-icon-label"-->
<!--                                ng-class="{'entry-loading': Settings.isArticlesImporting}"-->
<!--                                ng-disabled="Settings.isOPMLImporting || Settings.isArticlesImporting"-->
<!--                                news-trigger-click="#article-upload">-->
<!--                        </button>-->

<!--                        <a title="<?php p($l->t('Export')); ?>"-->
<!--                           class="button icon-download svg button-icon-label"-->
<!--                           href="<?php p($_['url_generator']->linkToRoute('news.export.articles')); ?>"-->
<!--                           target="_blank"-->
<!--                           rel="noreferrer"-->
<!--                           ng-hide="App.isFirstRun()">-->
<!--                        </a>-->
<!--                        <button-->
<!--                            class="icon-download svg button-icon-label"-->
<!--                            title="<?php p($l->t('Export')); ?>"-->
<!--                            ng-show="App.isFirstRun()"-->
<!--                            disabled>-->
<!--                        </button>-->

<!--                        <p class="error" ng-show="Settings.articleImportError">-->
<!--                            <?php p(-->
<!--                $l->t('Error when importing: file does not contain valid JSON')-->
<!--                            ); ?>-->
<!--                        </p>-->

                        </AppSettingsSection>

                    <AppSettingsSection :title="t('news', 'Help')">
                        <p>
                            <a href="#/shortcuts/">{{ t('news', 'Keyboard shortcuts') }}</a>
                        </p>

                        <p>
                            <a target="_blank"
                               rel="noreferrer"
                               href="https://github.com/nextcloud/news/tree/master/docs">{{ t('news', 'Documentation') }}</a>
                        </p>
                        <p>
                            <a target="_blank"
                               rel="noreferrer"
                               href="https://github.com/nextcloud/news/issues/new/choose">{{ t('news', 'Report a bug') }}</a>
                        </p>
                    </AppSettingsSection>
                    </div>

                </div>
            </AppNavigationSettings>
        </template>

    </AppNavigation>
</template>

<script>
import AppNavigation from '@nextcloud/vue/dist/Components/AppNavigation'
import AppNavigationNew from '@nextcloud/vue/dist/Components/AppNavigationNew'
import AppNavigationItem from '@nextcloud/vue/dist/Components/AppNavigationItem'
import AppNavigationNewItem from '@nextcloud/vue/dist/Components/AppNavigationNewItem'
import AppNavigationCounter from '@nextcloud/vue/dist/Components/AppNavigationCounter'
import AppNavigationSettings from '@nextcloud/vue/dist/Components/AppNavigationSettings'
import AppNavigationSpacer from '@nextcloud/vue/dist/Components/AppNavigationSpacer'
import AppSettingsSection from '@nextcloud/vue/dist/Components/AppSettingsSection'
import CounterBubble from '@nextcloud/vue/dist/Components/CounterBubble'
import ActionButton from '@nextcloud/vue/dist/Components/ActionButton'
import CheckboxRadioSwitch from '@nextcloud/vue/dist/Components/CheckboxRadioSwitch'
import Button from '@nextcloud/vue/dist/Components/Button'
import AddFeed from "./AddFeed";
import Feed from "./Feed";

export default {
    components: {
        Feed,
        AppNavigation,
        AppNavigationNew,
        AppNavigationItem,
        AppNavigationNewItem,
        AppNavigationCounter,
        AppNavigationSettings,
        AppNavigationSpacer,
        CheckboxRadioSwitch,
        AppSettingsSection,
        CounterBubble,
        ActionButton,
        Button,
        AddFeed
    },
    data() {
        return {
            showAddFeed: false,
        }
    },
    computed: {
        folders() {
            return this.$store.state.folders
        },
        feedsWithoutFolders() {
            return this.$store.state.feeds.filter(feed => !feed.folderId)
        },
        totalUnreadCount() {
            return this.$store.state.unreadCount
        },
        settings() {
            return this.$store.state.settings
        },
    },
    methods: {
        newFolder(value) {
            const folderName = value.trim();
            const folder = {name: folderName};
            this.$store.dispatch('addFolder', {folder})
        },
        deleteFolder(folder) {
            this.$store.dispatch('deleteFolder', {folder})
        },
        showShowAddFeed() {
            this.showAddFeed = true;
        },
        closeShowAddFeed() {
            this.showAddFeed = false;
        },
        toggleSetting(key) {
            this.$store.dispatch('updateOption', {key})
        }
    },
    created() {
    }
}
</script>
