<script>
import {itemFieldModel} from '../../../mixins';
export default {
    mixins: [itemFieldModel]
}
</script>
<template>
    <div class="md-title" v-if="update">
        <md-input-container md-inline>
            <label>{{ $t('item.name.label') }}</label>
            <md-input v-model="internalValue"></md-input>
        </md-input-container>
    </div>
    <div class="md-title" v-else>{{ internalValue }}</div>
</template>

<style lang="scss">
    .item-dialog .md-card .md-title {
        margin-bottom: 8px;
    }
</style>