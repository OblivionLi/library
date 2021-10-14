import Vue from "vue";
import Vuex from "vuex";
import userAuth from "./modules/auth/userAuth";
import user from "./modules/user/user";

Vue.use(Vuex);

export const store = new Vuex.Store({
    modules: {
        userAuth,
        user
    }
});
