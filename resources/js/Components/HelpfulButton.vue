<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    replyId: { type: Number, required: true },
    count: { type: Number, default: 0 },
    voted: { type: Boolean, default: false },
});

const isAuth = computed(() => !!usePage().props.auth?.user);

// Optimistic local state mirrored from props.
const voted = ref(props.voted);
const count = ref(props.count);
const busy = ref(false);

const toggle = () => {
    if (!isAuth.value) return router.visit(route('login'));
    if (busy.value) return;
    busy.value = true;

    // Optimistic update.
    voted.value = !voted.value;
    count.value += voted.value ? 1 : -1;

    router.post(
        route('forum.replies.helpful', props.replyId),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            onError: () => {
                // Roll back on failure.
                voted.value = !voted.value;
                count.value += voted.value ? 1 : -1;
            },
            onFinish: () => {
                busy.value = false;
            },
        },
    );
};
</script>

<template>
    <button
        type="button"
        @click="toggle"
        :disabled="busy"
        :style="`display:inline-flex;align-items:center;gap:6px;font-family:'Quicksand',sans-serif;font-weight:600;font-size:12.5px;cursor:pointer;border-radius:999px;padding:5px 12px;transition:all .15s;${
            voted
                ? 'color:#4a7d61;background:#e4f3e9;border:1px solid #bfe3cd'
                : 'color:#7a6c67;background:#faf6f3;border:1px solid #f1e7e2'
        }`"
    >
        💛 Dit hielp mij<span v-if="count > 0"> ({{ count }})</span>
    </button>
</template>
