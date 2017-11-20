<script>
    import * as moment from 'moment';
    export default {
        name: 'ItemDateDelay',
        props: {
            date: {
                required: true
            },
            lateDate: {
                required: true
            }
        },
        computed: {
            classes() {
                return {
                    'light-green': this.differenceDays > 0,
                    'red': this.differenceDays < 0,
                    'amber': this.differenceDays === 0
                }
            },
            internalDate() {
                return moment(this.date || new Date);
            },
            internalLateDate() {
                return moment(this.lateDate || new Date);
            },
            difference() {
                return this.internalDate.from(this.internalLateDate);
            },
            differenceDays() {
                return this.internalDate.diff(this.internalLateDate, 'days');
            }
        },
    }
</script>
<template>
    <v-chip class="white--text" :class="classes" small v-tooltip:bottom="{ html: $t('item.late') }">
        {{ difference }}
    </v-chip>
</template>