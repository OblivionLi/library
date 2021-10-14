<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <router-link class="navbar-brand" to="/">Library</router-link>
        <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarToggle"
            aria-controls="navbarToggle"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarToggle">
            <ul class="navbar-nav m-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <router-link class="nav-link" to="/"
                        >Home
                        <span class="sr-only">(current)</span></router-link
                    >
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="#">Book List</router-link>
                </li>

                <li class="nav-item">
                    <form class="form-inline my-2 my-lg-0">
                        <input
                            class="form-control mr-sm-2"
                            type="search"
                            placeholder="Search"
                        />
                        <button
                            class="btn btn-outline-success my-2 my-sm-0"
                            type="submit"
                        >
                            Search
                        </button>
                    </form>
                </li>
            </ul>

            <div class="dropdown" v-if="user && user.name">
                <button
                    class="btn btn-secondary dropdown-toggle"
                    type="button"
                    id="dropDownMenu"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    {{ user.name }}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropDownMenu">
                    <button class="dropdown-item" type="button">
                        Another action
                    </button>
                    <router-link class="dropdown-item" to="/settings">Settings</router-link>
                    <button class="dropdown-item" type="button" @click.prevent="logout">
                        Logout
                    </button>
                </div>
            </div>

            <ul v-else class="navbar-nav navbar__links--list">
                <li>
                    <router-link to="/login" class="navbar__links--list-a"
                        >Login</router-link
                    >
                </li>
                <li>
                    <router-link
                        to="/register"
                        class="navbar__links--list-a signUp"
                        >Register</router-link
                    >
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
export default {
    data: () => ({
        user: {}
    }),

    mounted() {
        this.user = this.$store.getters["userAuth/userInfo"];
    },

    methods: {
        logout() {
            this.$store.dispatch("userAuth/logoutUser");
        }
    }
};
</script>
