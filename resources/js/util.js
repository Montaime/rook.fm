import {router, usePage} from "@inertiajs/vue3";
import route from 'ziggy-js'

export const logout = () => {
    router.post(route('logout'));
}

export const isAdmin = () => {
    return isAuthenticated() && false
}

export const isAuthenticated = () => {
    return usePage().props.auth;
}

export const getUser = () => {
    return usePage().props.user;
}
