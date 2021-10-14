import axios from "axios";

const state = {
    user: {},
    passwordResetForEmail: ''
};

const getters = {
    userInfo(state) {
        const userInfo = JSON.parse(localStorage.getItem("userInfo"));
        return (state.user = userInfo);
    },
};

const actions = {
    async loginUser({}, user) {
        const { data } = await axios.post("/api/login", {
            email: user.email,
            password: user.password,
            remember_me: user.remember_me
        });

        if (data.data.access_token) {
            localStorage.setItem("userInfo", JSON.stringify(data.data));
            window.location.replace("/");
        }
    },

    async logoutUser({}) {
        const userInfo = JSON.parse(localStorage.getItem("userInfo"));
        const userToken = (axios.defaults.headers.common["Authorization"] =
            "Bearer " + userInfo.access_token);

        await axios.get("/api/logout", {}, userToken);

        if (userToken) {
            localStorage.removeItem("userInfo");
            window.location.replace("/");
        }
    },

    async registerUser({ dispatch }, user) {
        await axios.post("/api/register", {
            name: user.name,
            email: user.email,
            password: user.password,
            password_confirmation: user.confirm_password,
            remember_me: user.remember_me
        });

        dispatch("loginUser", user);
    },

    async forgotPassword({}, user) {
        await axios.post("/api/forgot-password", {
            email: user.email,
        });
    },

    async resetPassword({state}, user) {
        await axios.patch(`/api/reset-password/${state.passwordResetForEmail}`, {
            password: user.password,
            password_confirmation: user.confirm_password,
        });

        window.location.replace("/login");
    },

    async getTokenResetPassword(state, token) {
        const { data } = await axios.get(`/api/reset-password/${token}`);

        if (data.length > 0) {
            state.commit("setEmailPasswordReset", data[0].email);
        }
    },

    async userUpdate({}, user) {
        await axios.patch(`/api/update-credentials/${user.id}`, {
            name: user.name,
            email: user.email,
            password: user.password,
            password_confirmation: user.confirm_password,
        })

        window.location.replace("/settings");
    }
};

const mutations = {
    setEmailPasswordReset(state, payload) {
        state.passwordResetForEmail = payload;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
