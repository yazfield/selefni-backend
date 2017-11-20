<script>
import {itemFieldModel} from '../../../mixins';
import {itemTypes} from '../../../constants';
export default {
    name: 'ItemAmount',
    mixins: [itemFieldModel],
    props: ['type'],
    computed: {
        hasAmount() {
            return this.internalValue && this.internalValue > 0;
        },
        // FIXME: maybe put these methods inside the store
        isMoney() {
            return this.type === this.types.money;
        },
        isObject() {
            return this.type === this.types.object;
        },
        showHeader() {
            return !this.update && (this.isMoney || (this.hasAmount && this.isObject));
        },
        types() {
            return itemTypes;
        },
    },
}
</script>
<template>
    <div>
        <slide-transition>
            <v-subheader v-if="showHeader">{{ $t('item.amount.header') }}</v-subheader>
        </slide-transition>

        <slide-transition>
            <v-list-item v-if="!update && isMoney">
                <v-list-tile tag="div">
                    <v-list-tile-action>
                        <v-icon class="indigo--text">attach_money</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>{{ internalValue }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list-item>
        </slide-transition>
        <slide-transition>
            <v-list-item v-if="!update && hasAmount && isObject">
                <v-list-tile tag="div">
                    <v-list-tile-action>
                        <v-icon class="indigo--text">layers</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>{{ internalValue }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list-item>
        </slide-transition>

        <slide-transition>
            <v-list-item v-if="update && isMoney">
                <v-container>
                    <v-row>
                        <v-col xs6>
                            <v-text-field v-model="internalValue" :label="$t('item.amount.label')"
                                prepend-icon="attach_money"></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
            </v-list-item>
        </slide-transition>

        <slide-transition>
            <v-list-item v-if="update && isObject">
                <v-container>
                    <v-row>
                        <v-col xs6>
                            <v-text-field v-model="internalValue" :label="$t('item.amount.label')"
                                  prepend-icon="layers" class="pl-3"></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
            </v-list-item>
        </slide-transition>
    </div>
</template>