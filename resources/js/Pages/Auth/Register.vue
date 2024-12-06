<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    user_name: '',
    user_id: '',
    user_pass: '',
    user_pass_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        // data: {
        //     user_name: form.user_name,  // 이름
        //     user_id: form.user_id,      // 이메일
        //     user_pass: form.user_pass,  // 비밀번호
        //     user_pass_confirmation: form.user_pass_confirmation  // 비밀번호 확인
        // },
        onFinish: () => form.reset('user_pass', 'user_pass_confirmation'),
        onError: (errors) => {
            console.error('Login failed:', errors);
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="user_name" value="user_name" />

                <TextInput
                    id="user_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.user_name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.user_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="user_id" value="user_id" />
                <TextInput
                    id="user_id"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.user_id"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.user_id" />
            </div>

            <div class="mt-4">
                <InputLabel for="user_pass" value="Password" />

                <TextInput
                    id="user_pass"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.user_pass"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.user_pass" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="user_pass_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="user_pass_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.user_pass_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.user_pass_confirmation"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
