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
            <v-list-item>
                <v-container>
                    <v-row>
                        <v-col xs12>
                            <v-text-field v-if="update" :label="$t('item.details')"
                                          multi-line v-model="internalValue"></v-text-field>
                            <span v-else>{{ internalValue }}</span>
                        </v-col>
                    </v-row>
                </v-container>
            </v-list-item>
        </slide-transition>
    </div>
</template>