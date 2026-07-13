<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: { type: Number, default: 0 },
    readonly: { type: Boolean, default: false },
    size: { type: Number, default: 20 },
});

const emit = defineEmits(['update:modelValue']);

const stars = [1, 2, 3, 4, 5];
const value = computed(() => Math.round(props.modelValue || 0));

const pick = (n) => {
    if (props.readonly) return;
    emit('update:modelValue', n);
};
</script>

<template>
    <span :style="{ display: 'inline-flex', alignItems: 'center', gap: '2px', lineHeight: 1 }" role="img" :aria-label="`${value} van 5 sterren`">
        <span
            v-for="n in stars"
            :key="n"
            @click="pick(n)"
            :style="{
                fontSize: size + 'px',
                color: n <= value ? '#f0a92e' : '#e2d6cf',
                cursor: readonly ? 'default' : 'pointer',
                userSelect: 'none',
                transition: 'transform 0.08s ease',
            }"
            >{{ n <= value ? '★' : '☆' }}</span
        >
    </span>
</template>
