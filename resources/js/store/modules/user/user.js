import axios from "axios";

const state = {
    usersList: {},
    userDetail: {}
};

const getters = {
    usersList: state => state.usersList,
    userDetail: state => state.userDetail
};

const actions = {
    async usersList(state) {
        const { data } = await axios.get("/api/users");

        if (data.data.length > 0) {
            state.commit("setUsersList", data.data);
        }
    },

    async userDetail(state, userId) {
        const { data } = await axios.get(`/api/users/${userId}`);

        if (data.data) {
            state.commit("setUserDetail", data.data);
        }
    }
};

const mutations = {
    setUsersList(state, payload) {
        state.usersList = payload;
    },

    setUserDetail(state, payload) {
        state.userDetail = payload;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
