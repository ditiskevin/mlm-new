<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    audience: { type: Object, required: true },
    group: { type: Object, default: null },
    forumCategory: { type: Object, default: null },
    articles: { type: Array, default: () => [] },
});
</script>

<template>
    <Head :title="`Voor ${audience.name}`" />
    <MlmLayout>
        <section style="padding: 36px 0 8px">
            <Link :href="route('audiences.index')" style="font-size: 13.5px; font-weight: 600; color: #c0566b; text-decoration: none">‹ Alle doelgroepen</Link>

            <!-- hero -->
            <div :style="{ marginTop: '14px', borderRadius: '28px', padding: '34px 36px', background: 'linear-gradient(150deg,' + audience.color_from + ',' + audience.color_to + ')', display: 'flex', alignItems: 'center', gap: '20px' }">
                <span style="font-size: 56px">{{ audience.emoji }}</span>
                <div>
                    <span style="font-family: 'Dancing Script', cursive; font-size: 22px; color: #b07f56">{{ audience.tagline }}</span>
                    <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 32px; color: #473537; margin: 2px 0 0; letter-spacing: -0.4px">{{ audience.name }}</h1>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr); gap: 32px; align-items: start; margin-top: 26px">
                <!-- intro + tips -->
                <div>
                    <p v-for="(p, i) in audience.paragraphs" :key="i" style="font-size: 15.5px; line-height: 1.75; color: #5d514d; margin: 0 0 16px; white-space: pre-line">{{ p }}</p>

                    <div v-if="audience.tips.length" style="margin-top: 8px; background: linear-gradient(150deg, #fff6f8, #f4faf6); border: 1.5px dashed #f0cdd4; border-radius: 20px; padding: 22px 24px">
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 16px; color: #473537; margin-bottom: 12px">💡 Tips &amp; aandachtspunten</div>
                        <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: 10px">
                            <li v-for="tip in audience.tips" :key="tip" style="display: flex; gap: 9px; font-size: 14.5px; line-height: 1.55; color: #6c5f5b">
                                <span style="color: #f28b82; flex: none">♥</span><span>{{ tip }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- linked content -->
                <div style="display: grid; gap: 16px; position: sticky; top: 90px">
                    <Link
                        v-if="forumCategory"
                        :href="route('forum.category', forumCategory.slug)"
                        style="display: block; background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; padding: 20px; text-decoration: none; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)"
                    >
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 15px; color: #473537; margin-bottom: 4px">{{ forumCategory.emoji }} Praat mee op het forum</div>
                        <div style="font-size: 13px; color: #8a7d78">{{ forumCategory.topics_count }} onderwerpen in “{{ forumCategory.name }}” →</div>
                    </Link>

                    <Link
                        v-if="group"
                        :href="route('community')"
                        style="display: block; background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; padding: 20px; text-decoration: none; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)"
                    >
                        <div style="display: flex; align-items: center; gap: 12px">
                            <span :style="{ width: '40px', height: '40px', borderRadius: '13px', flex: 'none', background: 'linear-gradient(135deg,' + group.color_from + ',' + group.color_to + ')' }"></span>
                            <div>
                                <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 15px; color: #473537">Groep {{ group.name }}</div>
                                <div style="font-size: 13px; color: #8a7d78">Vind je community →</div>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- articles -->
            <div v-if="articles.length" style="margin-top: 34px">
                <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 20px; color: #473537; margin: 0 0 16px">Lees ook</h2>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px">
                    <Link
                        v-for="a in articles"
                        :key="a.slug"
                        :href="route('blog.show', a.slug)"
                        style="display: block; background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; overflow: hidden; text-decoration: none; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)"
                    >
                        <div :style="{ height: '90px', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '34px', background: 'linear-gradient(150deg,' + a.color_from + ',' + a.color_to + ')' }">{{ a.emoji }}</div>
                        <div style="padding: 14px 16px 16px">
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; line-height: 1.3; color: #473537">{{ a.title }}</div>
                            <div style="font-size: 12.5px; color: #8a7d78; margin-top: 5px">{{ a.excerpt }}</div>
                        </div>
                    </Link>
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
