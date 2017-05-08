<script>
export default {
    name: 'ItemUpdateButton',
    props: ['pending', 'update'],
    computed: {
        colorClass() {
            return {
                warning: this.update,
                'blue-grey': !this.update
            };
        }
    },
    methods: {
        click(e) {
            this.$emit('click', e);
        }
    }
}
</script>
<template>
    <v-btn :loading="pending" :disabled="pending" :class="colorClass"
           floating="floating" @click.native="click" class="floating-button">
        <transition enter-active-class="animated rotateIn"
                    leave-active-class="animated speed-animation rotateOut" mode="out-in">
            <v-icon v-if="!pending && update">done</v-icon>
        </transition>
        <transition enter-active-class="animated rotateIn"
                    leave-active-class="animated speed-animation rotateOut" mode="out-in">
            <v-icon class="float" v-if="!pending && !update">edit</v-icon>
        </transition>
    </v-btn>
</template>

<style lang="scss" scoped>
    .float {
        position: absolute;
    }
    .btn.floating-button {
        position: absolute;
        right: 15px;
        margin-top: 8%;
        z-index: 1;
    }
</style>