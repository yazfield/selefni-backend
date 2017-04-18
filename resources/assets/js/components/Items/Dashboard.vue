<script>
    import {mapGetters} from 'vuex';
    import {templates} from '../../constants';
    import NewItem from './NewItem';
    import Item from './Item';
    export default {
        data() {
            return {
                pending: false
            }
        },
        computed: {
            ...mapGetters(['items', 'showingItem'])
        },
        methods: {
            loadItems() {
                const nextPage = this.items.current_page + 1;
                if (this.items.last_page !== null && nextPage > this.items.last_page) {
                    return;
                }
                this.pending = true;
                this.$store.dispatch('loadItems', nextPage)
                    .then(() => {
                        this.pending = false;
                    }).catch(() => {
                    this.pending = false;
                });
            },
            selectItem(itemId) {
                this.$router.push({name: 'item', params: {id: itemId}});
                this.$store.dispatch('showItem', itemId);
            }
        },
        components: {
            NewItem,
            Item
        },
        mounted() {
            this.$store.dispatch('changeTemplate', templates.dashboard);
        }
    }
</script>

<template>
    <md-layout>
        <NewItem></NewItem>
        <div class="cards-wrapper">
            <md-layout class="cards-container" v-infinite-scroll="loadItems" infinite-scroll-disabled="pending"
                       infinite-scroll-distance="10">
                <md-card
                        :class="{hide: showingItem === item.id, 'md-primary': item.type=='object', 'md-accent':item.type=='money', 'md-warn':item.type=='book'}"
                        :id="'item-' + item.id" md-with-hover v-for="item in items.data">
                    <a @click.stop.prevent="selectItem(item.id)">
                        <md-card-media md-ratio="1:1">
                            <md-ink-ripple @click="selectItem(item.id)"></md-ink-ripple>
                            <img :src="item.image" :alt="item.name">
                        </md-card-media>
                    </a>

                    <md-card-header>
                        <div class="md-title" @click="selectItem(item.id)">{{ item.name }}</div>
                    </md-card-header>

                    <md-card-actions>
                        <md-button class="md-icon-button">
                            <md-icon>notifications</md-icon>
                        </md-button>
                        <md-button class="md-icon-button">
                            <md-icon>email</md-icon>
                        </md-button>
                    </md-card-actions>
                </md-card>
                <md-layout class="loading" v-if="pending" md-align="center">
                    <md-spinner md-indeterminate></md-spinner>
                </md-layout>
            </md-layout>
        </div>
        <keep-alive>
            <router-view></router-view>
        </keep-alive>
    </md-layout>
</template>

<style lang="scss">
    .cards-wrapper {
        height: 100%;
        width: 100%;
    }

    .cards-container {
        padding: 0.875%;
    }

    .md-card {
        flex-grow: 1;
        width: 31%;
        margin: 0.875%;
    }

    .md-theme-default .md-card .md-card-area .md-card-actions .md-icon-button:not(.md-primary):not(.md-warn):not(.md-accent) .md-icon {
        color: rgba(255, 255, 255, .54);
    }

    .hide {
        opacity: 0;
    }

    .md-card {
        max-height: 260px;
    }

    .md-card-media {
        height: 150px;
    }
</style>