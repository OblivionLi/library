<template>
    <div class="user">
        <h1 class="user__title">Register</h1>

        <div v-if="!loading">
            <form>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        class="form-control"
                        id="username"
                        placeholder="Enter username"
                        name="username"
                        v-model="user.name"
                        required
                    />
                    <div v-if="error.length > 0 && !loading" class="mt-2 mb-2">
                        <div class="alert alert-danger" role="alert">
                            <p>
                                {{
                                    error.includes("The name must be a string.")
                                        ? "The name must be a string."
                                        : ""
                                }}
                            </p>

                            <p>
                                {{
                                    error.includes(
                                        "The name field is required."
                                    )
                                        ? "The name field is required."
                                        : ""
                                }}
                            </p>
                        </div>
                    </div>
                </div>
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
                        required
                    />
                    <small id="emailHelp" class="form-text text-muted"
                        >We'll never share your email with anyone else.</small
                    >
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
                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        placeholder="Password"
                        name="password"
                        v-model="user.password"
                        required
                    />
                    <div v-if="error.length > 0 && !loading" class="mt-2 mb-2">
                        <div class="alert alert-danger" role="alert">
                            <p>
                                {{
                                    error.includes(
                                        "The password must be a string."
                                    )
                                        ? "The password must be a string."
                                        : ""
                                }}
                            </p>

                            <p>
                                {{
                                    error.includes(
                                        "The password field is required."
                                    )
                                        ? "The password field is required."
                                        : ""
                                }}
                            </p>

                            <p>
                                {{
                                    error.includes(
                                        "The password confirmation does not match."
                                    )
                                        ? "The password confirmation does not match."
                                        : ""
                                }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input
                        type="password"
                        class="form-control"
                        id="confirm_password"
                        placeholder="Confirm Password"
                        name="confirm_password"
                        v-model="user.confirm_password"
                        required
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
                    @click.prevent="register"
                >
                    Register
                </button>
            </form>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <router-link to="/login"
                        >Already have an account? Then login here.</router-link
                    >
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
            name: "",
            email: "",
            password: "",
            confirm_password: "",
            remember_me: false
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

        async register() {
            this.error = [];
            this.loading = true;

            try {
                await this.$store.dispatch("userAuth/registerUser", this.user);
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
