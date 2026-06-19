<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({ groups: { type: Array, required: true } });

const remove = (g) => {
    if (confirm(`Groep "${g.name}" verwijderen?`)) router.delete(route('admin.groups.destroy', g.id), { preserveScroll: true });
};
</script>

<template>
    <Head title="Groepen · Beheer" />
    <AdminLayout>
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 6px">
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 26px; color: #473537; margin: 0; letter-spacing: -0.4px">Groepen</h1>
            <Link :href="route('admin.groups.create')" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border-radius: 999px; padding: 10px 20px; text-decoration: none; box-shadow: 0 8px 18px rgba(242, 139, 130, 0.3)">+ Nieuwe groep</Link>
        </div>
        <p style="font-size: 14.5px; color: #7a6c67; margin: 0 0 18px">Beheer community-groepen, ook die door leden zijn aangemaakt.</p>

        <div style="display: grid; gap: 10px">
            <div v-for="g in groups" :key="g.id" class="mlm-wrap-mobile" style="display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid #f2e7e2; border-radius: 16px; padding: 13px 18px; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)">
                <span :style="{ width: '40px', height: '40px', borderRadius: '13px', flex: 'none', background: 'linear-gradient(135deg,' + g.color_from + ',' + g.color_to + ')' }"></span>
                <div style="flex: 1; min-width: 0">
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14.5px; color: #473537">{{ g.name }}</div>
                    <div style="font-size: 13px; color: #8a7d78; white-space: nowrap; overflow: hidden; text-overflow: ellipsis">{{ g.members }} leden<span v-if="g.owner"> · door {{ g.owner }}</span><span v-if="g.description"> · {{ g.description }}</span></div>
                </div>
                <Link :href="route('admin.groups.edit', g.id)" style="flex: none; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #5d514d; background: #faf4f1; border: 1px solid #f0e6e2; border-radius: 999px; padding: 8px 13px; text-decoration: none">Bewerk</Link>
                <button @click="remove(g)" style="flex: none; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #b4574e; background: #fdf0ef; border: 1px solid #f6cfca; border-radius: 999px; padding: 8px 13px; cursor: pointer">Verwijder</button>
            </div>
            <div v-if="!groups.length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 16px; padding: 30px; text-align: center; color: #8a7d78">Nog geen groepen.</div>
        </div>
    </AdminLayout>
</template>
