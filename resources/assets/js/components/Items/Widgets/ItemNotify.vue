
<script>
import {sendNotification} from '../../../mixins';
export default {
    name: 'ItemNotify',
    mixins: [sendNotification],
    props: ['update'],
}
</script>
<template>
    <div>
        <slide-transition>
            <v-subheader v-if="!update" v-text="$t('item.notification.notify')"/>
        </slide-transition>

        <slide-transition>
            <v-list-item v-if="!update">
                <v-container style="text-align: left;" class="pl-3">
                    <v-btn @click.native="sendWebNotification" icon small flat>
                        <v-icon>notifications</v-icon>
                    </v-btn>
                    <v-btn @click.native="sendEmailNotification" icon small flat>
                        <v-icon>email</v-icon>
                    </v-btn>
                </v-container>
            </v-list-item>
        </slide-transition>
    </div>
</template>