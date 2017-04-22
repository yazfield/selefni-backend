
<template>
        <md-menu style="flex: 1; display: block" :md-align-trigger="true"
                 md-direction="bottom left" md-size="4" ref="autocompleteList"
            @close="hideAll" @open="keepFocusHack">
            <md-input-container>
                <label v-if="label">{{ label }}</label>
                <md-input type="text" md-menu-trigger ref="autocompleteInput"
                          :id="id"
                          :class="inputClass"
                          :placeholder="placeholder"
                          v-model="type"
                          @input="input(type)"
                          @dblclick="showAll"
                          @blur="hideAll"
                          @keydown="keydown"
                          @focus="focus"
                          autocomplete="off"></md-input>
            </md-input-container>
            <md-menu-content>
                <md-menu-item v-if="!json.length">
                    <p>No results</p>
                </md-menu-item>
                <md-menu-item @selected="selectList(data)" v-for="(data, i) in json">
                    <md-avatar>
                        <img :src="data.avatar" :alt="data.name">
                    </md-avatar>
                    <div class="md-list-text-container">
                        <span>{{data.name}}</span>
                        <p>{{data.phone_number}}</p>
                        <p>{{data.email}}</p>
                    </div>
                </md-menu-item>
            </md-menu-content>
        </md-menu>
</template>


<script>

    /*! Copyright (c) 2016 Naufal Rabbani (http://github.com/BosNaufal)
     * Licensed Under MIT (http://opensource.org/licenses/MIT)
     *
     * Vue 2 Autocomplete @ Version 0.0.1
     *
     */

    /*!
     *  javascript-debounce 1.0.0
     *
     *  A lightweight, dependency-free JavaScript module for debouncing functions based on David Walsh's debounce function.
     *
     *  Source code available at: https://github.com/jgarber623/javascript-debounce
     *
     *  (c) 2015-present Jason Garber (http://sixtwothree.org)
     *
     *  javascript-debounce may be freely distributed under the MIT license.
     */

    var debounce = function(callback, delay) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                callback.apply(context, args);
            }, delay);
        };
    };

    export default {

        props: {
            id: String,
            className: String,
            inputClass: {
                type: String,
                default: ""
            },
            placeholder: String,

            // Intial Value
            initValue: {
                type: String,
                default: ""
            },
            initialData: {
                type: Array,
                default: []
            },

            // Anchor of list
            anchor: {
                type: String,
                required: true
            },

            // subtitle of list
            subtitle: String,
            // label of input
            label: String,

            // Debounce time
            debounce: Number,

            // ajax URL will be fetched
            url: {
                type: String,
                required: true
            },

            // query param
            param: {
                type: String,
                default: 'q'
            },

            // Custom Params
            customParams: Object,

            // minimum length
            min: {
                type: Number,
                default: 0
            },

            // Create a custom template from data.
            template: Function,

            // Process the result before retrieveng the result array.
            process: Function,

            // Callback
            onInput: Function,
            onShow: Function,
            onBlur: Function,
            onHide: Function,
            onFocus: Function,
            onSelect: Function,
            onBeforeAjax: Function,
            onAjaxProgress: Function,
            onAjaxLoaded: Function,

        },

        data() {
            return {
                showList: false,
                type: "",
                json: [],
                focusList: "",
                fetching: false
            };
        },


        methods: {

            // Netralize Autocomplete
            clearInput() {
                this.showList = false;
                this.type = "";
                this.json = [];
                this.focusList = ""
            },

            // Get the original data
            cleanUp(data){
                return JSON.parse(JSON.stringify(data));
            },

            input(val){
                this.showList = true;

                // Callback Event
                this.onInput ? this.onInput(val) : null;

                // Debounce the "getData" method.
                if(!this.debouncedGetData || this.debounce !== this.oldDebounce) {
                    this.oldDebounce = this.debounce;
                    this.debouncedGetData = this.debounce ? debounce(this.getData.bind(this), this.debounce) : this.getData;
                }

                // Get The Data
                this.debouncedGetData(val)
            },

            showAll(){
                this.json = [];

                this.getData("");

                // Callback Event
                this.onShow ? this.onShow() : null;

                this.showList = true;
            },

            keepFocusHack() {
                this.mdInput.focus();
            },

            hideAll(e){
                // Callback Event
                this.onBlur ? this.onBlur(e) : null;

                setTimeout(() => {

                    // Callback Event
                    this.onHide ? this.onHide() : null;

                    this.showList = false;
                },250);
            },

            focus(e){
                this.focusList = 0;

                // Callback Event
                this.onFocus ? this.onFocus(e) : null
            },

            mousemove(i){
                this.focusList = i;
            },

            keydown(e){
                let key = e.keyCode;

                // Disable when list isn't showing up
                if(!this.showList) return;

                switch (key) {
                    case 40: //down
                        this.focusList++;
                        break;
                    case 38: //up
                        this.focusList--;
                        break;
                    case 13: //enter
                        this.selectList(this.json[this.focusList]);
                        this.showList = false;
                        break;
                    case 27: //esc
                        this.showList = false;
                        break;
                }

                // When cursor out of range
                let listLength = this.json.length - 1;
                this.focusList = this.focusList > listLength ? 0 : this.focusList < 0 ? listLength : this.focusList;

            },

            activeClass(i){
                return {
                    'focus-list' : i == this.focusList
                };
            },

            selectList(data){
                let clean = this.cleanUp(data);

                // Put the selected data to type (model)
                this.type = clean[this.anchor];

                this.showList = false;

                /**
                 * Callback Event
                 * Deep clone of the original object
                 */
                this.onSelect ? this.onSelect(clean) : null
            },

            getData(val){

                this.fetching = true;
                let self = this;

                if (val.length < this.min) return;

                if(this.url != null){

                    // Callback Event
                    this.onBeforeAjax ? this.onBeforeAjax(val) : null;

                    let params = {
                        [this.param]: val
                    };
                    if(this.customParams) {
                        Object.keys(this.customParams).forEach((key) => {
                            params[key] = this.customParams[key];
                        })
                    }
                    this.$http.get(this.url, {
                        params
                    }).then(response => {
                        let json = response.data;

                        // Callback Event
                        this.onAjaxLoaded ? this.onAjaxLoaded(json) : null;

                        self.json = self.process ? self.process(json) : json;
                        self.fetching = false;

                    }).catch(error => {
                        self.fetching = false;
                    });

                    /*
                    ajax.open('GET', `${this.url}?${this.param}=${val}${params}`, true);
                    ajax.send();

                    ajax.addEventListener('progress', function (data) {
                        if(data.lengthComputable){
                            // Callback Event
                            this.onAjaxProgress ? this.onAjaxProgress(data) : null
                        }
                    });

                    ajax.addEventListener('loadend', function (data) {
                        let json = JSON.parse(this.responseText);

                        // Callback Event
                        this.onAjaxLoaded ? this.onAjaxLoaded(json) : null;

                        self.json = self.process ? self.process(json) : json;
                    });
                    */

                }
            },

            setValue(val) {
                this.type = val
            }
        },

        created(){
            // Sync parent model with initValue Props
            this.type = this.initValue ? this.initValue : null
        },
        mounted() {
            this.mdInput = this.$el.querySelector('.md-input');
            this.json = this.json.concat(this.initialData);
        },
        watch: {
            showList: function(value) {
                if(value && !this.$refs['autocompleteList'].active) {
                    this.$refs['autocompleteList'].open();
                }
            }
        }

    }
</script>