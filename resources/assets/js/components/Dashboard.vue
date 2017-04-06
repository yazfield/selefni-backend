<template>
    <div>
        <NewItem></NewItem>
        <h1>Laravel 5 - dashboard</h1>
    </div>
</template>

<script>
import {templates} from '../constants';
export default {
    mounted() {
        this.$store.dispatch('changeTemplate', templates.dashboard);
    }
}
</script>