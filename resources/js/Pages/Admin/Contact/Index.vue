<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    messages: { type: Array, required: true },
});

const filter = ref('open'); // open | all
const visible = computed(() => (filter.value === 'open' ? props.messages.filter((m) => !m.handled) : props.messages));
const openCount = computed(() => props.messages.filter((m) => !m.handled).length);

const toggleHandled = (id) => router.patch(route('admin.contact.toggle', id), {}, { preserveScroll: true });
const remove = (id) => {
    if (confirm('Dit contactbericht definitief verwijderen?')) {
        router.delete(route('admin.contact.destroy', id), { preserveScroll: true });
    }
};

const tabStyle = (a) =>
    "font-family:'Quicksand',sans-serif;font-weight:600;font-size:13.5px;border-radius:999px;padding:9px 15px;cursor:pointer;border:1.5px solid " +
    (a ? '#F28B82' : '#EFE3E4') + ';background:' + (a ? '#FCE7EB' : '#fff') + ';color:' + (a ? '#C0566B' : '#9a8d88');
</script>

<template>
    <Head title="Contactberichten · Beheer" />
    <MlmLayout>
        <section style="padding: 38px 0 8px">
            <Link :href="route('admin.dashboard')" style="font-size: 13px; color: #9a8d88; text-decoration: none">← Terug naar beheer</Link>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 32px; color: #473537; margin: 8px 0 6px; letter-spacing: -0.4px">Contactberichten</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 18px">Berichten die via het contactformulier zijn binnengekomen.</p>

            <div style="display: flex; gap: 8px; margin-bottom: 20px">
                <button @click="filter = 'open'" :style="tabStyle(filter === 'open')">📬 Open ({{ openCount }})</button>
                <button @click="filter = 'all'" :style="tabStyle(filter === 'all')">🗂️ Alle ({{ messages.length }})</button>
            </div>

            <div style="display: grid; gap: 12px">
                <article
                    v-for="m in visible"
                    :key="m.id"
                    :style="{
                        background: '#fff',
                        border: '1px solid ' + (m.handled ? '#eee5e0' : '#f6cfca'),
                        borderRadius: '18px',
                        padding: '18px 22px',
                        boxShadow: '0 6px 16px rgba(180, 150, 150, 0.05)',
                        opacity: m.handled ? 0.72 : 1,
                    }"
                >
                    <div style="display: flex; align-items: flex-start; gap: 14px">
                        <div style="flex: 1; min-width: 0">
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap">
                                <span style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 16px; color: #473537">{{ m.subject }}</span>
                                <span v-if="m.is_member" style="font-size: 11px; font-weight: 600; color: #5fa07c; background: #e4f3e9; border-radius: 999px; padding: 2px 9px">lid</span>
                                <span v-if="m.handled" style="font-size: 11px; font-weight: 600; color: #9a8d88; background: #f3ece9; border-radius: 999px; padding: 2px 9px">afgehandeld</span>
                            </div>
                            <div style="font-size: 13px; color: #8a7d78; margin: 4px 0 12px">
                                {{ m.name }} ·
                                <a :href="'mailto:' + m.email" style="color: #c0566b; text-decoration: none">{{ m.email }}</a>
                                · {{ m.when }}
                            </div>
                            <p style="margin: 0; font-size: 14.5px; line-height: 1.6; color: #5d514d; white-space: pre-wrap">{{ m.message }}</p>
                        </div>
                        <div style="flex: none; display: grid; gap: 8px">
                            <button @click="toggleHandled(m.id)" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #5d514d; background: #faf4f1; border: 1px solid #f0e6e2; border-radius: 999px; padding: 8px 14px; cursor: pointer; white-space: nowrap">
                                {{ m.handled ? 'Heropenen' : 'Afgehandeld' }}
                            </button>
                            <button @click="remove(m.id)" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #b4574e; background: #fdf0ef; border: 1px solid #f6cfca; border-radius: 999px; padding: 8px 14px; cursor: pointer">Verwijderen</button>
                        </div>
                    </div>
                </article>

                <div v-if="!visible.length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 16px; padding: 36px; text-align: center; color: #8a7d78">
                    {{ filter === 'open' ? 'Geen open berichten. Mooi werk! 🌿' : 'Nog geen contactberichten.' }}
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
