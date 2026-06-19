<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    title: { type: String, required: true },
    description: { type: String, default: '' },
    columns: { type: Array, required: true },
    rows: { type: Array, default: () => [] },
    deleteRoute: { type: String, default: null },
});

const search = ref('');
const filtered = computed(() => {
    const q = search.value.trim().toLowerCase();
    if (!q) return props.rows;
    return props.rows.filter((r) => props.columns.some((c) => String(r[c.key] ?? '').toLowerCase().includes(q)));
});

const remove = (row) => {
    if (confirm('Weet je zeker dat je dit definitief wilt verwijderen?')) {
        router.delete(route(props.deleteRoute, row.id), { preserveScroll: true });
    }
};
</script>

<template>
    <Head :title="title + ' · Beheer'" />
    <AdminLayout>
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 26px; color: #473537; margin: 0 0 4px; letter-spacing: -0.4px">{{ title }}</h1>
        <p style="font-size: 14.5px; color: #7a6c67; margin: 0 0 18px">{{ description }}</p>

        <div style="display: flex; align-items: center; gap: 10px; background: #fff; border: 1px solid #f0e6e2; border-radius: 999px; padding: 10px 16px; max-width: 360px; margin-bottom: 16px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="#C9B8B3" stroke-width="2" /><path d="m20 20-3.5-3.5" stroke="#C9B8B3" stroke-width="2" stroke-linecap="round" /></svg>
            <input v-model="search" placeholder="Zoeken…" style="border: none; outline: none; background: none; font-family: 'Quicksand', sans-serif; font-size: 14px; color: #5d514d; width: 100%" />
        </div>

        <div class="mlm-table-wrap" style="background: #fff; border: 1px solid #f2e7e2; border-radius: 18px; overflow: hidden; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)">
            <table style="width: 100%; border-collapse: collapse">
                <thead>
                    <tr style="background: #faf6f3">
                        <th v-for="c in columns" :key="c.key" style="text-align: left; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 12px; color: #8a7d78; padding: 12px 16px; text-transform: uppercase; letter-spacing: 0.3px">{{ c.label }}</th>
                        <th style="width: 1%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in filtered" :key="row.id" style="border-top: 1px solid #f4ece8">
                        <td v-for="(c, ci) in columns" :key="c.key" :style="{ padding: '12px 16px', fontSize: '13.5px', color: ci === 0 ? '#473537' : '#7a6c67', fontWeight: ci === 0 ? 600 : 400, maxWidth: '320px' }">
                            <span style="display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap">{{ row[c.key] ?? '—' }}</span>
                        </td>
                        <td style="padding: 10px 16px; white-space: nowrap; text-align: right">
                            <a v-if="row.view_url" :href="row.view_url" target="_blank" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12px; color: #5d514d; background: #faf4f1; border: 1px solid #f0e6e2; border-radius: 999px; padding: 6px 12px; text-decoration: none; margin-right: 6px">Bekijk</a>
                            <button v-if="deleteRoute" @click="remove(row)" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12px; color: #b4574e; background: #fdf0ef; border: 1px solid #f6cfca; border-radius: 999px; padding: 6px 12px; cursor: pointer">Verwijder</button>
                        </td>
                    </tr>
                    <tr v-if="!filtered.length">
                        <td :colspan="columns.length + 1" style="padding: 30px; text-align: center; color: #8a7d78; font-size: 14px">Niets gevonden. 🌿</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="font-size: 12.5px; color: #b5a8a3; margin-top: 10px">{{ filtered.length }} van {{ rows.length }}</div>
    </AdminLayout>
</template>
