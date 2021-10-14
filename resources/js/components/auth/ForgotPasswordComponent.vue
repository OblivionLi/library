<template>
    <div class="user">
        <h1 class="user__title">Forgot Password</h1>

        <div v-if="!loading">
            <form>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        placeholder="Enter email"
                        name="email"
                        v-model="user.email"
                    />
                    <div v-if="error.length > 0 && !loading" class="mt-2 mb-2">
                        <div class="alert alert-danger" role="alert">
                            <p>
                                {{
                                    error.includes(
                                        "The email must be a string."
                                    )
                                        ? "The email must be a string."
                                        : ""
                                }}
                            </p>

                            <p>
                                {{
                                    error.includes(
                                        "The email must be a valid email address."
                                    )
                                        ? "The email must be a valid email address."
                                        : ""
                                }}
                            </p>

                            <p>
                                {{
                                    error.includes(
                                        "The email field is required."
                                    )
                                        ? "The email field is required."
                                        : ""
                                }}
                            </p>
                        </div>
                    </div>
                </div>
                <button
                    type="submit"
                    class="btn btn-primary"
                    @click.prevent="forgotPassword"
                >
                    Send Password Reset Link
                </button>
            </form>

            <div class="container">
                <div class="row text-center">
                    <div class="col-sm">
                        <router-link to="/login"
                            >Nevermind, I remembered my password..</router-link
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
            email: ""
        },
        loading: false,
        error: []
    }),

    created() {
        this.loggedIn();
    },

    methods: {
        loggedIn() {
            this.$store.getters["userAuth/userInfo"] && this.$router.push('/');
        },

        async forgotPassword() {
            this.error = [];
            this.loading = true;

            try {
                await this.$store.dispatch(
                    "userAuth/forgotPassword",
                    this.user
                );
            } catch (error) {
                Object.values(error.response.data.errors).map(err =>
                    err.forEach(e => this.error.push(e))
                );
            }
            this.loading = false;
        }
    }
};
</script>
