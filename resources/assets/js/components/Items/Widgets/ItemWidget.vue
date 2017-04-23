<script>
import {itemTypes} from '../../../constants';
import {sendNotification} from '../../../mixins';
export default {
    props: ['item', 'selected', 'hide'],
    mixins: [sendNotification],
    computed: {
        itemClasses: function() {
            return {
                hide: this.hide,
                'md-primary': this.item.type === itemTypes.object,
                'md-accent': this.item.type  === itemTypes.money,
                'md-warn': this.item.type === itemTypes.book,
                'selected': this.selected
            };
        },
        id: function() {
            return 'item-' + this.item.id;
        }
    },
    methods: {
        toggleSelected(e) {
            this.$emit('selected', e);
        },
        clicked() {
            this.$emit('click');
        }
    }
}
</script>
<template>
    <md-card :class="itemClasses" :id="id" :md-with-hover="!selected">
        <md-button @click.native="toggleSelected($event)" class="md-icon-button select-button">
            <md-icon>done</md-icon>
        </md-button>

        <a @click.stop.prevent="clicked">
            <md-card-media md-ratio="1:1">
                <md-ink-ripple @click="clicked"></md-ink-ripple>
                <img :src="item.image" :alt="item.name">
            </md-card-media>
        </a>

        <md-card-header>
            <div class="md-title" @click="clicked">{{ item.name }}</div>
        </md-card-header>

        <md-card-actions>
            <md-button @click.native="sendWebNotification" class="md-icon-button">
                <md-icon>notifications</md-icon>
            </md-button>
            <md-button @click.native="sendEmailNotification" class="md-icon-button">
                <md-icon>email</md-icon>
            </md-button>
        </md-card-actions>
    </md-card>
</template>
<style lang="scss" scoped>
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
    .cards-wrapper .md-card {
        overflow: visible;
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
    .md-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>