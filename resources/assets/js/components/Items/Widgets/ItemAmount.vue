<script>
import {itemFieldModel} from '../../../mixins';
import {itemTypes} from '../../../constants';
export default {
    props: ['type'],
    mixins: [itemFieldModel],
    computed: {
        // FIXME: maybe put these methods inside the store
        isMoney() {
            return this.type === this.types.money;
        },
        isObject() {
            return this.type === this.types.object;
        },
        hasAmount() {
            return this.internalValue && this.internalValue > 0;
        },
        showHeader: function () {
            return !this.update && (this.isMoney || (this.hasAmount && this.isObject));
        },
        types: function() {
            return itemTypes;
        },
    },
}
</script>
<template>
    <div>
        <slide-transition>
            <md-subheader v-if="showHeader">{{ $t('item.amount.header') }}</md-subheader>
        </slide-transition>

        <slide-transition>
            <md-list-item v-if="!update && isMoney">
                <md-icon>attach_money</md-icon>
                <span>{{ internalValue }}</span>
            </md-list-item>
        </slide-transition>
        <slide-transition>
            <md-list-item v-if="!update && hasAmount && isObject">
                <md-icon>layers</md-icon>
                <span>{{ internalValue }}</span>
            </md-list-item>
        </slide-transition>

        <slide-transition>
            <md-list-item v-if="update && isMoney">
                <md-input-container style="flex:1">
                    <md-icon>attach_money</md-icon>
                    <label>{{ $t('item.amount.label') }}</label>
                    <md-input v-model="internalValue"></md-input>
                </md-input-container>
                <span style="flex:1"></span>
            </md-list-item>
        </slide-transition>

        <slide-transition>
            <md-list-item v-if="update && isObject">
                <md-input-container style="flex:1">
                    <md-icon>layers</md-icon>
                    <label>{{ $t('item.amount.label') }}</label>
                    <md-input type="number" min="0" v-model="internalValue"></md-input>
                </md-input-container>
                <span style="flex:1"></span>
            </md-list-item>
        </slide-transition>
    </div>
</template>