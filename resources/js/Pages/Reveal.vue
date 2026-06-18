<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';

const props = defineProps({
    vertellen: { type: Array, required: true }, // [{key,label,ideas:[{title,description}]}]
    gender: { type: Array, required: true },
    cards: { type: Array, required: true },
    names: { type: Object, required: true },
    favCount: { type: Number, default: 0 },
});

const page = usePage();
const isAuth = computed(() => !!page.props.auth?.user);

const initialTab = new URLSearchParams(page.url.split('?')[1] || '').get('tab') === 'namen' ? 'namen' : 'vertellen';
const mainTab = ref(initialTab); // vertellen | gender | kaartje | namen
const vertellenSub = ref(props.vertellen[0]?.key ?? null);
const genderSub = ref(props.gender[0]?.key ?? null);

const nameType = ref('meisjes');
const nameQuery = ref('');

const mainTabs = [
    { key: 'vertellen', label: 'Vertellen' },
    { key: 'gender', label: 'Gender reveal' },
    { key: 'kaartje', label: 'Geboortekaartjes' },
    { key: 'namen', label: 'Babynamen' },
];

const subCategories = computed(() => (mainTab.value === 'vertellen' ? props.vertellen : mainTab.value === 'gender' ? props.gender : []));
const activeSub = computed({
    get: () => (mainTab.value === 'vertellen' ? vertellenSub.value : genderSub.value),
    set: (v) => (mainTab.value === 'vertellen' ? (vertellenSub.value = v) : (genderSub.value = v)),
});
const activeCategory = computed(() => subCategories.value.find((c) => c.key === activeSub.value) ?? null);

const blobs = ['#FCE7EB', '#E4F3E9', '#E1EEFB', '#FBEFD8', '#EEE6F6'];

// Favourite names
const favs = reactive(new Set());
for (const type of ['meisjes', 'jongens', 'tweeling']) {
    for (const n of props.names[type]) {
        if (n.fav) favs.add(n.id);
    }
}
const filteredNames = computed(() => {
    const q = nameQuery.value.toLowerCase();
    return props.names[nameType.value].filter((n) => n.name.toLowerCase().includes(q));
});
const toggleFav = (n) => {
    if (favs.has(n.id)) favs.delete(n.id);
    else favs.add(n.id);
    if (isAuth.value) {
        router.post(route('names.favourite', n.id), {}, { preserveScroll: true, preserveState: true, only: [] });
    }
};
const setNameType = (t) => {
    nameType.value = t;
    nameQuery.value = '';
};

const mainTabStyle = (active) =>
    "flex:none;font-family:'Poppins',sans-serif;font-weight:600;font-size:14px;border-radius:999px;padding:11px 22px;cursor:pointer;border:1px solid " +
    (active ? 'transparent' : '#F0E6E2') +
    ';' +
    (active ? 'background:#F28B82;color:#fff;box-shadow:0 6px 16px rgba(242,139,130,0.28)' : 'background:#fff;color:#9a8d88');

const subTabStyle = (active) =>
    "flex:none;font-family:'Quicksand',sans-serif;font-weight:600;font-size:13px;border-radius:999px;padding:8px 15px;cursor:pointer;border:1.5px solid " +
    (active ? '#F28B82' : '#EFE3E4') +
    ';background:' +
    (active ? '#FCE7EB' : '#fff') +
    ';color:' +
    (active ? '#C0566B' : '#9a8d88');

const nameTypeStyle = (active) =>
    "font-family:'Quicksand',sans-serif;font-weight:600;font-size:13.5px;border-radius:999px;padding:9px 15px;cursor:pointer;border:1px solid " +
    (active ? 'transparent' : '#F0E6E2') +
    ';' +
    (active ? 'background:#FCE7EB;color:#C0566B' : 'background:#fff;color:#9a8d88');

const nameChipStyle = (fav) =>
    "display:inline-flex;align-items:center;gap:7px;font-family:'Quicksand',sans-serif;font-weight:600;font-size:14px;border-radius:999px;padding:9px 15px;cursor:pointer;border:1.5px solid " +
    (fav ? '#F28B82' : '#EFE3E4') +
    ';background:' +
    (fav ? '#FCE7EB' : '#fff') +
    ';color:' +
    (fav ? '#C0566B' : '#5d514d');
</script>

<template>
    <Head title="Hoera Zwanger!" />
    <MlmLayout>
        <section style="padding: 38px 0 8px">
            <span style="font-family: 'Dancing Script', cursive; font-size: 26px; color: #e0a24a">Hoera, zwanger!</span>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 32px; color: #473537; margin: 2px 0 4px; letter-spacing: -0.4px">Vertellen, vieren &amp; namen kiezen</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 22px">Honderden ideeën om het grote nieuws te delen, een gender reveal te vieren, geboortekaartjes te schrijven en de mooiste naam te vinden.</p>

            <!-- main tabs -->
            <div class="mlm-scroll" style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 8px; margin-bottom: 18px">
                <button v-for="t in mainTabs" :key="t.key" @click="mainTab = t.key" :style="mainTabStyle(mainTab === t.key)">{{ t.label }}</button>
            </div>

            <!-- sub-category tabs for vertellen / gender -->
            <div v-if="mainTab === 'vertellen' || mainTab === 'gender'" class="mlm-scroll" style="display: flex; gap: 7px; overflow-x: auto; padding-bottom: 8px; margin-bottom: 22px">
                <button v-for="c in subCategories" :key="c.key" @click="activeSub = c.key" :style="subTabStyle(activeSub === c.key)">{{ c.label }}</button>
            </div>

            <!-- ideas grid (vertellen / gender) -->
            <template v-if="(mainTab === 'vertellen' || mainTab === 'gender') && activeCategory">
                <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 20px; color: #473537; margin: 0 0 16px">{{ activeCategory.label }}</h2>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px">
                    <div v-for="(idea, i) in activeCategory.ideas" :key="idea.title" style="background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; padding: 20px 22px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06); position: relative; overflow: hidden">
                        <span :style="{ position: 'absolute', top: '-14px', right: '-14px', width: '60px', height: '60px', borderRadius: '50%', background: blobs[i % blobs.length], opacity: 0.5 }"></span>
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 16px; color: #473537; margin-bottom: 6px; position: relative">{{ idea.title }}</div>
                        <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #7a6c67; position: relative">{{ idea.description }}</p>
                    </div>
                </div>
            </template>

            <!-- geboortekaartjes -->
            <template v-else-if="mainTab === 'kaartje'">
                <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 20px; color: #473537; margin: 0 0 16px">Geboortekaartje-teksten</h2>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px">
                    <div v-for="c in cards" :key="c.title" style="background: linear-gradient(160deg, #fff6f8, #f4faf6); border: 1.5px dashed #f0cdd4; border-radius: 20px; padding: 22px; text-align: center">
                        <div style="font-family: 'Dancing Script', cursive; font-size: 24px; color: #473537; margin-bottom: 8px">{{ c.title }}</div>
                        <div style="font-size: 12.5px; font-weight: 600; letter-spacing: 0.4px; text-transform: uppercase; color: #c99ba2">{{ c.description }}</div>
                    </div>
                </div>
            </template>

            <!-- babynamen -->
            <template v-else-if="mainTab === 'namen'">
                <div style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center; margin-bottom: 18px">
                    <div style="display: flex; gap: 6px">
                        <button @click="setNameType('meisjes')" :style="nameTypeStyle(nameType === 'meisjes')">Meisjes</button>
                        <button @click="setNameType('jongens')" :style="nameTypeStyle(nameType === 'jongens')">Jongens</button>
                        <button @click="setNameType('tweeling')" :style="nameTypeStyle(nameType === 'tweeling')">Tweeling</button>
                    </div>
                    <div style="flex: 1; min-width: 200px; display: flex; align-items: center; gap: 10px; background: #fff; border: 1px solid #f0e6e2; border-radius: 999px; padding: 10px 18px">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="#C9B8B3" stroke-width="2" /><path d="m20 20-3.5-3.5" stroke="#C9B8B3" stroke-width="2" stroke-linecap="round" /></svg>
                        <input v-model="nameQuery" placeholder="Zoek een naam…" style="border: none; outline: none; background: none; font-family: 'Quicksand', sans-serif; font-size: 14px; color: #5d514d; width: 100%" />
                    </div>
                    <div style="font-size: 13px; color: #9a8d88; display: flex; align-items: center; gap: 6px">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="#F28B82"><path d="M12 20s-7-4.6-9.3-9.1C1 7.6 2.7 4.5 6 4.5c2 0 3.2 1.1 4 2.3.8-1.2 2-2.3 4-2.3 3.3 0 5 3.1 3.3 6.4C19 15.4 12 20 12 20Z" /></svg>
                        <b style="color: #c0566b">{{ favs.size }}</b> favoriet
                    </div>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 10px">
                    <button v-for="n in filteredNames" :key="n.id" @click="toggleFav(n)" :style="nameChipStyle(favs.has(n.id))">
                        <svg width="15" height="15" viewBox="0 0 24 24" :fill="favs.has(n.id) ? '#F28B82' : 'none'"><path d="M12 20s-7-4.6-9.3-9.1C1 7.6 2.7 4.5 6 4.5c2 0 3.2 1.1 4 2.3.8-1.2 2-2.3 4-2.3 3.3 0 5 3.1 3.3 6.4C19 15.4 12 20 12 20Z" stroke="#E0A9B1" stroke-width="1.5" /></svg>
                        {{ n.name }}
                    </button>
                </div>
            </template>
        </section>
    </MlmLayout>
</template>
