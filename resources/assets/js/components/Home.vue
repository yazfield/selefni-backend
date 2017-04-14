<template>
    <md-menu md-size="4" md-direction="top right" ref="menu">
        <md-button class="md-icon-button" md-menu-trigger>
            <md-icon>contacts</md-icon>
        </md-button>

        <md-menu-content>
            <div class="author-card">
                <md-avatar class="md-large">
                    <img src="https://vuematerial.github.io/assets/marcosmoura.jpg" alt="Marcos Moura">
                </md-avatar>

                <div class="author-card-info">
                    <span>Marcos Moura</span>
                    <div class="author-card-links">
                        <md-button class="md-icon-button" md-menu-trigger>
                            <md-icon>contacts</md-icon>
                        </md-button>
                    </div>
                </div>
            </div>
        </md-menu-content>
    </md-menu>
</template>