<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    reports: { type: Array, required: true },
    counts: { type: Object, required: true },
    activeStatus: { type: String, default: 'open' },
});

const setStatus = (report, status) => router.patch(route('admin.reports.status', report), { status }, { preserveScroll: true });
const removeReport = (report) => {
    if (confirm('Deze melding verwijderen?')) router.delete(route('admin.reports.destroy', report), { preserveScroll: true });
};
const removeContent = (report) => {
    if (confirm('De gemelde inhoud definitief verwijderen? Dit kan niet ongedaan worden gemaakt.')) {
        router.delete(route('admin.reports.content.destroy', report), { preserveScroll: true });
    }
};

const tabs = [
    { key: 'open', label: 'Open', emoji: '🚩' },
    { key: 'reviewed', label: 'Behandeld', emoji: '✅' },
    { key: 'dismissed', label: 'Afgewezen', emoji: '🗑️' },
];
const tabStyle = (a) =>
    "font-family:'Quicksand',sans-serif;font-weight:600;font-size:13.5px;border-radius:999px;padding:9px 15px;cursor:pointer;text-decoration:none;border:1.5px solid " +
    (a ? '#F28B82' : '#EFE3E4') + ';background:' + (a ? '#FCE7EB' : '#fff') + ';color:' + (a ? '#C0566B' : '#9a8d88');
</script>

<template>
    <Head title="Meldingen · Beheer" />
    <MlmLayout>
        <section style="padding: 38px 0 8px">
            <Link :href="route('admin.dashboard')" style="font-size: 13px; color: #9a8d88; text-decoration: none">← Terug naar beheer</Link>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 32px; color: #473537; margin: 8px 0 6px; letter-spacing: -0.4px">Meldingen</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 18px">Door leden gerapporteerde inhoud en profielen.</p>

            <div style="display: flex; gap: 8px; margin-bottom: 20px">
                <Link v-for="t in tabs" :key="t.key" :href="route('admin.reports.index', { status: t.key })" :style="tabStyle(activeStatus === t.key)">
                    {{ t.emoji }} {{ t.label }} ({{ counts[t.key] }})
                </Link>
            </div>

            <div style="display: grid; gap: 12px">
                <article v-for="r in reports" :key="r.id" style="background: #fff; border: 1px solid #f2e7e2; border-radius: 18px; padding: 18px 22px; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)">
                    <div style="display: flex; align-items: flex-start; gap: 14px">
                        <div style="flex: 1; min-width: 0">
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 6px">
                                <span style="font-size: 11px; font-weight: 700; color: #7a6c67; background: #f3ece9; border-radius: 999px; padding: 3px 10px; text-transform: uppercase; letter-spacing: 0.4px">{{ r.target.label }}</span>
                                <span style="font-size: 12px; font-weight: 600; color: #b4574e; background: #fdf0ef; border-radius: 999px; padding: 3px 10px">{{ r.reason }}</span>
                            </div>
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537">{{ r.target.title }}</div>
                            <p v-if="r.target.excerpt" style="margin: 4px 0 0; font-size: 13.5px; line-height: 1.55; color: #8a7d78">{{ r.target.excerpt }}</p>
                            <p v-if="r.details" style="margin: 8px 0 0; font-size: 13.5px; color: #5d514d; background: #faf4f1; border-radius: 10px; padding: 8px 12px">“{{ r.details }}”</p>
                            <div style="font-size: 12.5px; color: #b5a8a3; margin-top: 8px">Gemeld door {{ r.reporter }} · {{ r.when }}</div>
                        </div>
                        <div style="flex: none; display: grid; gap: 8px; width: 150px">
                            <a v-if="r.target.url" :href="r.target.url" target="_blank" style="text-align: center; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #5d514d; background: #faf4f1; border: 1px solid #f0e6e2; border-radius: 999px; padding: 8px 14px; text-decoration: none">Bekijk inhoud</a>
                            <button v-if="r.status !== 'reviewed'" @click="setStatus(r, 'reviewed')" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #5fa07c; background: #e4f3e9; border: 1px solid #b7e1c0; border-radius: 999px; padding: 8px 14px; cursor: pointer">Markeer behandeld</button>
                            <button v-if="r.status !== 'dismissed'" @click="setStatus(r, 'dismissed')" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #9a8d88; background: #f7f1ee; border: 1px solid #ece0db; border-radius: 999px; padding: 8px 14px; cursor: pointer">Afwijzen</button>
                            <button @click="removeContent(r)" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #b4574e; background: #fdf0ef; border: 1px solid #f6cfca; border-radius: 999px; padding: 8px 14px; cursor: pointer">Verwijder inhoud</button>
                            <button @click="removeReport(r)" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 11.5px; color: #b5a8a3; background: none; border: none; cursor: pointer">melding wissen</button>
                        </div>
                    </div>
                </article>

                <div v-if="!reports.length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 16px; padding: 36px; text-align: center; color: #8a7d78">Geen meldingen in deze categorie. 🌿</div>
            </div>
        </section>
    </MlmLayout>
</template>
