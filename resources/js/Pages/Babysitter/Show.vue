<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import ReportButton from '@/Components/ReportButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    sitter: { type: Object, required: true },
});

const remove = () => {
    if (confirm('Weet je zeker dat je dit oppasprofiel wilt verwijderen?')) {
        router.delete(route('babysitters.destroy', props.sitter.id));
    }
};
</script>

<template>
    <Head :title="`Oppas ${sitter.name}`" />
    <MlmLayout>
        <section style="max-width: 760px; margin: 0 auto; padding: 36px 0 8px">
            <Link :href="route('babysitters.index')" style="font-size: 13.5px; font-weight: 600; color: #5fa07c; text-decoration: none">‹ Terug naar oppas vinden</Link>

            <div style="margin-top: 16px; background: #fff; border: 1px solid #f2e7e2; border-radius: 26px; overflow: hidden; box-shadow: 0 12px 30px rgba(180, 150, 150, 0.1)">
                <div style="height: 90px; background: linear-gradient(120deg, #b7e1c0, #f9c8d0)"></div>
                <div style="padding: 0 28px 28px; margin-top: -40px">
                    <span :style="{ width: '80px', height: '80px', borderRadius: '50%', background: sitter.avatar_color, border: '5px solid #fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#fff', fontSize: '30px' }">{{ sitter.initial }}</span>
                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 10px">
                        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 26px; color: #473537; margin: 0">{{ sitter.name }}<span v-if="sitter.age" style="font-weight: 500; color: #9a8d88">, {{ sitter.age }}</span></h1>
                        <span v-if="sitter.has_vog" style="font-size: 12px; font-weight: 600; color: #5e9e78; background: #e4f3e9; border-radius: 999px; padding: 5px 12px">✓ VOG aanwezig</span>
                    </div>
                    <div style="font-size: 13.5px; color: #9a8d88; margin-top: 2px">📍 {{ sitter.location }}</div>

                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin: 20px 0">
                        <div style="background: #faf6f3; border-radius: 16px; padding: 14px; text-align: center">
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 18px; color: #5fa07c">{{ sitter.hourly_rate ? '€ ' + sitter.hourly_rate : '—' }}</div>
                            <div style="font-size: 12px; color: #9a8d88">per uur</div>
                        </div>
                        <div style="background: #faf6f3; border-radius: 16px; padding: 14px; text-align: center">
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 18px; color: #473537">{{ sitter.experience_years }} jr</div>
                            <div style="font-size: 12px; color: #9a8d88">ervaring</div>
                        </div>
                        <div style="background: #faf6f3; border-radius: 16px; padding: 14px; text-align: center">
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 14px; color: #473537; line-height: 1.3; margin-top: 2px">{{ sitter.availability }}</div>
                            <div style="font-size: 12px; color: #9a8d88">beschikbaar</div>
                        </div>
                    </div>

                    <p style="font-size: 15px; line-height: 1.7; color: #5d514d; margin: 0 0 22px; white-space: pre-line">{{ sitter.bio }}</p>

                    <div style="display: flex; gap: 12px; align-items: center; border-top: 1px solid #f4ece8; padding-top: 18px">
                        <Link :href="route('community')" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #8fd0a6, #5fb07f); border: none; border-radius: 999px; padding: 12px 22px; text-decoration: none">Neem contact op</Link>
                        <button v-if="sitter.canDelete" @click="remove" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 13px; color: #b4574e; background: none; border: none; cursor: pointer">Profiel verwijderen</button>
                        <span v-if="!sitter.canDelete" style="margin-left: auto"><ReportButton type="babysitter" :id="sitter.id" label="Profiel melden" /></span>
                    </div>
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
