<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    articles: { type: Array, required: true },
});

const remove = (slug) => {
    if (confirm('Dit artikel definitief verwijderen?')) {
        router.delete(route('admin.articles.destroy', slug), { preserveScroll: true });
    }
};

const statusBadge = {
    published: { label: 'gepubliceerd', color: '#5fa07c', bg: '#e4f3e9' },
    pending: { label: 'wacht op review', color: '#b07b1f', bg: '#fbeed3' },
    rejected: { label: 'afgewezen', color: '#9a8d88', bg: '#f3ece9' },
};
</script>

<template>
    <Head title="Blogbeheer · Beheer" />
    <AdminLayout>
        <section style="padding: 38px 0 8px">
            <Link :href="route('admin.dashboard')" style="font-size: 13px; color: #9a8d88; text-decoration: none">← Terug naar beheer</Link>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin: 8px 0 6px">
                <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 32px; color: #473537; margin: 0; letter-spacing: -0.4px">Blogbeheer</h1>
                <Link :href="route('admin.articles.create')" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border-radius: 999px; padding: 11px 22px; text-decoration: none; box-shadow: 0 8px 18px rgba(242, 139, 130, 0.3)">+ Nieuw artikel</Link>
            </div>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 20px">Maak, bewerk en publiceer blogartikelen.</p>

            <div style="display: grid; gap: 10px">
                <div
                    v-for="a in articles"
                    :key="a.slug"
                    style="display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid #f2e7e2; border-radius: 16px; padding: 13px 18px; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)"
                >
                    <span :style="{ flex: 'none', width: '40px', height: '40px', borderRadius: '12px', background: '#fce7eb', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '20px' }">{{ a.emoji }}</span>
                    <div style="flex: 1; min-width: 0">
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14.5px; color: #473537">
                            {{ a.title }}
                            <span :style="{ marginLeft: '8px', fontSize: '11px', fontWeight: 600, borderRadius: '999px', padding: '2px 9px', color: (statusBadge[a.status] || statusBadge.pending).color, background: (statusBadge[a.status] || statusBadge.pending).bg }">{{ (statusBadge[a.status] || statusBadge.pending).label }}</span>
                        </div>
                        <div style="font-size: 13px; color: #8a7d78">{{ a.category }} · {{ a.author }}<span v-if="a.published"> · {{ a.published }}</span></div>
                    </div>
                    <Link :href="route('admin.articles.edit', a.slug)" style="flex: none; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #5d514d; background: #faf4f1; border: 1px solid #f0e6e2; border-radius: 999px; padding: 8px 14px; text-decoration: none">Bewerken</Link>
                    <button @click="remove(a.slug)" style="flex: none; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #b4574e; background: #fdf0ef; border: 1px solid #f6cfca; border-radius: 999px; padding: 8px 14px; cursor: pointer">Verwijderen</button>
                </div>
                <div v-if="!articles.length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 16px; padding: 36px; text-align: center; color: #8a7d78">Nog geen artikelen. Maak er één aan! ✍️</div>
            </div>
        </section>
    </AdminLayout>
</template>
