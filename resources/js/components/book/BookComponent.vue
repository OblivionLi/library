<template>
    <div class="container">
        <p>Hello from book_component</p>
        <p>with login</p>
    </div>
</template>

<script>
import axios from "axios";
export default {
    created() {
        // const userInfo = JSON.parse(localStorage.getItem("userInfo"));
        // axios.defaults.headers.common["Authorization"] =
        //     "Bearer " + localStorage.getItem(`${userInfo.access_token}`);
    }
};
</script>
