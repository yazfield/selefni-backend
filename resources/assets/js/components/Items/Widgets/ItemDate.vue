<script>
import {itemFieldModel} from '../../../mixins';
import VueFlatpickr from '../../../VueFlatPickr/components/index';
export default {
    props: ['options'],
    mixins: [itemFieldModel],
    filters: {
        date: value => {
            // FIXME: put date format in config
            let [date, time] = new Date(value).toLocaleString('en-GB').split(', ');
            return date;
        }
    },
    components: {
        'flatpickr': VueFlatpickr
    }
}
</script>
<template>
    <flatpickr :options="options" v-model="internalValue" v-if="update"></flatpickr>
    <span v-else>{{ internalValue | date}}</span>
</template>