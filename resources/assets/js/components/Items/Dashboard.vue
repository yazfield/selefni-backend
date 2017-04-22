<script>
    import {mapGetters} from 'vuex';
    import {includes} from 'lodash';
    import {templates} from '../../constants';
    import NewItem from './NewItem';
    import Item from './Item';
    export default {
        data() {
            return {
                pending: false,
                selection: []
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
            },
            isSelected(itemId) {
                return includes(this.selection, itemId);
            },
            toggleSelected(itemId) {
                if(this.isSelected(itemId)) {
                    this.selection.splice(this.selection.indexOf(itemId), 1);
                } else {
                    this.selection.push(itemId);
                }
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
        <md-button @click.native="" class="md-fab md-fab-bottom-right">
            <md-icon>add</md-icon>
        </md-button>
        <div class="cards-wrapper">
            <transition-group name="list" tag="div" class="md-layout cards-container"
                              v-infinite-scroll="loadItems" infinite-scroll-disabled="pending"
                              infinite-scroll-distance="10"
                              enter-active-class="animated slideInDown"
                              leave-active-class="animated slideOutUp">
                <md-card
                        :class="{hide: showingItem === item.id,
                            'md-primary': item.type=='object',
                            'md-accent':item.type=='money',
                            'md-warn':item.type=='book',
                            'selected': isSelected(item.id)
                            }"
                        :id="'item-' + item.id" :md-with-hover="!isSelected(item.id)"
                        v-for="item in items.data"
                        :key="item.id">
                    <md-button @click.native="toggleSelected(item.id)" class="md-icon-button select-button">
                        <md-icon>done</md-icon>
                    </md-button>

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
            </transition-group>
            <md-layout class="loading" v-if="pending" md-align="center">
                <md-spinner md-indeterminate></md-spinner>
            </md-layout>
        </div>
        <keep-alive>
            <router-view></router-view>
        </keep-alive>
    </md-layout>
</template>

<style lang="scss">
    @import 'node_modules/sass-material-colors/sass/sass-material-colors';
    .md-theme-default {

        .md-button.md-icon-button.select-button {
            position: absolute;
            top: -10px;
            left: -15px;
            z-index: 2;
            background: white;
            width: 24px;
            min-width: 24px;
            height: 24px;
            display: none;
            min-height: 24px;

            &:hover {
                background: white;
            }

            i {
                color: material-color('grey', '600');
                width: 16px;
                min-width: 16px;
                height: 16px;
                min-height: 16px;
                font-size: 16px;
            }
        }
    }
    .cards-wrapper {
        height: 100%;
        width: 100%;
        .md-card {
            flex-grow: 1;
            width: 31%;
            margin: 0.875%;
            overflow: visible;f
            &:hover .select-button {
                display: block;

             }

             &.selected {
                .select-button {
                      background: material-color('grey', '800');
                    display: block;
                    i {
                        color: white;
                    }
                    &:hover {
                         background: material-color('grey', '800');
                        i {
                            color: white;
                        }
                     }
                }
                border: 4px solid material-color('grey', '800');
                border-radius: 5px;
              }
        }
    }

    .cards-container {
        padding: 0.875%;
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