<script>
export default {
    props: ['pending', 'update'],
    computed: {
        colorClass: function() {
            return { orange: this.update };
        }
    },
    methods: {
        click: function(e) {
            this.$emit('click', e);
        }
    }
}
</script>
<template>
    <md-button @click.native="click" class="md-fab md-fab-bottom-right"
               :class="colorClass">
        <md-spinner class="md-warn md-icon" v-if="pending" md-indeterminate :md-size="24"
                    :md-stroke="4.4"></md-spinner>
        <transition enter-active-class="animated speed-animation rotateIn"
                    leave-active-class="animated speed-animation rotateOut">
            <md-icon v-if="!pending && update">done</md-icon>
        </transition>
        <transition enter-active-class="animated speed-animation rotateIn"
                    leave-active-class="animated speed-animation rotateOut">
            <md-icon v-if="!pending && !update">edit</md-icon>
        </transition>
    </md-button>
</template>

<style lang="scss">
    @import 'node_modules/sass-material-colors/sass/sass-material-colors';
    .md-theme-default.md-button:not([disabled]).md-fab.orange {
        background-color: material-color('orange', 'a200');
        &:hover {
            background-color: material-color('orange', '600');
        }
    }
    .item-dialog{
        .md-card {
            .md-fab.md-fab-bottom-right {
                bottom: -25px;
                .spinner {
                    position: absolute;
                }
            }
        }
    }

</style>