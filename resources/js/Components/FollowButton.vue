<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    userId: { type: Number, required: true },
    following: { type: Boolean, default: false },
});

const page = usePage();
const myId = computed(() => page.props.auth?.user?.id ?? null);

// Only show for logged-in users looking at someone else's profile.
const visible = computed(() => myId.value !== null && myId.value !== props.userId);

const isFollowing = ref(props.following);
const busy = ref(false);

const toggle = () => {
    if (busy.value) return;
    busy.value = true;
    // Optimistic flip.
    isFollowing.value = !isFollowing.value;
    router.post(
        route('follow.toggle', props.userId),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            only: [],
            onError: () => {
                isFollowing.value = !isFollowing.value; // revert on failure
            },
            onFinish: () => {
                busy.value = false;
            },
        },
    );
};

const btnStyle = computed(() => ({
    fontFamily: "'Poppins', sans-serif",
    fontWeight: 600,
    fontSize: '14px',
    borderRadius: '999px',
    padding: '11px 22px',
    cursor: 'pointer',
    border: '1.5px solid ' + (isFollowing.value ? '#F28B82' : '#F0D6DC'),
    background: isFollowing.value ? '#FCE7EB' : '#fff',
    color: '#C0566B',
}));
</script>

<template>
    <button v-if="visible" type="button" :style="btnStyle" @click="toggle">
        {{ isFollowing ? 'Volgend ✓' : 'Volgen' }}
    </button>
</template>
