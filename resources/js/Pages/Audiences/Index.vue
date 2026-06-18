<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    audiences: { type: Array, required: true },
});
</script>

<template>
    <Head title="Voor wie" />
    <MlmLayout>
        <section style="padding: 42px 0 8px">
            <span style="font-family: 'Dancing Script', cursive; font-size: 26px; color: #e0a24a">Elke ouder is uniek</span>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 34px; color: #473537; margin: 2px 0 8px; letter-spacing: -0.4px">Voor wie is MommyLovesMe?</h1>
            <p style="font-size: 15.5px; line-height: 1.65; color: #7a6c67; max-width: 640px; margin: 0 0 26px">
                Ouderschap heeft vele vormen. Daarom maken we ruimte voor iedere ouder — met herkenning, steun en een eigen plek. Ontdek de hoek die bij jou past.
            </p>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px">
                <Link
                    v-for="a in audiences"
                    :key="a.slug"
                    :href="route('audiences.show', a.slug)"
                    style="display: block; border-radius: 24px; overflow: hidden; text-decoration: none; border: 1px solid #f2e7e2; box-shadow: 0 8px 22px rgba(180, 150, 150, 0.07)"
                >
                    <div :style="{ display: 'flex', alignItems: 'center', gap: '18px', padding: '26px 28px', background: 'linear-gradient(150deg,' + a.color_from + ',' + a.color_to + ')' }">
                        <span style="font-size: 44px">{{ a.emoji }}</span>
                        <div>
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 21px; color: #473537">{{ a.name }}</div>
                            <div style="font-family: 'Dancing Script', cursive; font-size: 18px; color: #b07f56">{{ a.tagline }}</div>
                        </div>
                    </div>
                    <div style="background: #fff; padding: 18px 28px 22px">
                        <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #7a6c67">{{ a.intro }}</p>
                        <span style="display: inline-block; margin-top: 12px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13.5px; color: #c0566b">Ontdek deze hoek →</span>
                    </div>
                </Link>
            </div>
        </section>
    </MlmLayout>
</template>
