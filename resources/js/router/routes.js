import HomeScreenComponent from "../components/homescreen/HomescreenComponent.vue";
import BookComponent from "../components/book/BookComponent.vue";
import RegisterComponent from "../components/auth/RegisterComponent.vue";
import LoginComponent from "../components/auth/LoginComponent.vue";
import ForgotPasswordComponent from "../components/auth/ForgotPasswordComponent.vue";
import ResetPasswordComponent from "../components/auth/ResetPasswordComponent.vue"
import UserSettingsComponent from "../components/user/UserSettingsComponent.vue";

export const routes = [
    {
        name: "404",
        path: "*",
        component: HomeScreenComponent
    },
    {
        name: "home",
        path: "/",
        component: HomeScreenComponent
    },

    // auth routes
    {
        name: "register",
        path: "/register",
        component: RegisterComponent
    },
    {
        name: "login",
        path: "/login",
        component: LoginComponent
    },
    {
        name: "forgotPassword",
        path: "/forgot-password",
        component: ForgotPasswordComponent
    },
    {
        name: "resetPassword",
        path: "/reset-password/:id",
        component: ResetPasswordComponent,
    },

    // user routes
    {
        name: "userSettings",
        path: "/settings",
        component: UserSettingsComponent,
    },


    
    {
        name: "book",
        path: "/book",
        component: BookComponent
    }
];
