<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    category: { type: Object, required: true },
    topics: { type: Array, required: true },
});

const isAuth = computed(() => !!usePage().props.auth?.user);
</script>

<template>
    <Head :title="`Forum · ${category.name}`" />
    <MlmLayout>
        <section style="padding: 36px 0 8px">
            <Link :href="route('forum.index')" style="font-size: 13.5px; font-weight: 600; color: #c0566b; text-decoration: none">‹ Terug naar het forum</Link>

            <div :style="{ marginTop: '14px', borderRadius: '24px', padding: '26px 28px', background: 'linear-gradient(150deg,' + category.color_from + ',' + category.color_to + ')', display: 'flex', alignItems: 'center', gap: '18px' }">
                <span style="font-size: 40px">{{ category.emoji }}</span>
                <div style="flex: 1">
                    <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 28px; color: #473537; margin: 0">{{ category.name }}</h1>
                    <p style="font-size: 14.5px; color: #6c5f5b; margin: 4px 0 0">{{ category.description }}</p>
                </div>
                <Link
                    :href="isAuth ? route('forum.create', { category: category.slug }) : route('login')"
                    style="flex: none; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 11px 20px; text-decoration: none"
                    >+ Nieuw onderwerp</Link
                >
            </div>

            <div style="margin-top: 22px; display: grid; gap: 12px">
                <Link
                    v-for="t in topics"
                    :key="t.slug"
                    :href="route('forum.topic', t.slug)"
                    style="display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid #f2e7e2; border-radius: 18px; padding: 16px 20px; text-decoration: none; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)"
                >
                    <span :style="{ width: '44px', height: '44px', borderRadius: '50%', flex: 'none', background: t.avatar_color, display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#fff', fontSize: '17px' }">{{ t.initial }}</span>
                    <div style="flex: 1; min-width: 0">
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 16px; color: #473537">
                            <span v-if="t.pinned" title="Vastgepind">📌 </span>{{ t.title }}
                        </div>
                        <div style="font-size: 12.5px; color: #9a8d88">door {{ t.author_name }} · {{ t.last_activity }}</div>
                    </div>
                    <div style="flex: none; text-align: center">
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 16px; color: #c0566b">{{ t.replies_count }}</div>
                        <div style="font-size: 11px; color: #9a8d88">reacties</div>
                    </div>
                </Link>
                <div v-if="!topics.length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 18px; padding: 36px; text-align: center; color: #8a7d78">
                    Nog geen onderwerpen in deze categorie. Start het eerste gesprek! 💛
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
