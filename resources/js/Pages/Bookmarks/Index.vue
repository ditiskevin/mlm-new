<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    posts: { type: Array, default: () => [] },
    articles: { type: Array, default: () => [] },
});
</script>

<template>
    <Head title="Bewaard" />
    <MlmLayout>
        <section style="padding: 42px 0 8px">
            <span style="font-family: 'Dancing Script', cursive; font-size: 26px; color: #e0a24a">Jouw verzameling</span>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 34px; color: #473537; margin: 2px 0 8px; letter-spacing: -0.4px">🔖 Bewaard</h1>
            <p style="font-size: 15.5px; line-height: 1.65; color: #7a6c67; max-width: 640px; margin: 0 0 26px">
                Alles wat je hebt bewaard, op één plek terug te vinden - berichten uit de community en verhalen uit de blog.
            </p>

            <!-- Empty state -->
            <div
                v-if="!posts.length && !articles.length"
                style="background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 46px 30px; text-align: center; box-shadow: 0 8px 22px rgba(180, 150, 150, 0.07)"
            >
                <div style="font-size: 46px; margin-bottom: 10px">🔖</div>
                <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 19px; color: #473537; margin-bottom: 6px">Nog niets bewaard</div>
                <p style="font-size: 14.5px; color: #8a7d78; margin: 0 0 18px">Kom je iets moois tegen? Tik op "Bewaren" bij een bericht of verhaal en het verschijnt hier.</p>
                <Link
                    :href="route('community')"
                    style="display: inline-block; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border-radius: 999px; padding: 11px 24px; text-decoration: none; box-shadow: 0 8px 18px rgba(242, 139, 130, 0.3)"
                    >Naar de community</Link
                >
            </div>

            <!-- Berichten -->
            <div v-if="posts.length" style="margin-bottom: 34px">
                <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 20px; color: #473537; margin: 0 0 14px">Berichten</h2>
                <div style="display: grid; gap: 14px">
                    <Link
                        v-for="p in posts"
                        :key="'p-' + p.id"
                        :href="route('community')"
                        style="display: block; background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 18px 22px; text-decoration: none; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.07)"
                    >
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px">
                            <span style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537">{{ p.author_name }}</span>
                            <span style="font-size: 12.5px; color: #9a8d88">· {{ p.when }}</span>
                        </div>
                        <p style="margin: 0; font-size: 14.5px; line-height: 1.6; color: #5d514d">{{ p.excerpt }}</p>
                    </Link>
                </div>
            </div>

            <!-- Artikelen -->
            <div v-if="articles.length">
                <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 20px; color: #473537; margin: 0 0 14px">Artikelen</h2>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px">
                    <Link
                        v-for="a in articles"
                        :key="'a-' + a.id"
                        :href="route('blog.show', a.slug)"
                        style="display: block; background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; overflow: hidden; text-decoration: none; box-shadow: 0 8px 22px rgba(180, 150, 150, 0.07)"
                    >
                        <div :style="{ height: '110px', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '42px', background: 'linear-gradient(150deg,#FCE7EB,#FBE9ED)' }">{{ a.emoji }}</div>
                        <div style="padding: 16px 20px 20px">
                            <span style="display: inline-block; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 11px; letter-spacing: 0.5px; text-transform: uppercase; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 4px 11px; margin-bottom: 10px">{{ a.category }}</span>
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 17px; line-height: 1.3; color: #473537; margin-bottom: 7px">{{ a.title }}</div>
                            <p style="margin: 0; font-size: 13.5px; line-height: 1.55; color: #8a7d78">{{ a.excerpt }}</p>
                        </div>
                    </Link>
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
