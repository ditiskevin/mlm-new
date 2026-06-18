<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

const props = defineProps({
    trimesters: { type: Array, required: true },
    support: { type: Array, required: true },
    activities: { type: Array, default: () => [] },
});

const page = usePage();
const isAuth = computed(() => !!page.props.auth?.user);

const checked = reactive(new Set());
for (const cat of props.activities) {
    for (const it of cat.items) {
        if (it.done) checked.add(it.id);
    }
}

const toggle = (item) => {
    if (checked.has(item.id)) checked.delete(item.id);
    else checked.add(item.id);
    if (isAuth.value) {
        router.post(route('checklist.toggle', item.id), {}, { preserveScroll: true, preserveState: true, only: [] });
    }
};

const boxStyle = (done) =>
    'flex:none;width:22px;height:22px;border-radius:7px;display:flex;align-items:center;justify-content:center;transition:all .12s;' +
    (done ? 'background:linear-gradient(135deg,#F7A8B5,#F28B82);border:1.5px solid #F28B82' : 'background:#fff;border:1.5px solid #E6D6D2');

const labelStyle = (done) =>
    "font-size:14px;font-family:'Quicksand',sans-serif;transition:all .12s;" + (done ? 'color:#c2b6b1;text-decoration:line-through' : 'color:#5d514d');
</script>

<template>
    <Head title="Voor de aanstaande vader" />
    <MlmLayout>
        <section style="padding: 42px 0 8px">
            <span style="font-family: 'Dancing Script', cursive; font-size: 26px; color: #e0a24a">Ook voor papa</span>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 34px; color: #473537; margin: 2px 0 8px; letter-spacing: -0.4px">Voor de aanstaande vader</h1>
            <p style="font-size: 15.5px; line-height: 1.65; color: #7a6c67; max-width: 640px; margin: 0">
                Jij speelt een grotere rol dan je misschien denkt. Hier vind je per trimester wat er gebeurt en hoe je je partner - en jezelf - door deze
                bijzondere reis heen helpt.
            </p>

            <!-- Trimester cards -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 28px">
                <div
                    v-for="t in trimesters"
                    :key="t.label"
                    style="background: #fff; border: 1px solid #f2e7e2; border-radius: 24px; padding: 24px; box-shadow: 0 10px 26px rgba(180, 150, 150, 0.08)"
                >
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px">
                        <span :style="{ width: '48px', height: '48px', borderRadius: '15px', background: t.color, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '24px' }">{{ t.emoji }}</span>
                        <div>
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 17px; color: #473537">{{ t.label }}</div>
                            <div style="font-size: 12.5px; color: #9a8d88">{{ t.weeks }}</div>
                        </div>
                    </div>
                    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: 10px">
                        <li v-for="tip in t.tips" :key="tip" style="display: flex; gap: 9px; font-size: 14px; line-height: 1.55; color: #6c5f5b">
                            <span style="color: #f28b82; flex: none">♥</span><span>{{ tip }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Support section -->
            <div style="margin-top: 34px; background: linear-gradient(160deg, #eef8f1, #faf8f5); border: 1px solid #dcefe3; border-radius: 28px; padding: 30px 30px 34px">
                <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 22px; color: #473537; margin: 0 0 4px">Zo steun je je partner</h2>
                <p style="color: #8a7d78; margin: 0 0 22px; font-size: 15px">Kleine dingen maken een groot verschil tijdens de zwangerschap.</p>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px">
                    <div v-for="s in support" :key="s.title" style="background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; padding: 20px 22px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)">
                        <div style="font-size: 26px; margin-bottom: 8px">{{ s.icon }}</div>
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 16px; color: #473537; margin-bottom: 5px">{{ s.title }}</div>
                        <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #7a6c67">{{ s.text }}</p>
                    </div>
                </div>
            </div>

            <!-- Afvinklijst -->
            <div style="margin-top: 34px">
                <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 22px; color: #473537; margin: 0 0 4px">Afvinklijst voor papa</h2>
                <p style="color: #8a7d78; margin: 0 0 18px; font-size: 15px">
                    Houd per trimester bij wat je al gedaan hebt.
                    <template v-if="isAuth"> Je voortgang wordt automatisch bewaard. ✅</template>
                    <template v-else> Log in om je voortgang te bewaren. ✅</template>
                </p>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px">
                    <div v-for="cat in activities" :key="cat.name" style="background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; padding: 20px 22px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)">
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 16px; color: #473537; margin-bottom: 12px">{{ cat.name }}</div>
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
            </div>

            <!-- CTA -->
            <div style="margin-top: 30px; display: flex; gap: 12px; flex-wrap: wrap; align-items: center">
                <Link
                    :href="route('calendar')"
                    style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 14px 28px; text-decoration: none; box-shadow: 0 10px 22px rgba(242, 139, 130, 0.32)"
                    >Bekijk de zwangerschapskalender</Link
                >
                <Link
                    :href="route('checklists')"
                    style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #5fa07c; background: #e4f3e9; border: 1.5px solid #b7e1c0; border-radius: 999px; padding: 14px 28px; text-decoration: none"
                    >Naar de checklists</Link
                >
            </div>
        </section>
    </MlmLayout>
</template>
