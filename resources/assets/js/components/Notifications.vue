<script>
    import {mapGetters} from 'vuex';

    import {notificationTypes} from '../constants';
    export default{
        data() {
            return {
                loading: true
            };
        },
        computed: {
            ...mapGetters(['notifications', 'hasNotifications'])
        },
        methods: {
            notificationText(notification) {
                if(notification.type === notificationTypes.item.amountChanged) {
                    let text = ['Changed amount of ' + notification.data.item.name + ' '];
                    let p = `from ${notification.data.item.old_amount} `;
                    p += `to ${notification.data.item.new_amount}`;
                    text.push(p);
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
            },
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
        },
        mounted () {
            const authHeader = this.$store.getters.getToken.getAuthorizationHeader();
            const userId = this.$store.getters.user.id;
            this.$store.dispatch('initNotifications', {authHeader, userId});
        }
    }
</script>

<template>
    <md-menu @open="setNotificationsRead" md-size="7" md-align-trigger :md-offset-x="55" 
             md-direction="bottom left" ref="menu">
        <md-button class="md-icon-button" md-menu-trigger>
            <md-icon>notifications</md-icon>
        </md-button>

        <md-menu-content class="notifications-menu">
            <md-layout class="notifications-card" :md-gutter="20">
                <div class="notifications-header">
                    <md-button class="md-icon-button">
                        <md-icon>settings</md-icon>
                    </md-button>
                    <span class="md-display-1">{{ $t('notifications.header') }}</span>
                    <md-button @click.native="dismissNotifications" class="md-icon-button dismiss-all">
                        <md-icon>clear_all</md-icon>
                        <md-tooltip md-direction="bottom">{{ $t('notifications.dismiss_all') }}</md-tooltip>
                    </md-button>
                </div>
                <div class="notifications-content">
                    <md-list class="md-triple-line">
                        <transition enter-active-class="animated fadeIn"
                                    leave-active-class="animated speed-animation fadeOut" mode="out-in">
                            <md-list-item class="no-notifications" v-if="!hasNotifications">
                                <p class="md-caption">{{ $t('notifications.none') }}</p>
                            </md-list-item>
                        </transition>
                        <transition-group name="list" tag="div" class=""
                                          enter-active-class="animated slideInLeft"
                                          leave-active-class="animated speed-animation slideOutLeft">
                            <md-list-item v-for="notification in notifications" :key="notification.id"
                                          :class="{'new-notification': !notification.read_at}"
                                          class="md-whiteframe md-whiteframe-1dp">
                                <md-avatar>
                                    <img :src="notification.data.from.avatar" :alt="notification.data.from.name">
                                </md-avatar>

                                <div class="md-list-text-container">
                                    <span><strong>{{ notification.data.from.name }}</strong></span>
                                    <p v-for="text in notificationText(notification)">{{ text }}</p>
                                </div>

                                <md-button @click.native="dismissNotifications(notification.id)"
                                           class="md-icon-button md-list-action">
                                    <md-icon>close</md-icon>
                                </md-button>

                            </md-list-item>
                        </transition-group>
                    </md-list>
                </div>
            </md-layout>
        </md-menu-content>
    </md-menu>
</template>
<style lang="scss">
    @import '../../sass/_variables.scss';

    .md-layout.notifications-card {
        flex-direction: column;
    }

    .notifications-header {
        .md-display-1 {
            font-size: 20px;
        }

    }
    .notifications-content {
        min-height: 300px;
        .md-whiteframe {
            margin: 5px 10px;
            border-radius: 2px;
        }
    }

    .no-notifications {
        margin-top: 100px;
        p {
            text-align: center;
            width: 100%;
        }
    }

    .notifications-menu.md-menu-content {
        position: fixed;
        .md-list {
            background: $grey-100;
        }

    }
    .new-notification {
        background: white;
    }

    .dismiss-all {
        float: right;
    }
</style>