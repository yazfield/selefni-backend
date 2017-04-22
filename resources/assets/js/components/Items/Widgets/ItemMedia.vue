<script>
export default {
    props: ['update', 'image', 'alt'],
    data: function() {
        return {
            imageUpload: {
                file: null,
                name: '',
                image: ''
            }
        };
    },
    watch: {
        image: function(value) {
            this.imageUpload.image = value;
        }
    },
    methods: {
        chooseImage() {
            this.$refs.itemImage.click();
        },
        imageChange(e) {
            e.preventDefault();
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }
            this.createImage(files[0]);
            this.$emit('change', this.imageUpload);
        },
        createImage(file){
            this.imageUpload.file = file;
            let reader = new FileReader();
            this.imageUpload.name = file.name;
            reader.onload = (e) => {
                this.imageUpload.image = e.target.result;
                return this.internalImage = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    },
    created() {
        this.imageUpload.image = this.image;
    }
};
</script>
<template>
    <div>
        <input v-show="false" ref="itemImage" type="file" name="image" @change="imageChange">
        <transition enter-active-class="animated speed-animation fadeIn"
                    leave-active-class="animated speed-animation fadeOut">
            <md-button class="image-button" @click.native="chooseImage"
                       v-if="update">
                <md-icon>add_a_photo</md-icon>
            </md-button>
        </transition>
        <transition enter-active-class="animated speed-animation fadeIn"
                    leave-active-class="animated speed-animation fadeOut" mode="in-out">
            <img v-if="update" :src="imageUpload.image" :alt="alt"/>
            <img v-else :src="imageUpload.image" :alt="alt"/>
        </transition>
    </div>
</template>

<style lang="scss">
    @import 'node_modules/vue-material/src/core/stylesheets/variables.scss';
    .flatpickr-calendar.open {
        z-index: 20;
    }

    .flatpickr-input {
        border: none;
        border-bottom: 1px solid white;
        background: transparent;
        color: white;
        font-size: 14px;
        font-family: $font-roboto;

        &:focus {
            outline: none;
        }

    }
    .flatpickr-input.active {
        border-bottom: 2px solid white;
        color: rgba(255, 255, 255, .87);
    }

    .md-button.image-button {
        position: absolute;
        color: white;
        background: rgba(0, 0, 0, 0.5);
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        margin: 0;
        z-index: 50;
    }
</style>