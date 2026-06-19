<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
    color_from: '#FCE7EB',
    color_to: '#EAF5EE',
});

const label = "display:block;font-family:'Poppins',sans-serif;font-weight:600;font-size:13.5px;color:#473537;margin-bottom:6px";
const field = "width:100%;box-sizing:border-box;font-family:'Quicksand',sans-serif;font-size:14px;color:#5d514d;background:#fff;border:1px solid #f0e6e2;border-radius:14px;padding:11px 14px;outline:none";
const err = 'color:#b4574e;font-size:12.5px;margin-top:4px';

const submit = () => form.post(route('community.groups.store'));
</script>

<template>
    <Head title="Nieuwe groep" />
    <MlmLayout>
        <section style="max-width: 640px; margin: 0 auto; padding: 36px 0 8px">
            <Link :href="route('community')" style="font-size: 13.5px; font-weight: 600; color: #c0566b; text-decoration: none">‹ Terug naar de community</Link>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 28px; color: #473537; margin: 14px 0 4px; letter-spacing: -0.4px">Maak je eigen groep</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 22px">Start een groep rond een thema dat jou bezighoudt. Andere leden kunnen hem volgen en meepraten.</p>

            <form @submit.prevent="submit" style="display: grid; gap: 18px; background: #fff; border: 1px solid #f2e7e2; border-radius: 24px; padding: 26px; box-shadow: 0 10px 30px rgba(180, 150, 150, 0.08)">
                <div>
                    <label :style="label">Naam van de groep</label>
                    <input v-model="form.name" type="text" :style="field" placeholder="Bijv. Mama's van Utrecht" />
                    <div v-if="form.errors.name" :style="err">{{ form.errors.name }}</div>
                </div>

                <div>
                    <label :style="label">Omschrijving (optioneel)</label>
                    <textarea v-model="form.description" rows="3" :style="field" placeholder="Waar gaat deze groep over? Wie is welkom?"></textarea>
                    <div style="display: flex; justify-content: space-between; margin-top: 4px">
                        <span v-if="form.errors.description" :style="err">{{ form.errors.description }}</span>
                        <span style="font-size: 12px; color: #b5a8a3; margin-left: auto">{{ form.description.length }}/500</span>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 110px; gap: 16px; align-items: end">
                    <div>
                        <label :style="label">Kleur 1</label>
                        <input v-model="form.color_from" type="color" style="width: 100%; height: 42px; border: 1px solid #f0e6e2; border-radius: 14px; cursor: pointer; background: #fff" />
                    </div>
                    <div>
                        <label :style="label">Kleur 2</label>
                        <input v-model="form.color_to" type="color" style="width: 100%; height: 42px; border: 1px solid #f0e6e2; border-radius: 14px; cursor: pointer; background: #fff" />
                    </div>
                    <div>
                        <label :style="label">Voorbeeld</label>
                        <span :style="{ display: 'block', height: '42px', borderRadius: '14px', background: 'linear-gradient(135deg,' + form.color_from + ',' + form.color_to + ')' }"></span>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; align-items: center">
                    <button type="submit" :disabled="form.processing" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 13px 26px; cursor: pointer; box-shadow: 0 10px 22px rgba(242, 139, 130, 0.32)">Groep aanmaken</button>
                    <Link :href="route('community')" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 14px; color: #9a8d88; text-decoration: none">Annuleren</Link>
                </div>
            </form>
        </section>
    </MlmLayout>
</template>
