<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    slug: { type: String, required: true },
    favorited: { type: Boolean, default: false },
});

const page = usePage();
const isAuth = computed(() => !!page.props.auth?.user);

// Optimistic local state, seeded from the server-provided `favorited` flag.
const isFav = ref(props.favorited);
const busy = ref(false);

const toggle = () => {
    if (busy.value) return;
    busy.value = true;
    isFav.value = !isFav.value; // optimistic
    router.post(
        route('marketplace.favorite', props.slug),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            only: [],
            onError: () => {
                isFav.value = !isFav.value; // revert on failure
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
        (isFav.value ? '#C0566B' : '#b5a8a3'),
);
</script>

<template>
    <button v-if="isAuth" type="button" @click.stop.prevent="toggle" :title="isFav ? 'Uit favorieten verwijderen' : 'Bewaren'" :style="btnStyle">
        <span v-if="isFav">♥ Bewaard</span>
        <span v-else>♡ Bewaren</span>
    </button>
</template>
