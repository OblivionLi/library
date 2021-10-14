<template>
    <div class="user">
        <h1 class="user__title">Login</h1>

        <div v-if="error && !loading" class="mb-2 mt-2">
            <div class="alert alert-danger" role="alert">
                {{ error }}
            </div>
        </div>

        <div v-if="!loading">
            <form>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        aria-describedby="emailHelp"
                        placeholder="Enter email"
                        name="email"
                        v-model="user.email"
                    />
                    <small id="emailHelp" class="form-text text-muted"
                        >We'll never share your email with anyone else.</small
                    >
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        placeholder="Password"
                        name="password"
                        v-model="user.password"
                    />
                </div>
                <div class="form-check">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="remember-me"
                        name="remember_me"
                        v-model="user.remember_me"
                    />
                    <label class="form-check-label" for="remember-me"
                        >Remember me (for 1 week)</label
                    >
                </div>
                <button
                    type="submit"
                    class="btn btn-primary"
                    @click.prevent="login"
                >
                    Login
                </button>
            </form>
            <div class="container">
                <div class="row text-center">
                    <div class="col-sm">
                        <router-link to="/forgot-password"
                            >Forgot Password?</router-link
                        >
                    </div>
                    <div class="col-sm">
                        <router-link to="/register"
                            >Don't have an account? Then register
                            here.</router-link
                        >
                    </div>
                </div>
            </div>
        </div>

        <div v-if="loading">
            <Spinner />
        </div>
    </div>
</template>

<script>
import Spinner from "../alerts/SpinnerComponent.vue";

export default {
    components: {
        Spinner
    },

    data: () => ({
        user: {
            email: "",
            password: "",
            remember_me: false
        },
        loading: false,
        error: ""
    }),

    created() {
        this.loggedIn();
    },

    methods: {
        loggedIn() {
            this.$store.getters["userAuth/userInfo"] && this.$router.push('/');
        },

        async login() {
            this.error = "";
            this.loading = true;
            
            try {
                await this.$store.dispatch("userAuth/loginUser", this.user);
            } catch (error) {
                this.error =
                    error.response && error.response.data.message
                        ? error.response.data.message
                        : error.message;
            }
            this.loading = false;
        }
    }
};
</script>
