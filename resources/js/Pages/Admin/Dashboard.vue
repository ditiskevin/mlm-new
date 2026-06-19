<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    stats: { type: Array, required: true },
    openContactCount: { type: Number, default: 0 },
    pendingArticleCount: { type: Number, default: 0 },
    openReportCount: { type: Number, default: 0 },
    posts: Array,
    comments: Array,
    topics: Array,
    replies: Array,
    listings: Array,
    babysitters: Array,
    articles: Array,
    users: Array,
});

const sections = [
    { key: 'posts', label: 'Berichten', emoji: '💬', route: 'admin.posts.destroy', primary: 'author', secondary: 'excerpt' },
    { key: 'comments', label: 'Reacties', emoji: '💭', route: 'admin.comments.destroy', primary: 'author', secondary: 'excerpt' },
    { key: 'topics', label: 'Forum', emoji: '🗣️', route: 'admin.topics.destroy', primary: 'title', secondary: 'author' },
    { key: 'replies', label: 'Forumreacties', emoji: '↩️', route: 'admin.replies.destroy', primary: 'author', secondary: 'excerpt' },
    { key: 'listings', label: 'Marktplaats', emoji: '🛍️', route: 'admin.listings.destroy', primary: 'title', secondary: 'author' },
    { key: 'babysitters', label: 'Oppas', emoji: '🧸', route: 'admin.babysitters.destroy', primary: 'name', secondary: 'location' },
    { key: 'articles', label: 'Blog', emoji: '📚', route: 'admin.articles.destroy', primary: 'title', secondary: 'category' },
    { key: 'users', label: 'Leden', emoji: '👥', route: null, primary: 'name', secondary: 'email' },
];

const active = ref('posts');
const rows = (key) => props[key] ?? [];

const remove = (sectionRoute, id) => {
    if (confirm('Weet je zeker dat je dit definitief wilt verwijderen?')) {
        router.delete(route(sectionRoute, id), { preserveScroll: true });
    }
};

const toggleAdmin = (id) => router.patch(route('admin.users.toggle-admin', id), {}, { preserveScroll: true });

const removeUser = (u) => {
    if (confirm(`Lid "${u.name}" definitief verwijderen? Dit kan niet ongedaan worden gemaakt.`)) {
        router.delete(route('admin.users.destroy', u.id), { preserveScroll: true });
    }
};

const tabStyle = (a) =>
    "flex:none;font-family:'Quicksand',sans-serif;font-weight:600;font-size:13.5px;border-radius:999px;padding:9px 15px;cursor:pointer;border:1.5px solid " +
    (a ? '#F28B82' : '#EFE3E4') + ';background:' + (a ? '#FCE7EB' : '#fff') + ';color:' + (a ? '#C0566B' : '#9a8d88');
</script>

<template>
    <Head title="Beheer" />
    <MlmLayout>
        <section style="padding: 38px 0 8px">
            <span style="font-family: 'Dancing Script', cursive; font-size: 26px; color: #e0a24a">Moderatie</span>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 32px; color: #473537; margin: 2px 0 6px; letter-spacing: -0.4px">Beheer</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 14px">Houd de community veilig en netjes. Verwijder ongepaste inhoud en beheer leden.</p>
            <div style="margin-bottom: 22px; display: flex; gap: 10px; flex-wrap: wrap">
                <Link :href="route('admin.reports.index')" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13.5px; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 9px 16px; text-decoration: none">
                    🚩 Meldingen<span v-if="openReportCount" style="margin-left: 7px; font-size: 11px; color: #fff; background: #f28b82; border-radius: 999px; padding: 1px 8px">{{ openReportCount }}</span>
                </Link>
                <Link :href="route('admin.audiences.index')" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13.5px; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 9px 16px; text-decoration: none">🎯 Doelgroepen &amp; "Ik ben…" beheren</Link>
                <Link :href="route('admin.contact.index')" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13.5px; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 9px 16px; text-decoration: none">
                    ✉️ Contactberichten<span v-if="openContactCount" style="margin-left: 7px; font-size: 11px; color: #fff; background: #f28b82; border-radius: 999px; padding: 1px 8px">{{ openContactCount }}</span>
                </Link>
                <Link :href="route('admin.articles.pending')" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13.5px; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 9px 16px; text-decoration: none">
                    📝 Blog-inzendingen<span v-if="pendingArticleCount" style="margin-left: 7px; font-size: 11px; color: #fff; background: #f28b82; border-radius: 999px; padding: 1px 8px">{{ pendingArticleCount }}</span>
                </Link>
                <Link :href="route('admin.articles.index')" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13.5px; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 9px 16px; text-decoration: none">📚 Blogbeheer</Link>
            </div>

            <!-- stats -->
            <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 14px; margin-bottom: 26px">
                <div v-for="s in stats" :key="s.label" style="background: #fff; border: 1px solid #f2e7e2; border-radius: 18px; padding: 16px; text-align: center; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)">
                    <div style="font-size: 22px">{{ s.emoji }}</div>
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 22px; color: #473537">{{ s.value }}</div>
                    <div style="font-size: 11.5px; color: #9a8d88">{{ s.label }}</div>
                </div>
            </div>

            <!-- tabs -->
            <div class="mlm-scroll" style="display: flex; gap: 7px; overflow-x: auto; padding-bottom: 8px; margin-bottom: 18px">
                <button v-for="sec in sections" :key="sec.key" @click="active = sec.key" :style="tabStyle(active === sec.key)">{{ sec.emoji }} {{ sec.label }}</button>
            </div>

            <!-- generic content sections -->
            <template v-for="sec in sections" :key="sec.key">
                <div v-if="active === sec.key && sec.key !== 'users'" style="display: grid; gap: 10px">
                    <div
                        v-for="row in rows(sec.key)"
                        :key="row.id"
                        style="display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid #f2e7e2; border-radius: 16px; padding: 14px 18px; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)"
                    >
                        <div style="flex: 1; min-width: 0">
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14.5px; color: #473537">{{ row[sec.primary] }}</div>
                            <div style="font-size: 13px; color: #8a7d78; white-space: nowrap; overflow: hidden; text-overflow: ellipsis">
                                {{ row[sec.secondary] }}<span v-if="row.category && sec.secondary !== 'category'"> · {{ row.category }}</span><span v-if="row.when"> · {{ row.when }}</span>
                            </div>
                        </div>
                        <button @click="remove(sec.route, row.id)" style="flex: none; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #b4574e; background: #fdf0ef; border: 1px solid #f6cfca; border-radius: 999px; padding: 8px 14px; cursor: pointer">Verwijderen</button>
                    </div>
                    <div v-if="!rows(sec.key).length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 16px; padding: 30px; text-align: center; color: #8a7d78">Niks te modereren hier. 🌿</div>
                </div>

                <!-- users -->
                <div v-if="active === sec.key && sec.key === 'users'" style="display: grid; gap: 10px">
                    <div
                        v-for="u in users"
                        :key="u.id"
                        style="display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid #f2e7e2; border-radius: 16px; padding: 14px 18px; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)"
                    >
                        <div style="flex: 1; min-width: 0">
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14.5px; color: #473537">{{ u.name }}<span v-if="u.is_admin" style="margin-left: 8px; font-size: 11px; font-weight: 600; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 3px 9px">beheerder</span></div>
                            <div style="font-size: 13px; color: #8a7d78">{{ u.email }}</div>
                        </div>
                        <template v-if="!u.is_self">
                            <button @click="toggleAdmin(u.id)" style="flex: none; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #5d514d; background: #faf4f1; border: 1px solid #f0e6e2; border-radius: 999px; padding: 8px 14px; cursor: pointer">{{ u.is_admin ? 'Beheer intrekken' : 'Maak beheerder' }}</button>
                            <button @click="removeUser(u)" style="flex: none; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #b4574e; background: #fdf0ef; border: 1px solid #f6cfca; border-radius: 999px; padding: 8px 14px; cursor: pointer">Verwijderen</button>
                        </template>
                        <span v-else style="flex: none; font-size: 12px; color: #b5a8a3">jij</span>
                    </div>
                </div>
            </template>
        </section>
    </MlmLayout>
</template>
