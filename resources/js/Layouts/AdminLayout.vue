<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const badges = computed(() => page.props.adminBadges ?? {});

// Sidebar structure: section header + items. `match` drives active highlight,
// `badge` keys into adminBadges.
const sections = [
    {
        title: null,
        items: [{ label: 'Overzicht', emoji: '🏠', routeName: 'admin.dashboard', match: 'admin.dashboard' }],
    },
    {
        title: 'Leden',
        items: [{ label: 'Leden', emoji: '👥', routeName: 'admin.users.index', match: 'admin.users.*' }],
    },
    {
        title: 'Community',
        items: [
            { label: 'Berichten', emoji: '💬', routeName: 'admin.posts.index', match: 'admin.posts.*' },
            { label: 'Reacties', emoji: '💭', routeName: 'admin.comments.index', match: 'admin.comments.*' },
            { label: 'Groepen', emoji: '👪', routeName: 'admin.groups.index', match: 'admin.groups.*' },
        ],
    },
    {
        title: 'Forum',
        items: [
            { label: 'Categorieën', emoji: '🗂️', routeName: 'admin.forum-categories.index', match: 'admin.forum-categories.*' },
            { label: 'Onderwerpen', emoji: '🗣️', routeName: 'admin.topics.index', match: 'admin.topics.*' },
            { label: 'Forumreacties', emoji: '↩️', routeName: 'admin.replies.index', match: 'admin.replies.*' },
        ],
    },
    {
        title: 'Aanbod',
        items: [
            { label: 'Marktplaats', emoji: '🛍️', routeName: 'admin.listings.index', match: 'admin.listings.*' },
            { label: 'Oppas', emoji: '🧸', routeName: 'admin.babysitters.index', match: 'admin.babysitters.*' },
        ],
    },
    {
        title: 'Content',
        items: [
            { label: 'Blog', emoji: '📚', routeName: 'admin.articles.index', match: 'admin.articles.index' },
            { label: 'Blog-inzendingen', emoji: '📝', routeName: 'admin.articles.pending', match: 'admin.articles.pending', badge: 'pendingArticles' },
            { label: 'Doelgroepen', emoji: '🎯', routeName: 'admin.audiences.index', match: 'admin.audiences.*' },
        ],
    },
    {
        title: 'Moderatie',
        items: [
            { label: 'Meldingen', emoji: '🚩', routeName: 'admin.reports.index', match: 'admin.reports.*', badge: 'reports' },
            { label: 'Contact', emoji: '✉️', routeName: 'admin.contact.index', match: 'admin.contact.*', badge: 'contact' },
        ],
    },
];

const isActive = (item) => route().current(item.match);
</script>

<template>
    <MlmLayout>
        <section style="display: grid; grid-template-columns: 232px minmax(0, 1fr); gap: 28px; padding: 24px 0 8px; align-items: start">
            <!-- Sidebar -->
            <aside style="position: sticky; top: 90px; background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 14px; box-shadow: 0 10px 26px rgba(180, 150, 150, 0.08)">
                <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 15px; color: #473537; padding: 6px 10px 12px">🛡️ Beheer</div>
                <template v-for="(sec, si) in sections" :key="si">
                    <div v-if="sec.title" style="font-size: 10.5px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; color: #b5a8a3; padding: 12px 10px 5px">{{ sec.title }}</div>
                    <Link
                        v-for="item in sec.items"
                        :key="item.label"
                        :href="route(item.routeName)"
                        :style="{
                            display: 'flex',
                            alignItems: 'center',
                            gap: '9px',
                            fontFamily: 'Quicksand, sans-serif',
                            fontWeight: 600,
                            fontSize: '13.5px',
                            borderRadius: '11px',
                            padding: '9px 11px',
                            textDecoration: 'none',
                            color: isActive(item) ? '#C0566B' : '#6c5f5b',
                            background: isActive(item) ? '#FBE9ED' : 'transparent',
                        }"
                    >
                        <span style="font-size: 15px">{{ item.emoji }}</span>
                        <span style="flex: 1">{{ item.label }}</span>
                        <span v-if="item.badge && badges[item.badge]" style="font-size: 10.5px; font-weight: 700; color: #fff; background: #f28b82; border-radius: 999px; padding: 1px 7px">{{ badges[item.badge] }}</span>
                    </Link>
                </template>
            </aside>

            <!-- Content -->
            <div style="min-width: 0">
                <slot />
            </div>
        </section>
    </MlmLayout>
</template>
