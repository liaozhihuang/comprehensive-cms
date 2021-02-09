<template>
<div class="wrapper">
    <v-head></v-head>
    <router-view :key="this.$route.path"></router-view>
</div>
</template>

<script>
import vHead from './Header.vue';

export default {
    data() {
        return {};
    },
    components: {
        vHead,
    },
};
</script>
