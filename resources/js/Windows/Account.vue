<script setup>
import {Link, useForm} from "@inertiajs/vue3";
import {$$$, getUser, isAuthenticated, logout} from "@/util.js";
import {nextTick, ref} from "vue";

const user = ref(getUser());

const formLogin = useForm({
    email: '',
    password: '',
    remember: false,
});

const formProfile = useForm({
    name: user.value?.name ?? '',
    email: user.value?.email ?? '',
});

const formPassword = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submitLogin = () => {
    formLogin.post(route('login'), {
        onFinish: (e) => {
            formLogin.reset('password');
            nextTick(() => {
                user.value = getUser();
                formProfile.name = user.value.name;
                formProfile.email = user.value.email;
            })
        },
    });
};

const submitProfile = () => {
    formProfile.patch(route('profile.update'))
};

const submitPassword = () => {
    formPassword.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => formPassword.reset(),
        onError: () => {
            if (formPassword.errors.password) formPassword.reset('password', 'password_confirmation');
            if (formPassword.errors.current_password) formPassword.reset('current_password');
        },
    });
};

const formDelete = useForm({
    password: '',
});

const submitDelete = () => {
    formDelete.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => deleteLightbox.value = false,
        onError: () => formDelete.reset(),
        onFinish: () => formDelete.reset(),
    });
};

const deleteLightbox = ref(false);
</script>
<template>
    <div v-if="isAuthenticated()" class="flex flex-col space-y-2">
<!--        <span class="text-sm">Avatar</span>-->
<!--        <input type="file" class="!mt-0"/>-->

        <span class="text-sm">Name</span>
        <input type="text" v-model="formProfile.name" class="!mt-0"/>

        <span class="text-sm">Email</span>
        <input type="email" v-model="formProfile.email" class="!mt-0"/>

        <div class="flex items-center space-x-2">
            <button @click="submitProfile" :disabled="formProfile.processing">Edit Profile</button>
            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                <p v-if="formProfile.recentlySuccessful" class="text-sm text-green-500">Saved</p>
            </Transition>
        </div>

        <span class="text-sm">Current Password</span>
        <input type="password" v-model="formPassword.current_password" class="!mt-0"/>

        <span class="text-sm">New Password</span>
        <input type="password" v-model="formPassword.password" class="!mt-0"/>

        <span class="text-sm">Confirm New Password</span>
        <input type="password" v-model="formPassword.password_confirmation" class="!mt-0"/>

        <div class="flex items-center space-x-2">
            <button @click="submitPassword" :disabled="formPassword.processing">Change Password</button>
            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                <p v-if="formPassword.recentlySuccessful" class="text-sm text-green-500">Saved</p>
            </Transition>
        </div>

        <button @click="logout">Log Out</button>
        <button @click="deleteLightbox = true">Delete Account</button>

        <teleport to="#teleports">
            <div @click="deleteLightbox = false; formDelete.reset()" v-if="deleteLightbox" class="absolute inset-0 flex flex-col items-center justify-center bg-black/75 backdrop-blur-md z-50">
                <div @click.stop class="window-container" :class="$$$.theme.style">
                    <div class="flex flex-col space-y-2 window p-4">
                        <p class="max-w-2xl w-full">
                            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting
                            your account, please download any data or information that you wish to retain.
                        </p>
                        <p>Please enter your password to confirm deletion</p>
                        <input class="placeholder:text-neutral-500" type="password" v-model="formDelete.password" placeholder="Confirm your password..."/>
                        <button @click="submitDelete">Delete My Account</button>
                    </div>
                </div>
            </div>
        </teleport>
    </div>
    <div v-else class="flex space-x-2">
        <div class="login-facade w-fit mx-auto h-fit">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-48">
                <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
            </svg>
        </div>
        <div class="flex flex-col space-y-2 p-2">
            <h1 class="text-2xl font-bold">Log In</h1>

            <span class="text-sm">Email</span>
            <input type="email" v-model="formLogin.email" class="!mt-0"/>

            <span class="text-sm">Password</span>
            <input type="password" v-model="formLogin.password" class="!mt-0"/>

            <div class="flex items-center justify-between space-x-4">
                <label class="flex items-center space-x-1">
                    <input type="checkbox" class="rounded !text-blue-500" v-model="formLogin.remember"/>
                    <span>Remember Me</span>
                </label>
                <div class="flex items-center space-x-2">
                    <Link class="underline text-sm">Sign Up</Link>
                    <button @click="submitLogin">Log In</button>
                </div>
            </div>
        </div>
    </div>
</template>
