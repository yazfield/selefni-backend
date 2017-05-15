<script>
    import {mapGetters} from 'vuex';
    import * as apiConstants from '../../api/constants';
    import * as ItemWidgets from './Widgets';
    import {itemTypes} from '../../constants';
    import {includes} from 'lodash';

    // FIXME: should this be here?
    const itemStates = {show: 'show', edit: 'edit'};

    export default {
        name: 'Item',
        props: ['id'],
        data() {
            return {
                dirtyItem: {
                    id: '',
                    name: '',
                    owner_id: null, // user id
                    amount: null,
                    type: '',
                    image: '',
                    details: '',
                    borrowed_from: this.user,
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
                modal: {
                    show: false
                },
                state: itemStates.show, // show/edit
            };
        },
        computed: {
            ...mapGetters(['areItemsLoaded', 'items', 'user']),
            // which fields has the friend user object and not currently authenticated user
            friendField() {
                if (this.isBorrowing) {
                    return 'borrowed_to';
                }
                return 'borrowed_from';
            },
            friend() {
                if(! (this.dirtyItem.borrowed_to && this.dirtyItem.borrowed_from)) {
                    return null;
                }
                if (this.dirtyItem.borrowed_to.id !== this.user.id) {
                    return this.dirtyItem.borrowed_to;
                }
                return this.dirtyItem.borrowed_from;
            },
            isBorrowing() {
                return this.dirtyItem.borrowed_to && this.dirtyItem.borrowed_to.id !== this.user.id;
            },
            isBorrower() {
                return !this.isBorrowing;
            },
            isOwner() {
                if (this.areItemsLoaded) {
                    return this.item.owner_id === this.user.id
                }
                return null;
            },
            item() {
                // FIXME: maybe show some spinners if items are not loaded
                if (this.areItemsLoaded) {
                    return this.items.data.find(item => item.id === this.id);
                }
                return null;
            },
            itemClasses() {
                // FIXME: these shuold be props maybe
                return {
                    hide: this.hide,
                    'cyan lighten-2 white--text': includes(itemTypes, this.item.type),
                    'card-ripple': this.isInEditState
                };
            },
            isInEditState() {
                return this.state === itemStates.edit;
            },
            updateClasses() {
                return {'pt-4': this.isInEditState};
            },
            isInShowState() {
                return this.state === itemStates.show;
            },
        },
        components: {
            ...ItemWidgets
        },
        watch: {
            'modal.show': function(value) {
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
                this.modal.show = false;
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
                        this.state = itemStates.show;
                        this.pending = false;
                    })
                    .catch(() => {
                        this.init();
                        // TODO: show toast
                    });
            },
            directionChanged(direction){
                if(this.friendField === direction) {
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
                this.dirtyItem = Object.assign({}, this.item);
                this.imageUpload = {
                    image: '',
                    name: '',
                    file: null
                };
                this.state = itemStates.show;
                this.pending = false;
                this.modal.show = true;
            },
            selectedFriend(friend){
                this.dirtyItem[this.friendField] = friend;
            },
            setShowState(){
                this.state = itemStates.show;
            },
            setEditState(){
                this.state = itemStates.edit;
            },
            startEdit() {
                this.setEditState();
            },
            toggleState() {
                if (this.isInShowState) {
                    this.setEditState();
                } else {
                    this.setShowState();
                }
            },
            updateOrCommit() {
                if (!this.isInEditState) {
                    this.startEdit();
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
        }
    }
</script>
<template>
    <v-dialog width="500" v-model="modal.show" class="item-dialog">
        <v-card :class="itemClasses" class="dialog-card" style="/*overflow: auto;*/">
            <v-card-row class="card-media" style="height: 200px;">
                <item-media @change="imageChange" :image="dirtyItem.image" :update="isInEditState"
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
                        <v-col xs11><item-name :update="isInEditState" v-model="dirtyItem.name"></item-name></v-col>
                    </v-row>
                    <v-row class="pl-4">
                        <item-date v-model="dirtyItem.borrowed_at" :update="isInEditState" style="flex: 1"
                                   class="header-date mr-3" :label="$t('item.borrowed_date')"></item-date>
                        <item-date v-model="dirtyItem.return_at" :update="isInEditState" style="flex: 1"
                                   class="header-date" :label="$t('item.return_date')"></item-date>
                        <item-date-delay v-if="!isInEditState" :date="dirtyItem.return_at"
                                         :late-date="dirtyItem.returned_at"></item-date-delay>
                        <span style="flex: 1"></span>
                    </v-row>
                </v-container>
                <item-update-button @click="updateOrCommit" :update="isInEditState"
                                    :pending="pending"></item-update-button>
            </v-card-row>
            <v-card-row class="card-content">
                <v-list three-line subheader>
                    <item-type v-model="dirtyItem.type" :update="isInEditState" :class="updateClasses"></item-type>
                    <item-friend :update="isInEditState" :direction="friendField" :friend="friend"
                                 @friend="selectedFriend" @direction="directionChanged"
                                 :is-owner="dirtyItem.owner_id === user.id" :class="updateClasses"></item-friend>
                    <item-notify :update="isInEditState"></item-notify>
                    <item-amount v-model="dirtyItem.amount" :type="dirtyItem.type" :update="isInEditState" :class="updateClasses"></item-amount>
                    <item-details v-model="dirtyItem.details" :update="isInEditState"
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
