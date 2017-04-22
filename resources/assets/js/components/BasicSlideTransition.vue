<template>
    <transition :enter-active-class="inAnimation" :leave-active-class="outAnimation"
                mode="out-in">
        <slot></slot>
    </transition>
</template>
<script>
export default {
    props: {
        inAnimation: {
            type: String,
            default: 'animated fadeInDown'
        },
        outAnimation: {
            type: String,
            default: 'animated speed-animation fadeOutLeft'
        }
    }
}
</script>