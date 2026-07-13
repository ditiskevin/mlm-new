<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import FavoriteButton from '@/Components/FavoriteButton.vue';
import ListingStatusBadge from '@/Components/ListingStatusBadge.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    listings: { type: Array, required: true },
});

const offerColor = (type) =>
    type === 'gratis' ? '#5FB07F' : type === 'gevraagd' ? '#7AA8DC' : type === 'ruil' ? '#A87FD0' : '#C0566B';
</script>

<template>
    <Head title="Favorieten" />
    <MlmLayout>
        <section style="padding: 42px 0 8px">
            <div style="margin-bottom: 22px">
                <span style="font-family: 'Dancing Script', cursive; font-size: 26px; color: #e0a24a">Bewaard voor later</span>
                <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 34px; color: #473537; margin: 2px 0 6px; letter-spacing: -0.4px">Favorieten</h1>
                <p style="font-size: 15px; color: #7a6c67; margin: 0; max-width: 560px">De advertenties die je hebt bewaard. Kom later terug om te kijken of er iets is veranderd.</p>
            </div>

            <div v-if="listings.length" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px">
                <div v-for="l in listings" :key="l.slug" style="background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; overflow: hidden; box-shadow: 0 8px 22px rgba(180, 150, 150, 0.07)">
                    <Link :href="route('marketplace.show', l.slug)" style="display: block; text-decoration: none">
                        <div style="position: relative; height: 130px; display: flex; align-items: center; justify-content: center; font-size: 50px; background: linear-gradient(150deg, #fce7eb, #eaf5ee); overflow: hidden">
                            <img v-if="l.image_url" :src="l.image_url" alt="" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover" />
                            <span v-else>{{ l.emoji }}</span>
                            <span :style="{ position: 'absolute', top: '12px', left: '12px', fontFamily: 'Poppins, sans-serif', fontWeight: 600, fontSize: '11px', color: '#fff', background: offerColor(l.offer_type), borderRadius: '999px', padding: '4px 11px' }">{{ l.offer_label }}</span>
                            <span style="position: absolute; top: 12px; right: 12px"><ListingStatusBadge :status="l.status" /></span>
                        </div>
                        <div style="padding: 16px 18px 6px">
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 16px; line-height: 1.3; color: #473537; margin-bottom: 5px">{{ l.title }}</div>
                            <div style="font-size: 13px; color: #8a7d78; margin-bottom: 10px">{{ l.excerpt }}</div>
                            <div style="display: flex; align-items: center; justify-content: space-between">
                                <span style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 16px; color: #c0566b">{{ l.price ? '€ ' + l.price : l.offer_label }}</span>
                                <span style="font-size: 12px; color: #9a8d88">📍 {{ l.location }}</span>
                            </div>
                        </div>
                    </Link>
                    <div style="padding: 4px 18px 16px">
                        <FavoriteButton :slug="l.slug" :favorited="l.favorited" />
                    </div>
                </div>
            </div>
            <div v-else style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 22px; padding: 40px; text-align: center; color: #8a7d78">
                Je hebt nog geen advertenties bewaard. Tik op ♡ Bewaren om ze hier te verzamelen. 💛
            </div>
        </section>
    </MlmLayout>
</template>
