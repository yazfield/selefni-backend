export const itemFieldModel = {
    props: ['update', 'value'],
    data() {
        return {
            internalValue: ''
        };
    },
    watch: {
        internalValue: function(value) {
            this.$emit('input', value);
        },
        value: function(value) {
            this.internalValue = value;
        }
    },
    created() {
        this.internalValue = this.value;
    }
};