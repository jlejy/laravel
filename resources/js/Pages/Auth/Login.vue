<!--<template>-->
<!--    <div class="name">-->
<!--        {{ greeting(name) }}-->
<!--    </div>-->
<!--</template>-->

<!--<script>-->
<!--    export default {-->
<!--        setup() {-->
<!--            const name = 'abc';-->

<!--            const greeting = (name) => {-->
<!--                return 'Hello' + name;-->
<!--            };-->

<!--            return {-->
<!--                name,-->
<!--                greeting,-->
<!--            };-->
<!--        }-->
<!--    }-->
<!--</script>-->



<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    user_id: '',
    user_pass: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('user_pass'),
        onError: (errors) => {
            console.error('Login failed:', errors);
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="user_id" value="user_id" />

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
                <InputLabel for="user_pass" value="Password" />

                <TextInput
                    id="user_pass"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.user_pass"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.user_pass" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600"
                        >Remember me</span
                    >
                </label>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
