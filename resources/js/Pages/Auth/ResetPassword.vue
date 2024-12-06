<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    user_id: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    user_id: props.user_id,
    user_pass: '',
    user_pass_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('user_pass', 'user_pass_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="user_id" value="Email" />

                <TextInput
                    id="user_id"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.user_id"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.user_id" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

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
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Reset Password
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
