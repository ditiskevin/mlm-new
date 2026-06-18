<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: { type: Boolean },
    status: { type: String },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Inloggen" />

        <span style="font-family: 'Dancing Script', cursive; font-size: 22px; color: #e0a24a">Welkom terug,</span>
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 26px; color: #473537; margin: 2px 0 6px; letter-spacing: -0.3px">Log in op je account 💛</h1>
        <p style="font-size: 14px; color: #8a7d78; margin: 0 0 22px">Fijn dat je er weer bent. Vul je gegevens in om verder te gaan.</p>

        <div v-if="status" style="margin-bottom: 16px; font-size: 14px; font-weight: 600; color: #5e9e78">{{ status }}</div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="E-mailadres" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Wachtwoord" />
                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 flex items-center justify-between">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm" style="color: #6c5f5b">Onthoud mij</span>
                </label>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    style="font-size: 13px; color: #c0566b; font-weight: 600; text-decoration: none"
                >
                    Wachtwoord vergeten?
                </Link>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                :style="{
                    width: '100%',
                    marginTop: '22px',
                    fontFamily: 'Poppins, sans-serif',
                    fontWeight: 600,
                    fontSize: '15px',
                    color: '#fff',
                    background: 'linear-gradient(135deg,#F7A8B5,#F28B82)',
                    border: 'none',
                    borderRadius: '999px',
                    padding: '13px',
                    cursor: 'pointer',
                    boxShadow: '0 10px 22px rgba(242,139,130,0.32)',
                    opacity: form.processing ? 0.6 : 1,
                }"
            >
                Inloggen
            </button>

            <p style="text-align: center; font-size: 14px; color: #8a7d78; margin: 20px 0 0">
                Nog geen account?
                <Link :href="route('register')" style="color: #c0566b; font-weight: 600; text-decoration: none">Word lid van de community</Link>
            </p>
        </form>
    </GuestLayout>
</template>
