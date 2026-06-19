<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import ReportButton from '@/Components/ReportButton.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

const props = defineProps({
    profile: { type: Object, required: true },
    groups: { type: Array, required: true },
    posts: { type: Array, required: true },
});

const page = usePage();
const isAuth = computed(() => !!page.props.auth?.user);

// Local interactive state seeded from server
const liked = reactive(new Set(props.posts.filter((p) => p.liked).map((p) => p.id)));
const likeCounts = reactive(Object.fromEntries(props.posts.map((p) => [p.id, p.like_count])));
const following = reactive(new Set(props.groups.filter((g) => g.following).map((g) => g.id)));
// Comments are expanded by default for every post.
const openComments = reactive(new Set(props.posts.map((p) => p.id)));
const commentDrafts = reactive({});

const requireAuth = () => router.visit(route('login'));

const toggleLike = (post) => {
    if (!isAuth.value) return requireAuth();
    if (liked.has(post.id)) {
        liked.delete(post.id);
        likeCounts[post.id]--;
    } else {
        liked.add(post.id);
        likeCounts[post.id]++;
    }
    router.post(route('community.posts.like', post.id), {}, { preserveScroll: true, preserveState: true, only: [] });
};

const toggleFollow = (group) => {
    if (!isAuth.value) return requireAuth();
    if (following.has(group.id)) following.delete(group.id);
    else following.add(group.id);
    router.post(route('community.groups.follow', group.id), {}, { preserveScroll: true, preserveState: true, only: [] });
};

const deleteGroup = (group) => {
    if (confirm(`Weet je zeker dat je de groep "${group.name}" wilt verwijderen?`)) {
        router.delete(route('community.groups.destroy', group.id), { preserveScroll: true });
    }
};

const toggleComments = (post) => {
    if (openComments.has(post.id)) openComments.delete(post.id);
    else openComments.add(post.id);
};

const submitComment = (post) => {
    if (!isAuth.value) return requireAuth();
    const body = (commentDrafts[post.id] || '').trim();
    if (!body) return;
    router.post(
        route('community.posts.comments.store', post.id),
        { body },
        {
            preserveScroll: true,
            onSuccess: () => {
                commentDrafts[post.id] = '';
                openComments.add(post.id);
            },
        },
    );
};

// Post composer
const form = useForm({ body: '', community_group_id: null });
const submitPost = () => {
    if (!isAuth.value) return requireAuth();
    if (!form.body.trim()) return;
    form.post(route('community.posts.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset('body'),
    });
};

const likeBtnStyle = (isLiked) =>
    "display:flex;align-items:center;gap:7px;background:none;border:none;cursor:pointer;font-weight:600;font-size:14px;font-family:'Quicksand',sans-serif;color:" +
    (isLiked ? '#F28B82' : '#9a8d88');
</script>

<template>
    <Head title="Community" />
    <MlmLayout>
        <section style="padding: 38px 0 8px">
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 32px; color: #473537; margin: 0 0 4px; letter-spacing: -0.4px">Community</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 26px">Van ouder tot ouder - deel, vraag en vind herkenning. Zonder oordeel. 💛</p>

            <div style="display: grid; grid-template-columns: 320px minmax(0, 1fr); align-items: start; gap: 48px">
                <!-- sidebar -->
                <div style="min-width: 0; display: grid; gap: 20px; position: sticky; top: 90px">
                    <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 26px rgba(180, 150, 150, 0.09)">
                        <div style="height: 78px; background: linear-gradient(120deg, #f9c8d0, #b7e1c0)"></div>
                        <div style="padding: 0 20px 20px; margin-top: -34px">
                            <img v-if="profile.avatar_url" :src="profile.avatar_url" alt="" style="width: 68px; height: 68px; border-radius: 50%; border: 4px solid #fff; object-fit: cover" />
                            <div v-else :style="{ width: '68px', height: '68px', borderRadius: '50%', background: profile.avatar_color, border: '4px solid #fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#fff', fontSize: '24px' }">{{ profile.name.charAt(0) }}</div>
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 18px; color: #473537; margin-top: 8px">{{ profile.name }}</div>
                            <span v-if="profile.type" style="display: inline-block; margin-top: 4px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 11px; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 3px 10px">{{ profile.type }}</span>
                            <div style="font-size: 13px; color: #9a8d88; margin-top: 6px">{{ profile.role }}</div>
                            <p style="font-size: 13px; line-height: 1.55; color: #7a6c67; margin: 10px 0 14px">{{ profile.bio }}</p>
                            <div style="display: flex; text-align: center; border-top: 1px solid #f4ece8; padding-top: 12px">
                                <div v-for="stat in profile.stats" :key="stat.label" style="flex: 1">
                                    <div style="font-family: 'Poppins', sans-serif; font-weight: 700; color: #473537">{{ stat.value }}</div>
                                    <div style="font-size: 11px; color: #9a8d88">{{ stat.label }}</div>
                                </div>
                            </div>
                            <Link
                                v-if="profile.editable"
                                :href="route('profile.edit')"
                                style="display: block; text-align: center; margin-top: 14px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13px; color: #c0566b; background: #fbe9ed; border-radius: 999px; padding: 9px 14px; text-decoration: none"
                                >Profiel bewerken</Link
                            >
                        </div>
                    </div>
                    <div style="min-width: 0; background: #fff; border: 1px solid #f2e7e2; border-radius: 24px; padding: 20px; box-shadow: 0 10px 26px rgba(180, 150, 150, 0.09)">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px"><span style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 15px; color: #473537">Groepen</span><span style="font-size: 12px; color: #c0566b; font-weight: 600">Alle</span></div>
                        <div style="display: grid; gap: 12px">
                            <div v-for="g in groups" :key="g.id" style="min-width: 0; display: flex; align-items: flex-start; gap: 12px">
                                <span :style="{ width: '40px', height: '40px', borderRadius: '13px', flex: 'none', background: 'linear-gradient(135deg,' + g.color_from + ',' + g.color_to + ')' }"></span>
                                <div style="flex: 1; min-width: 0">
                                    <div style="font-weight: 600; font-size: 14px; color: #473537; white-space: nowrap; overflow: hidden; text-overflow: ellipsis">{{ g.name }}</div>
                                    <div style="font-size: 12px; color: #9a8d88">{{ g.members }} leden<span v-if="g.is_owner"> · jouw groep</span></div>
                                    <div v-if="g.description" style="font-size: 12px; color: #8a7d78; line-height: 1.4; margin-top: 3px">{{ g.description }}</div>
                                    <button v-if="g.is_owner" @click="deleteGroup(g)" style="font-size: 11.5px; font-weight: 600; color: #b4574e; background: none; border: none; cursor: pointer; padding: 4px 0 0">Verwijderen</button>
                                </div>
                                <button @click="toggleFollow(g)" :style="{ flex: 'none', whiteSpace: 'nowrap', border: '1.5px solid ' + (following.has(g.id) ? '#F28B82' : '#F0D6DC'), background: following.has(g.id) ? '#FCE7EB' : '#fff', color: '#C0566B', fontWeight: 600, fontSize: '12px', borderRadius: '999px', padding: '6px 13px', cursor: 'pointer' }">{{ following.has(g.id) ? 'Volgend' : 'Volg' }}</button>
                            </div>
                        </div>
                        <Link
                            :href="isAuth ? route('community.groups.create') : route('login')"
                            style="display: block; text-align: center; margin-top: 16px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13px; color: #c0566b; background: #fbe9ed; border-radius: 999px; padding: 10px 14px; text-decoration: none"
                            >+ Maak een groep</Link
                        >
                    </div>
                </div>

                <!-- feed -->
                <div style="min-width: 0; display: grid; gap: 16px">
                    <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 18px 20px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.07)">
                        <div style="display: flex; gap: 12px; align-items: center">
                            <span style="width: 42px; height: 42px; border-radius: 50%; flex: none; background: linear-gradient(135deg, #cfe3f5, #a9cce8)"></span>
                            <input
                                v-model="form.body"
                                @keyup.enter="submitPost"
                                :placeholder="isAuth ? 'Deel iets met de community…' : 'Log in om iets te delen…'"
                                style="flex: 1; min-width: 0; background: #faf6f3; border: 1px solid #f1e7e2; border-radius: 999px; padding: 12px 18px; color: #5d514d; font-family: 'Quicksand', sans-serif; font-size: 14px; outline: none"
                            />
                            <button @click="submitPost" :disabled="form.processing" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 11px 22px; cursor: pointer; flex: none">Plaats</button>
                        </div>
                    </div>

                    <div v-for="p in posts" :key="p.id" style="background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 20px 22px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.07)">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 13px">
                            <span :style="{ width: '46px', height: '46px', borderRadius: '50%', flex: 'none', background: p.avatar_color, display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#fff', fontSize: '18px' }">{{ p.initial }}</span>
                            <div style="flex: 1"><div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537">{{ p.author_name }}</div><div style="font-size: 12.5px; color: #9a8d88">{{ p.meta }}</div></div>
                            <span v-if="p.tag" style="font-size: 11.5px; font-weight: 600; color: #c58a2e; background: #fbefd8; border-radius: 999px; padding: 5px 12px">{{ p.tag }}</span>
                        </div>
                        <p style="margin: 0 0 14px; font-size: 15px; line-height: 1.65; color: #5d514d">{{ p.body }}</p>
                        <div style="display: flex; align-items: center; gap: 18px; border-top: 1px solid #f4ece8; padding-top: 12px">
                            <button @click="toggleLike(p)" :style="likeBtnStyle(liked.has(p.id))">
                                <svg width="18" height="18" viewBox="0 0 24 24" :fill="liked.has(p.id) ? '#F28B82' : 'none'"><path d="M12 20s-7-4.6-9.3-9.1C1 7.6 2.7 4.5 6 4.5c2 0 3.2 1.1 4 2.3.8-1.2 2-2.3 4-2.3 3.3 0 5 3.1 3.3 6.4C19 15.4 12 20 12 20Z" stroke="#E0A9B1" stroke-width="1.6" /></svg>
                                <span>{{ likeCounts[p.id] }}</span>
                            </button>
                            <button @click="toggleComments(p)" style="display: flex; align-items: center; gap: 7px; background: none; border: none; cursor: pointer; color: #9a8d88; font-weight: 600; font-size: 14px; font-family: 'Quicksand', sans-serif">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M21 12a8 8 0 0 1-8 8H7l-4 3V12a8 8 0 0 1 8-8h2a8 8 0 0 1 8 8Z" stroke="#9a8d88" stroke-width="1.8" /></svg>
                                Reageer<span v-if="p.comment_count"> · {{ p.comment_count }}</span>
                            </button>
                            <span style="margin-left: auto"><ReportButton type="post" :id="p.id" /></span>
                        </div>

                        <!-- comments -->
                        <div v-if="openComments.has(p.id) || p.comment_count" v-show="openComments.has(p.id)" style="margin-top: 14px; border-top: 1px solid #f4ece8; padding-top: 14px; display: grid; gap: 12px">
                            <div v-for="c in p.comments" :key="c.id" style="display: flex; gap: 10px; align-items: flex-start">
                                <span :style="{ width: '34px', height: '34px', borderRadius: '50%', flex: 'none', background: c.avatar_color, display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#fff', fontSize: '14px' }">{{ c.initial }}</span>
                                <div style="flex: 1; background: #faf6f3; border: 1px solid #f1e7e2; border-radius: 16px; padding: 10px 14px">
                                    <div style="display: flex; align-items: baseline; gap: 8px">
                                        <span style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13.5px; color: #473537">{{ c.author_name }}</span>
                                        <span style="font-size: 11.5px; color: #9a8d88">{{ c.meta }}</span>
                                        <span style="margin-left: auto"><ReportButton type="comment" :id="c.id" variant="icon" /></span>
                                    </div>
                                    <p style="margin: 3px 0 0; font-size: 14px; line-height: 1.55; color: #5d514d">{{ c.body }}</p>
                                </div>
                            </div>

                            <div style="display: flex; gap: 10px; align-items: center">
                                <input
                                    v-model="commentDrafts[p.id]"
                                    @keyup.enter="submitComment(p)"
                                    :placeholder="isAuth ? 'Schrijf een reactie…' : 'Log in om te reageren…'"
                                    style="flex: 1; min-width: 0; background: #fff; border: 1px solid #f1e7e2; border-radius: 999px; padding: 10px 16px; color: #5d514d; font-family: 'Quicksand', sans-serif; font-size: 14px; outline: none"
                                />
                                <button @click="submitComment(p)" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13px; color: #c0566b; background: #fbe9ed; border: none; border-radius: 999px; padding: 10px 18px; cursor: pointer; flex: none">Reageer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
