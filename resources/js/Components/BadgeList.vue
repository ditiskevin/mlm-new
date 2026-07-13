<script setup>
defineProps({
    badges: { type: Array, default: () => [] },
});
</script>

<template>
    <div v-if="badges.length" style="display: flex; flex-wrap: wrap; gap: 8px">
        <span
            v-for="b in badges"
            :key="b.key"
            :title="b.description"
            style="
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-family: 'Poppins', sans-serif;
                font-weight: 600;
                font-size: 12.5px;
                color: #c0566b;
                background: #fce7eb;
                border: 1px solid #f6d6dd;
                border-radius: 999px;
                padding: 5px 12px;
                cursor: default;
            "
        >
            <span style="font-size: 14px; line-height: 1">{{ b.emoji }}</span>
            <span>{{ b.name }}</span>
        </span>
    </div>
</template>
