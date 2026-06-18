<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    categories: { type: Array, required: true },
    preselect: { type: String, default: null },
});

const form = useForm({
    forum_category: props.preselect && props.categories.some((c) => c.slug === props.preselect) ? props.preselect : props.categories[0]?.slug,
    title: '',
    body: '',
});

const label = 'display:block;font-family:\'Poppins\',sans-serif;font-weight:600;font-size:13.5px;color:#473537;margin-bottom:6px';
const field = 'width:100%;font-family:\'Quicksand\',sans-serif;font-size:14px;color:#5d514d;background:#fff;border:1px solid #f0e6e2;border-radius:14px;padding:11px 14px;outline:none';
const err = 'color:#b4574e;font-size:12.5px;margin-top:4px';

const submit = () => form.post(route('forum.topics.store'));
</script>

<template>
    <Head title="Nieuw onderwerp" />
    <MlmLayout>
        <section style="max-width: 680px; margin: 0 auto; padding: 36px 0 8px">
            <Link :href="route('forum.index')" style="font-size: 13.5px; font-weight: 600; color: #c0566b; text-decoration: none">‹ Terug naar het forum</Link>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 28px; color: #473537; margin: 14px 0 4px; letter-spacing: -0.4px">Nieuw onderwerp</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 24px">Stel je vraag of start een gesprek met andere ouders.</p>

            <form @submit.prevent="submit" style="display: grid; gap: 18px; background: #fff; border: 1px solid #f2e7e2; border-radius: 24px; padding: 26px; box-shadow: 0 10px 30px rgba(180, 150, 150, 0.08)">
                <div>
                    <label :style="label">Categorie</label>
                    <select v-model="form.forum_category" :style="field">
                        <option v-for="c in categories" :key="c.slug" :value="c.slug">{{ c.name }}</option>
                    </select>
                    <div v-if="form.errors.forum_category" :style="err">{{ form.errors.forum_category }}</div>
                </div>
                <div>
                    <label :style="label">Titel</label>
                    <input v-model="form.title" type="text" :style="field" placeholder="Waar gaat je vraag of verhaal over?" />
                    <div v-if="form.errors.title" :style="err">{{ form.errors.title }}</div>
                </div>
                <div>
                    <label :style="label">Bericht</label>
                    <textarea v-model="form.body" rows="6" :style="field" placeholder="Vertel meer…"></textarea>
                    <div v-if="form.errors.body" :style="err">{{ form.errors.body }}</div>
                </div>
                <div style="display: flex; gap: 12px; align-items: center">
                    <button type="submit" :disabled="form.processing" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 13px 26px; cursor: pointer; box-shadow: 0 10px 22px rgba(242, 139, 130, 0.32)">Plaatsen</button>
                    <Link :href="route('forum.index')" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 14px; color: #9a8d88; text-decoration: none">Annuleren</Link>
                </div>
            </form>
        </section>
    </MlmLayout>
</template>
