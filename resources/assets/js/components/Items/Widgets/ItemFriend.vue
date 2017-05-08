<script>
import Autocomplete from '../../../Autocomplete/v-autocomplete';
import {debounce} from 'lodash';
export default {
    name: 'ItemFriend',
    props: ['direction', 'friend', 'isOwner', 'update'],
    data() {
        return {
            friends: [],
            internalDirection: '',
            internalFriend: {}
        };
    },
    computed: {
        directionItems() {
            return [
                {value: 'borrowed_from', text: this.$t('item.friend.borrowed_from') },
                {value: 'borrowed_to', text: this.$t('item.friend.borrowed_to') }
            ];
        },
        friendItemText() {
            return this.internalFriend.email;//`<p>${this.internalFriend.email}</p><p>${this.internalFriend.phone_number}</p>`;
        }
    },
    components: {
        'v-autocomplete': Autocomplete
    },
    watch: {
        internalDirection(value) {
            this.$emit('direction', value);
        },
        internalFriend(value) {
            console.log('internalFriend', value);
            this.$emit('friend', value);
        }
    },
    methods: {
        getFriends(searchQuery) {
            console.log('searching');
            const self = this;
            this.$http.get('/api/users/searchFriends', {
                params: {q: searchQuery}
            }).then(function(response) {
                console.log('before fetched', self.friends);
                self.friends.splice(0, self.friends.length);
                self.friends = self.friends.concat(response.data);
                console.log('fetched', self.friends);
            }).catch((error) => {
                self.friends = [];
                console.log('error', error);
            });
        },
        search(value) {
            let debouncedFunction = debounce(this.getFriends.bind(this), 500);
            debouncedFunction(value)
        },
        selectedFriend(obj) {
            console.log('selectedFriend', obj);
            this.internalFriend = obj;
        }
    },
    created() {
        this.internalDirection = this.direction;
        this.internalFriend = Object.assign({}, this.friend);
        this.friends.push(this.friend);
        this.autocompleteInput = this.friend.name;
    }
}
</script>
<template>
    <div>
        <slide-transition>
            <v-subheader v-if="!update">{{ $t('item.friend.' + internalDirection) }}</v-subheader>
        </slide-transition>
        <slide-transition>
            <v-list-item v-if="!update">
                <v-list-tile avatar tag="div">
                    <v-list-tile-avatar>
                        <img :src="internalFriend.avatar" :alt="internalFriend.name" />
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title v-html="internalFriend.name" />
                        <v-list-tile-sub-title v-html="internalFriend.email"/>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list-item>

            <v-list-item v-else-if="isOwner">
                <v-select :items="directionItems" v-model="internalDirection" style="flex:3"
                          :label="$t('item.friend.direction')" light single-line auto />
                <span style="flex:1"></span>
                <v-autocomplete @search="search" :label="$t('item.search')" :items="friends" v-model="internalFriend"
                            autocomplete item-text="name" item-value="name" chips light max-height="auto"
                                single-line style="flex:4" :filter="i=>i">
                    <template slot="selection" scope="data">
                        <v-chip close @input="data.parent.selectItem(data.item)" @click.native.stop
                                class="chip--select-multi" :key="data.item">
                            <v-avatar>
                                <img :src="data.item.avatar">
                            </v-avatar>
                            {{ data.item.name }}
                        </v-chip>
                    </template>
                    <template slot="item" scope="data">
                        <v-list-tile-avatar>
                            <img :src="data.item.avatar"/>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title v-html="data.item.name"/>
                            <v-list-tile-sub-title v-html="data.item.email"/>
                        </v-list-tile-content>
                    </template>
                </v-autocomplete>
                <span style="flex:1"> </span>
            </v-list-item>
        </slide-transition>
    </div>
</template>