<script setup>
import StarRating from '@/Components/StarRating.vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    sitterId: { type: Number, required: true },
    reviews: { type: Array, default: () => [] },
    average: { type: [Number, null], default: null },
    count: { type: Number, default: 0 },
    canReview: { type: Boolean, default: false },
    myReview: { type: [Object, null], default: null },
});

const isAuth = computed(() => !!usePage().props.auth?.user);

const form = useForm({
    rating: props.myReview?.rating ?? 0,
    body: props.myReview?.body ?? '',
});

const averageLabel = computed(() =>
    props.average !== null ? Number(props.average).toFixed(1).replace('.', ',') : null
);

const submit = () => {
    if (!form.rating) return;
    form.post(route('babysitter.reviews.store', props.sitterId), {
        preserveScroll: true,
    });
};

const removeReview = (id) => {
    if (confirm('Weet je zeker dat je deze beoordeling wilt verwijderen?')) {
        router.delete(route('babysitter.reviews.destroy', id), { preserveScroll: true });
    }
};
</script>

<template>
    <section style="margin-top: 22px; border-top: 1px solid #f4ece8; padding-top: 22px">
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap">
            <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 19px; color: #473537; margin: 0">Beoordelingen</h2>
            <div v-if="average !== null" style="display: flex; align-items: center; gap: 8px">
                <StarRating :model-value="average" readonly :size="18" />
                <span style="font-size: 13.5px; color: #7a6c67; font-weight: 600">{{ averageLabel }} uit 5 · {{ count }} {{ count === 1 ? 'beoordeling' : 'beoordelingen' }}</span>
            </div>
            <span v-else style="font-size: 13.5px; color: #9a8d88">Nog geen beoordelingen</span>
        </div>

        <!-- Review list -->
        <div v-if="reviews.length" style="margin-top: 18px; display: flex; flex-direction: column; gap: 12px">
            <div
                v-for="review in reviews"
                :key="review.id"
                style="background: #faf6f3; border: 1px solid #f2e7e2; border-radius: 18px; padding: 16px 18px"
            >
                <div style="display: flex; align-items: center; gap: 10px">
                    <span :style="{ width: '38px', height: '38px', borderRadius: '50%', background: '#e4f3e9', display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#5e9e78', fontSize: '15px' }">{{ review.initial }}</span>
                    <div style="flex: 1; min-width: 0">
                        <div style="font-family: 'Quicksand', sans-serif; font-weight: 700; font-size: 14.5px; color: #473537">{{ review.author_name }}</div>
                        <div style="display: flex; align-items: center; gap: 8px">
                            <StarRating :model-value="review.rating" readonly :size="15" />
                            <span style="font-size: 12px; color: #9a8d88">{{ review.when }}</span>
                        </div>
                    </div>
                    <button
                        v-if="review.can_delete"
                        @click="removeReview(review.id)"
                        title="Beoordeling verwijderen"
                        style="background: none; border: none; color: #b4574e; font-size: 16px; cursor: pointer; line-height: 1"
                        >✕</button
                    >
                </div>
                <p v-if="review.body" style="font-size: 14px; line-height: 1.6; color: #5d514d; margin: 10px 0 0; white-space: pre-line">{{ review.body }}</p>
            </div>
        </div>

        <!-- Review form (members, not the profile owner) -->
        <form
            v-if="canReview"
            @submit.prevent="submit"
            style="margin-top: 18px; background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; padding: 18px 20px; box-shadow: 0 8px 22px rgba(180, 150, 150, 0.08)"
        >
            <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 15px; color: #473537; margin-bottom: 10px">{{ myReview ? 'Jouw beoordeling aanpassen' : 'Laat een beoordeling achter' }}</div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px">
                <StarRating v-model="form.rating" :size="26" />
                <span style="font-size: 13px; color: #9a8d88">{{ form.rating ? form.rating + ' / 5' : 'Kies een score' }}</span>
            </div>
            <div v-if="form.errors.rating" style="font-size: 12.5px; color: #C0566B; margin-bottom: 8px">{{ form.errors.rating }}</div>
            <textarea
                v-model="form.body"
                rows="3"
                maxlength="1500"
                placeholder="Deel je ervaring (optioneel)…"
                style="width: 100%; box-sizing: border-box; font-family: 'Quicksand', sans-serif; font-size: 14px; color: #473537; border: 1px solid #eaddd7; border-radius: 14px; padding: 12px 14px; resize: vertical; background: #fdfbfa"
            ></textarea>
            <div v-if="form.errors.body" style="font-size: 12.5px; color: #C0566B; margin-top: 6px">{{ form.errors.body }}</div>
            <div style="display: flex; align-items: center; gap: 12px; margin-top: 12px">
                <button
                    type="submit"
                    :disabled="form.processing || !form.rating"
                    :style="{
                        fontFamily: 'Poppins, sans-serif',
                        fontWeight: 600,
                        fontSize: '14px',
                        color: '#fff',
                        background: 'linear-gradient(135deg, #8fd0a6, #5fb07f)',
                        border: 'none',
                        borderRadius: '999px',
                        padding: '11px 22px',
                        cursor: form.processing || !form.rating ? 'not-allowed' : 'pointer',
                        opacity: form.processing || !form.rating ? 0.6 : 1,
                    }"
                    >{{ myReview ? 'Beoordeling bijwerken' : 'Beoordeling plaatsen' }}</button
                >
            </div>
        </form>

        <!-- Not allowed to review -->
        <div v-else style="margin-top: 16px; font-size: 13.5px; color: #9a8d88">
            <Link v-if="!isAuth" :href="route('login')" style="color: #5fa07c; font-weight: 600; text-decoration: none">Log in</Link>
            <span v-if="!isAuth"> om een beoordeling achter te laten.</span>
            <span v-else>Je kunt je eigen oppasprofiel niet beoordelen.</span>
        </div>
    </section>
</template>
