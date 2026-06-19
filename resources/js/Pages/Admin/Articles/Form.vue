<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    article: { type: Object, default: null },
});

const isEdit = !!props.article;

const form = useForm({
    title: props.article?.title ?? '',
    category: props.article?.category ?? '',
    author_name: props.article?.author_name ?? '',
    emoji: props.article?.emoji ?? '💛',
    color_from: props.article?.color_from ?? '#FCE7EB',
    color_to: props.article?.color_to ?? '#EAF5EE',
    reading_minutes: props.article?.reading_minutes ?? null,
    excerpt: props.article?.excerpt ?? '',
    body: props.article?.body ?? '',
    published: props.article?.published ?? true,
});

const label = "display:block;font-family:'Poppins',sans-serif;font-weight:600;font-size:13.5px;color:#473537;margin-bottom:6px";
const field = "width:100%;box-sizing:border-box;font-family:'Quicksand',sans-serif;font-size:14px;color:#5d514d;background:#fff;border:1px solid #f0e6e2;border-radius:14px;padding:11px 14px;outline:none";
const err = 'color:#b4574e;font-size:12.5px;margin-top:4px';

const submit = () => {
    if (isEdit) {
        form.patch(route('admin.articles.update', props.article.slug));
    } else {
        form.post(route('admin.articles.store'));
    }
};
</script>

<template>
    <Head :title="isEdit ? 'Artikel bewerken' : 'Nieuw artikel'" />
    <MlmLayout>
        <section style="max-width: 740px; margin: 0 auto; padding: 36px 0 8px">
            <Link :href="route('admin.articles.index')" style="font-size: 13.5px; font-weight: 600; color: #c0566b; text-decoration: none">‹ Terug naar blogbeheer</Link>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 28px; color: #473537; margin: 14px 0 18px; letter-spacing: -0.4px">{{ isEdit ? 'Artikel bewerken' : 'Nieuw artikel' }}</h1>

            <form @submit.prevent="submit" style="display: grid; gap: 18px; background: #fff; border: 1px solid #f2e7e2; border-radius: 24px; padding: 26px; box-shadow: 0 10px 30px rgba(180, 150, 150, 0.08)">
                <div>
                    <label :style="label">Titel</label>
                    <input v-model="form.title" type="text" :style="field" placeholder="Titel van het artikel" />
                    <div v-if="form.errors.title" :style="err">{{ form.errors.title }}</div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 90px; gap: 16px">
                    <div>
                        <label :style="label">Categorie</label>
                        <input v-model="form.category" type="text" :style="field" placeholder="Bijv. Zwangerschap" />
                        <div v-if="form.errors.category" :style="err">{{ form.errors.category }}</div>
                    </div>
                    <div>
                        <label :style="label">Auteur</label>
                        <input v-model="form.author_name" type="text" :style="field" placeholder="Stephanie van der Kooij" />
                        <div v-if="form.errors.author_name" :style="err">{{ form.errors.author_name }}</div>
                    </div>
                    <div>
                        <label :style="label">Emoji</label>
                        <input v-model="form.emoji" type="text" maxlength="8" :style="field" placeholder="💛" />
                    </div>
                </div>

                <div>
                    <label :style="label">Korte samenvatting</label>
                    <textarea v-model="form.excerpt" rows="2" :style="field" placeholder="Eén of twee zinnen"></textarea>
                    <div v-if="form.errors.excerpt" :style="err">{{ form.errors.excerpt }}</div>
                </div>

                <div>
                    <label :style="label">Tekst (alinea's gescheiden door een lege regel)</label>
                    <textarea v-model="form.body" rows="10" :style="field"></textarea>
                    <div v-if="form.errors.body" :style="err">{{ form.errors.body }}</div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; align-items: end">
                    <div>
                        <label :style="label">Kleur 1</label>
                        <input v-model="form.color_from" type="color" style="width: 100%; height: 42px; border: 1px solid #f0e6e2; border-radius: 14px; cursor: pointer; background: #fff" />
                    </div>
                    <div>
                        <label :style="label">Kleur 2</label>
                        <input v-model="form.color_to" type="color" style="width: 100%; height: 42px; border: 1px solid #f0e6e2; border-radius: 14px; cursor: pointer; background: #fff" />
                    </div>
                    <div>
                        <label :style="label">Leestijd (min, leeg = auto)</label>
                        <input v-model="form.reading_minutes" type="number" min="1" max="120" :style="field" placeholder="auto" />
                        <div v-if="form.errors.reading_minutes" :style="err">{{ form.errors.reading_minutes }}</div>
                    </div>
                </div>

                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-family: 'Quicksand', sans-serif; font-size: 14.5px; color: #5d514d">
                    <input v-model="form.published" type="checkbox" style="width: 18px; height: 18px; accent-color: #f28b82" />
                    Direct publiceren (anders blijft het artikel verborgen tot je het publiceert)
                </label>

                <div style="display: flex; gap: 12px; align-items: center">
                    <button type="submit" :disabled="form.processing" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 13px 26px; cursor: pointer; box-shadow: 0 10px 22px rgba(242, 139, 130, 0.32)">{{ isEdit ? 'Opslaan' : 'Aanmaken' }}</button>
                    <Link :href="route('admin.articles.index')" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 14px; color: #9a8d88; text-decoration: none">Annuleren</Link>
                </div>
            </form>
        </section>
    </MlmLayout>
</template>
