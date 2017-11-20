<script>
    import Select from '../../../../node_modules/vuetify/src/components/selects/Select';
    export default {
        mixins: [Select],
        watch: {
            searchValue (value) {
                this.$emit('search', value);
            }
        },
        computed: {
            filteredItems () {
                const items = !this.autocomplete && this.searchValue
                    ? this.filterSearch()
                    : this.items;
                console.log('filtered', items);
                return !this.auto ? items.slice(0, this.lastItem) : items
            }
        }
    }
</script>