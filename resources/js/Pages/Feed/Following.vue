<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    posts: { type: Array, default: () => [] },
    count: { type: Number, default: 0 },
});

const avatarStyle = (color, size = 46) =>
    `width:${size}px;height:${size}px;border-radius:50%;flex:none;background:${color};display:flex;align-items:center;justify-content:center;font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:${size > 40 ? 18 : 14}px;text-decoration:none`;
</script>

<template>
    <Head title="Van wie ik volg" />
    <MlmLayout>
        <section style="max-width: 680px; margin: 0 auto; padding: 38px 0 8px">
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 32px; color: #473537; margin: 0 0 4px; letter-spacing: -0.4px">Van wie ik volg</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 26px">De nieuwste berichten van de leden die jij volgt. 💛</p>

            <div v-if="posts.length" style="display: grid; gap: 16px">
                <article v-for="p in posts" :key="p.id" style="background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 20px 22px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.07)">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 13px">
                        <Link :href="route('members.show', p.author_id)" :style="avatarStyle(p.avatar_color)">{{ p.initial }}</Link>
                        <div style="flex: 1">
                            <Link :href="route('members.show', p.author_id)" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537; text-decoration: none">{{ p.author_name }}</Link>
                            <div style="font-size: 12.5px; color: #9a8d88">{{ p.when }}</div>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 15px; line-height: 1.65; color: #5d514d">{{ p.body }}</p>
                </article>
            </div>

            <div v-else style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 18px; padding: 40px 30px; text-align: center; color: #8a7d78">
                <div style="font-size: 34px; margin-bottom: 8px">👥</div>
                <p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6">Je volgt nog niemand — ontdek leden in de community.</p>
                <Link
                    :href="route('community')"
                    style="display: inline-block; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border-radius: 999px; padding: 11px 24px; text-decoration: none; box-shadow: 0 8px 18px rgba(242, 139, 130, 0.3)"
                    >Naar de community</Link
                >
            </div>
        </section>
    </MlmLayout>
</template>
