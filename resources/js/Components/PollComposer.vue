<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const isAuth = computed(() => !!page.props.auth?.user);

const open = ref(false);

// Start with two empty options; members can add up to six.
const form = useForm({
    question: '',
    options: ['', ''],
    community_group_id: null,
});

const requireAuth = () => router.visit(route('login'));

const toggle = () => {
    if (!isAuth.value) return requireAuth();
    open.value = !open.value;
};

const addOption = () => {
    if (form.options.length < 6) form.options.push('');
};

const removeOption = (index) => {
    if (form.options.length > 2) form.options.splice(index, 1);
};

const canSubmit = computed(
    () =>
        form.question.trim().length > 0 &&
        form.options.filter((o) => o.trim().length > 0).length >= 2,
);

const submit = () => {
    if (!isAuth.value) return requireAuth();
    if (!canSubmit.value) return;
    form.post(route('polls.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.options = ['', ''];
            open.value = false;
        },
    });
};
</script>

<template>
    <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 16px 20px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.07)">
        <button
            type="button"
            @click="toggle"
            style="display: flex; align-items: center; gap: 10px; width: 100%; background: none; border: none; cursor: pointer; text-align: left; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #c0566b"
        >
            <span style="width: 42px; height: 42px; border-radius: 50%; flex: none; background: linear-gradient(135deg, #f9c8d0, #b7e1c0); display: flex; align-items: center; justify-content: center; font-size: 18px">📊</span>
            <span style="flex: 1">{{ isAuth ? 'Poll maken' : 'Log in om een poll te maken…' }}</span>
            <span v-if="isAuth" style="font-size: 13px; color: #9a8d88">{{ open ? '−' : '+' }}</span>
        </button>

        <div v-if="open" style="margin-top: 16px; border-top: 1px solid #f4ece8; padding-top: 16px">
            <input
                v-model="form.question"
                placeholder="Stel je vraag…"
                maxlength="200"
                style="width: 100%; box-sizing: border-box; background: #faf6f3; border: 1px solid #f1e7e2; border-radius: 14px; padding: 12px 16px; color: #473537; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; outline: none"
            />
            <div v-if="form.errors.question" style="color: #d9695f; font-size: 12.5px; margin-top: 6px">{{ form.errors.question }}</div>

            <div style="display: grid; gap: 8px; margin-top: 12px">
                <div v-for="(opt, i) in form.options" :key="i" style="display: flex; gap: 8px; align-items: center">
                    <input
                        v-model="form.options[i]"
                        :placeholder="'Optie ' + (i + 1)"
                        maxlength="80"
                        style="flex: 1; min-width: 0; background: #fff; border: 1px solid #f1e7e2; border-radius: 999px; padding: 10px 16px; color: #5d514d; font-family: 'Quicksand', sans-serif; font-size: 14px; outline: none"
                    />
                    <button
                        v-if="form.options.length > 2"
                        type="button"
                        @click="removeOption(i)"
                        title="Verwijder optie"
                        style="flex: none; width: 32px; height: 32px; border-radius: 50%; border: 1px solid #f1e2e2; background: #faf6f3; color: #b4574e; cursor: pointer; font-size: 16px; line-height: 1"
                    >
                        ×
                    </button>
                </div>
            </div>
            <div v-if="form.errors.options" style="color: #d9695f; font-size: 12.5px; margin-top: 6px">{{ form.errors.options }}</div>

            <div style="display: flex; align-items: center; gap: 12px; margin-top: 14px">
                <button
                    v-if="form.options.length < 6"
                    type="button"
                    @click="addOption"
                    style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 13px; color: #c0566b; background: #fbe9ed; border: none; border-radius: 999px; padding: 9px 16px; cursor: pointer"
                >
                    + Optie toevoegen
                </button>
                <button
                    type="button"
                    @click="submit"
                    :disabled="form.processing || !canSubmit"
                    :style="{
                        marginLeft: 'auto',
                        fontFamily: 'Poppins, sans-serif',
                        fontWeight: 600,
                        fontSize: '14px',
                        color: '#fff',
                        background: 'linear-gradient(135deg, #f7a8b5, #f28b82)',
                        border: 'none',
                        borderRadius: '999px',
                        padding: '11px 22px',
                        cursor: form.processing || !canSubmit ? 'not-allowed' : 'pointer',
                        opacity: form.processing || !canSubmit ? 0.6 : 1,
                    }"
                >
                    Plaats poll
                </button>
            </div>
        </div>
    </div>
</template>
