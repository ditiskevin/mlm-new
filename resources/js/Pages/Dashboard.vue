<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const user = computed(() => usePage().props.auth?.user ?? {});

const props = defineProps({
    suggestedAudience: { type: Object, default: null },
    fatherCard: { type: Object, default: null },
});

const fatherPct = computed(() =>
    props.fatherCard && props.fatherCard.total ? Math.round((props.fatherCard.done / props.fatherCard.total) * 100) : 0,
);

const shortcuts = [
    { label: 'Mijn profiel', desc: 'Naam, bio en avatarkleur aanpassen', href: route('profile.edit'), emoji: '👤', bg: '#FCE7EB' },
    { label: 'Community', desc: 'Tijdlijn, groepen en reacties', href: route('community'), emoji: '💬', bg: '#E4F3E9' },
    { label: 'Checklists', desc: 'Baby uitzet & vluchttas', href: route('checklists'), emoji: '✅', bg: '#FBEFD8' },
    { label: 'Babynamen', desc: 'Je favoriete namen', href: route('reveal') + '?tab=namen', emoji: '💖', bg: '#E1EEFB' },
    { label: 'Forum', desc: 'Vraag & deel met ouders', href: route('forum.index'), emoji: '🗣️', bg: '#EEE6F6' },
    { label: 'Marktplaats', desc: 'Jouw advertenties & meer', href: route('marketplace.index'), emoji: '🛍️', bg: '#FCE7EB' },
];
</script>

<template>
    <Head title="Mijn account" />
    <MlmLayout>
        <section style="padding: 42px 0 8px">
            <div style="background: linear-gradient(135deg, #fce7eb, #eaf5ee); border-radius: 28px; padding: 30px 32px; display: flex; align-items: center; gap: 20px; box-shadow: 0 12px 30px rgba(214, 150, 160, 0.14)">
                <img v-if="user.avatar_url" :src="user.avatar_url" alt="" style="width: 64px; height: 64px; border-radius: 50%; flex: none; object-fit: cover" />
                <span v-else :style="{ width: '64px', height: '64px', borderRadius: '50%', flex: 'none', background: user.avatar_color || '#F7A8B5', display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#fff', fontSize: '26px' }">{{ (user.name || '?').charAt(0) }}</span>
                <div style="flex: 1">
                    <span style="font-family: 'Dancing Script', cursive; font-size: 22px; color: #e0a24a">Welkom terug,</span>
                    <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 28px; color: #473537; margin: 0; letter-spacing: -0.3px">{{ user.name }}</h1>
                    <span v-if="user.parent_type_label" style="display: inline-block; margin-top: 5px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 12px; color: #c0566b; background: #fff; border-radius: 999px; padding: 4px 12px">{{ user.parent_type_label }}</span>
                    <p style="font-size: 14px; color: #7a6c67; margin: 6px 0 0">{{ user.role_label || 'Fijn dat je er bent — je staat er niet alleen voor. 💛' }}</p>
                </div>
                <Link :href="route('profile.edit')" style="flex: none; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #c0566b; background: #fff; border-radius: 999px; padding: 11px 18px; text-decoration: none">Profiel bewerken</Link>
            </div>

            <Link
                v-if="suggestedAudience"
                :href="route('audiences.show', suggestedAudience.slug)"
                :style="{ display: 'flex', alignItems: 'center', gap: '16px', marginTop: '20px', borderRadius: '22px', padding: '20px 24px', textDecoration: 'none', border: '1px solid #f2e7e2', background: 'linear-gradient(150deg,' + suggestedAudience.color_from + ',' + suggestedAudience.color_to + ')' }"
            >
                <span style="font-size: 40px">{{ suggestedAudience.emoji }}</span>
                <div style="flex: 1">
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 17px; color: #473537">Jouw hoek: {{ suggestedAudience.name }}</div>
                    <div style="font-size: 13.5px; color: #6c5f5b">{{ suggestedAudience.tagline }} — bekijk tips, je groep en het forum →</div>
                </div>
            </Link>

            <!-- Father journey (shown when the member is a (expectant) father) -->
            <Link
                v-if="fatherCard"
                :href="route('father')"
                style="display: flex; align-items: center; gap: 18px; margin-top: 20px; border-radius: 22px; padding: 20px 24px; text-decoration: none; border: 1px solid #dcefe3; background: linear-gradient(150deg, #eef8f1, #faf8f5)"
            >
                <span style="font-size: 40px">👨‍🍼</span>
                <div style="flex: 1">
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 17px; color: #473537">Voor de aanstaande vader</div>
                    <div style="font-size: 13.5px; color: #6c5f5b; margin-bottom: 8px">Tips per trimester en jouw papa-checklist — {{ fatherCard.done }}/{{ fatherCard.total }} afgerond.</div>
                    <div style="height: 8px; background: #e2efe7; border-radius: 999px; overflow: hidden; max-width: 360px">
                        <div :style="{ height: '100%', width: fatherPct + '%', background: 'linear-gradient(90deg, #8fd0a6, #5fb07f)', borderRadius: '999px', transition: 'width .3s' }"></div>
                    </div>
                </div>
                <span style="flex: none; font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 20px; color: #5fa07c">{{ fatherPct }}%</span>
            </Link>

            <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 20px; color: #473537; margin: 30px 0 16px">Snel naar</h2>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px">
                <Link
                    v-for="s in shortcuts"
                    :key="s.label"
                    :href="s.href"
                    style="display: block; background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 22px; text-decoration: none; box-shadow: 0 8px 22px rgba(180, 150, 150, 0.07)"
                >
                    <span :style="{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: '50px', height: '50px', borderRadius: '15px', background: s.bg, fontSize: '24px', marginBottom: '14px' }">{{ s.emoji }}</span>
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 16.5px; color: #473537; margin-bottom: 4px">{{ s.label }}</div>
                    <div style="font-size: 13.5px; line-height: 1.5; color: #8a7d78">{{ s.desc }}</div>
                </Link>
            </div>
        </section>
    </MlmLayout>
</template>
