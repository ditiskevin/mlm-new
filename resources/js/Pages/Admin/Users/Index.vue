<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    users: { type: Array, required: true },
    filters: { type: Object, default: () => ({}) },
});

const q = ref(props.filters.q ?? '');
const search = () => router.get(route('admin.users.index'), { q: q.value || undefined }, { preserveState: true, preserveScroll: true });

const toggleAdmin = (u) => router.patch(route('admin.users.toggle-admin', u.id), {}, { preserveScroll: true });
const remove = (u) => {
    if (confirm(`Lid "${u.name}" definitief verwijderen?`)) router.delete(route('admin.users.destroy', u.id), { preserveScroll: true });
};
</script>

<template>
    <Head title="Leden · Beheer" />
    <AdminLayout>
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 26px; color: #473537; margin: 0 0 4px; letter-spacing: -0.4px">Leden</h1>
        <p style="font-size: 14.5px; color: #7a6c67; margin: 0 0 18px">Beheer accounts: bewerk gegevens, geef of ontneem beheerrechten, of verwijder een lid.</p>

        <div style="display: flex; align-items: center; gap: 10px; background: #fff; border: 1px solid #f0e6e2; border-radius: 999px; padding: 10px 16px; max-width: 360px; margin-bottom: 16px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="#C9B8B3" stroke-width="2" /><path d="m20 20-3.5-3.5" stroke="#C9B8B3" stroke-width="2" stroke-linecap="round" /></svg>
            <input v-model="q" @keyup.enter="search" placeholder="Zoek op naam of e-mail…" style="border: none; outline: none; background: none; font-family: 'Quicksand', sans-serif; font-size: 14px; color: #5d514d; width: 100%" />
        </div>

        <div style="display: grid; gap: 10px">
            <div
                v-for="u in users"
                :key="u.id"
                style="display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid #f2e7e2; border-radius: 16px; padding: 13px 18px; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)"
            >
                <div style="flex: 1; min-width: 0">
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14.5px; color: #473537">
                        {{ u.name }}
                        <span v-if="u.is_admin" style="margin-left: 8px; font-size: 11px; font-weight: 600; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 3px 9px">beheerder</span>
                    </div>
                    <div style="font-size: 13px; color: #8a7d78">{{ u.email }} · {{ u.role }} · {{ u.posts_count }} berichten · sinds {{ u.when }}</div>
                </div>
                <Link :href="u.profile_url" style="flex: none; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #5d514d; background: #faf4f1; border: 1px solid #f0e6e2; border-radius: 999px; padding: 8px 13px; text-decoration: none">Profiel</Link>
                <Link :href="route('admin.users.edit', u.id)" style="flex: none; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #5d514d; background: #faf4f1; border: 1px solid #f0e6e2; border-radius: 999px; padding: 8px 13px; text-decoration: none">Bewerk</Link>
                <template v-if="!u.is_self">
                    <button @click="toggleAdmin(u)" style="flex: none; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #5d514d; background: #faf4f1; border: 1px solid #f0e6e2; border-radius: 999px; padding: 8px 13px; cursor: pointer">{{ u.is_admin ? 'Intrekken' : 'Maak beheerder' }}</button>
                    <button @click="remove(u)" style="flex: none; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #b4574e; background: #fdf0ef; border: 1px solid #f6cfca; border-radius: 999px; padding: 8px 13px; cursor: pointer">Verwijder</button>
                </template>
                <span v-else style="flex: none; font-size: 12px; color: #b5a8a3">jij</span>
            </div>
            <div v-if="!users.length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 16px; padding: 30px; text-align: center; color: #8a7d78">Geen leden gevonden.</div>
        </div>
    </AdminLayout>
</template>
