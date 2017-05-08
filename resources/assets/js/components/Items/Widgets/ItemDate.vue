<script>
import {itemFieldModel} from '../../../mixins';
export default {
    name: 'ItemDate',
    mixins: [itemFieldModel],
    props: ['options'],
    data() {
        return {
            menu: ''
        }
    },
    methods: {
        formatDate(value) {
            console.log('format date');
            let date = new Date(value);//.toLocaleString('en-GB').split(', ');
            let params = {
                weekday: this.$t(`date.weekdays[${date.getDay()}]`),
                month: this.$t(`date.months[${date.getMonth()}]`),
                day: date.getDay() + 1
            };
            return this.$t('date.format', params);
        }
    },
    created() {
        if(!this.internalValue) {
            this.internalValue = new Date();
        }
        console.log(this.internalValue);
    },
}
</script>
<template>
    <v-menu v-if="update" lazy :close-on-content-click="false" v-model="menu" style="z-index: 3">
        <v-text-field slot="activator" single-line label="Picker in menu" v-model="internalValue"
                prepend-icon="event" readonly small></v-text-field>
        <v-date-picker v-model="internalValue" no-title :date-format="formatDate">
        </v-date-picker>
    </v-menu>
    <span v-else>{{ formatDate(internalValue) }}</span>
</template>

<style lang="scss" scoped>

</style>