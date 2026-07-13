<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    blocked: { type: Array, default: () => [] },
});

const busyId = ref(null);

const avatarStyle = (color, size = 46) =>
    `width:${size}px;height:${size}px;border-radius:50%;flex:none;background:${color};display:flex;align-items:center;justify-content:center;font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:${size > 40 ? 18 : 14}px;text-decoration:none;object-fit:cover`;

const unblock = (id) => {
    busyId.value = id;
    router.post(
        route('members.block', id),
        {},
        {
            preserveScroll: true,
            onFinish: () => {
                busyId.value = null;
            },
        },
    );
};
</script>

<template>
    <Head title="Geblokkeerde leden" />
    <MlmLayout>
        <section style="max-width: 680px; margin: 0 auto; padding: 38px 0 8px">
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 32px; color: #473537; margin: 0 0 4px; letter-spacing: -0.4px">Geblokkeerde leden</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 26px">Je ziet hun berichten niet meer en jullie kunnen elkaar geen bericht sturen.</p>

            <div v-if="blocked.length" style="display: grid; gap: 12px">
                <div v-for="m in blocked" :key="m.id" style="display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid #f2e7e2; border-radius: 18px; padding: 14px 18px; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)">
                    <Link :href="route('members.show', m.id)">
                        <img v-if="m.avatar_url" :src="m.avatar_url" alt="" :style="avatarStyle(m.avatar_color)" />
                        <span v-else :style="avatarStyle(m.avatar_color)">{{ m.initial }}</span>
                    </Link>
                    <div style="flex: 1; min-width: 0">
                        <Link :href="route('members.show', m.id)" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537; text-decoration: none">{{ m.name }}</Link>
                    </div>
                    <button
                        type="button"
                        :disabled="busyId === m.id"
                        @click="unblock(m.id)"
                        style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #c0566b; background: #fce7eb; border: 1.5px solid #f0d6dc; border-radius: 999px; padding: 9px 16px; cursor: pointer"
                    >
                        Deblokkeren
                    </button>
                </div>
            </div>

            <div v-else style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 18px; padding: 40px 30px; text-align: center; color: #8a7d78">
                <div style="font-size: 34px; margin-bottom: 8px">🚫</div>
                <p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6">Je hebt niemand geblokkeerd.</p>
                <Link
                    :href="route('community')"
                    style="display: inline-block; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border-radius: 999px; padding: 11px 24px; text-decoration: none; box-shadow: 0 8px 18px rgba(242, 139, 130, 0.3)"
                    >Naar de community</Link
                >
            </div>
        </section>
    </MlmLayout>
</template>
