<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    articles: { type: Array, required: true },
});

const approve = (slug) => router.patch(route('admin.articles.approve', slug), {}, { preserveScroll: true });
const reject = (slug) => {
    if (confirm('Dit verhaal afwijzen en verwijderen?')) {
        router.delete(route('admin.articles.destroy', slug), { preserveScroll: true });
    }
};
</script>

<template>
    <Head title="Blog-inzendingen · Beheer" />
    <AdminLayout>
        <section style="padding: 38px 0 8px">
            <Link :href="route('admin.dashboard')" style="font-size: 13px; color: #9a8d88; text-decoration: none">← Terug naar beheer</Link>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 32px; color: #473537; margin: 8px 0 6px; letter-spacing: -0.4px">Blog-inzendingen</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 20px">Verhalen die leden hebben ingestuurd en wachten op goedkeuring.</p>

            <div style="display: grid; gap: 12px">
                <article v-for="a in articles" :key="a.slug" style="background: #fff; border: 1px solid #f6cfca; border-radius: 18px; padding: 18px 22px; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)">
                    <div style="display: flex; align-items: flex-start; gap: 14px">
                        <span :style="{ flex: 'none', width: '48px', height: '48px', borderRadius: '14px', background: '#fce7eb', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '24px' }">{{ a.emoji }}</span>
                        <div style="flex: 1; min-width: 0">
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap">
                                <span style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 16.5px; color: #473537">{{ a.title }}</span>
                                <span style="font-size: 11px; font-weight: 600; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 2px 9px">{{ a.category }}</span>
                            </div>
                            <div style="font-size: 13px; color: #8a7d78; margin: 4px 0 10px">door {{ a.author }} · {{ a.reading_minutes }} min lezen · {{ a.when }}</div>
                            <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #5d514d">{{ a.excerpt }}</p>
                        </div>
                        <div style="flex: none; display: grid; gap: 8px">
                            <Link :href="route('blog.show', a.slug)" target="_blank" style="text-align: center; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #5d514d; background: #faf4f1; border: 1px solid #f0e6e2; border-radius: 999px; padding: 8px 14px; text-decoration: none">Bekijk</Link>
                            <button @click="approve(a.slug)" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #5fa07c; background: #e4f3e9; border: 1px solid #b7e1c0; border-radius: 999px; padding: 8px 14px; cursor: pointer">Goedkeuren</button>
                            <button @click="reject(a.slug)" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #b4574e; background: #fdf0ef; border: 1px solid #f6cfca; border-radius: 999px; padding: 8px 14px; cursor: pointer">Afwijzen</button>
                        </div>
                    </div>
                </article>

                <div v-if="!articles.length" style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 16px; padding: 36px; text-align: center; color: #8a7d78">Geen open inzendingen. 🌿</div>
            </div>
        </section>
    </AdminLayout>
</template>
