<script>
    import {mapGetters} from 'vuex';
    import * as apiConstants from '../../api/constants';
    import * as ItemWidgets from './Widgets';
    import {itemTypes} from '../../constants';

    export default {
        name: 'Item',
        props: ['id'],
        data() {
            return {
                dirtyItem: {},
                imageUpload: {
                    file: null, image: '', name: ''
                },
                pending: false,
                persist: true,
                show: false,
                update: false,
            };
        },
        computed: {
            ...mapGetters(['areItemsLoaded', 'items', 'user']),
            borrowerField() {
                if (this.iBorrowed) {
                    return 'borrowed_to';
                }
                return 'borrowed_from';
            },
            friend() {
                if (this.dirtyItem.borrowed_to.id !== this.user.id) {
                    return this.dirtyItem.borrowed_to;
                }
                return this.dirtyItem.borrowed_from;
            },
            iBorrowed() {
                return this.dirtyItem.borrowed_to.id  !== this.user.id;
            },
            item() {
                // FIXME: maybe show some spinners if items are not loaded
                if (this.areItemsLoaded) {
                    return this.items.data.find(item => item.id === this.id);
                }
                return null;
            },
            itemClasses() {
                return {
                    hide: this.hide,
                    'blue darken-1 white--text': this.item.type === itemTypes.object,
                    'green darken-1 white--text': this.item.type  === itemTypes.money,
                    'orange darken-1 white--text': this.item.type === itemTypes.book,
                    'card-ripple': this.update
                };
            }
        },
        components: {
            ...ItemWidgets
        },
        watch: {
            show(value) {
                if(value === false) {
                    this.closing();
                }
            }
        },
        filters: {
            itemId: value => '#item-' + value
        },
        methods: {
            closeModal() {
                this.show = false;
            },
            closing() {
                setTimeout(() => {
                    this.$store.dispatch('hideItem');
                }, 250);
                // FIXME: bug had to do this because otherwise routing would happen before md-dialog
                // closing animation which produced a bug
                setTimeout(() => { // FIXME: what if there is no precedent page?
                    this.$router.go(-1);
                }, 500);
            },
            commitUpdate() {
                this.pending = true;
                // FIXME: don't update if not dirty
                this.$store.dispatch('updateItem', this.dirtyItem)
                    .then(() => {
                        if (this.imageUpload.file) {
                            const payload = {id: this.id, image: this.imageUpload.file};
                            return this.$store.dispatch('uploadItemImage', payload);
                        }
                    })
                    .then(() => {
                        this.update = false;
                        this.pending = false;
                    })
                    .catch(() => {
                        this.init();
                        // TODO: show toast
                    });
            },
            directionChanged(direction){
                if(this.borrowerField === direction) {
                    return;
                }
                const swap = Object.assign({}, this.dirtyItem.borrowed_from);
                this.dirtyItem.borrowed_from = Object.assign({}, this.dirtyItem.borrowed_to);
                this.dirtyItem.borrowed_to = swap;
            },
            imageChange(imageUpload) {
                this.imageUpload = imageUpload;
            },
            init() {
                this.persist = true;
                this.show = true;
                this.dirtyItem = Object.assign({}, this.item);
                this.imageUpload = {
                    image: '',
                    name: '',
                    file: null
                };
                this.update = false;
                this.pending = false;
                // FIXME: this is a hack to avoid autoclosing the dialog
                // the problem resides in click-outside directive
                setTimeout(() => {
                    this.persist = false;
                }, 500);
            },
            selectedFriend(friend){
                this.dirtyItem[this.borrowerField] = friend;
            },
            startUpdate() {
                this.update = true;
            },
            updateOrCommit() {
                if (!this.update) {
                    this.startUpdate();
                } else if (!this.pending) {
                    this.commitUpdate();
                }
            }
        },
        created() {
            this.dirtyItem = Object.assign({}, this.item);
        },
        activated() {
            // FIXME: this is producing a bug when you access an item from route directly
            if (this.item === null) {
                this.$router.replace({name: 'dashboard'});
            }
            this.init();
            /*this.$nextTick(() => {
                this.$refs['itemDialog'].open();
            });*/
        }
    }
</script>
<template>
    <v-dialog width="500" v-model="show" :persistent="persist" class="item-dialog">
        <v-card :class="itemClasses" class="dialog-card">
            <v-card-row class="card-media">
                <item-media @change="imageChange" :image="dirtyItem.image" :update="update"
                            :alt="dirtyItem.name"></item-media>
            </v-card-row>
            <v-card-row class="card-header">
                <v-container class="elevation-1 pl-3">
                    <v-row>
                        <v-col xs1 class="pa-0 pt-1">
                            <v-btn icon small @click.native="closeModal">
                                <v-icon>arrow_back</v-icon>
                            </v-btn>
                        </v-col>
                        <v-col xs11><item-name :update="update" v-model="dirtyItem.name"></item-name></v-col>
                    </v-row>
                    <v-row class="pl-4">
                        <item-date v-model="dirtyItem.borrowed_at"
                                   :update="update" style="flex: 1;"></item-date>
                        <span style="flex: 1;"></span>
                        <item-date v-model="dirtyItem.return_at"
                                   :update="update"  style="flex: 1;"></item-date>
                        <span style="flex: 1;"></span>
                    </v-row>
                </v-container>
                <item-update-button @click="updateOrCommit" :update="update"
                                    :pending="pending"></item-update-button>
            </v-card-row>
            <v-card-row class="card-content">
                <v-list three-line subheader>
                    <item-type v-model="dirtyItem.type" :update="update"></item-type>
                    <item-friend :update="update" :direction="borrowerField" :friend="friend"
                                 @friend="selectedFriend" @direction="directionChanged"
                                 :is-owner="dirtyItem.owner_id === user.id"></item-friend>
                    <item-notify :update="update"></item-notify>
                    <item-amount v-model="dirtyItem.amount" :type="dirtyItem.type" :update="update"></item-amount>
                    <item-details v-model="dirtyItem.details" :update="update"></item-details>
                </v-list>
            </v-card-row>
        </v-card>
    </v-dialog>
</template>
<style lang="scss" scoped>

    .item-dialog {
        .dialog {
            max-height: 90%;
        }
        .card.dialog-card {
            width: 500px;
            max-height: 700px;
            margin: 0;
            .card-media {
                overflow: hidden;
                height: 200px;
                min-height: 200px;
                max-height: 200px;
                text-align: center;
            }
            .card-header + .card-content {
                background: white;
                color: black;
            }
        }
    }
</style>
