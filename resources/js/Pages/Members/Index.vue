<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import FollowButton from '@/Components/FollowButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    members: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
    total: { type: Number, default: 0 },
});

const q = ref(props.filters.q ?? '');
const search = () => router.get(route('members.index'), { q: q.value || undefined }, { preserveState: true, preserveScroll: true });

const avatarStyle = (color) =>
    `width:56px;height:56px;border-radius:50%;flex:none;background:${color};display:flex;align-items:center;justify-content:center;font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:22px`;
</script>

<template>
    <Head title="Leden" />
    <MlmLayout>
        <section style="padding: 42px 0 8px">
            <span style="font-family: 'Dancing Script', cursive; font-size: 26px; color: #e0a24a">Ons kleine dorp</span>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 34px; color: #473537; margin: 2px 0 8px; letter-spacing: -0.4px">Leden</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 20px">Ontdek de {{ total }} ouders in de community, volg elkaar en maak vrienden. 💛</p>

            <div style="display: flex; align-items: center; gap: 10px; background: #fff; border: 1px solid #f0e6e2; border-radius: 999px; padding: 11px 18px; max-width: 420px; margin-bottom: 22px">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="#C9B8B3" stroke-width="2" /><path d="m20 20-3.5-3.5" stroke="#C9B8B3" stroke-width="2" stroke-linecap="round" /></svg>
                <input v-model="q" @keyup.enter="search" placeholder="Zoek op naam of @gebruikersnaam…" style="border: none; outline: none; background: none; font-family: 'Quicksand', sans-serif; font-size: 14px; color: #5d514d; width: 100%" />
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px">
                <div
                    v-for="m in members"
                    :key="m.id"
                    style="background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 20px; box-shadow: 0 8px 22px rgba(180, 150, 150, 0.07)"
                >
                    <Link :href="route('members.show', m.id)" style="display: flex; align-items: center; gap: 14px; text-decoration: none">
                        <img v-if="m.avatar_url" :src="m.avatar_url" alt="" style="width: 56px; height: 56px; border-radius: 50%; flex: none; object-fit: cover" />
                        <span v-else :style="avatarStyle(m.avatar_color)">{{ m.initial }}</span>
                        <div style="min-width: 0">
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 16px; color: #473537; white-space: nowrap; overflow: hidden; text-overflow: ellipsis">{{ m.name }}</div>
                            <div v-if="m.username" style="font-size: 12.5px; color: #c0566b; font-weight: 600">@{{ m.username }}</div>
                            <div v-if="m.role" style="font-size: 12.5px; color: #9a8d88">{{ m.role }}</div>
                        </div>
                    </Link>
                    <div style="margin-top: 14px; display: flex; gap: 8px">
                        <FollowButton :user-id="m.id" :following="m.following" />
                        <Link :href="route('members.show', m.id)" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #5d514d; background: #faf4f1; border: 1px solid #f0e6e2; border-radius: 999px; padding: 7px 14px; text-decoration: none">Profiel</Link>
                    </div>
                </div>
            </div>
            <div v-if="!members.length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 22px; padding: 40px; text-align: center; color: #8a7d78">Geen leden gevonden.</div>
        </section>
    </MlmLayout>
</template>
