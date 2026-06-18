<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    listings: { type: Array, required: true },
    categories: { type: Array, required: true },
    offerTypes: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

const page = usePage();
const isAuth = computed(() => !!page.props.auth?.user);
const search = ref(props.filters.q ?? '');

const offerEntries = computed(() => Object.entries(props.offerTypes)); // [[key,label],...]

const visit = (params) => {
    const next = { category: props.filters.category, type: props.filters.type, q: props.filters.q || undefined, ...params };
    Object.keys(next).forEach((k) => (next[k] == null || next[k] === '') && delete next[k]);
    router.get(route('marketplace.index'), next, { preserveScroll: true, preserveState: true });
};

const offerColor = (type) =>
    type === 'gratis' ? '#5FB07F' : type === 'gevraagd' ? '#7AA8DC' : type === 'ruil' ? '#A87FD0' : '#C0566B';

const pillStyle = (active) =>
    "flex:none;font-family:'Quicksand',sans-serif;font-weight:600;font-size:13px;border-radius:999px;padding:8px 15px;cursor:pointer;text-decoration:none;border:1.5px solid " +
    (active ? '#F28B82' : '#EFE3E4') +
    ';background:' + (active ? '#FCE7EB' : '#fff') + ';color:' + (active ? '#C0566B' : '#9a8d88');
</script>

<template>
    <Head title="Marktplaats" />
    <MlmLayout>
        <section style="padding: 42px 0 8px">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-bottom: 18px">
                <div>
                    <span style="font-family: 'Dancing Script', cursive; font-size: 26px; color: #e0a24a">Van ouder tot ouder</span>
                    <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 34px; color: #473537; margin: 2px 0 6px; letter-spacing: -0.4px">Marktplaats</h1>
                    <p style="font-size: 15px; color: #7a6c67; margin: 0; max-width: 560px">Bied babyspullen aan, ruil of vind iets moois tweedehands. Geef spullen een tweede leven binnen de community.</p>
                </div>
                <Link
                    :href="isAuth ? route('marketplace.create') : route('login')"
                    style="flex: none; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 13px 24px; text-decoration: none; box-shadow: 0 10px 22px rgba(242, 139, 130, 0.32)"
                    >+ Plaats advertentie</Link
                >
            </div>

            <!-- search -->
            <div style="display: flex; align-items: center; gap: 10px; background: #fff; border: 1px solid #f0e6e2; border-radius: 999px; padding: 11px 18px; max-width: 420px; margin-bottom: 14px">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="#C9B8B3" stroke-width="2" /><path d="m20 20-3.5-3.5" stroke="#C9B8B3" stroke-width="2" stroke-linecap="round" /></svg>
                <input v-model="search" @keyup.enter="visit({ q: search })" placeholder="Zoek in de marktplaats…" style="border: none; outline: none; background: none; font-family: 'Quicksand', sans-serif; font-size: 14px; color: #5d514d; width: 100%" />
            </div>

            <!-- type filter -->
            <div class="mlm-scroll" style="display: flex; gap: 7px; overflow-x: auto; padding-bottom: 8px; margin-bottom: 8px">
                <button @click="visit({ type: undefined })" :style="pillStyle(!filters.type)">Alles</button>
                <button v-for="[key, label] in offerEntries" :key="key" @click="visit({ type: key })" :style="pillStyle(filters.type === key)">{{ label }}</button>
            </div>
            <!-- category filter -->
            <div class="mlm-scroll" style="display: flex; gap: 7px; overflow-x: auto; padding-bottom: 8px; margin-bottom: 22px">
                <button @click="visit({ category: undefined })" :style="pillStyle(!filters.category)">Alle categorieën</button>
                <button v-for="c in categories" :key="c" @click="visit({ category: c })" :style="pillStyle(filters.category === c)">{{ c }}</button>
            </div>

            <div v-if="listings.length" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px">
                <Link
                    v-for="l in listings"
                    :key="l.slug"
                    :href="route('marketplace.show', l.slug)"
                    style="display: block; background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; overflow: hidden; text-decoration: none; box-shadow: 0 8px 22px rgba(180, 150, 150, 0.07)"
                >
                    <div style="position: relative; height: 130px; display: flex; align-items: center; justify-content: center; font-size: 50px; background: linear-gradient(150deg, #fce7eb, #eaf5ee); overflow: hidden">
                        <img v-if="l.image_url" :src="l.image_url" alt="" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover" />
                        <span v-else>{{ l.emoji }}</span>
                        <span :style="{ position: 'absolute', top: '12px', left: '12px', fontFamily: 'Poppins, sans-serif', fontWeight: 600, fontSize: '11px', color: '#fff', background: offerColor(l.offer_type), borderRadius: '999px', padding: '4px 11px' }">{{ l.offer_label }}</span>
                    </div>
                    <div style="padding: 16px 18px 18px">
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 16px; line-height: 1.3; color: #473537; margin-bottom: 5px">{{ l.title }}</div>
                        <div style="font-size: 13px; color: #8a7d78; margin-bottom: 10px">{{ l.excerpt }}</div>
                        <div style="display: flex; align-items: center; justify-content: space-between">
                            <span style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 16px; color: #c0566b">{{ l.price ? '€ ' + l.price : l.offer_label }}</span>
                            <span style="font-size: 12px; color: #9a8d88">📍 {{ l.location }}</span>
                        </div>
                    </div>
                </Link>
            </div>
            <div v-else style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 22px; padding: 40px; text-align: center; color: #8a7d78">
                Nog geen advertenties gevonden. Wees de eerste die iets plaatst! 💛
            </div>
        </section>
    </MlmLayout>
</template>
