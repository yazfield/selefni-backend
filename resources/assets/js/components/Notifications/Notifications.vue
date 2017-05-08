<script>
    import {mapGetters} from 'vuex';
    import {notificationTypes} from '../../constants';
    export default{
        data() {
            return {
                loading: true
            };
        },
        computed: {
            ...mapGetters(['hasNotifications', 'notifications']),
            notificationItemClasses() {
                return {'new-notification': !notification.read_at};
            }
        },
        methods: {
            dismissNotifications(notification) {
                let ids = [];
                if(typeof notification !== 'string') {
                    for (let i = 0; i < this.notifications.length; i++) {
                        ids.push(this.notifications[i].id);
                    }
                } else {
                    ids.push(notification);
                }
                console.log('dispatch', ids);
                this.$store.dispatch('dismissNotifications', ids);
            },
            notificationText(notification) {
                if(notification.type === notificationTypes.item.amountChanged) {
                    let text = `<strong class="grey--text text--darken-2">${notification.data.from.name}</strong>`;
                    text += ' — Changed amount from ';
                    text += `<strong>${notification.data.item.old_amount}</strong> `;
                    text += `to <strong>${notification.data.item.new_amount}</strong>`;
                    return text;
                }
            },
            setNotificationsRead() {
                let ids = [];
                for(let i = 0; i < this.notifications.length; i++) {
                    if(this.notifications[i].read_at) {
                        continue;
                    }
                    ids.push(this.notifications[i].id);
                }
                if(ids.length) {
                    this.$store.dispatch('setNotificationsRead', ids);
                }
            }
        },
        mounted () {
            const authHeader = this.$store.getters.getToken.getAuthorizationHeader();
            const userId = this.$store.getters.user.id;
            this.$store.dispatch('initNotifications', {authHeader, userId});
        }
    }
</script>

<template>
    <v-menu class="notifications-card" offset-y :nudge-right="-30" origin="center center"
            :closeOnContentClick="false">
        <v-btn icon dark @click.native="setNotificationsRead" slot="activator">
            <v-icon>notifications</v-icon>
        </v-btn>
        <v-container fluid="fluid" class="notifications-content grey lighten-4 pa-3">
            <div class="notifications-header">
                <v-btn icon class="grey--text" v-tooltip:bottom="{ html: $t('notifications.settings') }">
                    <v-icon>settings</v-icon>
                </v-btn>
                <span class="title grey--text">{{ $t('notifications.header') }}</span>
                <v-btn icon v-tooltip:bottom="{ html: $t('notifications.dismiss_all') }"
                       @click.native="dismissNotifications" class="grey--text dismiss-all">
                    <v-icon>clear_all</v-icon>
                </v-btn>
            </div>
            <v-list three-line>
                <transition-group name="list" tag="div" class=""
                      enter-active-class="animated slideInLeft"
                      leave-active-class="animated speed-animation slideOutLeft">
                    <v-list-item  v-for="notification in notifications" :key="notification.id"
                                  :class="notificationItemClasses"
                                  class="notification-item elevation-1">
                        <v-list-tile avatar>
                            <v-list-tile-avatar>
                                <img :src="notification.data.from.avatar" />
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title v-html="notification.data.item.name" />
                                <v-list-tile-sub-title v-html="notificationText(notification)" />
                            </v-list-tile-content>
                            <v-list-tile-action>
                                <v-btn icon ripple v-tooltip:bottom="{ html: $t('notifications.dismiss') }"
                                       @click.native="dismissNotifications(notification.id)">
                                    <v-icon class="grey--text">close</v-icon>
                                </v-btn>
                            </v-list-tile-action>
                        </v-list-tile>
                    </v-list-item>
                </transition-group>
                <transition enter-active-class="animated fadeIn"
                            leave-active-class="animated speed-animation fadeOut" mode="out-in">
                    <v-list-item class="no-notifications" v-if="!hasNotifications">
                        <p class="grey--text">{{ $t('notifications.none') }}</p>
                    </v-list-item>
                </transition>
            </v-list>
        </v-container>
    </v-menu>
</template>
<style lang="scss" scoped>
    .notifications-card {
        flex-direction: column;
    }
    .notifications-content {
        min-height: 300px;
        width: 400px;
    }

    .no-notifications {
        margin-top: 100px;
        p {
            text-align: center;
            width: 100%;
        }
    }

    .new-notification {
        background: white;
    }

    .dismiss-all {
        float: right;
    }

    .notification-item {
        border-radius: 3px;
    }
</style>