<template>
    <div id="explore">
        <AddFeed v-if="showAddFeed" @close="closeShowAddFeed()" :feed="feed"></AddFeed>
        <div class="grid-container">
            <div v-for="entry in explorableSites" class="explore-feed grid-item">
                <h2 class="explore-title" v-if="entry.favicon"
                    :style="{ backgroundImage: 'url(' + entry.favicon + ')'}">
                    <a target="_blank" rel="noreferrer" :href="entry.url">{{ entry.title }}</a>
                </h2>
                <h2 class="icon-rss explore-title" v-if="!entry.favicon">
                    {{ entry.title }}
                </h2>
                <div class="explore-content">
                    <p>{{ entry.description }}</p>

                    <div class="explore-logo">
                        <img :src="entry.image">
                    </div>
                </div>
                <Button @click="subscribe(entry.feed)">
                    {{ t('news', 'Subscribe to') }} {{ entry.title }}
                </Button>
            </div>
        </div>
    </div>
</template>

<script>
import Modal from '@nextcloud/vue/dist/Components/Modal'
import Button from '@nextcloud/vue/dist/Components/Button'
import axios from "@nextcloud/axios";
import AddFeed from "./AddFeed";
import {generateUrl} from "@nextcloud/router";

export default {
    components: {
        Modal,
        Button,
        AddFeed
    },
    data() {
        return {
            explorableSites: [],
            showAddFeed: false,
            feed: ''
        }
    },
    methods: {
        async sites() {
            const settings = await axios.get(generateUrl("/apps/news/settings"));

            const exploreUrl = settings.data.settings.exploreUrl + 'feeds.en.json';
            const explore = await axios.get(exploreUrl);

            Object.keys(explore.data).forEach(key =>
                explore.data[key].forEach(value =>
                    this.explorableSites.push(value)
                )
            );
        },
        async subscribe(feed) {
            this.feed = feed;
            this.showAddFeed = true;
        },
        closeShowAddFeed() {
            this.showAddFeed = false;
        }
    },
    created() {
        this.sites();
    }
}
</script>
