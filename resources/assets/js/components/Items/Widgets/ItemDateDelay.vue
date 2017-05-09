<script>
    import * as moment from 'moment';
    export default {
        name: 'ItemDateDelay',
        props: {
            date: {
                required: true,
                validator(value) {
                    return value instanceof Date || Date.parse(value)
                }
            },
            lateDate: {
                required: true
            }
        },
        computed: {
            internalDate() {
                return moment(this.date);
            },
            internalLateDate() {
                return moment(this.lateDate || new Date);
            },
            difference() {
                return this.internalDate.from(this.internalLateDate);
            },
            vuableDifference() {
                return this.internalDate.diff(this.internalLateDate, 'years')
                    || this.internalDate.diff(this.internalLateDate, 'months')
                    || this.internalDate.diff(this.internalLateDate, 'days');
            }
        },
    }
</script>
<template>
    <v-chip class="red white--text" small v-if="vuableDifference"
            v-tooltip:bottom="{ html: $t('item.late') }">
        {{ difference }}
    </v-chip>
</template>