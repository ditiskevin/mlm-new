<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import FollowButton from '@/Components/FollowButton.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    member: { type: Object, required: true },
    type: { type: String, required: true },
    people: { type: Array, default: () => [] },
});

const avatarStyle = (color) =>
    `width:48px;height:48px;border-radius:50%;flex:none;background:${color};display:flex;align-items:center;justify-content:center;font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:19px`;
</script>

<template>
    <Head :title="(type === 'volgers' ? 'Volgers' : 'Volgend') + ' · ' + member.name" />
    <MlmLayout>
        <section style="max-width: 640px; margin: 0 auto; padding: 32px 0 8px">
            <Link :href="route('members.show', member.id)" style="font-size: 13.5px; font-weight: 600; color: #c0566b; text-decoration: none">‹ Terug naar {{ member.name }}</Link>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 28px; color: #473537; margin: 12px 0 18px; letter-spacing: -0.4px">
                {{ type === 'volgers' ? 'Volgers van' : 'Gevolgd door' }} {{ member.name }}
            </h1>

            <div style="display: grid; gap: 10px">
                <div v-for="p in people" :key="p.id" style="display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid #f2e7e2; border-radius: 16px; padding: 13px 18px; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)">
                    <Link :href="route('members.show', p.id)" style="flex: none">
                        <img v-if="p.avatar_url" :src="p.avatar_url" alt="" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover" />
                        <span v-else :style="avatarStyle(p.avatar_color)">{{ p.initial }}</span>
                    </Link>
                    <div style="flex: 1; min-width: 0">
                        <Link :href="route('members.show', p.id)" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537; text-decoration: none">{{ p.name }}</Link>
                        <div style="font-size: 12.5px; color: #9a8d88"><span v-if="p.username" style="color: #c0566b; font-weight: 600">@{{ p.username }}</span><span v-if="p.username && p.role"> · </span>{{ p.role }}</div>
                    </div>
                    <FollowButton :user-id="p.id" :following="false" />
                </div>
                <div v-if="!people.length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 16px; padding: 34px; text-align: center; color: #8a7d78">Nog niemand hier.</div>
            </div>
        </section>
    </MlmLayout>
</template>
