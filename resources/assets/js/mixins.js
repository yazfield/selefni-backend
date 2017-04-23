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

export const sendNotification = {
    methods: {
        sendEmailNotification: function() {
            // FIXME: should i send an action here?y
            this.$emit('emailNotification');
        },
        sendWebNotification: function() {
            this.$emit('webNotification');
        }
    }
};