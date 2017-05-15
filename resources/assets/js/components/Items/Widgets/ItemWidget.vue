<script>
import {itemTypes} from '../../../constants';
import {sendNotification} from '../../../mixins';
import {includes} from 'lodash';
export default {
    name: 'ItemWidget',
    mixins: [sendNotification],
    props: ['item', 'hide', 'selected'],
    computed: {
        id() {
            return this.item.id ? 'item-' + this.item.id : '';
        },
        itemClasses() {
            return {
                hide: this.hide,
                'cyan lighten-2 white--text': includes(itemTypes, this.item.type),
                'selected': this.selected
            };
        }
    },
    methods: {
        clicked(e) {
            this.$emit('click', e);
        },
        toggleSelected(e) {
            this.$emit('selected', e);
        }
    }
}
</script>
<template>
    <v-card :id="id" :hover="!selected" :raised="!selected" :class="itemClasses">
        <v-btn icon @click.native="toggleSelected($event)" class="select-button"
               v-tooltip:bottom="{ html: $t('item.select') }">
            <v-icon>done</v-icon>
        </v-btn>
        <v-card-row height="150px" style="overflow: hidden;">
            <v-btn tag="a" href="" ripple flat @click.native.prevent="clicked($event)"
                   style="display: block; height: 100%; padding: 0; margin: 0">
                <img :src="item.image" :alt="item.name" style="max-width: 100%">
            </v-btn>
        </v-card-row>
        <v-card-row>
            <v-card-title>
                <span class="white--text card-title" @click="clicked">{{ item.name }}</span>
            </v-card-title>
        </v-card-row>
        <v-card-row class="mt-0">
            <v-spacer></v-spacer>
            <v-btn small icon @click.native="sendWebNotification"
                   v-tooltip:bottom="{ html: $t('item.notification.notify') }">
                <v-icon class="white--text">notifications</v-icon>
            </v-btn>
            <v-btn small icon @click.native="sendEmailNotification"
                   v-tooltip:bottom="{ html: $t('item.notification.email') }">
                <v-icon class="white--text">email</v-icon>
            </v-btn>
        </v-card-row>
    </v-card>
</template>
<style lang="scss" scoped>
    @import 'node_modules/sass-material-colors/sass/sass-material-colors';
    .btn.select-button {
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
    .cards-wrapper .card {
        &:hover .select-button {
            display: block;
        }
        &.selected {
            .select-button {
                background: material-color('grey', '800');
                display: block;
                top: -14px;
                left: -18px;
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
            &.card {
                 border: 4px solid material-color('grey', '800');
                 border-radius: 5px;
             }
        }
    }
    .hide {
        opacity: 0;
    }
    .card__row {
        /* FIXME: it's not working */
        cursor: pointer;
    }
    .card__title {
        width: 100%;
        height: 100%;
    }
    .card-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>