<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';

const props = defineProps({
    lists: { type: Object, required: true }, // { uitzet: [...], vluchttas: [...] }
});

const page = usePage();
const isAuth = computed(() => !!page.props.auth?.user);

const activeType = ref('uitzet');

// Reactive set of checked item ids, seeded from the server state.
const checked = reactive(new Set());
for (const type of ['uitzet', 'vluchttas']) {
    for (const cat of props.lists[type]) {
        for (const it of cat.items) {
            if (it.done) checked.add(it.id);
        }
    }
}

const activeCats = computed(() => props.lists[activeType.value]);

const stats = computed(() => {
    let total = 0;
    let done = 0;
    for (const cat of activeCats.value) {
        for (const it of cat.items) {
            total++;
            if (checked.has(it.id)) done++;
        }
    }
    return { total, done, pct: total ? Math.round((done / total) * 100) : 0 };
});

const persist = (id) => {
    if (!isAuth.value) return;
    router.post(route('checklist.toggle', id), {}, { preserveScroll: true, preserveState: true, only: [] });
};

const toggle = (item) => {
    if (checked.has(item.id)) checked.delete(item.id);
    else checked.add(item.id);
    persist(item.id);
};

const reset = () => {
    for (const cat of activeCats.value) {
        for (const it of cat.items) {
            if (checked.has(it.id)) {
                checked.delete(it.id);
                persist(it.id);
            }
        }
    }
};

const catCount = (cat) => `${cat.items.filter((it) => checked.has(it.id)).length}/${cat.items.length}`;

const tabStyle = (active) =>
    "font-family:'Poppins',sans-serif;font-weight:600;font-size:14px;border-radius:999px;padding:11px 22px;cursor:pointer;border:1px solid " +
    (active ? 'transparent' : '#F0E6E2') +
    ';' +
    (active ? 'background:#F28B82;color:#fff;box-shadow:0 6px 16px rgba(242,139,130,0.28)' : 'background:#fff;color:#9a8d88');

const boxStyle = (done) =>
    'flex:none;width:22px;height:22px;border-radius:7px;display:flex;align-items:center;justify-content:center;transition:all .12s;' +
    (done ? 'background:linear-gradient(135deg,#F7A8B5,#F28B82);border:1.5px solid #F28B82' : 'background:#fff;border:1.5px solid #E6D6D2');

const labelStyle = (done) =>
    "font-size:14px;font-family:'Quicksand',sans-serif;transition:all .12s;" + (done ? 'color:#c2b6b1;text-decoration:line-through' : 'color:#5d514d');
</script>

<template>
    <Head title="Checklists" />
    <MlmLayout>
        <section style="padding: 38px 0 8px">
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 32px; color: #473537; margin: 0 0 4px; letter-spacing: -0.4px">Checklists</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 22px">
                Vink af wat je al hebt - <template v-if="isAuth">je voortgang wordt automatisch bewaard. ✅</template
                ><template v-else>maak een account aan om je voortgang te bewaren. ✅</template>
            </p>

            <div style="display: flex; gap: 8px; margin-bottom: 20px">
                <button @click="activeType = 'uitzet'" :style="tabStyle(activeType === 'uitzet')">Baby uitzet</button>
                <button @click="activeType = 'vluchttas'" :style="tabStyle(activeType === 'vluchttas')">Vluchttas / ziekenhuis</button>
            </div>

            <div style="background: linear-gradient(135deg, #fce7eb, #eaf5ee); border-radius: 24px; padding: 24px 26px; margin-bottom: 22px; display: flex; align-items: center; gap: 24px; box-shadow: 0 10px 26px rgba(214, 150, 160, 0.12)">
                <div style="flex: none">
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 38px; color: #473537; line-height: 1">{{ stats.pct }}%</div>
                    <div style="font-size: 13px; color: #8a7d78">{{ stats.done }} / {{ stats.total }} afgevinkt</div>
                </div>
                <div style="flex: 1">
                    <div style="height: 14px; background: #fff; border-radius: 999px; overflow: hidden; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05)">
                        <div :style="{ height: '100%', width: stats.pct + '%', background: 'linear-gradient(90deg,#F7A8B5,#F28B82)', borderRadius: '999px', transition: 'width .35s ease' }"></div>
                    </div>
                </div>
                <button @click="reset" style="flex: none; background: #fff; border: 1px solid #f0d6dc; color: #c0566b; font-weight: 600; font-size: 13px; border-radius: 999px; padding: 9px 16px; cursor: pointer">Reset</button>
            </div>

            <div style="columns: 2; column-gap: 18px">
                <div v-for="cat in activeCats" :key="cat.name" style="break-inside: avoid; margin-bottom: 18px; background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; padding: 18px 20px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px">
                        <span style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 16px; color: #473537">{{ cat.name }}</span>
                        <span style="font-size: 12px; font-weight: 600; color: #9a8d88; background: #faf4f1; border-radius: 999px; padding: 3px 10px">{{ catCount(cat) }}</span>
                    </div>
                    <div style="display: grid; gap: 3px">
                        <button v-for="it in cat.items" :key="it.id" @click="toggle(it)" style="display: flex; align-items: center; gap: 11px; background: none; border: none; cursor: pointer; text-align: left; padding: 7px 4px; border-radius: 10px; width: 100%">
                            <span :style="boxStyle(checked.has(it.id))">
                                <svg v-if="checked.has(it.id)" width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M9 16.2 4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4Z" fill="#fff" /></svg>
                            </span>
                            <span :style="labelStyle(checked.has(it.id))">{{ it.label }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
