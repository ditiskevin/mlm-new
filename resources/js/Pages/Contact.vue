<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);

const form = useForm({
    name: user.value?.name ?? '',
    email: user.value?.email ?? '',
    subject: '',
    message: '',
    website: '', // honeypot
});

const submit = () => {
    form.post(route('contact.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset('subject', 'message'),
    });
};

const labelStyle = "display:block;font-family:'Poppins',sans-serif;font-weight:600;font-size:13px;color:#5d514d;margin:0 0 6px";
const inputStyle =
    "width:100%;box-sizing:border-box;font-family:'Quicksand',sans-serif;font-size:14.5px;color:#473537;background:#fff;border:1.5px solid #EFE3E4;border-radius:14px;padding:12px 15px;outline:none";

const channels = [
    { icon: '✉️', title: 'E-mail', text: 'hello@mommylovesme.nl', sub: 'We reageren meestal binnen 1–2 werkdagen.' },
    { icon: '💬', title: 'Community', text: 'Stel je vraag in het forum', sub: 'Vaak weten andere ouders het antwoord al.' },
    { icon: '🛟', title: 'Iets melden?', text: 'Gebruik de meldknop bij berichten', sub: 'Zo houden we de community veilig.' },
];
</script>

<template>
    <Head title="Contact" />
    <MlmLayout>
        <section style="padding: 42px 0 8px">
            <span style="font-family: 'Dancing Script', cursive; font-size: 26px; color: #e0a24a">We horen graag van je</span>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 34px; color: #473537; margin: 2px 0 8px; letter-spacing: -0.4px">Contact</h1>
            <p style="font-size: 15.5px; line-height: 1.65; color: #7a6c67; max-width: 640px; margin: 0">
                Een vraag, een idee of gewoon even gedag zeggen? Laat hieronder een berichtje achter, dan komen we zo snel mogelijk bij je terug.
            </p>

            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; margin-top: 30px; align-items: start">
                <!-- Form -->
                <form
                    @submit.prevent="submit"
                    style="background: #fff; border: 1px solid #f2e7e2; border-radius: 24px; padding: 28px; box-shadow: 0 10px 26px rgba(180, 150, 150, 0.08)"
                >
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px">
                        <div>
                            <label :style="labelStyle" for="name">Je naam</label>
                            <input id="name" v-model="form.name" type="text" :style="inputStyle" autocomplete="name" />
                            <div v-if="form.errors.name" style="color: #d9695f; font-size: 12.5px; margin-top: 5px">{{ form.errors.name }}</div>
                        </div>
                        <div>
                            <label :style="labelStyle" for="email">E-mailadres</label>
                            <input id="email" v-model="form.email" type="email" :style="inputStyle" autocomplete="email" />
                            <div v-if="form.errors.email" style="color: #d9695f; font-size: 12.5px; margin-top: 5px">{{ form.errors.email }}</div>
                        </div>
                    </div>

                    <div style="margin-top: 16px">
                        <label :style="labelStyle" for="subject">Onderwerp</label>
                        <input id="subject" v-model="form.subject" type="text" :style="inputStyle" placeholder="Waar gaat je bericht over?" />
                        <div v-if="form.errors.subject" style="color: #d9695f; font-size: 12.5px; margin-top: 5px">{{ form.errors.subject }}</div>
                    </div>

                    <div style="margin-top: 16px">
                        <label :style="labelStyle" for="message">Je bericht</label>
                        <textarea id="message" v-model="form.message" rows="6" :style="inputStyle + ';resize:vertical;line-height:1.6'" placeholder="Vertel ons waar we je mee kunnen helpen…"></textarea>
                        <div v-if="form.errors.message" style="color: #d9695f; font-size: 12.5px; margin-top: 5px">{{ form.errors.message }}</div>
                    </div>

                    <!-- Honeypot (hidden from humans) -->
                    <input v-model="form.website" type="text" tabindex="-1" autocomplete="off" aria-hidden="true" style="position: absolute; left: -9999px; width: 1px; height: 1px; opacity: 0" />

                    <button
                        type="submit"
                        :disabled="form.processing"
                        :style="{
                            marginTop: '22px',
                            fontFamily: 'Poppins, sans-serif',
                            fontWeight: 600,
                            fontSize: '15px',
                            color: '#fff',
                            background: 'linear-gradient(135deg, #f7a8b5, #f28b82)',
                            border: 'none',
                            borderRadius: '999px',
                            padding: '14px 30px',
                            cursor: form.processing ? 'default' : 'pointer',
                            opacity: form.processing ? 0.65 : 1,
                            boxShadow: '0 10px 22px rgba(242, 139, 130, 0.32)',
                        }"
                    >
                        {{ form.processing ? 'Versturen…' : 'Verstuur bericht 💌' }}
                    </button>
                </form>

                <!-- Channels -->
                <div style="display: grid; gap: 14px">
                    <div
                        v-for="c in channels"
                        :key="c.title"
                        style="background: linear-gradient(160deg, #fff, #fdf6f3); border: 1px solid #f2e7e2; border-radius: 20px; padding: 20px 22px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)"
                    >
                        <div style="font-size: 24px; margin-bottom: 6px">{{ c.icon }}</div>
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15.5px; color: #473537">{{ c.title }}</div>
                        <div style="font-size: 14px; color: #c0566b; font-weight: 600; margin: 3px 0">{{ c.text }}</div>
                        <p style="margin: 0; font-size: 13px; line-height: 1.55; color: #8a7d78">{{ c.sub }}</p>
                    </div>
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
