<template>
    <div class="user">
        <h1 class="user__title">Update Credentials</h1>

        <div v-if="showError && !loading" class="mb-2 mt-2">
            <div class="alert alert-danger" role="alert">
                {{ showError }}
            </div>
        </div>

        <div v-if="!loading">
            <form autocomplete="on">
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

                    <div
                        v-if="updateError[0] && updateError[0].name && !loading"
                        class="mt-2 mb-2"
                    >
                        <div
                            class="alert alert-danger"
                            role="alert"
                            v-for="error in updateError[0].name"
                            :key="error"
                        >
                            <p
                                v-if="
                                    error.includes('The name must be a string.')
                                "
                            >
                                The name must be a string.
                            </p>

                            <p
                                v-else-if="
                                    error.includes(
                                        'The name field is required.'
                                    )
                                "
                            >
                                The name field is required.
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

                    <div
                        v-if="
                            updateError[0] && updateError[0].email && !loading
                        "
                        class="mt-2 mb-2"
                    >
                        <div
                            class="alert alert-danger"
                            role="alert"
                            v-for="error in updateError[0].email"
                            :key="error"
                        >
                            <p
                                v-if="
                                    error.includes(
                                        'The email must be a string.'
                                    )
                                "
                            >
                                The email must be a string.
                            </p>

                            <p
                                v-else-if="
                                    error.includes(
                                        'The email must be a valid email address.'
                                    )
                                "
                            >
                                The email must be a valid email address.
                            </p>

                            <p
                                v-else-if="
                                    error.includes(
                                        'The email field is required.'
                                    )
                                "
                            >
                                The email field is required.
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
                    />
                    <div
                        v-if="
                            updateError[0] &&
                                updateError[0].password &&
                                !loading
                        "
                        class="mt-2 mb-2"
                    >
                        <div
                            class="alert alert-danger"
                            role="alert"
                            v-for="error in updateError[0].password"
                            :key="error"
                        >
                            <p
                                v-if="
                                    error.includes(
                                        'The password must be a string.'
                                    )
                                "
                            >
                                The password must be a string.
                            </p>

                            <p
                                v-else-if="
                                    error.includes(
                                        'The password confirmation does not match.'
                                    )
                                "
                            >
                                The password confirmation does not match.
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
                    @click.prevent="updateUser"
                >
                    Update My Credentials
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
            id: "",
            name: "",
            email: "",
            password: "",
            confirm_password: ""
        },
        loading: false,
        showError: "",
        updateError: [],
        isLoggedIn: true
    }),

    created() {
        this.loggedIn();

        if (this.isLoggedIn) {
            this.showUser();
        }
    },

    methods: {
        loggedIn() {
            !this.$store.getters["userAuth/userInfo"] && this.$router.push('/');
            this.isLoggedIn = false;
        },

        async showUser() {
            this.user.id = this.$store.getters["userAuth/userInfo"].id;
            this.showError = "";
            this.loading = true;

            try {
                await this.$store.dispatch("user/userDetail", this.user.id);
            } catch (error) {
                this.showError =
                    error.response && error.response.data.message
                        ? error.response.data.message
                        : error.message;
            }

            this.user.name = this.$store.getters["user/userDetail"].name;
            this.user.email = this.$store.getters["user/userDetail"].email;
            this.loading = false;
        },

        async updateUser() {
            this.updateError = [];
            this.loading = true;

            try {
                await this.$store.dispatch("userAuth/userUpdate", this.user);
            } catch (error) {
                this.updateError.push(error.response.data.errors);
            }
            this.loading = false;
        }
    }
};
</script>
