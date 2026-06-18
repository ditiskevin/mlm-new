<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    categories: { type: Array, required: true },
    offerTypes: { type: Object, required: true },
});

const form = useForm({
    title: '',
    category: props.categories[0],
    offer_type: 'aanbod',
    price: '',
    condition: '',
    location: '',
    description: '',
    image: null,
});

const imagePreview = ref(null);
const onImageChange = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    form.image = file;
    imagePreview.value = URL.createObjectURL(file);
};

const needsPrice = computed(() => form.offer_type === 'aanbod' || form.offer_type === 'ruil');

const label = 'display:block;font-family:\'Poppins\',sans-serif;font-weight:600;font-size:13.5px;color:#473537;margin-bottom:6px';
const field = 'width:100%;font-family:\'Quicksand\',sans-serif;font-size:14px;color:#5d514d;background:#fff;border:1px solid #f0e6e2;border-radius:14px;padding:11px 14px;outline:none';
const err = 'color:#b4574e;font-size:12.5px;margin-top:4px';

const submit = () => form.post(route('marketplace.store'));
</script>

<template>
    <Head title="Advertentie plaatsen" />
    <MlmLayout>
        <section style="max-width: 680px; margin: 0 auto; padding: 36px 0 8px">
            <Link :href="route('marketplace.index')" style="font-size: 13.5px; font-weight: 600; color: #c0566b; text-decoration: none">‹ Terug naar de marktplaats</Link>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 28px; color: #473537; margin: 14px 0 4px; letter-spacing: -0.4px">Advertentie plaatsen</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 24px">Deel je spullen met andere ouders in de community.</p>

            <form @submit.prevent="submit" style="display: grid; gap: 18px; background: #fff; border: 1px solid #f2e7e2; border-radius: 24px; padding: 26px; box-shadow: 0 10px 30px rgba(180, 150, 150, 0.08)">
                <div>
                    <label :style="label">Titel</label>
                    <input v-model="form.title" type="text" :style="field" placeholder="Bijv. Maxi-Cosi autostoeltje" />
                    <div v-if="form.errors.title" :style="err">{{ form.errors.title }}</div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px">
                    <div>
                        <label :style="label">Categorie</label>
                        <select v-model="form.category" :style="field">
                            <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
                        </select>
                    </div>
                    <div>
                        <label :style="label">Type</label>
                        <select v-model="form.offer_type" :style="field">
                            <option v-for="(lbl, key) in offerTypes" :key="key" :value="key">{{ lbl }}</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px">
                    <div v-if="needsPrice">
                        <label :style="label">Prijs (€)</label>
                        <input v-model="form.price" type="number" step="0.01" min="0" :style="field" placeholder="0,00" />
                        <div v-if="form.errors.price" :style="err">{{ form.errors.price }}</div>
                    </div>
                    <div>
                        <label :style="label">Staat (optioneel)</label>
                        <input v-model="form.condition" type="text" :style="field" placeholder="nieuw / zo goed als nieuw / gebruikt" />
                    </div>
                </div>

                <div>
                    <label :style="label">Plaats</label>
                    <input v-model="form.location" type="text" :style="field" placeholder="Bijv. Utrecht" />
                    <div v-if="form.errors.location" :style="err">{{ form.errors.location }}</div>
                </div>

                <div>
                    <label :style="label">Foto (optioneel)</label>
                    <div style="display: flex; align-items: center; gap: 14px">
                        <img v-if="imagePreview" :src="imagePreview" alt="" style="width: 72px; height: 72px; border-radius: 14px; object-fit: cover; flex: none" />
                        <label style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 13px; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 10px 16px; cursor: pointer">
                            {{ imagePreview ? 'Andere foto kiezen' : 'Foto uploaden' }}
                            <input type="file" accept="image/*" style="display: none" @change="onImageChange" />
                        </label>
                    </div>
                    <div v-if="form.errors.image" :style="err">{{ form.errors.image }}</div>
                </div>

                <div>
                    <label :style="label">Omschrijving</label>
                    <textarea v-model="form.description" rows="5" :style="field" placeholder="Vertel iets over de staat, maat, ophalen/verzenden…"></textarea>
                    <div v-if="form.errors.description" :style="err">{{ form.errors.description }}</div>
                </div>

                <div style="display: flex; gap: 12px; align-items: center">
                    <button type="submit" :disabled="form.processing" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 13px 26px; cursor: pointer; box-shadow: 0 10px 22px rgba(242, 139, 130, 0.32)">Plaatsen</button>
                    <Link :href="route('marketplace.index')" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 14px; color: #9a8d88; text-decoration: none">Annuleren</Link>
                </div>
            </form>
        </section>
    </MlmLayout>
</template>
