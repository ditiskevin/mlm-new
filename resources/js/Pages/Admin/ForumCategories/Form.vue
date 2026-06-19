<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ category: { type: Object, default: null } });
const isEdit = !!props.category;

const form = useForm({
    name: props.category?.name ?? '',
    description: props.category?.description ?? '',
    emoji: props.category?.emoji ?? '💬',
    color_from: props.category?.color_from ?? '#FCE7EB',
    color_to: props.category?.color_to ?? '#EAF5EE',
    position: props.category?.position ?? null,
});

const label = "display:block;font-family:'Poppins',sans-serif;font-weight:600;font-size:13.5px;color:#473537;margin-bottom:6px";
const field = "width:100%;box-sizing:border-box;font-family:'Quicksand',sans-serif;font-size:14px;color:#5d514d;background:#fff;border:1px solid #f0e6e2;border-radius:14px;padding:11px 14px;outline:none";
const err = 'color:#b4574e;font-size:12.5px;margin-top:4px';

const submit = () => (isEdit ? form.patch(route('admin.forum-categories.update', props.category.slug)) : form.post(route('admin.forum-categories.store')));
</script>

<template>
    <Head :title="(isEdit ? 'Categorie bewerken' : 'Nieuwe categorie') + ' · Beheer'" />
    <AdminLayout>
        <Link :href="route('admin.forum-categories.index')" style="font-size: 13px; color: #9a8d88; text-decoration: none">← Terug naar categorieën</Link>
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 26px; color: #473537; margin: 8px 0 18px; letter-spacing: -0.4px">{{ isEdit ? 'Categorie bewerken' : 'Nieuwe categorie' }}</h1>

        <form @submit.prevent="submit" style="display: grid; gap: 18px; max-width: 580px; background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 26px; box-shadow: 0 10px 26px rgba(180, 150, 150, 0.08)">
            <div style="display: grid; grid-template-columns: 1fr 90px 110px; gap: 16px">
                <div>
                    <label :style="label">Naam</label>
                    <input v-model="form.name" type="text" :style="field" />
                    <div v-if="form.errors.name" :style="err">{{ form.errors.name }}</div>
                </div>
                <div>
                    <label :style="label">Emoji</label>
                    <input v-model="form.emoji" type="text" maxlength="8" :style="field" />
                </div>
                <div>
                    <label :style="label">Volgorde</label>
                    <input v-model="form.position" type="number" min="0" :style="field" placeholder="auto" />
                </div>
            </div>
            <div>
                <label :style="label">Omschrijving</label>
                <textarea v-model="form.description" rows="2" :style="field"></textarea>
                <div v-if="form.errors.description" :style="err">{{ form.errors.description }}</div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px">
                <div>
                    <label :style="label">Kleur 1</label>
                    <input v-model="form.color_from" type="color" style="width: 100%; height: 42px; border: 1px solid #f0e6e2; border-radius: 14px; cursor: pointer; background: #fff" />
                </div>
                <div>
                    <label :style="label">Kleur 2</label>
                    <input v-model="form.color_to" type="color" style="width: 100%; height: 42px; border: 1px solid #f0e6e2; border-radius: 14px; cursor: pointer; background: #fff" />
                </div>
            </div>
            <div style="display: flex; gap: 12px; align-items: center">
                <button type="submit" :disabled="form.processing" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 13px 26px; cursor: pointer; box-shadow: 0 10px 22px rgba(242, 139, 130, 0.32)">{{ isEdit ? 'Opslaan' : 'Aanmaken' }}</button>
                <Link :href="route('admin.forum-categories.index')" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 14px; color: #9a8d88; text-decoration: none">Annuleren</Link>
            </div>
        </form>
    </AdminLayout>
</template>
