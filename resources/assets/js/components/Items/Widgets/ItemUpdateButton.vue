<script>
export default {
    name: 'ItemUpdateButton',
    props: ['pending', 'update'],
    computed: {
        classes() {
            return {
                warning: this.update,
                'blue-grey': !this.update,
                'update': this.update,
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
    <v-btn :loading="pending" :disabled="pending" :class="classes"
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
</style>