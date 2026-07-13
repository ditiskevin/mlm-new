<script setup>
import { computed } from 'vue';

const props = defineProps({
    // Uitgerekende datum als 'YYYY-MM-DD' (of null wanneer niet zwanger).
    dueDate: { type: String, default: null },
});

// Standaard zwangerschapsduur.
const TERM_WEEKS = 40;
const MS_PER_DAY = 1000 * 60 * 60 * 24;

const due = computed(() => {
    if (!props.dueDate) return null;
    const d = new Date(props.dueDate);
    return Number.isNaN(d.getTime()) ? null : d;
});

const daysUntil = computed(() => {
    if (!due.value) return null;
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    return Math.round((due.value.getTime() - today.getTime()) / MS_PER_DAY);
});

const weeksPregnant = computed(() => {
    if (daysUntil.value === null) return 0;
    const weeksUntil = daysUntil.value / 7;
    const weeks = TERM_WEEKS - weeksUntil;
    return Math.max(0, Math.min(TERM_WEEKS, Math.round(weeks)));
});

const progressPct = computed(() => Math.round((weeksPregnant.value / TERM_WEEKS) * 100));

// Vriendelijke status-tekst.
const remainingLabel = computed(() => {
    if (daysUntil.value === null) return '';
    if (daysUntil.value > 0) return `Nog ± ${daysUntil.value} dagen te gaan`;
    if (daysUntil.value === 0) return 'Vandaag is de dag! 🎉';
    return `${Math.abs(daysUntil.value)} dagen over tijd — heel veel sterkte 💛`;
});
</script>

<template>
    <div
        v-if="due"
        class="mlm-wrap-mobile"
        style="display: flex; align-items: center; gap: 18px; margin-top: 20px; border-radius: 22px; padding: 20px 24px; border: 1px solid #f2e1e4; background: linear-gradient(150deg, #fdf2f4, #eef8f1)"
    >
        <span style="font-size: 40px">🤰</span>
        <div style="flex: 1">
            <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 18px; color: #473537">
                Je bent ± {{ weeksPregnant }} weken zwanger
            </div>
            <div style="font-size: 13.5px; color: #6c5f5b; margin-bottom: 8px">{{ remainingLabel }}</div>
            <div style="height: 8px; background: #f4dfe4; border-radius: 999px; overflow: hidden; max-width: 360px">
                <div :style="{ height: '100%', width: progressPct + '%', background: 'linear-gradient(90deg, #F7A8B5, #F28B82)', borderRadius: '999px', transition: 'width .3s' }"></div>
            </div>
        </div>
        <span style="flex: none; font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 20px; color: #c0566b">{{ weeksPregnant }}/40</span>
    </div>
</template>
