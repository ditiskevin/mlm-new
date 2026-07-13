<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    userId: { type: Number, required: true },
    blocked: { type: Boolean, default: false },
});

const page = usePage();
const myId = computed(() => page.props.auth?.user?.id ?? null);

// Only show for logged-in users looking at someone else's profile.
const visible = computed(() => myId.value !== null && myId.value !== props.userId);

const isBlocked = ref(props.blocked);
const busy = ref(false);

const toggle = () => {
    if (busy.value) return;

    // Confirm before blocking (unblocking is harmless).
    if (!isBlocked.value && !window.confirm('Weet je zeker dat je dit lid wilt blokkeren? Je ziet hun berichten niet meer en jullie kunnen elkaar geen bericht sturen.')) {
        return;
    }

    busy.value = true;
    router.post(
        route('members.block', props.userId),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                isBlocked.value = !isBlocked.value;
            },
            onFinish: () => {
                busy.value = false;
            },
        },
    );
};

const btnStyle = computed(() => ({
    fontFamily: "'Quicksand', sans-serif",
    fontWeight: 600,
    fontSize: '12.5px',
    borderRadius: '999px',
    padding: '9px 16px',
    cursor: 'pointer',
    border: '1.5px solid ' + (isBlocked.value ? '#F0D6DC' : '#EFE3E4'),
    background: isBlocked.value ? '#FCE7EB' : '#fff',
    color: isBlocked.value ? '#C0566B' : '#9a8d88',
}));
</script>

<template>
    <button v-if="visible" type="button" :style="btnStyle" @click="toggle">
        {{ isBlocked ? 'Deblokkeren' : '🚫 Blokkeren' }}
    </button>
</template>
