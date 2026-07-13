<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    slug: { type: String, required: true },
    status: { type: String, default: 'beschikbaar' },
});

const options = [
    { key: 'beschikbaar', label: 'Beschikbaar', color: '#5fa07c', bg: '#e4f3e9' },
    { key: 'gereserveerd', label: 'Gereserveerd', color: '#b07b1f', bg: '#fbeed3' },
    { key: 'verkocht', label: 'Verkocht', color: '#9a8d88', bg: '#f3ece9' },
];

const current = ref(props.status);
const busy = ref(false);

const setStatus = (key) => {
    if (busy.value || current.value === key) return;
    const previous = current.value;
    busy.value = true;
    current.value = key; // optimistic
    router.patch(
        route('marketplace.status', props.slug),
        { status: key },
        {
            preserveScroll: true,
            preserveState: true,
            only: [],
            onError: () => {
                current.value = previous; // revert on failure
            },
            onFinish: () => {
                busy.value = false;
            },
        },
    );
};

const btnStyle = (o) =>
    "font-family:'Quicksand',sans-serif;font-weight:600;font-size:12.5px;cursor:pointer;border-radius:999px;padding:7px 14px;transition:all .15s;" +
    (current.value === o.key
        ? 'background:' + o.bg + ';color:' + o.color + ';border:1.5px solid ' + o.color
        : 'background:#fff;color:#9a8d88;border:1.5px solid #EFE3E4');
</script>

<template>
    <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 16px; border-top: 1px solid #f4ece8; padding-top: 16px">
        <span style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #9a8d88">Status van jouw advertentie</span>
        <div style="display: flex; flex-wrap: wrap; gap: 8px">
            <button v-for="o in options" :key="o.key" type="button" @click="setStatus(o.key)" :style="btnStyle(o)">{{ o.label }}</button>
        </div>
    </div>
</template>
