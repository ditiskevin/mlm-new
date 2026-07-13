<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    parentTypes: { type: Object, default: () => ({ ouder: 'Ouder' }) },
    parentingRoles: { type: Object, default: () => ({}) },
});

const form = useForm({
    is_expecting: false,
    due_date: null,
    children_count: null,
    parent_type: null,
    parenting_role: null,
});

// Click a selected chip again to clear an optional choice.
const toggleOptional = (field, key) => {
    form[field] = form[field] === key ? null : key;
};

const setExpecting = (value) => {
    form.is_expecting = value;
    if (!value) form.due_date = null;
};

const chipStyle = (active) =>
    "font-family:'Quicksand',sans-serif;font-weight:600;font-size:13px;border-radius:999px;padding:8px 14px;cursor:pointer;border:1.5px solid " +
    (active ? '#F28B82' : '#EFE3E4') + ';background:' + (active ? '#FCE7EB' : '#fff') + ';color:' + (active ? '#C0566B' : '#9a8d88');

const toggleStyle = (active) =>
    "font-family:'Poppins',sans-serif;font-weight:600;font-size:14px;border-radius:999px;padding:11px 22px;cursor:pointer;border:1.5px solid " +
    (active ? '#F28B82' : '#EFE3E4') + ';background:' + (active ? 'linear-gradient(135deg,#F7A8B5,#F28B82)' : '#fff') + ';color:' + (active ? '#fff' : '#9a8d88');

const submit = () => {
    form.post(route('onboarding.store'));
};
</script>

<template>
    <Head title="Welkom" />
    <MlmLayout>
        <section style="padding: 42px 0 40px; max-width: 620px; margin: 0 auto">
            <div style="text-align: center; margin-bottom: 26px">
                <span style="font-family: 'Dancing Script', cursive; font-size: 24px; color: #e0a24a">Wat leuk dat je er bent,</span>
                <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 28px; color: #473537; margin: 2px 0 8px; letter-spacing: -0.3px">Vertel ons kort iets over jou 💛</h1>
                <p style="font-size: 14.5px; color: #8a7d78; margin: 0">Zo maken we de app persoonlijk. Je kunt dit later altijd aanpassen.</p>
            </div>

            <form @submit.prevent="submit" style="background: #fff; border: 1px solid #f2e7e2; border-radius: 26px; padding: 30px 32px; box-shadow: 0 12px 30px rgba(214, 150, 160, 0.12)">
                <!-- Verwacht je een kindje? -->
                <div>
                    <label style="display: block; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15.5px; color: #473537; margin-bottom: 10px">Verwacht je een kindje?</label>
                    <div style="display: flex; gap: 10px">
                        <button type="button" @click="setExpecting(true)" :style="toggleStyle(form.is_expecting === true)">Ja, ik ben zwanger 🤰</button>
                        <button type="button" @click="setExpecting(false)" :style="toggleStyle(form.is_expecting === false)">Nee</button>
                    </div>
                    <InputError class="mt-2" :message="form.errors.is_expecting" />
                </div>

                <!-- Uitgerekende datum -->
                <div v-if="form.is_expecting" style="margin-top: 22px">
                    <label for="due_date" style="display: block; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15.5px; color: #473537; margin-bottom: 8px">Wanneer ben je uitgerekend?</label>
                    <input
                        id="due_date"
                        type="date"
                        v-model="form.due_date"
                        style="font-family: 'Quicksand', sans-serif; font-size: 15px; color: #473537; border: 1.5px solid #efe3e4; border-radius: 14px; padding: 11px 14px; width: 100%; max-width: 260px"
                    />
                    <p style="font-size: 12.5px; color: #a99b96; margin: 7px 0 0">We laten je dan een handige “je bent … weken” teller zien op je dashboard.</p>
                    <InputError class="mt-2" :message="form.errors.due_date" />
                </div>

                <!-- Aantal kinderen -->
                <div style="margin-top: 22px">
                    <label for="children_count" style="display: block; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15.5px; color: #473537; margin-bottom: 8px">Hoeveel kinderen heb je al?</label>
                    <input
                        id="children_count"
                        type="number"
                        min="0"
                        max="12"
                        v-model="form.children_count"
                        placeholder="0"
                        style="font-family: 'Quicksand', sans-serif; font-size: 15px; color: #473537; border: 1.5px solid #efe3e4; border-radius: 14px; padding: 11px 14px; width: 120px"
                    />
                    <InputError class="mt-2" :message="form.errors.children_count" />
                </div>

                <!-- Ik ben… -->
                <div style="margin-top: 22px">
                    <label style="display: block; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15.5px; color: #473537; margin-bottom: 10px">Ik ben… (optioneel)</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px">
                        <button v-for="(lbl, key) in parentTypes" :key="key" type="button" @click="toggleOptional('parent_type', key)" :style="chipStyle(form.parent_type === key)">{{ lbl }}</button>
                    </div>
                    <InputError class="mt-2" :message="form.errors.parent_type" />
                </div>

                <!-- Je rol -->
                <div style="margin-top: 22px">
                    <label style="display: block; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15.5px; color: #473537; margin-bottom: 10px">Jouw rol als ouder (optioneel)</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px">
                        <button v-for="(lbl, key) in parentingRoles" :key="key" type="button" @click="toggleOptional('parenting_role', key)" :style="chipStyle(form.parenting_role === key)">{{ lbl }}</button>
                    </div>
                    <InputError class="mt-2" :message="form.errors.parenting_role" />
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    :style="{
                        width: '100%',
                        marginTop: '28px',
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
                    Klaar, naar mijn dashboard →
                </button>

                <p style="text-align: center; font-size: 14px; color: #8a7d78; margin: 18px 0 0">
                    <Link :href="route('dashboard')" style="color: #c0566b; font-weight: 600; text-decoration: none">Sla over</Link>
                </p>
            </form>
        </section>
    </MlmLayout>
</template>
