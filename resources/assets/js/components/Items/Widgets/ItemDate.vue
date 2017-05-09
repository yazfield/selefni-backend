<script>
import {itemFieldModel} from '../../../mixins';
export default {
    name: 'ItemDate',
    mixins: [itemFieldModel],
    props: ['label', 'options'],
    data() {
        return {
            menu: '',
            showValue: ''
        }
    },
    watch: {
        internalValue(value) {
            this.showValue = this.formatDate(value);
        }
    },
    methods: {
        formatDate(value) {
            let date = new Date(value);//.toLocaleString('en-GB').split(', ');
            let params = {
                weekday: this.$t(`date.weekdays[${date.getDay()}]`),
                month: this.$t(`date.months[${date.getMonth()}]`),
                day: date.getDate()
            };
            return this.$t('date.format', params);
        }
    },
    created() {
        if(!this.internalValue) {
            this.internalValue = new Date();
        }
        this.showValue = this.formatDate(this.internalValue);
    },
}
</script>
<template>
    <v-menu v-if="update" lazy :close-on-content-click="false" v-model="menu">
        <v-text-field slot="activator" :label="label" v-model="showValue"
                prepend-icon="event" readonly small dark></v-text-field>
        <v-date-picker v-model="internalValue" no-title>
        </v-date-picker>
    </v-menu>
    <span style="display: flex; flex-direction: column" v-else>
        <span class="grey--text text--lighten-2" style="font-size: 12px">{{ label }}</span>
        <span style="font-size: 16px">{{ formatDate(internalValue) }}</span>
    </span>
</template>

<style lang="scss" scoped>

</style>