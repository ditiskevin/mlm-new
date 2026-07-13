<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    type: { type: String, required: true }, // post | article
    id: { type: [Number, String], required: true },
    saved: { type: Boolean, default: false },
});

const page = usePage();
const isAuth = computed(() => !!page.props.auth?.user);

// Optimistic local state, seeded from the server-provided `saved` flag.
const isSaved = ref(props.saved);
const busy = ref(false);

const toggle = () => {
    if (busy.value) return;
    busy.value = true;
    isSaved.value = !isSaved.value; // optimistic
    router.post(
        route('bookmarks.toggle'),
        { type: props.type, id: props.id },
        {
            preserveScroll: true,
            preserveState: true,
            only: [],
            onError: () => {
                isSaved.value = !isSaved.value; // revert on failure
            },
            onFinish: () => {
                busy.value = false;
            },
        },
    );
};

const btnStyle = computed(
    () =>
        "display:inline-flex;align-items:center;gap:6px;font-family:'Quicksand',sans-serif;font-weight:600;font-size:12px;background:none;border:none;cursor:pointer;padding:0;color:" +
        (isSaved.value ? '#C0566B' : '#b5a8a3'),
);
</script>

<template>
    <button v-if="isAuth" type="button" @click="toggle" :title="isSaved ? 'Uit bewaard verwijderen' : 'Bewaren'" :style="btnStyle">
        <span v-if="isSaved">✓ Bewaard</span>
        <span v-else>🔖 Bewaren</span>
    </button>
</template>
