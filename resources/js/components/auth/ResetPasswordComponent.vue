<template>
    <div class="user">
        <h1 class="user__title">Reset Password</h1>

        <div v-if="!loading">
            <form>
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
                    />
                </div>
                <button
                    type="submit"
                    class="btn btn-primary"
                    @click.prevent="resetPassword"
                >
                    Reset Password
                </button>
            </form>
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
            password: "",
            confirm_password: ""
        },
        loading: false,
        error: []
    }),

    mounted() {
        this.$store.dispatch(
            "userAuth/getTokenResetPassword",
            this.$route.params.id
        );
    },

    created() {
        this.loggedIn();
    },

    methods: {
        loggedIn() {
            this.$store.getters["userAuth/userInfo"] && this.$router.push('/');
        },
        
        async resetPassword() {
            this.error = [];
            this.loading = true;

            try {
                await this.$store.dispatch("userAuth/resetPassword", this.user);
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
