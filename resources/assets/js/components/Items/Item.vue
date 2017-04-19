<script>
    import {mapGetters} from 'vuex';
    import VueFlatpickr from '../../VueFlatPickr/components/index';
    import * as apiConstants from '../../api/constants';
    import Autocomplete from '../../Autocomplete/vue-autocomplete.vue';
    export default {
        props: ['id'],
        data(){
            return {
                borrowedOrLent: '',
                update: false,
                pending: false,
                dirtyItem: {},
                createdAtOptions: {
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
                inAnimation: 'animated fadeInDown',
                outAnimation: 'animated speed-animation fadeOutLeft',
                imageUpload: {
                    image: '',
                    name: '',
                    file: null
                },
            };
        },
        computed: {
            ...mapGetters(['items', 'areItemsLoaded', 'user']),
            item() {
                if (this.areItemsLoaded) {
                    return this.items.data.find(item => item.id === this.id);
                }
                return null;
            },
            theOtherGuy() {
                console.log('other guy', this.item.borrowed_to.id, this.user.id);
                if (this.item.borrowed_to.id !== this.user.id) {
                    return this.item.borrowed_to;
                }
                return this.item.borrowed_from;
            },
            isMoney() {
                return this.item.type === 'money';
            },
            isObject() {
                return this.item.type === 'object';
            },
            hasAmount() {
                return this.item.amount && this.item.amount > 0;
            },
            iBorrowed() {
                return this.item.borrowed_to.id  !== this.user.id;
            },
            borrowerField() {
                if (this.iBorrowed) {
                    return 'borrowed_to';
                }
                return 'borrowed_from';
            },
            borrowerHeader() {
                if (this.iBorrowed) {
                    return 'Borrowed to';
                }
                return 'Borrowed from';
            }

        },
        filters: {
            itemId: value => '#item-' + value,
            date: value => {
                let [date, time] = new Date(value).toLocaleString('en-GB').split(', ');
                return date;
            }
        },
        methods: {
            closing() {
                setTimeout(() => {
                    this.$store.dispatch('hideItem');
                }, 250);
                // had to do this because otherwise routing would happen before md-dialog closing animation
                // which produced a bug
                setTimeout(() => {
                    // FIXME: what if there is no precedent page?
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
                //borrowedOrLent
                console.log(this.dirtyItem.borrowed_from.id, this.dirtyItem.borrowed_to.id);
                if(this.borrowedOrLent !== this.borrowerField) {
                    console.log(this.borrowedOrLent, this.borrowerField);
                    const swap = Object.assign({}, this.dirtyItem.borrowed_from);
                    this.dirtyItem.borrowed_from = Object.assign({}, this.dirtyItem.borrowed_to);
                    this.dirtyItem.borrowed_to = swap;
                }
                console.log(this.dirtyItem.borrowed_from.id, this.dirtyItem.borrowed_to.id);
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
                        this.pending = false;
                        this.dirtyItem = Object.assign({}, this.item);
                        // TODO: show toast
                        this.update = false;
                    });
            },
            updateOrCommit() {
                if (!this.update) {
                    this.startUpdate();
                } else if (!this.pending) {
                    this.commitUpdate();
                }
            },
            imageChange(e) {
                e.preventDefault();
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length) {
                    return;
                }
                this.createImage(files[0]);
            },
            createImage(file){
                this.imageUpload.file = file;
                let reader = new FileReader();
                this.imageUpload.name = file.name;
                let me = this;
                reader.onload = function (e) {
                    me.imageUpload.image = e.target.result;
                    return me.dirtyItem.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            chooseImage() {
                this.$refs.itemImage.click();
            },
            selectedFriend(obj){
                console.log('selected', obj);
                this.dirtyItem[this.borrowerField] = obj;
            },
        },
        activated() {
            if (this.item === null) {
                this.$router.replace({name: 'dashboard'});
            }
            this.dirtyItem = Object.assign({}, this.item);
            this.update = false;
            this.pending = false;
            this.borrowedOrLent = this.borrowerField;
            this.$nextTick(() => {
                this.$refs['itemDialog'].open();
            });
        },
        components: {
            'Flatpickr': VueFlatpickr,
            'Autocomplete': Autocomplete,
        }
    }
</script>
<template>
    <md-dialog class="item-dialog" :md-open-from="id | itemId" :md-close-to="id | itemId" v-on:close="closing"
               ref="itemDialog">
        <md-card class="md-primary" :class="{'card-ripple': update}">
            <md-card-media md-ratio="1:1">
                <input v-show="false" ref="itemImage" type="file" name="image" v-on:change="imageChange">
                <transition enter-active-class="animate speed-animation fadeIn"
                            leave-active-class="animate speed-animation fadeOut">
                    <md-button class="image-button" @click.native="chooseImage"
                               v-if="update">
                        <md-icon>add_a_photo</md-icon>
                    </md-button>
                </transition>
                <transition enter-active-class="animate speed-animation fadeIn"
                            leave-active-class="animate speed-animation fadeOut">
                    <img v-show="!update" :src="item.image" :alt="item.name"/>
                </transition>
                <transition enter-active-class="animate speed-animation fadeIn"
                            leave-active-class="animate speed-animation fadeOut">
                    <img v-show="update" :src="dirtyItem.image" :alt="dirtyItem.name"/>
                </transition>
            </md-card-media>

            <md-card-header class="md-whiteframe md-whiteframe-2dp">
                <md-layout>
                    <div style="flex:1">
                        <md-button @click.native="closeModal" class="md-icon-button">
                            <md-icon>arrow_back</md-icon>
                        </md-button>
                    </div>
                    <div style="flex:10">
                        <div class="md-title" v-if="!update">{{ item.name }}</div>
                        <div class="md-title" v-if="update">
                            <md-input-container md-inline>
                                <label>Give me a name</label>
                                <md-input v-model="dirtyItem.name"></md-input>
                            </md-input-container>
                        </div>
                        <md-layout>
                            <span style="flex:2" v-if="!update">{{ item.return_at | date}}</span>
                            <transition enter-active-class="animate speed-animation fadeInUp">
                                <Flatpickr style="flex:2" :options="createdAtOptions" v-model="dirtyItem.created_at"
                                           v-if="update"/>
                            </transition>
                            <span style="flex:1"></span>
                            <span style="flex:2" v-if="!update">{{ item.return_at | date}}</span>
                            <transition enter-active-class="animate speed-animation fadeInUp">
                                <Flatpickr style="flex:2" :options="returnAtOptions" v-model="dirtyItem.return_at"
                                           v-if="update"/>
                            </transition>
                            <span style="flex:1"></span>

                            <md-button @click.native="updateOrCommit" class="md-fab md-fab-bottom-right"
                                       :class="{ orange: update }">
                                <transition enter-active-class="animate speed-animation rotateIn"
                                            leave-active-class="animate speed-animation rotateOut">
                                    <md-icon v-if="!pending && !update">edit</md-icon>
                                </transition>
                                <transition enter-active-class="animate speed-animation rotateIn"
                                            leave-active-class="animate speed-animation rotateOut">
                                    <md-icon v-if="!pending && update">done</md-icon>
                                </transition>
                                <md-spinner class="md-warn md-icon" v-if="pending" md-indeterminate :md-size="24"
                                            :md-stroke="4.4"></md-spinner>
                            </md-button>
                        </md-layout>
                    </div>
                </md-layout>

            </md-card-header>

            <md-card-content>
                <md-list class="custom-list md-triple-line">
                    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation">
                        <md-list-item v-if="update">
                            <md-input-container style="flex:1">
                                <label>Item type</label>
                                <md-select v-model="dirtyItem.type">
                                    <md-option value="object">Object</md-option>
                                    <md-option value="money">Money</md-option>
                                    <md-option value="book">Book</md-option>
                                </md-select>
                            </md-input-container>
                            <span style="flex:1"> </span>
                        </md-list-item>
                    </transition>
                    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation">
                        <md-subheader v-if="!update">{{ borrowerHeader }}</md-subheader>
                    </transition>
                    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation">
                        <md-list-item v-if="!update">
                            <md-avatar>
                                <img :src="theOtherGuy.avatar " :alt="theOtherGuy.name">
                            </md-avatar>
                            <div class="md-list-text-container">
                                <span>{{ theOtherGuy.name }}</span>
                                <p>{{ theOtherGuy.phone_number }}</p>
                                <p>{{ theOtherGuy.email }}</p>
                            </div>
                        </md-list-item>
                    </transition>

                    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation">
                        <md-list-item v-if="update">
                            <md-input-container  style="flex:4">
                                <label>Direction</label>
                                <md-select v-model="borrowedOrLent">
                                    <md-option value="borrowed_from">Borrowed from</md-option>
                                    <md-option value="borrowed_to">Borrowed to</md-option>
                                </md-select>
                            </md-input-container>
                            <span style="flex:1"></span>
                            <autocomplete
                                    :initValue="this.dirtyItem[this.borrowerField].name"
                                    url="/api/users/searchFriends"
                                    anchor="name"
                                    subtitle="email"
                                    label="Search"
                                    :on-select="selectedFriend"
                                    :wrapInput="true"
                                    :inputWrapperClass="'md-input-container'"
                                    :inputClass="'md-input'" style="flex:10">
                            </autocomplete>
                            <span style="flex:1"> </span>
                        </md-list-item>
                    </transition>

                    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation">
                        <md-subheader v-if="!update">Notify</md-subheader>
                    </transition>

                    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation">
                        <md-list-item v-if="!update">
                            <md-layout>
                                <md-button class="md-icon-button">
                                    <md-icon>notifications</md-icon>
                                </md-button>
                                <md-button class="md-icon-button">
                                    <md-icon>email</md-icon>
                                </md-button>
                            </md-layout>
                        </md-list-item>
                    </transition>

                    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation">
                        <md-subheader v-if="!update && (isMoney || (hasAmount && isObject))">More information
                        </md-subheader>
                    </transition>

                    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation">
                        <md-list-item v-if="!update && isMoney">
                            <md-icon>attach_money</md-icon>
                            <span>{{ item.amount }}</span>
                        </md-list-item>
                    </transition>
                    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation">
                        <md-list-item v-if="!update && hasAmount && isObject">
                            <md-icon>layers</md-icon>
                            <span>{{ item.amount }}</span>
                        </md-list-item>
                    </transition>

                    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation">
                        <md-list-item v-if="update && isMoney">
                            <md-input-container style="flex:1">
                                <md-icon>attach_money</md-icon>
                                <label>Amount</label>
                                <md-input v-model="item.amount"></md-input>
                            </md-input-container>
                            <span style="flex:1"></span>
                        </md-list-item>
                    </transition>

                    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation">
                        <md-list-item v-if="update && isObject">
                            <md-input-container style="flex:1">
                                <md-icon>layers</md-icon>
                                <label>Amount</label>
                                <md-input type="number" min="0" v-model="item.amount"></md-input>
                            </md-input-container>
                            <span style="flex:1"></span>
                        </md-list-item>
                    </transition>

                    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation">
                        <md-subheader v-if="!update">Details</md-subheader>
                    </transition>

                    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation">
                        <md-list-item v-if="!update">
                            <span>{{ item.details }}</span>
                        </md-list-item>
                    </transition>

                    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation">
                        <md-list-item v-if="update">
                            <md-input-container>
                                <label>Details</label>
                                <md-textarea v-model="dirtyItem.details"></md-textarea>
                            </md-input-container>
                        </md-list-item>
                    </transition>

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
    /*@import '/node_modules/vue2-autocomplete-js/dist/style/vue2-autocomplete.css';*/

    .flatpickr-calendar.open {
        z-index: 20;
    }

    .flatpickr-input {
        border: none;
        border-bottom: 1px solid white;
        background: transparent;
        color: white;
        font-size: 14px;
        font-family: $font-roboto;

    &:focus {
        outline: none;
    }

    }
    .flatpickr-input.active {
        border-bottom: 2px solid white;
        color: rgba(255, 255, 255, .87);
    }

    .md-theme-default.md-button:not([disabled]).md-fab.orange {
        background-color: material-color('orange', 'a200');

    &:hover {
        background-color: material-color('orange', '600');
    }

    }

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

    .md-select {
        /* max-width: 250 px;
         border-bottom: 2 px solid material-color('grey', '400'); */
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
        text-align: center;

    img {
        /*max-height: 200px;*/
    }

    .md-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

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
        /* padding-top: 16 px; */
    }

    .md-fab.md-fab-bottom-right {
        bottom: -25px;

    .spinner {
        position: absolute;
    }

    }
    .md-title {
        margin-bottom: 8px;
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
        /* padding-top: 0; */
        margin-bottom: 15px;
    }

    }

    }

    .md-button.image-button {
        position: absolute;
        color: white;
        background: rgba(0, 0, 0, 0.5);
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        margin: 0;
        z-index: 50;
    }


</style>
