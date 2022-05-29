<template>
    <AppNavigationItem :title="feed.title" :to="{ name: 'content' }">
        <template #icon>
            <img :src="feed.faviconLink" v-if="feed.faviconLink" alt="feedIcon">
            <div class="icon-rss" v-if="!feed.faviconLink"></div>
        </template>
        <template #counter v-if="feed.unreadCount > 0">
            <CounterBubble>{{ feed.unreadCount }}</CounterBubble>
        </template>
        <template #actions>
            <ActionButton icon="icon-checkmark" @click="alert('Mark read')">
                {{ t('news', 'Mark read') }}
            </ActionButton>
            <ActionButton icon="icon-pinned" @click="alert('Rename')" v-if="feed.pinned">
                {{ t('news', 'Unpin from top') }}
            </ActionButton>
            <ActionButton icon="icon-pinned" @click="alert('Rename')" v-if="!feed.pinned">
                {{ t('news', 'Pin to top') }}
            </ActionButton>
            <ActionButton icon="icon-caret-dark" @click="deleteFolder(folder)" v-if="feed.ordering === 0">
                {{ t('news', 'Newest first') }}
            </ActionButton>
            <ActionButton icon="icon-caret-dark" @click="deleteFolder(folder)" v-if="feed.ordering === 1">
                {{ t('news', 'Oldest first') }}
            </ActionButton>
            <ActionButton icon="icon-caret-dark" @click="deleteFolder(folder)" v-if="feed.ordering === 2">
                {{ t('news', 'Default order') }}
            </ActionButton>
            <ActionButton icon="icon-full-text-disabled" @click="deleteFolder(folder)" v-if="!feed.fullTextEnabled">
                {{ t('news', 'Enable full text') }}
            </ActionButton>
            <ActionButton icon="icon-full-text-enabled" @click="deleteFolder(folder)" v-if="feed.fullTextEnabled">
                {{ t('news', 'Disable full text') }}
            </ActionButton>
            <ActionButton icon="icon-updatemode-default" @click="setUpdateMode(feed.id, 1)" v-if="feed.updateMode !== 1">
                {{ t('news', 'Unread updated') }}
            </ActionButton>
            <ActionButton icon="icon-updatemode-unread" @click="setUpdateMode(feed.id, 0)" v-if="feed.updateMode === 1">
                {{ t('news', 'Ignore updated') }}
            </ActionButton>
            <ActionLink icon="icon-rss" :href="feed.url" target="_blank" rel="noopener noreferrer">
                {{ t('news', 'Open feed URL') }}
            </ActionLink>
            <ActionButton icon="icon-rename" @click="editFeed(folder)">
                {{ t('news', 'Rename') }}
            </ActionButton>
            <ActionButton icon="icon-delete" @click="deleteFeed(feed)">
                {{ t('news', 'Delete') }}
            </ActionButton>
        </template>
    </AppNavigationItem>
</template>

<script>
import AppNavigationItem from '@nextcloud/vue/dist/Components/AppNavigationItem'
import CounterBubble from '@nextcloud/vue/dist/Components/CounterBubble'
import ActionButton from '@nextcloud/vue/dist/Components/ActionButton'
import ActionLink from '@nextcloud/vue/dist/Components/ActionLink'

export default {
    components: {
        AppNavigationItem,
        CounterBubble,
        ActionButton,
        ActionLink
    },
    props: {
        feed: {},
    },
    methods: {
        deleteFeed(feed) {
            this.$store.dispatch('deleteFeed', {feed})
        }
    }
}
</script>
