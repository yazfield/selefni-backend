<script>
    import {mapGetters} from 'vuex';
    import * as apiConstants from '../../api/constants';
    import * as ItemWidgets from './Widgets';
    import {itemTypes} from '../../constants';

    export default {
        props: ['id'],
        data() {
            return {
                update: false,
                pending: false,
                dirtyItem: {},
                borrowedAtOptions: {
                    allowInput: true,
                    enableTime: false,
                    maxDate: new Date()
                },
                returnAtOptions: {
                    allowInput: true,
                    enableTime: false,
                    // FIXME: find a way to do this
                    //midDate: this.createdAtOptions.maxDate
                },
                imageUpload: {
                    image: '', name: '', file: null
                },
            };
        },
        computed: {
            ...mapGetters(['items', 'areItemsLoaded', 'user']),
            item() {
                // FIXME: maybe show some spinners if items are not loaded
                if (this.areItemsLoaded) {
                    return this.items.data.find(item => item.id === this.id);
                }
                return null;
            },
            itemClasses: function() {
                return {
                    hide: this.hide,
                    'md-primary': this.dirtyItem.type === itemTypes.object,
                    'md-accent': this.dirtyItem.type  === itemTypes.money,
                    'md-warn': this.dirtyItem.type === itemTypes.book,
                    'card-ripple': this.update
                };
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
            borrowerField() {
                if (this.iBorrowed) {
                    return 'borrowed_to';
                }
                return 'borrowed_from';
            },

        },
        filters: {
            itemId: value => '#item-' + value
        },
        methods: {
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
            closeModal() {
                this.$refs['itemDialog'].close();
            },
            startUpdate() {
                this.update = true;
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
            updateOrCommit() {
                if (!this.update) {
                    this.startUpdate();
                } else if (!this.pending) {
                    this.commitUpdate();
                }
            },
            imageChange(imageUpload) {
                this.imageUpload = imageUpload;
            },
            selectedFriend(friend){
                this.dirtyItem[this.borrowerField] = friend;
            },
            directionChanged(direction){
                if(this.borrowerField === direction) {
                    return;
                }
                const swap = Object.assign({}, this.dirtyItem.borrowed_from);
                this.dirtyItem.borrowed_from = Object.assign({}, this.dirtyItem.borrowed_to);
                this.dirtyItem.borrowed_to = swap;
            },
            init() {
                this.dirtyItem = Object.assign({}, this.item);
                this.imageUpload = {
                    image: '',
                    name: '',
                    file: null
                };
                this.update = false;
                this.pending = false;
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
            this.$nextTick(() => {
                this.$refs['itemDialog'].open();
            });
        },
        components: {
            ...ItemWidgets
        }
    }
</script>
<template>
    <md-dialog class="item-dialog" :md-open-from="id | itemId" :md-close-to="id | itemId" v-on:close="closing"
               ref="itemDialog">
        <md-card class="md-primary" :class="itemClasses">
            <md-card-media md-ratio="1:1">
                <item-media @change="imageChange" :image="dirtyItem.image" :update="update"
                            :alt="dirtyItem.name"></item-media>
            </md-card-media>
            <md-card-header class="md-whiteframe md-whiteframe-2dp">
                <md-layout>
                    <div style="flex:1">
                        <md-button @click.native="closeModal" class="md-icon-button">
                            <md-icon>arrow_back</md-icon>
                        </md-button>
                    </div>
                    <div style="flex:10">
                        <item-name :update="update" v-model="dirtyItem.name"></item-name>
                        <md-layout>
                            <item-date :options="borrowedAtOptions" v-model="dirtyItem.borrowed_at"
                                       :update="update" style="flex:2"></item-date>
                            <span style="flex:1"></span>
                            <item-date :options="returnAtOptions" v-model="dirtyItem.return_at"
                                       :update="update" style="flex:2"></item-date>
                            <span style="flex:1"></span>
                            <item-update-button @click="updateOrCommit" :update="update"
                                                :pending="pending"></item-update-button>
                        </md-layout>
                    </div>
                </md-layout>
            </md-card-header>
            <md-card-content>
                <md-list class="custom-list md-triple-line">
                    <item-type v-model="dirtyItem.type" :update="update"></item-type>
                    <item-friend :update="update" :direction="borrowerField" :friend="friend"
                                 @friend="selectedFriend" @direction="directionChanged"
                                :is-owner="dirtyItem.owner_id === user.id"></item-friend>
                    <item-notify :update="update"></item-notify>
                    <item-amount v-model="dirtyItem.amount" :type="dirtyItem.type" :update="update"></item-amount>
                    <item-details v-model="dirtyItem.details" :update="update"></item-details>
                </md-list>
            </md-card-content>
        </md-card>
    </md-dialog>
</template>
<style lang="scss" rel="stylesheet/scss">
    @import '../../VueFlatPickr/theme/material_blue.css';
    @import '../../../sass/_variables.scss';
    @import 'node_modules/sass-material-colors/sass/sass-material-colors';
    @import 'node_modules/vue-material/src/core/stylesheets/variables.scss';

    .item-dialog {
        .md-theme-default.md-card.md-primary .md-card-content .md-input-container {
            &:after {
                height: 1px;
                position: absolute;
                right: 0;
                bottom: 0;
                left: 0;
                background-color: material-color('grey', '400');
                transition: $swift-ease-out;
                content: " ";
            }
            textarea, input, label {
                color: material-color('grey', '600');
            }
            .md-icon:not(.md-icon-delete) {
                color: material-color('grey', '600');
            }
        }
        .md-theme-default.md-card.md-primary .md-card-content .md-input-container.md-input-focused {
            textarea, input {
                color: material-color('grey', '600');
                -webkit-text-fill-color: material-color('grey', '600');
            }
            &:after {
                height: 2px;
                position: absolute;
                right: 0;
                bottom: 0;
                left: 0;
                background-color: material-color('grey', '400');
                transition: $swift-ease-out;
                content: " ";
            }
        }
        .md-dialog {
            max-height: 90%;
        }
        .md-list.md-triple-line .md-list-item .md-list-item-container {
            min-height: auto;
            padding-top: 0;
        }
        .md-card {
            width: 500px;
            max-height: 700px;
            margin: 0;
            .md-card-media {
                overflow: hidden;
                height: 200px;
                min-height: 200px;
                text-align: center;
                &:has(.md-ink-ripple) {
                    cursor: pointer;
                    cursor: hand;
                }
                .md-ink-ripple {
                    cursor: pointer;
                    cursor: hand;
                }
            }
            .md-card-media + .md-card-header {
                padding-top: 16px;
            }
            .md-card-header + .md-card-content {
                background: white;
                color: black;
            }
            .md-card-header {
                padding-left: 8px;
                .md-input-container {
                    margin: 0;
                    padding: 0;
                    min-height: auto;
                    label {
                        top: 3px;
                    }
                }
                .md-input-container.md-input-inline.md-input-focused label {
                    top: 3px;
                }
            }
        }
        .md-card-content {
            /* TODO: scroll the card image up */
            overflow: auto;
            .md-input-container {
                margin-bottom: 15px;
            }
        }
    }
</style>
