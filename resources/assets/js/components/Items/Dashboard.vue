<script>
    import {mapGetters} from 'vuex';
    import {includes, orderBy} from 'lodash';
    import {templates} from '../../constants';
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
        components: { ItemWidget },
        methods: {
            hideItemWidget: function(itemId) {
                return this.showingItem === itemId;
            },
            isSelected(itemId) {
                return includes(this.selection, itemId);
            },
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
            sortedItems: function(items) {
                return orderBy(items, 'updated_at', 'desc');
            },
            toggleSelected(itemId) {
                if(this.isSelected(itemId)) {
                    this.selection.splice(this.selection.indexOf(itemId), 1);
                } else {
                    this.selection.push(itemId);
                }
            }
        },
        mounted() {
            this.$store.dispatch('changeTemplate', templates.dashboard);
        }
    }
</script>

<template>
    <v-container>
        <v-btn floating primary dark class="add-item-button">
            <v-icon>add</v-icon>
        </v-btn>
        <div class="cards-wrapper">
            <transition-group name="list" tag="div" class="cards-container"
                              v-infinite-scroll="loadItems" infinite-scroll-disabled="pending"
                              infinite-scroll-distance="10" enter-active-class="animated slideInDown"
                              leave-active-class="animated slideOutUp">
                <item-widget v-for="item in sortedItems(items.data)" :key="item.id" :hide="hideItemWidget(item.id)"
                             :item="item" @selected="toggleSelected(item.id)" @click="showItem(item.id)"
                             :selected="isSelected(item.id)"></item-widget>
            </transition-group>
            <div class="loading" v-if="pending">
                <v-progress-circular indeterminate class="primary--text" :size="70" />
            </div>
        </div>
        <keep-alive>
            <router-view></router-view>
        </keep-alive>
    </v-container>
</template>

<style lang="scss">
    /* TODO: check if this is repeated */
    .loading {
        text-align: center;
    }
    .add-item-button {
        float: right;
        margin-top: 50%;
    }
    .cards-wrapper {
        height: 100%;
        width: 100%;
        .card {
            flex-grow: 1;
        }
    }
    @media screen and (max-width: 600px){
        .cards-wrapper .card {
            min-width: 100%;
            width: 100%;
            margin: 0 0 2% 0;
        }
    }
    @media screen and (max-width: 960px){
        .cards-wrapper .card {
            min-width: 46.5%;
            width: 46.5%;
            margin: 0.875%;
        }
    }
    @media screen and (max-width: 1280px){
        .cards-wrapper .card {
            min-width: 31%;
            width: 31%;
            margin: 0.875%;
        }
    }
    @media screen and (max-width: 1920px){
        .cards-wrapper .card {
            min-width: 31%;
            width: 31%;
            margin: 0.875%;
        }
    }
    .cards-container {
        padding: 0.875%;
        display: flex;
        flex-wrap: wrap;
        flex-direction: row;
    }
</style>