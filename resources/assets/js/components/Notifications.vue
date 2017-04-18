<script>
    export default{
        props: ['notifications', 'loading']
    }
</script>

<template>
    <md-menu md-size="6" md-align-trigger md-direction="bottom left" ref="menu">
        <md-button class="md-icon-button" md-menu-trigger>
            <md-icon>notifications</md-icon>
        </md-button>

        <md-menu-content class="notifications-menu">
            <md-layout class="loading" v-if="loading" md-align="center">
                <md-spinner md-indeterminate></md-spinner>
            </md-layout>
            <md-layout v-else-if="!loading" class="notifications-card" :md-gutter="20">
                <div class="notifications-header">
                    <md-button class="md-icon-button">
                        <md-icon>settings</md-icon>
                    </md-button>
                    <span class="md-display-1">Notifications</span>
                </div>
                <div class="notifications-content">
                    <md-list>
                        <md-whiteframe class="new-notification">
                            <md-list-item>
                                <md-avatar>
                                    <img src="https://placeimg.com/40/40/people/1" alt="People">
                                </md-avatar>

                                <div class="md-list-text-container">
                                    <span>Ali Connors</span>
                                    <p>I'll be in your neighborhood doing errands...</p>
                                </div>

                                <md-button class="md-icon-button md-list-action">
                                    <md-icon>close</md-icon>
                                </md-button>

                            </md-list-item>
                        </md-whiteframe>
                        <md-whiteframe>
                            <md-list-item>
                                <md-avatar>
                                    <img src="https://placeimg.com/40/40/people/1" alt="People">
                                </md-avatar>

                                <div class="md-list-text-container">
                                    <span>Ali Connors</span>
                                    <p>I'll be in your neighborhood doing errands...</p>
                                </div>

                                <md-button class="md-icon-button md-list-action">
                                    <md-icon>close</md-icon>
                                </md-button>

                            </md-list-item>
                        </md-whiteframe>
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
        /*padding: 1.5 %;*/

        .md-display-1 {
            font-size: 20px;
        }

    }
    .notifications-content .md-whiteframe {
        margin: 5px 10px;
        border-radius: 2px;
    }

    .notifications-menu.md-menu-content {
        .md-list {
            background: $grey-100;
        }

    }
    .new-notification {
        background: white;
    }
</style>