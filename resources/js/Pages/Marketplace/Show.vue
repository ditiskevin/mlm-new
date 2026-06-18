<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    listing: { type: Object, required: true },
});

const remove = () => {
    if (confirm('Weet je zeker dat je deze advertentie wilt verwijderen?')) {
        router.delete(route('marketplace.destroy', props.listing.slug));
    }
};
</script>

<template>
    <Head :title="listing.title" />
    <MlmLayout>
        <section style="max-width: 820px; margin: 0 auto; padding: 36px 0 8px">
            <Link :href="route('marketplace.index')" style="font-size: 13.5px; font-weight: 600; color: #c0566b; text-decoration: none">‹ Terug naar de marktplaats</Link>

            <div style="margin-top: 16px; display: grid; grid-template-columns: 320px minmax(0, 1fr); gap: 32px; align-items: start">
                <div style="position: relative; border-radius: 26px; height: 260px; display: flex; align-items: center; justify-content: center; font-size: 96px; background: linear-gradient(150deg, #fce7eb, #eaf5ee); box-shadow: 0 16px 36px rgba(214, 150, 160, 0.16); overflow: hidden">
                    <img v-if="listing.image_url" :src="listing.image_url" alt="" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover" />
                    <span v-else>{{ listing.emoji }}</span>
                </div>

                <div>
                    <span style="display: inline-block; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 11px; letter-spacing: 0.5px; text-transform: uppercase; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 4px 11px; margin-bottom: 10px">{{ listing.category }} · {{ listing.offer_label }}</span>
                    <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 28px; line-height: 1.2; color: #473537; margin: 0 0 8px">{{ listing.title }}</h1>
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 24px; color: #c0566b; margin-bottom: 14px">{{ listing.price ? '€ ' + listing.price : listing.offer_label }}</div>

                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px">
                        <span style="font-size: 13px; color: #6c5f5b; background: #faf4f1; border-radius: 999px; padding: 6px 13px">📍 {{ listing.location }}</span>
                        <span v-if="listing.condition" style="font-size: 13px; color: #6c5f5b; background: #faf4f1; border-radius: 999px; padding: 6px 13px">✨ {{ listing.condition }}</span>
                        <span style="font-size: 13px; color: #6c5f5b; background: #faf4f1; border-radius: 999px; padding: 6px 13px">🗓️ {{ listing.created }}</span>
                    </div>

                    <p style="font-size: 15px; line-height: 1.7; color: #5d514d; margin: 0 0 22px; white-space: pre-line">{{ listing.description }}</p>

                    <div style="display: flex; align-items: center; gap: 12px; border-top: 1px solid #f4ece8; padding-top: 16px">
                        <span :style="{ width: '44px', height: '44px', borderRadius: '50%', flex: 'none', background: listing.avatar_color, display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#fff', fontSize: '18px' }">{{ listing.author_name.charAt(0) }}</span>
                        <div style="flex: 1"><div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537">{{ listing.author_name }}</div><div style="font-size: 12.5px; color: #9a8d88">Aanbieder</div></div>
                        <Link :href="route('community')" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 11px 20px; text-decoration: none">Stuur bericht</Link>
                    </div>

                    <button v-if="listing.canDelete" @click="remove" style="margin-top: 16px; font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 13px; color: #b4574e; background: none; border: none; cursor: pointer; padding: 0">Advertentie verwijderen</button>
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
