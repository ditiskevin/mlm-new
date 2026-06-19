<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({ categories: { type: Array, required: true } });

const remove = (c) => {
    if (confirm(`Categorie "${c.name}" verwijderen? Onderwerpen erin verdwijnen mogelijk mee.`)) {
        router.delete(route('admin.forum-categories.destroy', c.slug), { preserveScroll: true });
    }
};
</script>

<template>
    <Head title="Forumcategorieën · Beheer" />
    <AdminLayout>
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 6px">
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 26px; color: #473537; margin: 0; letter-spacing: -0.4px">Forumcategorieën</h1>
            <Link :href="route('admin.forum-categories.create')" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border-radius: 999px; padding: 10px 20px; text-decoration: none; box-shadow: 0 8px 18px rgba(242, 139, 130, 0.3)">+ Nieuwe categorie</Link>
        </div>
        <p style="font-size: 14.5px; color: #7a6c67; margin: 0 0 18px">Onderverdeel het forum in thema's.</p>

        <div style="display: grid; gap: 10px">
            <div v-for="c in categories" :key="c.slug" class="mlm-wrap-mobile" style="display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid #f2e7e2; border-radius: 16px; padding: 13px 18px; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)">
                <span :style="{ width: '40px', height: '40px', borderRadius: '13px', flex: 'none', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '20px', background: 'linear-gradient(135deg,' + c.color_from + ',' + c.color_to + ')' }">{{ c.emoji }}</span>
                <div style="flex: 1; min-width: 0">
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14.5px; color: #473537">{{ c.name }} <span style="font-weight: 400; color: #b5a8a3; font-size: 12.5px">· {{ c.topics_count }} onderwerpen</span></div>
                    <div style="font-size: 13px; color: #8a7d78; white-space: nowrap; overflow: hidden; text-overflow: ellipsis">{{ c.description }}</div>
                </div>
                <Link :href="route('admin.forum-categories.edit', c.slug)" style="flex: none; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #5d514d; background: #faf4f1; border: 1px solid #f0e6e2; border-radius: 999px; padding: 8px 13px; text-decoration: none">Bewerk</Link>
                <button @click="remove(c)" style="flex: none; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #b4574e; background: #fdf0ef; border: 1px solid #f6cfca; border-radius: 999px; padding: 8px 13px; cursor: pointer">Verwijder</button>
            </div>
            <div v-if="!categories.length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 16px; padding: 30px; text-align: center; color: #8a7d78">Nog geen categorieën.</div>
        </div>
    </AdminLayout>
</template>
