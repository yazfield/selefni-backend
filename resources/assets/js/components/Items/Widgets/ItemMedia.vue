<script>
export default {
    name: 'ItemMedia',
    props: ['alt', 'image', 'update'],
    data() {
        return {
            imageUpload: {
                file: null,
                image: '',
                name: ''
            }
        };
    },
    watch: {
        image(value) {
            this.imageUpload.image = value;
        }
    },
    methods: {
        chooseImage() {
            this.$refs.itemImage.click();
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
        },
        imageChange(e) {
            e.preventDefault();
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }
            this.createImage(files[0]);
            this.$emit('change', this.imageUpload);
        }
    },
    created() {
        this.imageUpload.image = this.image;
    }
};
</script>
<template>
    <div style="position: relative">
        <input v-show="false" ref="itemImage" type="file" name="image" @change="imageChange">
        <transition enter-active-class="animated speed-animation fadeIn"
                    leave-active-class="animated speed-animation fadeOut">
            <v-btn class="image-button" @click.native="chooseImage" v-if="update" flat>
                <v-icon>add_a_photo</v-icon>
            </v-btn>
        </transition>
        <transition enter-active-class="animated speed-animation fadeIn"
                    leave-active-class="animated speed-animation fadeOut" mode="in-out">
            <img v-if="update" :src="imageUpload.image" :alt="alt"/>
            <img v-else :src="imageUpload.image" :alt="alt"/>
        </transition>
    </div>
</template>

<style lang="scss">
    .btn.image-button {
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