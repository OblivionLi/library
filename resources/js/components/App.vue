<template>
    <div>
        <MainNavbarComponent />
        <router-view />
    </div>
</template>

<script>
import MainNavbarComponent from "./navigation/MainNavbarComponent.vue";

export default {
    components: {
        MainNavbarComponent
    }
};
</script>
