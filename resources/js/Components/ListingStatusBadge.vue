<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: { type: String, default: 'beschikbaar' },
});

const palette = {
    beschikbaar: { bg: '#e4f3e9', color: '#5fa07c', label: 'Beschikbaar' },
    gereserveerd: { bg: '#fbeed3', color: '#b07b1f', label: 'Gereserveerd' },
    verkocht: { bg: '#f3ece9', color: '#9a8d88', label: 'Verkocht' },
};

const current = computed(() => palette[props.status] ?? palette.beschikbaar);

const pillStyle = computed(
    () =>
        "display:inline-block;font-family:'Poppins',sans-serif;font-weight:600;font-size:11px;letter-spacing:0.3px;border-radius:999px;padding:4px 11px;background:" +
        current.value.bg +
        ';color:' +
        current.value.color,
);
</script>

<template>
    <span :style="pillStyle">{{ current.label }}</span>
</template>
