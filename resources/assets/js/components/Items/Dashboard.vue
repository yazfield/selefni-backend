<script>
    import {mapGetters} from 'vuex';
    import {includes, orderBy} from 'lodash';
    import {templates} from '../../constants';
    import NewItem from './NewItem';
    import ItemWidget from './Widgets/ItemWidget';
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
            showItem(itemId) {
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
            },
            hideItemWidget: function(itemId) {
                return this.showingItem === itemId;
            },
            sortedItems: function(items) {
                return orderBy(items, 'updated_at', 'desc');
            }
        },
        components: { NewItem, ItemWidget },
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
                              infinite-scroll-distance="10" enter-active-class="animated slideInDown"
                              leave-active-class="animated slideOutUp">
                <item-widget v-for="item in sortedItems(items.data)" :key="item.id" :hide="hideItemWidget(item.id)"
                             :item="item" @selected="toggleSelected(item.id)" @click="showItem(item.id)"
                             :selected="isSelected(item.id)"></item-widget>
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

    .cards-wrapper {
        height: 100%;
        width: 100%;
        .md-card {
            flex-grow: 1;
            width: 31%;
            margin: 0.875%;
        }
    }
    .cards-container {
        padding: 0.875%;
    }
</style>