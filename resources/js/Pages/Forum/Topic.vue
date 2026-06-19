<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import ReportButton from '@/Components/ReportButton.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    topic: { type: Object, required: true },
    replies: { type: Array, required: true },
});

const isAuth = computed(() => !!usePage().props.auth?.user);

const form = useForm({ body: '' });
const submit = () => {
    if (!isAuth.value) return router.visit(route('login'));
    if (!form.body.trim()) return;
    form.post(route('forum.replies.store', props.topic.slug), {
        preserveScroll: true,
        onSuccess: () => form.reset('body'),
    });
};

const removeTopic = () => {
    if (confirm('Weet je zeker dat je dit onderwerp wilt verwijderen?')) {
        router.delete(route('forum.topics.destroy', props.topic.slug));
    }
};
</script>

<template>
    <Head :title="topic.title" />
    <MlmLayout>
        <section style="max-width: 800px; margin: 0 auto; padding: 36px 0 8px">
            <Link :href="route('forum.category', topic.category.slug)" style="font-size: 13.5px; font-weight: 600; color: #c0566b; text-decoration: none">‹ {{ topic.category.emoji }} {{ topic.category.name }}</Link>

            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 28px; line-height: 1.25; color: #473537; margin: 14px 0 16px; letter-spacing: -0.3px">
                <span v-if="topic.pinned">📌 </span>{{ topic.title }}
            </h1>

            <!-- opening post -->
            <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 22px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.07)">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 13px">
                    <span :style="{ width: '46px', height: '46px', borderRadius: '50%', flex: 'none', background: topic.avatar_color, display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#fff', fontSize: '18px' }">{{ topic.initial }}</span>
                    <div style="flex: 1"><div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537">{{ topic.author_name }}</div><div style="font-size: 12.5px; color: #9a8d88">startte dit onderwerp · {{ topic.created }}</div></div>
                    <ReportButton type="forum_topic" :id="topic.id" />
                    <button v-if="topic.can_delete" @click="removeTopic" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #b4574e; background: none; border: none; cursor: pointer">Verwijderen</button>
                </div>
                <p style="margin: 0; font-size: 15.5px; line-height: 1.7; color: #5d514d; white-space: pre-line">{{ topic.body }}</p>
            </div>

            <!-- replies -->
            <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 17px; color: #473537; margin: 26px 0 14px">{{ replies.length }} {{ replies.length === 1 ? 'reactie' : 'reacties' }}</h2>
            <div style="display: grid; gap: 12px">
                <div v-for="r in replies" :key="r.id" style="display: flex; gap: 12px; align-items: flex-start">
                    <span :style="{ width: '42px', height: '42px', borderRadius: '50%', flex: 'none', background: r.avatar_color, display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#fff', fontSize: '16px' }">{{ r.initial }}</span>
                    <div style="flex: 1; background: #fff; border: 1px solid #f2e7e2; border-radius: 18px; padding: 14px 18px; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)">
                        <div style="display: flex; align-items: baseline; gap: 8px; margin-bottom: 4px">
                            <span style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #473537">{{ r.author_name }}</span>
                            <span style="font-size: 11.5px; color: #9a8d88">{{ r.created }}</span>
                            <span style="margin-left: auto"><ReportButton type="forum_reply" :id="r.id" variant="icon" /></span>
                        </div>
                        <p style="margin: 0; font-size: 15px; line-height: 1.65; color: #5d514d; white-space: pre-line">{{ r.body }}</p>
                    </div>
                </div>
            </div>

            <!-- reply form -->
            <div style="margin-top: 22px; background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; padding: 20px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.07)">
                <template v-if="isAuth">
                    <label style="display: block; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #473537; margin-bottom: 8px">Schrijf een reactie</label>
                    <textarea v-model="form.body" rows="3" placeholder="Deel je gedachte, ervaring of tip…" style="width: 100%; font-family: 'Quicksand', sans-serif; font-size: 14px; color: #5d514d; background: #faf6f3; border: 1px solid #f1e7e2; border-radius: 14px; padding: 12px 14px; outline: none"></textarea>
                    <div style="margin-top: 12px"><button @click="submit" :disabled="form.processing" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 11px 22px; cursor: pointer">Plaats reactie</button></div>
                </template>
                <div v-else style="text-align: center; color: #8a7d78; font-size: 14px">
                    <Link :href="route('login')" style="color: #c0566b; font-weight: 600; text-decoration: none">Log in</Link> om mee te praten in dit onderwerp. 💛
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
