<script>
    import {mapGetters} from 'vuex';
    import * as apiConstants from '../../api/constants';
    import * as ItemWidgets from './Widgets';
    import {itemTypes} from '../../constants';
    import {includes} from 'lodash';

    export default {
        name: 'Item',
        props: ['id'],
        data() {
            return {
                dirtyItem: {
                    id: '',
                    name: '',
                    owner_id: '',
                    amount: null,
                    type: '',
                    image: '',
                    details: '',
                    borrowed_from: null,
                    borrowed_to: null,
                    borrowed_at: '',
                    return_at: '',
                    returned_at: '',
                    created_at: '',
                    updated_at: '',
                },
                imageUpload: {
                    file: null, image: '', name: ''
                },
                pending: false,
                persistModal: true,
                showModal: false,
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
                if(!this.dirtyItem.borrowed_to) {
                    return null;
                }
                if (this.dirtyItem.borrowed_to.id !== this.user.id) {
                    return this.dirtyItem.borrowed_to;
                }
                return this.dirtyItem.borrowed_from;
            },
            iBorrowed() {
                return this.dirtyItem.borrowed_to && this.dirtyItem.borrowed_to.id !== this.user.id;
            },
           /* isNew() {
                return !!this.id;
            },*/
            updating() {
                return !this.isNew && this.update;
            },
            item() {
                // FIXME: maybe show some spinners if items are not loaded
                if (/*!this.isNew && */this.areItemsLoaded) {
                    return this.items.data.find(item => item.id === this.id);
                }
                return null;
            },
            itemClasses() {
                // FIXME: these shold be props
                return {
                    hide: this.hide,
                    'cyan lighten-2 white--text': includes(itemTypes, this.item.type),
                    'card-ripple': this.update
                };
            },
            updateClasses() {
                return {'pt-4': this.update};
            }
        },
        components: {
            ...ItemWidgets
        },
        watch: {
            showModal(value) {
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
                this.showModal = false;
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
                this.persistModal = true;
                this.showModal = true;
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
                    this.persistModal = false;
                }, 500);
            },
            selectedFriend(friend){
                console.log('selected', friend);
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
    <v-dialog width="500" v-model="showModal" :persistent="persistModal" class="item-dialog">
        <v-card :class="itemClasses" class="dialog-card" style="/*overflow: auto;*/">
            <v-card-row class="card-media" style="height: 200px;">
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
                        <item-date v-model="dirtyItem.borrowed_at" :update="update" style="flex: 1"
                                   class="header-date mr-3" :label="$t('item.borrowed_date')"></item-date>
                        <item-date v-model="dirtyItem.return_at" :update="update" style="flex: 1"
                                   class="header-date" :label="$t('item.return_date')"></item-date>
                        <item-date-delay v-if="!update" :date="dirtyItem.return_at"
                                         :late-date="dirtyItem.returned_at"></item-date-delay>
                        <span style="flex: 1"></span>
                    </v-row>
                </v-container>
                <item-update-button @click="updateOrCommit" :update="update"
                                    :pending="pending"></item-update-button>
            </v-card-row>
            <v-card-row class="card-content">
                <v-list three-line subheader>
                    <item-type v-model="dirtyItem.type" :update="update" :class="updateClasses"></item-type>
                    <item-friend :update="update" :direction="borrowerField" :friend="friend"
                                 @friend="selectedFriend" @direction="directionChanged"
                                 :is-owner="dirtyItem.owner_id === user.id" :class="updateClasses"></item-friend>
                    <item-notify :update="update"></item-notify>
                    <item-amount v-model="dirtyItem.amount" :type="dirtyItem.type" :update="update" :class="updateClasses"></item-amount>
                    <item-details v-model="dirtyItem.details" :update="update"
                                  class="grey--text text--darken-2" :class="updateClasses"></item-details>
                </v-list>
            </v-card-row>
        </v-card>
    </v-dialog>
</template>
<style lang="scss">
    .list__item {
        text-align: left;
    }
    .header-date {
        z-index: 3; width: 35%;
        .input-group--text-field.input-group--prepend-icon .input-group__details {
            margin-left: 34px;
        }
        .input-group--text-field.input-group--prepend-icon label {
            margin-left: 34px;
        }
    }

    .btn.floating-button {
        position: absolute;
        right: 15px;
        margin-top: 8%;
        z-index: 1;
        &.update {
             margin-top: 15%;
        }
    }

    .item-dialog {
        .dialog {
            max-height: 90%;
            /*overflow: hidden;*/
            overflow-x: hidden;
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
            .card-content {
                /*overflow-y: auto;*/
                display: block;
                /*max-height: 250px;*/
            }
        }
    }
</style>
