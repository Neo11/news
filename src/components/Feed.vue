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
            <ActionButton icon="icon-pinned" @click="alert('Rename')">
                {{ t('news', 'Unpin from top') }}
            </ActionButton>
            <ActionButton icon="icon-caret-dark" @click="deleteFolder(folder)">
                {{ t('news', 'Newest first') }}
            </ActionButton>
            <ActionButton icon="icon-caret-dark" @click="deleteFolder(folder)">
                {{ t('news', 'Oldest first') }}
            </ActionButton>
            <ActionButton icon="icon-caret-dark" @click="deleteFolder(folder)">
                {{ t('news', 'Default order') }}
            </ActionButton>
            <ActionButton icon="icon-full-text-disabled" @click="deleteFolder(folder)">
                {{ t('news', 'Enable full text') }}
            </ActionButton>
            <ActionButton icon="icon-full-text-enabled" @click="deleteFolder(folder)">
                {{ t('news', 'Disable full text') }}
            </ActionButton>
            <ActionButton icon="icon-updatemode-default" @click="deleteFolder(folder)">
                {{ t('news', 'Unread updated') }}
            </ActionButton>
            <ActionButton icon="icon-updatemode-unread" @click="deleteFolder(folder)">
                {{ t('news', 'Ignore updated') }}
            </ActionButton>
            <ActionButton icon="icon-icon-rss" @click="deleteFolder(folder)">
                {{ t('news', 'Open feed URL') }}
            </ActionButton>
            <ActionButton icon="icon-icon-rename" @click="deleteFolder(folder)">
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

export default {
    components: {
        AppNavigationItem,
        CounterBubble,
        ActionButton
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
