<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    categories: { type: Array, default: () => [] },
});

const form = useForm({
    title: '',
    category: '',
    emoji: '',
    excerpt: '',
    body: '',
});

const wordCount = computed(() => form.body.trim().split(/\s+/).filter(Boolean).length);
const readMinutes = computed(() => Math.max(1, Math.round(wordCount.value / 200)));

const submit = () => form.post(route('blog.store'), { preserveScroll: true });

const labelStyle = "display:block;font-family:'Poppins',sans-serif;font-weight:600;font-size:13px;color:#5d514d;margin:0 0 6px";
const inputStyle =
    "width:100%;box-sizing:border-box;font-family:'Quicksand',sans-serif;font-size:14.5px;color:#473537;background:#fff;border:1.5px solid #EFE3E4;border-radius:14px;padding:12px 15px;outline:none";
const errStyle = 'color:#d9695f;font-size:12.5px;margin-top:5px';
</script>

<template>
    <Head title="Schrijf jouw verhaal" />
    <MlmLayout>
        <section style="padding: 42px 0 8px; max-width: 760px">
            <Link :href="route('blog.index')" style="font-size: 13px; color: #9a8d88; text-decoration: none">← Terug naar de blog</Link>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 32px; color: #473537; margin: 8px 0 6px; letter-spacing: -0.4px">Schrijf jouw verhaal</h1>
            <p style="font-size: 15px; line-height: 1.6; color: #7a6c67; margin: 0 0 24px">
                Deel jouw ervaring met de community. Na het insturen bekijkt een beheerder je verhaal kort voordat het wordt geplaatst. 💛
            </p>

            <form @submit.prevent="submit" style="background: #fff; border: 1px solid #f2e7e2; border-radius: 24px; padding: 28px; box-shadow: 0 10px 26px rgba(180, 150, 150, 0.08)">
                <div>
                    <label :style="labelStyle" for="title">Titel</label>
                    <input id="title" v-model="form.title" type="text" :style="inputStyle" placeholder="Geef je verhaal een titel" />
                    <div v-if="form.errors.title" :style="errStyle">{{ form.errors.title }}</div>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-top: 16px">
                    <div>
                        <label :style="labelStyle" for="category">Categorie</label>
                        <input id="category" v-model="form.category" list="cats" type="text" :style="inputStyle" placeholder="Bijv. Zwangerschap, ADHD…" />
                        <datalist id="cats">
                            <option v-for="c in categories" :key="c" :value="c" />
                        </datalist>
                        <div v-if="form.errors.category" :style="errStyle">{{ form.errors.category }}</div>
                    </div>
                    <div>
                        <label :style="labelStyle" for="emoji">Emoji (optioneel)</label>
                        <input id="emoji" v-model="form.emoji" type="text" maxlength="8" :style="inputStyle" placeholder="💛" />
                        <div v-if="form.errors.emoji" :style="errStyle">{{ form.errors.emoji }}</div>
                    </div>
                </div>

                <div style="margin-top: 16px">
                    <label :style="labelStyle" for="excerpt">Korte samenvatting</label>
                    <textarea id="excerpt" v-model="form.excerpt" rows="2" :style="inputStyle + ';resize:vertical;line-height:1.6'" placeholder="Eén of twee zinnen die je verhaal samenvatten…"></textarea>
                    <div style="display: flex; justify-content: space-between; margin-top: 5px">
                        <span v-if="form.errors.excerpt" :style="errStyle">{{ form.errors.excerpt }}</span>
                        <span style="font-size: 12px; color: #b5a8a3; margin-left: auto">{{ form.excerpt.length }}/300</span>
                    </div>
                </div>

                <div style="margin-top: 16px">
                    <label :style="labelStyle" for="body">Je verhaal</label>
                    <textarea id="body" v-model="form.body" rows="12" :style="inputStyle + ';resize:vertical;line-height:1.7'" placeholder="Schrijf hier je verhaal. Gebruik een lege regel tussen alinea's."></textarea>
                    <div style="display: flex; justify-content: space-between; margin-top: 5px">
                        <span v-if="form.errors.body" :style="errStyle">{{ form.errors.body }}</span>
                        <span style="font-size: 12px; color: #b5a8a3; margin-left: auto">{{ wordCount }} woorden · ± {{ readMinutes }} min lezen</span>
                    </div>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    :style="{
                        marginTop: '22px',
                        fontFamily: 'Poppins, sans-serif',
                        fontWeight: 600,
                        fontSize: '15px',
                        color: '#fff',
                        background: 'linear-gradient(135deg, #f7a8b5, #f28b82)',
                        border: 'none',
                        borderRadius: '999px',
                        padding: '14px 30px',
                        cursor: form.processing ? 'default' : 'pointer',
                        opacity: form.processing ? 0.65 : 1,
                        boxShadow: '0 10px 22px rgba(242, 139, 130, 0.32)',
                    }"
                >
                    {{ form.processing ? 'Insturen…' : 'Verhaal insturen 💌' }}
                </button>
            </form>
        </section>
    </MlmLayout>
</template>
