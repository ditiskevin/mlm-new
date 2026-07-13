<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    group: { type: Object, required: true },
    posts: { type: Array, default: () => [] },
    members_preview: { type: Array, default: () => [] },
});

const isAuth = computed(() => !!usePage().props.auth?.user);
const following = ref(props.group.following);
const memberCount = ref(props.group.members);

const toggleFollow = () => {
    if (!isAuth.value) return router.visit(route('login'));
    following.value = !following.value;
    memberCount.value += following.value ? 1 : -1;
    router.post(route('community.groups.follow', props.group.id), {}, { preserveScroll: true, preserveState: true, only: [] });
};

const avatarStyle = (color, size = 40) =>
    `width:${size}px;height:${size}px;border-radius:50%;flex:none;background:${color};display:flex;align-items:center;justify-content:center;font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:${size > 38 ? 16 : 14}px;text-decoration:none`;
</script>

<template>
    <Head :title="group.name" />
    <MlmLayout>
        <section style="max-width: 820px; margin: 0 auto; padding: 32px 0 8px">
            <Link :href="route('community')" style="font-size: 13.5px; font-weight: 600; color: #c0566b; text-decoration: none">‹ Terug naar de community</Link>

            <!-- Header -->
            <div style="margin-top: 14px; background: #fff; border: 1px solid #f2e7e2; border-radius: 26px; overflow: hidden; box-shadow: 0 12px 30px rgba(180, 150, 150, 0.1)">
                <div :style="{ height: '96px', background: 'linear-gradient(120deg,' + group.color_from + ',' + group.color_to + ')' }"></div>
                <div class="mlm-wrap-mobile" style="padding: 18px 28px 24px; display: flex; align-items: flex-end; gap: 18px">
                    <span :style="{ width: '76px', height: '76px', borderRadius: '20px', marginTop: '-52px', border: '5px solid #fff', background: 'linear-gradient(135deg,' + group.color_from + ',' + group.color_to + ')', flex: 'none' }"></span>
                    <div style="flex: 1; min-width: 0">
                        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 26px; color: #473537; margin: 0">{{ group.name }}</h1>
                        <div style="font-size: 13px; color: #9a8d88; margin-top: 2px">{{ memberCount }} leden<span v-if="group.owner"> · opgericht door {{ group.owner }}</span></div>
                    </div>
                    <button
                        @click="toggleFollow"
                        :style="{ flex: 'none', fontFamily: 'Poppins, sans-serif', fontWeight: 600, fontSize: '14px', border: '1.5px solid ' + (following ? '#F28B82' : '#F0D6DC'), background: following ? '#FCE7EB' : '#fff', color: '#C0566B', borderRadius: '999px', padding: '10px 22px', cursor: 'pointer' }"
                    >
                        {{ following ? 'Volgend ✓' : 'Volg groep' }}
                    </button>
                </div>
                <p v-if="group.description" style="padding: 0 28px 22px; margin: 0; font-size: 15px; line-height: 1.6; color: #5d514d">{{ group.description }}</p>
            </div>

            <div style="display: grid; grid-template-columns: minmax(0, 1fr) 240px; gap: 24px; margin-top: 22px; align-items: start">
                <!-- Posts -->
                <div style="min-width: 0">
                    <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 18px; color: #473537; margin: 0 0 14px">Berichten in deze groep</h2>
                    <div style="display: grid; gap: 14px">
                        <article v-for="p in posts" :key="p.id" style="background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; padding: 18px 20px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)">
                            <div style="display: flex; align-items: center; gap: 11px; margin-bottom: 10px">
                                <Link v-if="p.author_id" :href="route('members.show', p.author_id)" :style="avatarStyle(p.avatar_color, 42)">{{ p.initial }}</Link>
                                <span v-else :style="avatarStyle(p.avatar_color, 42)">{{ p.initial }}</span>
                                <div>
                                    <Link v-if="p.author_id" :href="route('members.show', p.author_id)" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14.5px; color: #473537; text-decoration: none">{{ p.author_name }}</Link>
                                    <span v-else style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14.5px; color: #473537">{{ p.author_name }}</span>
                                    <div style="font-size: 12px; color: #9a8d88">{{ p.when }}</div>
                                </div>
                            </div>
                            <p style="margin: 0; font-size: 14.5px; line-height: 1.6; color: #5d514d">{{ p.body }}</p>
                            <div style="font-size: 12.5px; color: #b5a8a3; margin-top: 8px">♥ {{ p.like_count }}</div>
                        </article>
                        <div v-if="!posts.length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 18px; padding: 30px; text-align: center; color: #8a7d78">Nog geen berichten in deze groep. Deel iets vanuit de community-tijdlijn. 💛</div>
                    </div>
                </div>

                <!-- Members -->
                <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; padding: 18px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)">
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 14px; color: #473537; margin-bottom: 12px">Leden</div>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px">
                        <Link v-for="m in members_preview" :key="m.id" :href="route('members.show', m.id)" :title="m.name">
                            <img v-if="m.avatar_url" :src="m.avatar_url" alt="" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover" />
                            <span v-else :style="avatarStyle(m.avatar_color, 38)">{{ m.initial }}</span>
                        </Link>
                    </div>
                    <div v-if="!members_preview.length" style="font-size: 13px; color: #9a8d88">Nog geen leden — volg als eerste!</div>
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
