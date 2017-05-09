<script>
import {itemFieldModel} from '../../../mixins';
export default {
    name: 'ItemName',
    mixins: [itemFieldModel]
}
</script>
<template>
    <v-text-field v-if="update" dark name="item-name" v-model="internalValue" single-line
                  :label="$t('item.name.label')" class="mt-2 mb-0" style="width: 80%"></v-text-field>
    <div v-else>
        <v-card-title class="pb-0 pl-0 pt-2">{{ internalValue }}</v-card-title>
    </div>
</template>