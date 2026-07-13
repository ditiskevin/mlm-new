<script setup>
import { Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    articleSlug: { type: String, required: true },
    comments: { type: Array, default: () => [] },
    canComment: { type: Boolean, default: false },
});

const form = useForm({ body: '' });

const submit = () => {
    if (!form.body.trim()) return;
    form.post(route('blog.comments.store', props.articleSlug), {
        preserveScroll: true,
        onSuccess: () => form.reset('body'),
    });
};

const remove = (comment) => {
    if (!confirm('Weet je zeker dat je deze reactie wilt verwijderen?')) return;
    router.delete(route('blog.comments.destroy', comment.id), { preserveScroll: true });
};

const avatarStyle = (color) =>
    `width:34px;height:34px;border-radius:50%;flex:none;background:${color};display:flex;align-items:center;justify-content:center;font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:14px`;
</script>

<template>
    <section style="max-width: 760px; margin: 30px auto 0; padding-top: 24px; border-top: 1px solid #f1e7e2">
        <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 19px; color: #473537; margin: 0 0 16px">
            Reacties<span v-if="comments.length" style="color: #b5a8a3; font-weight: 600"> · {{ comments.length }}</span>
        </h2>

        <div style="display: grid; gap: 12px">
            <div v-for="c in comments" :key="c.id" style="display: flex; gap: 10px; align-items: flex-start">
                <span :style="avatarStyle(c.avatar_color)">{{ c.initial }}</span>
                <div style="flex: 1; min-width: 0; background: #faf6f3; border: 1px solid #f1e7e2; border-radius: 16px; padding: 10px 14px">
                    <div style="display: flex; align-items: baseline; gap: 8px">
                        <span style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13.5px; color: #473537">{{ c.author_name }}</span>
                        <span style="font-size: 11.5px; color: #9a8d88">{{ c.when }}</span>
                        <button
                            v-if="c.can_delete"
                            @click="remove(c)"
                            title="Reactie verwijderen"
                            style="margin-left: auto; background: none; border: none; cursor: pointer; color: #b4574e; font-size: 13px; font-weight: 700; line-height: 1"
                        >
                            ✕
                        </button>
                    </div>
                    <p style="margin: 3px 0 0; font-size: 14px; line-height: 1.55; color: #5d514d; white-space: pre-line">{{ c.body }}</p>
                </div>
            </div>

            <p v-if="!comments.length" style="font-size: 14px; color: #9a8d88; margin: 0">Nog geen reacties. Wees de eerste die reageert! 💛</p>
        </div>

        <!-- comment form -->
        <div v-if="canComment" style="margin-top: 18px">
            <textarea
                v-model="form.body"
                placeholder="Schrijf een reactie…"
                rows="3"
                style="width: 100%; box-sizing: border-box; background: #fff; border: 1px solid #f1e7e2; border-radius: 16px; padding: 12px 16px; color: #5d514d; font-family: 'Quicksand', sans-serif; font-size: 14px; line-height: 1.55; outline: none; resize: vertical"
            ></textarea>
            <div v-if="form.errors.body" style="font-size: 12.5px; color: #b4574e; margin-top: 6px">{{ form.errors.body }}</div>
            <div style="display: flex; justify-content: flex-end; margin-top: 10px">
                <button
                    @click="submit"
                    :disabled="form.processing"
                    style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 11px 22px; cursor: pointer"
                >
                    Plaats reactie
                </button>
            </div>
        </div>

        <div v-else style="margin-top: 18px; background: #faf6f3; border: 1px solid #f1e7e2; border-radius: 16px; padding: 14px 18px; font-size: 14px; color: #7a6c67">
            <Link :href="route('login')" style="font-weight: 600; color: #c0566b; text-decoration: none">Log in om te reageren</Link>
            op dit verhaal.
        </div>
    </section>
</template>
