<template>
    <Modal @close="$emit('close')">
        <div id="new-feed" news-add-feed="Navigation.feed">

            <form ng-init="Navigation.feed.autoDiscover=true"
                  name="feedform">
                <fieldset ng-disabled="Navigation.addingFeed" style="padding: 16px">
                    <input type="text"
                           v-model="feedData.url"
                           ng-class="{'ng-invalid':
                        !Navigation.addingFeed &&
                        Navigation.feedUrlExists(Navigation.feed.url)
                    }"
                           :placeholder="t('news','Web address')"
                           name="address"
                           pattern="[^\s]+"
                           required
                           autofocus>

                    <p class="error"
                       ng-show="!Navigation.addingFeed &&
                        Navigation.feedUrlExists(Navigation.feed.url)">
                        {{ t('news', 'Feed exists already!') }}
                    </p>

                    <!-- select a folder -->
                    <CheckboxRadioSwitch :checked.sync="feedData.createNewFolder" type="switch">
                        {{ t('news', 'New folder') }}?
                    </CheckboxRadioSwitch>

                    <Multiselect v-if="!feedData.createNewFolder" v-model="feedData.folder" :options="folders" track-by="id" label="name"/>

                    <!-- add a folder -->
                    <input type="text"
                           ng-class="{'ng-invalid':
                            !Navigation.addingFeed &&
                            !Navigation.addingFeed &&
                            Navigation.showNewFolder &&
                            Navigation.folderNameExists(
                                Navigation.feed.newFolder
                            )
                        }"
                           :placeholder="t('news','Folder name')"
                           name="folderName"
                           v-if="feedData.createNewFolder"
                           v-model="feedData.newFolderName"
                           required>


                    <p class="error" ng-show="!Navigation.addingFeed &&
                    Navigation.folderNameExists(Navigation.feed.newFolder)">
                        {{ t('news', 'Folder exists already!') }}
                    </p>

                    <!-- basic auth -->

                    <CheckboxRadioSwitch :checked.sync="feedData.withBasicAuth" type="switch">
                        {{ t('news', 'Credentials') }}?
                    </CheckboxRadioSwitch>

                    <div v-if="feedData.withBasicAuth" class="add-feed-basicauth">
                        <p class="warning">{{
                                t('news',
                                    'HTTP Basic Auth credentials must be stored unencrypted! Everyone with access to the server or database will be able to access them!')
                            }}></p>
                        <input type="text"
                               ng-model="Navigation.feed.user"
                               :placeholder="t('news','Username')"
                               name="user"
                               autofocus>

                        <input type="password"
                               ng-model="Navigation.feed.password"
                               :placeholder="t('news','Password')"
                               name="password" autocomplete="new-password">
                    </div>

                    <CheckboxRadioSwitch :checked.sync="feedData.autoDiscover" type="switch">
                        {{ t('news', 'Auto discover Feed') }}?
                    </CheckboxRadioSwitch>

                    <Button :wide="true" type="primary" @click="addFeed()" ng-disabled="
                        Navigation.feedUrlExists(Navigation.feed.url) ||
                                (
                                    Navigation.showNewFolder &&
                                    Navigation.folderNameExists(folder.name)
                                )">
                        {{t('news','Subscribe')}}
                    </Button>
                </fieldset>
            </form>
        </div>
    </Modal>
</template>

<script>
import Modal from '@nextcloud/vue/dist/Components/Modal'
import CheckboxRadioSwitch from '@nextcloud/vue/dist/Components/CheckboxRadioSwitch'
import Button from '@nextcloud/vue/dist/Components/Button'
import Multiselect from '@nextcloud/vue/dist/Components/Multiselect'

export default {
    components: {
        Modal,
        CheckboxRadioSwitch,
        Button,
        Multiselect
    },
    computed: {
        folders() {
            return this.$store.state.folders
        }
    },
    methods: {
        addFeed() {
            this.$store.dispatch('addFeed', {feedReq: this.feedData});
            this.$emit('close');
        }
    },
    data() {
        return {
            feedData: {
                url: this.feed,
                folder: {},
                autoDiscover: true,
                withBasicAuth: false,
                createNewFolder: false,
                newFolderName: null
            }
        }
    },
    props: ['feed'],
    emits: ['close']
}
</script>

<style scoped>

    input {
        width: 100%;
    }
    .multiselect {
        width: 100%;
    }

</style>
