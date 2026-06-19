<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    type: { type: String, required: true }, // post | comment | forum_topic | forum_reply | listing | babysitter | article | user
    id: { type: [Number, String], required: true },
    label: { type: String, default: 'Melden' },
    // 'text' = subtle inline link, 'icon' = small flag button
    variant: { type: String, default: 'text' },
});

const page = usePage();
const isAuth = computed(() => !!page.props.auth?.user);

const reasons = {
    spam: 'Spam of reclame',
    ongepast: 'Ongepaste inhoud',
    intimidatie: 'Pesten of intimidatie',
    oplichting: 'Nep of oplichting',
    anders: 'Iets anders',
};

const open = ref(false);
const form = useForm({ type: props.type, id: props.id, reason: 'ongepast', details: '' });

const submit = () => {
    form.post(route('reports.store'), {
        preserveScroll: true,
        onSuccess: () => {
            open.value = false;
            form.reset('details');
            form.reason = 'ongepast';
        },
    });
};
</script>

<template>
    <span v-if="isAuth">
        <button
            type="button"
            @click="open = true"
            :title="label"
            :style="
                variant === 'icon'
                    ? 'background:none;border:none;cursor:pointer;color:#b5a8a3;font-size:14px;padding:4px'
                    : `font-family:'Quicksand',sans-serif;font-weight:600;font-size:12px;color:#b5a8a3;background:none;border:none;cursor:pointer;padding:0`
            "
        >
            <span v-if="variant === 'icon'">⚑</span>
            <span v-else>⚑ {{ label }}</span>
        </button>

        <!-- Modal -->
        <teleport to="body">
            <div
                v-if="open"
                @click.self="open = false"
                style="position: fixed; inset: 0; z-index: 300; background: rgba(60, 45, 45, 0.4); display: flex; align-items: center; justify-content: center; padding: 20px"
            >
                <div style="background: #fff; border-radius: 22px; padding: 26px; width: 100%; max-width: 440px; box-shadow: 0 24px 60px rgba(80, 50, 50, 0.3)">
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 19px; color: #473537; margin-bottom: 4px">Inhoud melden</div>
                    <p style="font-size: 13.5px; color: #8a7d78; margin: 0 0 18px">Help ons de community veilig te houden. Een beheerder bekijkt je melding.</p>

                    <form @submit.prevent="submit">
                        <div style="display: grid; gap: 8px; margin-bottom: 16px">
                            <label
                                v-for="(text, key) in reasons"
                                :key="key"
                                :style="{
                                    display: 'flex',
                                    alignItems: 'center',
                                    gap: '10px',
                                    cursor: 'pointer',
                                    fontFamily: 'Quicksand, sans-serif',
                                    fontSize: '14px',
                                    color: '#5d514d',
                                    border: '1.5px solid ' + (form.reason === key ? '#F28B82' : '#EFE3E4'),
                                    background: form.reason === key ? '#FCE7EB' : '#fff',
                                    borderRadius: '12px',
                                    padding: '10px 13px',
                                }"
                            >
                                <input v-model="form.reason" type="radio" :value="key" style="accent-color: #f28b82" />
                                {{ text }}
                            </label>
                        </div>

                        <textarea
                            v-model="form.details"
                            rows="3"
                            placeholder="Toelichting (optioneel)"
                            style="width: 100%; box-sizing: border-box; font-family: 'Quicksand', sans-serif; font-size: 14px; color: #473537; background: #fff; border: 1.5px solid #efe3e4; border-radius: 12px; padding: 11px 13px; outline: none; resize: vertical"
                        ></textarea>
                        <div v-if="form.errors.details" style="color: #d9695f; font-size: 12.5px; margin-top: 5px">{{ form.errors.details }}</div>

                        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 18px">
                            <button type="button" @click="open = false" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 14px; color: #9a8d88; background: none; border: none; cursor: pointer; padding: 10px 14px">Annuleren</button>
                            <button type="submit" :disabled="form.processing" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 11px 22px; cursor: pointer; box-shadow: 0 8px 18px rgba(242, 139, 130, 0.3)">Versturen</button>
                        </div>
                    </form>
                </div>
            </div>
        </teleport>
    </span>
</template>
