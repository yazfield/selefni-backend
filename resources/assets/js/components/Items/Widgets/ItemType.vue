<script>
import {itemTypes} from '../../../constants';
import {itemFieldModel} from '../../../mixins';
export default {
    mixins: [itemFieldModel],
    computed: {
        types: function() {
            return itemTypes;
        },
    }
}
</script>
<template>
    <slide-transition>
        <md-list-item v-if="update">
            <md-input-container style="flex:1">
                <label >{{ $t('item.type.label') }}</label>
                <md-select v-model="internalValue">
                    <md-option v-for="(type, key) in types" :value="key" v-once>{{ $t('item.type.types.' + type) }}</md-option>
                </md-select>
            </md-input-container>
            <span style="flex:1"> </span>
        </md-list-item>
    </slide-transition>
</template>