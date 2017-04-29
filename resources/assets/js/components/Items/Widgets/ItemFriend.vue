<script>
import Autocomplete from '../../../Autocomplete/vue-autocomplete';
export default {
    props: ['update', 'direction', 'friend', 'isOwner'],
    watch: {
        internalDirection: function (value) {
            this.$emit('direction', value);
        },
        internalFriend: function (value) {
            this.$emit('friend', value);
        }
    },
    data() {
        return {
            internalDirection: '',
            internalFriend: {}
        };
    },
    methods: {
        selectedFriend: function(obj) {
            this.internalFriend = obj;
        }
    },
    created() {
        this.internalDirection = this.direction;
        this.internalFriend = this.friend;
    },
    components: {
        Autocomplete
    }
}
</script>
<template>
    <div>
        <slide-transition>
            <md-subheader v-if="!update">{{ $t('item.friend.' + internalDirection) }}</md-subheader>
        </slide-transition>
        <slide-transition>
            <md-list-item v-if="!update">
                <md-avatar>
                    <img :src="internalFriend.avatar " :alt="internalFriend.name">
                </md-avatar>
                <div class="md-list-text-container">
                    <span>{{ internalFriend.name }}</span>
                    <p>{{ internalFriend.phone_number }}</p>
                    <p>{{ internalFriend.email }}</p>
                </div>
            </md-list-item>

            <md-list-item v-else-if="isOwner">
                <md-input-container  style="flex:4">
                    <label>{{ $t('item.friend.direction') }}</label>
                    <md-select v-model="internalDirection">
                        <md-option value="borrowed_from">{{ $t('item.friend.borrowed_from') }}</md-option>
                        <md-option value="borrowed_to">{{ $t('item.friend.borrowed_to') }}</md-option>
                    </md-select>
                </md-input-container>
                <span style="flex:1"></span>
                <autocomplete
                        :initValue="internalFriend.name"
                        url="/api/users/searchFriends"
                        anchor="name"
                        subtitle="email"
                        :label="$t('item.search')"
                        :on-select="selectedFriend"
                        :wrapInput="true"
                        :initialData="[internalFriend]"
                        :inputWrapperClass="'md-input-container'"
                        :inputClass="'md-input'" style="flex:10">
                </autocomplete>
                <span style="flex:1"> </span>
            </md-list-item>
        </slide-transition>
    </div>
</template>