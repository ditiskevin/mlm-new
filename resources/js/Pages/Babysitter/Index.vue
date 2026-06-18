<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    sitters: { type: Array, required: true },
    locations: { type: Array, required: true },
    filters: { type: Object, default: () => ({}) },
});

const page = usePage();
const isAuth = computed(() => !!page.props.auth?.user);
const search = ref(props.filters.q ?? '');

const visit = (params) => {
    const next = { location: props.filters.location, q: props.filters.q || undefined, ...params };
    Object.keys(next).forEach((k) => (next[k] == null || next[k] === '') && delete next[k]);
    router.get(route('babysitters.index'), next, { preserveScroll: true, preserveState: true });
};

const pillStyle = (active) =>
    "flex:none;font-family:'Quicksand',sans-serif;font-weight:600;font-size:13px;border-radius:999px;padding:8px 15px;cursor:pointer;border:1.5px solid " +
    (active ? '#5FB07F' : '#E3EFE7') +
    ';background:' + (active ? '#E4F3E9' : '#fff') + ';color:' + (active ? '#5E9E78' : '#9a8d88');
</script>

<template>
    <Head title="Oppas vinden" />
    <MlmLayout>
        <section style="padding: 42px 0 8px">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-bottom: 18px">
                <div>
                    <span style="font-family: 'Dancing Script', cursive; font-size: 26px; color: #e0a24a">Vertrouwd &amp; dichtbij</span>
                    <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 34px; color: #473537; margin: 2px 0 6px; letter-spacing: -0.4px">Oppas vinden</h1>
                    <p style="font-size: 15px; color: #7a6c67; margin: 0; max-width: 560px">Vind een betrouwbare oppas bij jou in de buurt, of meld je zelf aan om op te passen. Van ouder tot ouder.</p>
                </div>
                <Link
                    :href="isAuth ? route('babysitters.create') : route('login')"
                    style="flex: none; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #fff; background: linear-gradient(135deg, #8fd0a6, #5fb07f); border: none; border-radius: 999px; padding: 13px 24px; text-decoration: none; box-shadow: 0 10px 22px rgba(95, 176, 127, 0.3)"
                    >+ Meld je aan als oppas</Link
                >
            </div>

            <div style="display: flex; align-items: center; gap: 10px; background: #fff; border: 1px solid #f0e6e2; border-radius: 999px; padding: 11px 18px; max-width: 420px; margin-bottom: 14px">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="#C9B8B3" stroke-width="2" /><path d="m20 20-3.5-3.5" stroke="#C9B8B3" stroke-width="2" stroke-linecap="round" /></svg>
                <input v-model="search" @keyup.enter="visit({ q: search })" placeholder="Zoek op naam of beschrijving…" style="border: none; outline: none; background: none; font-family: 'Quicksand', sans-serif; font-size: 14px; color: #5d514d; width: 100%" />
            </div>

            <div class="mlm-scroll" style="display: flex; gap: 7px; overflow-x: auto; padding-bottom: 8px; margin-bottom: 22px">
                <button @click="visit({ location: undefined })" :style="pillStyle(!filters.location)">Alle plaatsen</button>
                <button v-for="loc in locations" :key="loc" @click="visit({ location: loc })" :style="pillStyle(filters.location === loc)">{{ loc }}</button>
            </div>

            <div v-if="sitters.length" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px">
                <Link
                    v-for="s in sitters"
                    :key="s.id"
                    :href="route('babysitters.show', s.id)"
                    style="display: block; background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 22px; text-decoration: none; box-shadow: 0 8px 22px rgba(180, 150, 150, 0.07)"
                >
                    <div style="display: flex; align-items: center; gap: 13px; margin-bottom: 12px">
                        <span :style="{ width: '52px', height: '52px', borderRadius: '50%', flex: 'none', background: s.avatar_color, display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#fff', fontSize: '20px' }">{{ s.initial }}</span>
                        <div>
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 17px; color: #473537">{{ s.name }}<span v-if="s.age" style="font-weight: 500; color: #9a8d88">, {{ s.age }}</span></div>
                            <div style="font-size: 12.5px; color: #9a8d88">📍 {{ s.location }}</div>
                        </div>
                    </div>
                    <p style="font-size: 13.5px; line-height: 1.55; color: #8a7d78; margin: 0 0 12px">{{ s.excerpt }}</p>
                    <div style="display: flex; align-items: center; justify-content: space-between">
                        <span style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 15px; color: #5fa07c">{{ s.hourly_rate ? '€ ' + s.hourly_rate + ' /uur' : 'In overleg' }}</span>
                        <span v-if="s.has_vog" style="font-size: 11.5px; font-weight: 600; color: #5e9e78; background: #e4f3e9; border-radius: 999px; padding: 4px 10px">✓ VOG</span>
                    </div>
                </Link>
            </div>
            <div v-else style="background: #fff; border: 1px dashed #cde8d6; border-radius: 22px; padding: 40px; text-align: center; color: #8a7d78">
                Nog geen oppas gevonden. Meld je aan en wees de eerste! 💛
            </div>
        </section>
    </MlmLayout>
</template>
