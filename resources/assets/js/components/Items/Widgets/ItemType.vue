<script>
import {itemTypes} from '../../../constants';
import {itemFieldModel} from '../../../mixins';
export default {
    name: 'ItemType',
    mixins: [itemFieldModel],
    computed: {
        types() {
            let items = [];
            for (let [k, v] of Object.entries(itemTypes)) {
                items.push({value: v, text: this.$t('item.type.types.' + v)});
            }
            return items;
        },
    }
}
</script>
<template>
    <slide-transition>
        <v-list-item v-if="update">
            <v-select :items="types" v-model="internalValue" :label="$t('item.type.label')" light
                    single-line auto style="flex:1"/>
            <span style="flex:3"> </span>
        </v-list-item>
    </slide-transition>
</template>