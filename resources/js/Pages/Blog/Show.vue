<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    article: { type: Object, required: true },
    related: { type: Array, default: () => [] },
});
</script>

<template>
    <Head :title="article.title" />
    <MlmLayout>
        <article style="max-width: 760px; margin: 0 auto; padding: 36px 0 8px">
            <Link :href="route('blog.index')" style="font-size: 13.5px; font-weight: 600; color: #c0566b; text-decoration: none">‹ Terug naar inspiratie</Link>

            <div :style="{ marginTop: '16px', borderRadius: '28px', height: '180px', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '64px', background: 'linear-gradient(150deg,' + article.color_from + ',' + article.color_to + ')' }">{{ article.emoji }}</div>

            <span style="display: inline-block; margin-top: 18px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 11px; letter-spacing: 0.5px; text-transform: uppercase; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 4px 11px">{{ article.category }}</span>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 32px; line-height: 1.2; color: #473537; margin: 12px 0 8px; letter-spacing: -0.4px">{{ article.title }}</h1>
            <div style="font-size: 13.5px; color: #9a8d88; margin-bottom: 24px">{{ article.author_name }} · {{ article.published }} · {{ article.reading_minutes }} min lezen</div>

            <p v-for="(p, i) in article.paragraphs" :key="i" style="font-size: 16px; line-height: 1.8; color: #5d514d; margin: 0 0 18px; white-space: pre-line">{{ p }}</p>
        </article>

        <section v-if="related.length" style="max-width: 760px; margin: 30px auto 0; padding-top: 24px; border-top: 1px solid #f1e7e2">
            <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 19px; color: #473537; margin: 0 0 16px">Meer over {{ article.category }}</h2>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px">
                <Link
                    v-for="a in related"
                    :key="a.slug"
                    :href="route('blog.show', a.slug)"
                    style="display: block; background: #fff; border: 1px solid #f2e7e2; border-radius: 18px; overflow: hidden; text-decoration: none; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)"
                >
                    <div :style="{ height: '70px', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '30px', background: 'linear-gradient(150deg,' + a.color_from + ',' + a.color_to + ')' }">{{ a.emoji }}</div>
                    <div style="padding: 12px 14px 14px">
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; line-height: 1.3; color: #473537">{{ a.title }}</div>
                        <div style="font-size: 12px; color: #b5a8a3; margin-top: 5px">{{ a.reading_minutes }} min lezen</div>
                    </div>
                </Link>
            </div>
        </section>
    </MlmLayout>
</template>
