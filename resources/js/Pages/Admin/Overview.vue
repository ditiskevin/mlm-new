<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: { type: Array, required: true },
    alerts: { type: Array, required: true },
    recent: { type: Object, required: true },
});
</script>

<template>
    <Head title="Beheer" />
    <AdminLayout>
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 28px; color: #473537; margin: 0 0 4px; letter-spacing: -0.4px">Overzicht</h1>
        <p style="font-size: 15px; color: #7a6c67; margin: 0 0 22px">Beheer alle onderdelen van de community vanuit één plek.</p>

        <!-- Alerts -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 24px">
            <Link
                v-for="a in alerts"
                :key="a.label"
                :href="route(a.route)"
                :style="{
                    display: 'flex',
                    alignItems: 'center',
                    gap: '14px',
                    textDecoration: 'none',
                    borderRadius: '18px',
                    padding: '16px 18px',
                    border: '1px solid ' + (a.value ? '#f6cfca' : '#eee5e0'),
                    background: a.value ? 'linear-gradient(150deg,#fdf2f4,#fff)' : '#fff',
                }"
            >
                <span style="font-size: 26px">{{ a.emoji }}</span>
                <div>
                    <div :style="{ fontFamily: 'Poppins, sans-serif', fontWeight: 700, fontSize: '22px', color: a.value ? '#c0566b' : '#473537' }">{{ a.value }}</div>
                    <div style="font-size: 12.5px; color: #8a7d78; line-height: 1.3">{{ a.label }}</div>
                </div>
            </Link>
        </div>

        <!-- Stat tiles -->
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 28px">
            <Link
                v-for="s in stats"
                :key="s.label"
                :href="route(s.route)"
                style="text-decoration: none; background: #fff; border: 1px solid #f2e7e2; border-radius: 16px; padding: 14px; text-align: center; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)"
            >
                <div style="font-size: 20px">{{ s.emoji }}</div>
                <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 20px; color: #473537">{{ s.value }}</div>
                <div style="font-size: 11px; color: #9a8d88">{{ s.label }}</div>
            </Link>
        </div>

        <!-- Recent -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px">
            <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 18px; padding: 18px 20px">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px">
                    <span style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 15px; color: #473537">Nieuwe leden</span>
                    <Link :href="route('admin.users.index')" style="font-size: 12.5px; color: #c0566b; text-decoration: none; font-weight: 600">Alle leden</Link>
                </div>
                <div v-for="m in recent.members" :key="m.id" style="display: flex; justify-content: space-between; padding: 7px 0; border-top: 1px solid #f7f1ee">
                    <Link :href="route('members.show', m.id)" style="font-size: 13.5px; color: #473537; text-decoration: none; font-weight: 600">{{ m.name }}</Link>
                    <span style="font-size: 12px; color: #b5a8a3">{{ m.when }}</span>
                </div>
                <div v-if="!recent.members.length" style="font-size: 13px; color: #9a8d88; padding: 8px 0">Nog geen leden.</div>
            </div>

            <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 18px; padding: 18px 20px">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px">
                    <span style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 15px; color: #473537">Laatste berichten</span>
                    <Link :href="route('admin.posts.index')" style="font-size: 12.5px; color: #c0566b; text-decoration: none; font-weight: 600">Alle berichten</Link>
                </div>
                <div v-for="p in recent.posts" :key="p.id" style="padding: 7px 0; border-top: 1px solid #f7f1ee">
                    <div style="font-size: 13px; color: #5d514d; line-height: 1.4">{{ p.excerpt }}</div>
                    <div style="font-size: 11.5px; color: #b5a8a3">{{ p.author }} · {{ p.when }}</div>
                </div>
                <div v-if="!recent.posts.length" style="font-size: 13px; color: #9a8d88; padding: 8px 0">Nog geen berichten.</div>
            </div>
        </div>
    </AdminLayout>
</template>
