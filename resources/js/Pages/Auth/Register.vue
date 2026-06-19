<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    parentTypes: { type: Object, default: () => ({ ouder: 'Ouder' }) },
    genders: { type: Object, default: () => ({}) },
    parentingRoles: { type: Object, default: () => ({}) },
});

const form = useForm({
    name: '',
    email: '',
    parent_type: 'ouder',
    gender: null,
    parenting_role: null,
    password: '',
    password_confirmation: '',
});

// Click a selected chip again to clear an optional choice.
const toggleOptional = (field, key) => {
    form[field] = form[field] === key ? null : key;
};

const typeChipStyle = (active) =>
    "font-family:'Quicksand',sans-serif;font-weight:600;font-size:13px;border-radius:999px;padding:8px 14px;cursor:pointer;border:1.5px solid " +
    (active ? '#F28B82' : '#EFE3E4') + ';background:' + (active ? '#FCE7EB' : '#fff') + ';color:' + (active ? '#C0566B' : '#9a8d88');

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Word lid" />

        <span style="font-family: 'Dancing Script', cursive; font-size: 22px; color: #e0a24a">Leuk dat je er bij komt,</span>
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 26px; color: #473537; margin: 2px 0 6px; letter-spacing: -0.3px">Word lid van de community 💛</h1>
        <p style="font-size: 14px; color: #8a7d78; margin: 0 0 22px">Maak gratis een account aan en deel de reis van ouder tot ouder.</p>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Naam" />
                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="E-mailadres" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel value="Ik ben…" />
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px">
                    <button v-for="(lbl, key) in parentTypes" :key="key" type="button" @click="form.parent_type = key" :style="typeChipStyle(form.parent_type === key)">{{ lbl }}</button>
                </div>
                <InputError class="mt-2" :message="form.errors.parent_type" />
            </div>

            <div class="mt-4">
                <InputLabel value="Mijn rol als ouder (optioneel)" />
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px">
                    <button v-for="(lbl, key) in parentingRoles" :key="key" type="button" @click="toggleOptional('parenting_role', key)" :style="typeChipStyle(form.parenting_role === key)">{{ lbl }}</button>
                </div>
                <p style="font-size: 12.5px; color: #a99b96; margin: 7px 0 0">Kies bijvoorbeeld “Vader” om papa-functies en de papa-checklist te zien.</p>
                <InputError class="mt-2" :message="form.errors.parenting_role" />
            </div>

            <div class="mt-4">
                <InputLabel value="Geslacht (optioneel)" />
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px">
                    <button v-for="(lbl, key) in genders" :key="key" type="button" @click="toggleOptional('gender', key)" :style="typeChipStyle(form.gender === key)">{{ lbl }}</button>
                </div>
                <InputError class="mt-2" :message="form.errors.gender" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Wachtwoord" />
                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Bevestig wachtwoord" />
                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
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
                Account aanmaken
            </button>

            <p style="text-align: center; font-size: 14px; color: #8a7d78; margin: 20px 0 0">
                Al lid?
                <Link :href="route('login')" style="color: #c0566b; font-weight: 600; text-decoration: none">Log hier in</Link>
            </p>
        </form>
    </GuestLayout>
</template>
