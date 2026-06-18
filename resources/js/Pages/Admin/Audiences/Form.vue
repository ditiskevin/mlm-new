<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    audience: { type: Object, default: null },
});

const isEdit = !!props.audience;

const form = useForm({
    name: props.audience?.name ?? '',
    emoji: props.audience?.emoji ?? '💛',
    tagline: props.audience?.tagline ?? '',
    intro: props.audience?.intro ?? '',
    body: props.audience?.body ?? '',
    tips: props.audience?.tips ?? '',
    color_from: props.audience?.color_from ?? '#FCE7EB',
    color_to: props.audience?.color_to ?? '#EAF5EE',
});

const label = 'display:block;font-family:\'Poppins\',sans-serif;font-weight:600;font-size:13.5px;color:#473537;margin-bottom:6px';
const field = 'width:100%;font-family:\'Quicksand\',sans-serif;font-size:14px;color:#5d514d;background:#fff;border:1px solid #f0e6e2;border-radius:14px;padding:11px 14px;outline:none';
const err = 'color:#b4574e;font-size:12.5px;margin-top:4px';

const submit = () => {
    if (isEdit) {
        form.patch(route('admin.audiences.update', props.audience.slug));
    } else {
        form.post(route('admin.audiences.store'));
    }
};
</script>

<template>
    <Head :title="isEdit ? 'Doelgroep bewerken' : 'Nieuwe doelgroep'" />
    <MlmLayout>
        <section style="max-width: 720px; margin: 0 auto; padding: 36px 0 8px">
            <Link :href="route('admin.audiences.index')" style="font-size: 13.5px; font-weight: 600; color: #c0566b; text-decoration: none">‹ Terug naar doelgroepen</Link>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 28px; color: #473537; margin: 14px 0 4px; letter-spacing: -0.4px">{{ isEdit ? 'Doelgroep bewerken' : 'Nieuwe doelgroep' }}</h1>
            <p v-if="!isEdit" style="font-size: 14.5px; color: #7a6c67; margin: 0 0 22px">Er wordt automatisch een community­groep en forumcategorie aan gekoppeld.</p>

            <form @submit.prevent="submit" style="display: grid; gap: 18px; background: #fff; border: 1px solid #f2e7e2; border-radius: 24px; padding: 26px; box-shadow: 0 10px 30px rgba(180, 150, 150, 0.08)">
                <div style="display: grid; grid-template-columns: 1fr 90px; gap: 16px">
                    <div>
                        <label :style="label">Naam</label>
                        <input v-model="form.name" type="text" :style="field" placeholder="Bijv. Co-ouders" />
                        <div v-if="form.errors.name" :style="err">{{ form.errors.name }}</div>
                    </div>
                    <div>
                        <label :style="label">Emoji</label>
                        <input v-model="form.emoji" type="text" :style="field" placeholder="💛" />
                    </div>
                </div>

                <div>
                    <label :style="label">Tagline</label>
                    <input v-model="form.tagline" type="text" :style="field" placeholder="Een korte, warme ondertitel" />
                    <div v-if="form.errors.tagline" :style="err">{{ form.errors.tagline }}</div>
                </div>

                <div>
                    <label :style="label">Intro (korte tekst)</label>
                    <textarea v-model="form.intro" rows="2" :style="field"></textarea>
                    <div v-if="form.errors.intro" :style="err">{{ form.errors.intro }}</div>
                </div>

                <div>
                    <label :style="label">Tekst (alinea's gescheiden door een lege regel)</label>
                    <textarea v-model="form.body" rows="7" :style="field"></textarea>
                    <div v-if="form.errors.body" :style="err">{{ form.errors.body }}</div>
                </div>

                <div>
                    <label :style="label">Tips (één per regel)</label>
                    <textarea v-model="form.tips" rows="4" :style="field" placeholder="Tip 1&#10;Tip 2"></textarea>
                    <div v-if="form.errors.tips" :style="err">{{ form.errors.tips }}</div>
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
                    <Link :href="route('admin.audiences.index')" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 14px; color: #9a8d88; text-decoration: none">Annuleren</Link>
                </div>
            </form>
        </section>
    </MlmLayout>
</template>
