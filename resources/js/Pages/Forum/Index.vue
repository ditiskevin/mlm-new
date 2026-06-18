<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    categories: { type: Array, required: true },
    recent: { type: Array, required: true },
});

const isAuth = computed(() => !!usePage().props.auth?.user);
</script>

<template>
    <Head title="Forum" />
    <MlmLayout>
        <section style="padding: 42px 0 8px">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-bottom: 22px">
                <div>
                    <span style="font-family: 'Dancing Script', cursive; font-size: 26px; color: #e0a24a">Vraag &amp; deel</span>
                    <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 34px; color: #473537; margin: 2px 0 6px; letter-spacing: -0.4px">Forum</h1>
                    <p style="font-size: 15px; color: #7a6c67; margin: 0; max-width: 560px">Stel je vraag, deel je ervaring of zoek herkenning. Van ouder tot ouder, zonder oordeel. 💛</p>
                </div>
                <Link
                    :href="isAuth ? route('forum.create') : route('login')"
                    style="flex: none; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 13px 24px; text-decoration: none; box-shadow: 0 10px 22px rgba(242, 139, 130, 0.32)"
                    >+ Nieuw onderwerp</Link
                >
            </div>

            <div style="display: grid; grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr); gap: 32px; align-items: start">
                <!-- categories -->
                <div style="display: grid; gap: 14px">
                    <Link
                        v-for="c in categories"
                        :key="c.slug"
                        :href="route('forum.category', c.slug)"
                        style="display: flex; align-items: center; gap: 16px; background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; padding: 18px 20px; text-decoration: none; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)"
                    >
                        <span :style="{ width: '52px', height: '52px', borderRadius: '15px', flex: 'none', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '26px', background: 'linear-gradient(150deg,' + c.color_from + ',' + c.color_to + ')' }">{{ c.emoji }}</span>
                        <div style="flex: 1; min-width: 0">
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 17px; color: #473537">{{ c.name }}</div>
                            <div style="font-size: 13px; color: #8a7d78">{{ c.description }}</div>
                        </div>
                        <div style="flex: none; text-align: center">
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 18px; color: #c0566b">{{ c.topics_count }}</div>
                            <div style="font-size: 11px; color: #9a8d88">onderwerpen</div>
                        </div>
                    </Link>
                </div>

                <!-- recent topics -->
                <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 22px; box-shadow: 0 10px 26px rgba(180, 150, 150, 0.08); position: sticky; top: 90px">
                    <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 16px; color: #473537; margin: 0 0 14px">Recent besproken</h2>
                    <div style="display: grid; gap: 14px">
                        <Link
                            v-for="t in recent"
                            :key="t.slug"
                            :href="route('forum.topic', t.slug)"
                            style="display: flex; gap: 11px; align-items: flex-start; text-decoration: none"
                        >
                            <span :style="{ width: '36px', height: '36px', borderRadius: '50%', flex: 'none', background: t.avatar_color, display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#fff', fontSize: '14px' }">{{ t.initial }}</span>
                            <div style="flex: 1; min-width: 0">
                                <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #473537; line-height: 1.3">{{ t.title }}</div>
                                <div style="font-size: 12px; color: #9a8d88; margin-top: 2px">
                                    <span v-if="t.category">{{ t.category.emoji }} {{ t.category.name }} · </span>{{ t.replies_count }} reacties · {{ t.last_activity }}
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
