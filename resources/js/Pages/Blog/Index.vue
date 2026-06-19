<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    articles: { type: Array, required: true },
    categories: { type: Array, required: true },
    activeCategory: { type: String, default: null },
    canWrite: { type: Boolean, default: false },
    mySubmissions: { type: Array, default: () => [] },
});

const statusLabel = { pending: 'In afwachting van goedkeuring', rejected: 'Niet geplaatst' };

const pillStyle = (active) =>
    "flex:none;font-family:'Quicksand',sans-serif;font-weight:600;font-size:13.5px;border-radius:999px;padding:9px 16px;cursor:pointer;text-decoration:none;border:1.5px solid " +
    (active ? '#F28B82' : '#EFE3E4') +
    ';background:' +
    (active ? '#FCE7EB' : '#fff') +
    ';color:' +
    (active ? '#C0566B' : '#9a8d88');
</script>

<template>
    <Head title="Inspiratie & blog" />
    <MlmLayout>
        <section style="padding: 42px 0 8px">
            <span style="font-family: 'Dancing Script', cursive; font-size: 26px; color: #e0a24a">Inspiratiehoek</span>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 34px; color: #473537; margin: 2px 0 8px; letter-spacing: -0.4px">Blog &amp; verhalen</h1>
            <p style="font-size: 15.5px; line-height: 1.65; color: #7a6c67; max-width: 640px; margin: 0 0 22px">
                Ervaringen, tips en verhalen over zwangerschap, het eerste jaar en bijzondere uitdagingen zoals Caprin1, schisis, autisme, NLD en ADHD -
                van ouder tot ouder.
            </p>

            <!-- Write CTA -->
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; background: linear-gradient(160deg, #fdf2f4, #f3f9f5); border: 1px solid #f2e1e4; border-radius: 22px; padding: 18px 24px; margin-bottom: 22px">
                <div>
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 17px; color: #473537">Heb jij een verhaal om te delen? ✍️</div>
                    <p style="margin: 3px 0 0; font-size: 14px; color: #7a6c67">Vertel jouw ervaring en inspireer andere ouders. Verhalen worden voor plaatsing kort bekeken.</p>
                </div>
                <Link
                    v-if="canWrite"
                    :href="route('blog.create')"
                    style="flex: none; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14.5px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border-radius: 999px; padding: 12px 24px; text-decoration: none; box-shadow: 0 8px 18px rgba(242, 139, 130, 0.3)"
                    >Schrijf jouw verhaal</Link
                >
                <Link
                    v-else
                    :href="route('login')"
                    style="flex: none; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14.5px; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 12px 24px; text-decoration: none"
                    >Log in om te schrijven</Link
                >
            </div>

            <!-- My pending submissions -->
            <div v-if="mySubmissions.length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 18px; padding: 16px 20px; margin-bottom: 22px">
                <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #473537; margin-bottom: 8px">Jouw inzendingen</div>
                <div v-for="s in mySubmissions" :key="s.slug" style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; color: #7a6c67; padding: 4px 0">
                    <span style="font-weight: 600; color: #473537">{{ s.title }}</span>
                    <span :style="{ fontSize: '11.5px', fontWeight: 600, borderRadius: '999px', padding: '2px 9px', color: s.status === 'pending' ? '#b07b1f' : '#9a8d88', background: s.status === 'pending' ? '#fbeed3' : '#f3ece9' }">{{ statusLabel[s.status] ?? s.status }}</span>
                </div>
            </div>

            <div class="mlm-scroll" style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 8px; margin-bottom: 22px">
                <Link :href="route('blog.index')" :style="pillStyle(!activeCategory)">Alle</Link>
                <Link v-for="c in categories" :key="c" :href="route('blog.index', { category: c })" :style="pillStyle(activeCategory === c)">{{ c }}</Link>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px">
                <Link
                    v-for="a in articles"
                    :key="a.slug"
                    :href="route('blog.show', a.slug)"
                    style="display: block; background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; overflow: hidden; text-decoration: none; box-shadow: 0 8px 22px rgba(180, 150, 150, 0.07)"
                >
                    <div :style="{ height: '120px', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '46px', background: 'linear-gradient(150deg,' + a.color_from + ',' + a.color_to + ')' }">{{ a.emoji }}</div>
                    <div style="padding: 18px 20px 20px">
                        <span style="display: inline-block; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 11px; letter-spacing: 0.5px; text-transform: uppercase; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 4px 11px; margin-bottom: 10px">{{ a.category }}</span>
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 17px; line-height: 1.3; color: #473537; margin-bottom: 7px">{{ a.title }}</div>
                        <p style="margin: 0 0 12px; font-size: 13.5px; line-height: 1.55; color: #8a7d78">{{ a.excerpt }}</p>
                        <div style="font-size: 12px; color: #b5a8a3">{{ a.published }} · {{ a.reading_minutes }} min lezen</div>
                    </div>
                </Link>
            </div>
        </section>
    </MlmLayout>
</template>
