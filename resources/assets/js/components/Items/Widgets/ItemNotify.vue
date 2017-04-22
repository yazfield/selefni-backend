
<script>
export default {
    props: ['update'],
    methods: {
        sendEmailNotification: function() {
            this.$emit('email');
        },
        sendWebNotification: function() {
            this.$emit('web');
        }
    },
}
</script>
<template>
    <div>
        <slide-transition>
            <md-subheader v-if="!update">{{ $t('item.notification.notify') }}</md-subheader>
        </slide-transition>

        <slide-transition>
            <md-list-item v-if="!update">
                <md-layout>
                    <md-button @click.native="sendWebNotification" class="md-icon-button">
                        <md-icon>notifications</md-icon>
                    </md-button>
                    <md-button @click.native="sendEmailNotification" class="md-icon-button">
                        <md-icon>email</md-icon>
                    </md-button>
                </md-layout>
            </md-list-item>
        </slide-transition>
    </div>
</template>