<script>
import {itemFieldModel} from '../../../mixins';
export default {
    mixins: [itemFieldModel]
}
</script>
<template>
    <div>
        <slide-transition>
            <md-subheader v-if="!update">{{ $t('item.details') }}</md-subheader>
        </slide-transition>

        <slide-transition>
            <md-list-item v-if="update">
                <md-input-container>
                    <label>{{ $t('item.details') }}</label>
                    <md-textarea v-model="internalValue"></md-textarea>
                </md-input-container>
            </md-list-item>
            <md-list-item v-else>
                <span>{{ internalValue }}</span>
            </md-list-item>
        </slide-transition>
    </div>
</template>