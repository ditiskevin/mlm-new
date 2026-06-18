<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    audiences: { type: Array, required: true },
});

const remove = (slug) => {
    if (confirm('Doelgroep verwijderen? De gekoppelde groep en het forum blijven bestaan (alleen ontkoppeld).')) {
        router.delete(route('admin.audiences.destroy', slug), { preserveScroll: true });
    }
};
</script>

<template>
    <Head title="Doelgroepen beheren" />
    <MlmLayout>
        <section style="padding: 38px 0 8px">
            <Link :href="route('admin.dashboard')" style="font-size: 13.5px; font-weight: 600; color: #c0566b; text-decoration: none">‹ Terug naar beheer</Link>
            <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin: 14px 0 22px">
                <div>
                    <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 28px; color: #473537; margin: 0 0 4px; letter-spacing: -0.4px">Doelgroepen beheren</h1>
                    <p style="font-size: 14.5px; color: #7a6c67; margin: 0">Deze doelgroepen verschijnen in het "Voor wie"-menu én als optie bij "Ik ben…" op het profiel.</p>
                </div>
                <Link :href="route('admin.audiences.create')" style="flex: none; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 12px 22px; text-decoration: none">+ Nieuwe doelgroep</Link>
            </div>

            <div style="display: grid; gap: 10px">
                <div v-for="a in audiences" :key="a.slug" style="display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid #f2e7e2; border-radius: 16px; padding: 14px 18px; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)">
                    <span style="font-size: 26px">{{ a.emoji }}</span>
                    <div style="flex: 1; min-width: 0">
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537">{{ a.name }}</div>
                        <div style="font-size: 13px; color: #8a7d78">{{ a.tagline }} · {{ a.articles_count }} artikel(en)</div>
                    </div>
                    <Link :href="route('audiences.show', a.slug)" style="font-size: 12.5px; font-weight: 600; color: #5d514d; text-decoration: none; background: #faf4f1; border: 1px solid #f0e6e2; border-radius: 999px; padding: 8px 14px">Bekijken</Link>
                    <Link :href="route('admin.audiences.edit', a.slug)" style="font-size: 12.5px; font-weight: 600; color: #c0566b; text-decoration: none; background: #fce7eb; border-radius: 999px; padding: 8px 14px">Bewerken</Link>
                    <button @click="remove(a.slug)" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #b4574e; background: #fdf0ef; border: 1px solid #f6cfca; border-radius: 999px; padding: 8px 14px; cursor: pointer">Verwijderen</button>
                </div>
                <div v-if="!audiences.length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 16px; padding: 30px; text-align: center; color: #8a7d78">Nog geen doelgroepen.</div>
            </div>
        </section>
    </MlmLayout>
</template>
