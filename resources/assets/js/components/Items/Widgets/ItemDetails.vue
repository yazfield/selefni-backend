<script>
import {itemFieldModel} from '../../../mixins';
export default {
    name: 'itemDetails',
    mixins: [itemFieldModel]
}
</script>
<template>
    <div>
        <slide-transition>
            <v-subheader v-if="!update">{{ $t('item.details') }}</v-subheader>
        </slide-transition>

        <slide-transition>
            <v-list-item v-if="update">
                <v-text-field :label="$t('item.details')" multi-line v-model="internalValue">
                </v-text-field>
            </v-list-item>
            <v-list-item v-else>
                <span>{{ internalValue }}</span>
            </v-list-item>
        </slide-transition>
    </div>
</template>