<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const props = defineProps({
    type: { type: String, default: 'aanbod' },
});

const form = useForm({
    type: props.type,
    name: page.props.auth?.user?.name ?? '',
    age: '',
    location: '',
    hourly_rate: '',
    experience_years: '',
    availability: '',
    has_vog: false,
    bio: '',
});

const isOffer = computed(() => form.type === 'aanbod');

const label = 'display:block;font-family:\'Poppins\',sans-serif;font-weight:600;font-size:13.5px;color:#473537;margin-bottom:6px';
const field = 'width:100%;font-family:\'Quicksand\',sans-serif;font-size:14px;color:#5d514d;background:#fff;border:1px solid #f0e6e2;border-radius:14px;padding:11px 14px;outline:none';
const err = 'color:#b4574e;font-size:12.5px;margin-top:4px';

const toggleStyle = (active) =>
    "flex:1;text-align:center;font-family:'Poppins',sans-serif;font-weight:600;font-size:14px;border-radius:14px;padding:12px;cursor:pointer;border:1.5px solid " +
    (active ? '#F28B82' : '#EFE3E4') + ';background:' + (active ? '#FCE7EB' : '#fff') + ';color:' + (active ? '#C0566B' : '#9a8d88');

const submit = () => form.post(route('babysitters.store'));
</script>

<template>
    <Head title="Aanmelden als oppas" />
    <MlmLayout>
        <section style="max-width: 680px; margin: 0 auto; padding: 36px 0 8px">
            <Link :href="route('babysitters.index')" style="font-size: 13.5px; font-weight: 600; color: #5fa07c; text-decoration: none">‹ Terug naar oppas vinden</Link>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 28px; color: #473537; margin: 14px 0 4px; letter-spacing: -0.4px">{{ isOffer ? 'Aanmelden als oppas' : 'Oproep: oppas gezocht' }}</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 22px">{{ isOffer ? 'Maak een profiel aan zodat ouders je kunnen vinden.' : 'Plaats een oproep zodat oppassers bij jou in de buurt kunnen reageren.' }}</p>

            <form @submit.prevent="submit" style="display: grid; gap: 18px; background: #fff; border: 1px solid #f2e7e2; border-radius: 24px; padding: 26px; box-shadow: 0 10px 30px rgba(180, 150, 150, 0.08)">
                <!-- Type toggle -->
                <div>
                    <label :style="label">Wat wil je plaatsen?</label>
                    <div style="display: flex; gap: 10px">
                        <button type="button" @click="form.type = 'aanbod'" :style="toggleStyle(isOffer)">🧸 Ik bied oppas aan</button>
                        <button type="button" @click="form.type = 'gezocht'" :style="toggleStyle(!isOffer)">🔎 Ik zoek een oppas</button>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px">
                    <div>
                        <label :style="label">{{ isOffer ? 'Naam' : 'Naam (van jou of je gezin)' }}</label>
                        <input v-model="form.name" type="text" :style="field" />
                        <div v-if="form.errors.name" :style="err">{{ form.errors.name }}</div>
                    </div>
                    <div v-if="isOffer">
                        <label :style="label">Leeftijd (optioneel)</label>
                        <input v-model="form.age" type="number" min="14" max="99" :style="field" />
                        <div v-if="form.errors.age" :style="err">{{ form.errors.age }}</div>
                    </div>
                </div>

                <div :style="{ display: 'grid', gridTemplateColumns: isOffer ? '1fr 1fr 1fr' : '1fr 1fr', gap: '16px' }">
                    <div>
                        <label :style="label">Plaats</label>
                        <input v-model="form.location" type="text" :style="field" placeholder="Bijv. Utrecht" />
                        <div v-if="form.errors.location" :style="err">{{ form.errors.location }}</div>
                    </div>
                    <div>
                        <label :style="label">{{ isOffer ? 'Tarief €/uur' : 'Budget €/uur (optioneel)' }}</label>
                        <input v-model="form.hourly_rate" type="number" step="0.50" min="0" :style="field" placeholder="9,00" />
                        <div v-if="form.errors.hourly_rate" :style="err">{{ form.errors.hourly_rate }}</div>
                    </div>
                    <div v-if="isOffer">
                        <label :style="label">Ervaring (jaar)</label>
                        <input v-model="form.experience_years" type="number" min="0" max="60" :style="field" placeholder="0" />
                        <div v-if="form.errors.experience_years" :style="err">{{ form.errors.experience_years }}</div>
                    </div>
                </div>

                <div>
                    <label :style="label">{{ isOffer ? 'Beschikbaarheid' : 'Wanneer heb je oppas nodig?' }}</label>
                    <input v-model="form.availability" type="text" :style="field" :placeholder="isOffer ? 'Bijv. Avonden & weekenden' : 'Bijv. Doordeweeks na schooltijd'" />
                    <div v-if="form.errors.availability" :style="err">{{ form.errors.availability }}</div>
                </div>

                <label v-if="isOffer" style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-family: 'Quicksand', sans-serif; font-size: 14px; color: #5d514d">
                    <input type="checkbox" v-model="form.has_vog" style="width: 18px; height: 18px; accent-color: #5fb07f" />
                    Ik beschik over een geldige VOG (Verklaring Omtrent Gedrag)
                </label>

                <div>
                    <label :style="label">{{ isOffer ? 'Over jou' : 'Wat zoek je?' }}</label>
                    <textarea v-model="form.bio" rows="5" :style="field" :placeholder="isOffer ? 'Vertel iets over jezelf, je ervaring en met welke leeftijden je het liefst werkt…' : 'Vertel iets over je gezin, de leeftijd(en) van je kind(eren) en wat voor oppas je zoekt…'"></textarea>
                    <div v-if="form.errors.bio" :style="err">{{ form.errors.bio }}</div>
                </div>

                <div style="display: flex; gap: 12px; align-items: center">
                    <button type="submit" :disabled="form.processing" :style="{ fontFamily: 'Poppins, sans-serif', fontWeight: 600, fontSize: '15px', color: '#fff', background: isOffer ? 'linear-gradient(135deg, #8fd0a6, #5fb07f)' : 'linear-gradient(135deg, #f7a8b5, #f28b82)', border: 'none', borderRadius: '999px', padding: '13px 26px', cursor: 'pointer', boxShadow: '0 10px 22px rgba(95, 176, 127, 0.3)' }">{{ isOffer ? 'Profiel plaatsen' : 'Oproep plaatsen' }}</button>
                    <Link :href="route('babysitters.index')" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 14px; color: #9a8d88; text-decoration: none">Annuleren</Link>
                </div>
            </form>
        </section>
    </MlmLayout>
</template>
