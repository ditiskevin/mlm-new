<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

const props = defineProps({
    poll: { type: Object, required: true },
});

const page = usePage();
const isAuth = computed(() => !!page.props.auth?.user);

// Local, optimistic vote state seeded from the server so the bars react instantly.
const state = reactive({
    myOption: props.poll.my_option ?? null,
    counts: Object.fromEntries(props.poll.options.map((o) => [o.id, o.votes])),
});

const totalVotes = computed(() =>
    Object.values(state.counts).reduce((sum, n) => sum + n, 0),
);

const percentFor = (optionId) => {
    const total = totalVotes.value;
    if (!total) return 0;
    return Math.round((state.counts[optionId] / total) * 100);
};

const requireAuth = () => router.visit(route('login'));

const vote = (option) => {
    if (!isAuth.value) return requireAuth();
    if (state.myOption === option.id) return; // already my choice

    // Optimistically move the vote from the previous option to the new one.
    if (state.myOption !== null && state.counts[state.myOption] !== undefined) {
        state.counts[state.myOption] = Math.max(0, state.counts[state.myOption] - 1);
    }
    state.counts[option.id] = (state.counts[option.id] ?? 0) + 1;
    state.myOption = option.id;

    router.post(
        route('polls.vote', props.poll.id),
        { poll_option_id: option.id },
        { preserveScroll: true, preserveState: true, only: [] },
    );
};

const remove = () => {
    if (confirm('Weet je zeker dat je deze poll wilt verwijderen?')) {
        router.delete(route('polls.destroy', props.poll.id), { preserveScroll: true });
    }
};

const avatarStyle = computed(
    () =>
        `width:46px;height:46px;border-radius:50%;flex:none;background:${props.poll.avatar_color};display:flex;align-items:center;justify-content:center;font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:18px`,
);
</script>

<template>
    <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 20px 22px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.07)">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px">
            <span :style="avatarStyle">{{ poll.initial }}</span>
            <div style="flex: 1">
                <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537">{{ poll.author_name }}</div>
                <div style="font-size: 12.5px; color: #9a8d88">{{ poll.when }}</div>
            </div>
            <span style="font-size: 11.5px; font-weight: 600; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 5px 12px">📊 Poll</span>
        </div>

        <p style="margin: 0 0 16px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 16px; line-height: 1.5; color: #473537">{{ poll.question }}</p>

        <div style="display: grid; gap: 10px">
            <button
                v-for="opt in poll.options"
                :key="opt.id"
                type="button"
                @click="vote(opt)"
                :style="{
                    position: 'relative',
                    display: 'block',
                    width: '100%',
                    textAlign: 'left',
                    border: '1.5px solid ' + (state.myOption === opt.id ? '#F28B82' : '#f1e2e2'),
                    background: '#faf6f3',
                    borderRadius: '14px',
                    padding: '11px 15px',
                    cursor: 'pointer',
                    overflow: 'hidden',
                    fontFamily: 'Quicksand, sans-serif',
                }"
            >
                <span
                    :style="{
                        position: 'absolute',
                        inset: '0',
                        width: percentFor(opt.id) + '%',
                        background: 'linear-gradient(90deg, #b7e1c0, #f9c8d0)',
                        opacity: 0.55,
                        transition: 'width .35s ease',
                    }"
                ></span>
                <span style="position: relative; display: flex; align-items: center; justify-content: space-between; gap: 12px">
                    <span style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px; color: #473537">
                        <span v-if="state.myOption === opt.id" style="color: #c0566b">✓</span>
                        {{ opt.label }}
                    </span>
                    <span style="font-weight: 700; font-size: 13px; color: #7a6c67; flex: none">{{ percentFor(opt.id) }}% · {{ state.counts[opt.id] }}</span>
                </span>
            </button>
        </div>

        <div style="display: flex; align-items: center; gap: 16px; border-top: 1px solid #f4ece8; margin-top: 15px; padding-top: 12px">
            <span style="font-size: 13px; color: #9a8d88; font-weight: 600">{{ totalVotes }} {{ totalVotes === 1 ? 'stem' : 'stemmen' }}</span>
            <span v-if="!isAuth" style="font-size: 12.5px; color: #9a8d88">Log in om te stemmen</span>
            <button
                v-if="poll.can_delete"
                type="button"
                @click="remove"
                style="margin-left: auto; font-size: 12px; font-weight: 600; color: #b4574e; background: none; border: none; cursor: pointer; font-family: 'Quicksand', sans-serif"
            >
                Verwijderen
            </button>
        </div>
    </div>
</template>
