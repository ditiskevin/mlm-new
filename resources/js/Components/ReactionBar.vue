<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';

const props = defineProps({
    type: { type: String, required: true }, // post | comment
    id: { type: [Number, String], required: true },
    // [{ emoji, count }] grouped counts from the server
    summary: { type: Array, default: () => [] },
    // the emoji the current user picked, or null
    mine: { type: [String, null], default: null },
});

const EMOJIS = ['❤️', '💛', '🤗', '👏', '😢'];

const page = usePage();
const isAuth = computed(() => !!page.props.auth?.user);

// Optimistic local state, seeded from the server.
const counts = reactive(Object.fromEntries(EMOJIS.map((e) => [e, 0])));
props.summary.forEach((s) => {
    if (s && s.emoji in counts) counts[s.emoji] = s.count;
});
const mine = ref(props.mine);

const picking = ref(false);
const busy = ref(false);

// Emojis that currently have at least one reaction, in the canonical order.
const shown = computed(() => EMOJIS.filter((e) => counts[e] > 0));
const total = computed(() => EMOJIS.reduce((n, e) => n + counts[e], 0));

const react = (emoji) => {
    if (!isAuth.value || busy.value) return;
    busy.value = true;
    picking.value = false;

    // Optimistic count update mirroring the server toggle rules.
    if (mine.value === emoji) {
        counts[emoji] = Math.max(0, counts[emoji] - 1);
        mine.value = null;
    } else {
        if (mine.value) counts[mine.value] = Math.max(0, counts[mine.value] - 1);
        counts[emoji] = (counts[emoji] || 0) + 1;
        mine.value = emoji;
    }

    router.post(
        route('reactions.toggle'),
        { type: props.type, id: props.id, emoji },
        {
            preserveScroll: true,
            preserveState: true,
            only: [],
            onFinish: () => {
                busy.value = false;
            },
        },
    );
};

const pillStyle = (isMine) =>
    'display:inline-flex;align-items:center;gap:5px;font-family:"Quicksand",sans-serif;font-weight:600;font-size:12.5px;border-radius:999px;padding:3px 9px;cursor:' +
    (isAuth.value ? 'pointer' : 'default') +
    ';border:1px solid ' +
    (isMine ? '#F28B82' : '#F0E1E3') +
    ';background:' +
    (isMine ? '#FCE7EB' : '#faf6f3') +
    ';color:' +
    (isMine ? '#C0566B' : '#7a6c67');
</script>

<template>
    <span style="position: relative; display: inline-flex; align-items: center; gap: 7px; flex-wrap: wrap">
        <!-- Existing reaction counts -->
        <button v-for="e in shown" :key="e" type="button" @click="react(e)" :disabled="!isAuth" :style="pillStyle(mine === e)">
            <span>{{ e }}</span>
            <span>{{ counts[e] }}</span>
        </button>

        <!-- Add-a-reaction trigger (members only) -->
        <button
            v-if="isAuth"
            type="button"
            @click="picking = !picking"
            title="Reageer met een emoji"
            style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12px; color: #b5a8a3; background: none; border: none; cursor: pointer; padding: 2px 4px"
        >
            ＋ reactie
        </button>

        <!-- Guests: gentle hint of the total when there are reactions -->
        <span v-else-if="total === 0" style="font-family: 'Quicksand', sans-serif; font-size: 12px; color: #c3b8b3">Reageer met 💛</span>

        <!-- Tiny emoji picker -->
        <span
            v-if="picking"
            style="position: absolute; bottom: 100%; left: 0; margin-bottom: 6px; z-index: 40; display: flex; gap: 4px; background: #fff; border: 1px solid #f2e7e2; border-radius: 999px; padding: 6px 8px; box-shadow: 0 10px 24px rgba(120, 80, 80, 0.16)"
        >
            <button
                v-for="e in EMOJIS"
                :key="e"
                type="button"
                @click="react(e)"
                :style="'font-size:18px;line-height:1;background:' + (mine === e ? '#FCE7EB' : 'none') + ';border:none;border-radius:50%;cursor:pointer;padding:4px 5px'"
            >
                {{ e }}
            </button>
        </span>
    </span>
</template>
