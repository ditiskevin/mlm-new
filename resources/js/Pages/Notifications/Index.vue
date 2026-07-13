<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    notifications: { type: Array, default: () => [] },
});

const markAll = () => router.patch(route('notifications.read-all'), {}, { preserveScroll: true });
const remove = (id) => router.delete(route('notifications.destroy', id), { preserveScroll: true });

const open = (n) => {
    if (!n.read) {
        n.read = true;
        router.patch(route('notifications.read', n.id), {}, { preserveScroll: true, preserveState: true, only: [] });
    }
    if (n.url) router.visit(n.url);
};
</script>

<template>
    <Head title="Notificaties" />
    <MlmLayout>
        <section style="max-width: 680px; margin: 0 auto; padding: 32px 0 8px">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 6px">
                <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 30px; color: #473537; margin: 0; letter-spacing: -0.4px">Notificaties</h1>
                <button v-if="notifications.some((n) => !n.read)" @click="markAll" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13px; color: #c0566b; background: #fce7eb; border: none; border-radius: 999px; padding: 9px 16px; cursor: pointer">Alles gelezen</button>
            </div>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 20px">Alles wat er speelt rond jou en je berichten. 💛</p>

            <div style="display: grid; gap: 10px">
                <div
                    v-for="n in notifications"
                    :key="n.id"
                    @click="open(n)"
                    :style="{ cursor: 'pointer', display: 'flex', gap: '14px', alignItems: 'flex-start', padding: '16px 18px', background: '#fff', border: '1px solid ' + (n.read ? '#f2e7e2' : '#f6cfca'), borderRadius: '16px', boxShadow: '0 6px 16px rgba(180,150,150,0.05)' }"
                >
                    <span style="font-size: 24px; flex: none">{{ n.icon }}</span>
                    <div style="flex: 1; min-width: 0">
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14.5px; color: #473537">{{ n.title }}</div>
                        <div v-if="n.body" style="font-size: 13.5px; color: #8a7d78; line-height: 1.5; margin-top: 2px">{{ n.body }}</div>
                        <div style="font-size: 12px; color: #b5a8a3; margin-top: 5px">{{ n.when }}</div>
                    </div>
                    <button @click.prevent.stop="remove(n.id)" style="flex: none; font-size: 12.5px; color: #b5a8a3; background: none; border: none; cursor: pointer">✕</button>
                </div>
                <div v-if="!notifications.length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 16px; padding: 40px; text-align: center; color: #8a7d78">Nog geen notificaties. Zodra er iets gebeurt zie je het hier. 🌿</div>
            </div>
        </section>
    </MlmLayout>
</template>
