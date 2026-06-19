<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { nextTick, onBeforeUnmount, ref, watch } from 'vue';

const props = defineProps({
    conversations: { type: Array, default: () => [] },
    active: { type: Object, default: null },
});

const page = usePage();
const currentUserId = page.props.auth?.user?.id;

const form = useForm({ body: '' });
const threadEl = ref(null);

const scrollToBottom = () => {
    nextTick(() => {
        if (threadEl.value) threadEl.value.scrollTop = threadEl.value.scrollHeight;
    });
};

watch(() => props.active?.id, scrollToBottom, { immediate: true });
watch(() => props.active?.messages?.length, scrollToBottom);

// --- Realtime via Laravel Reverb / Echo ---
let channelId = null;

const leaveChannel = () => {
    if (channelId && window.Echo) {
        window.Echo.leave(`conversation.${channelId}`);
        channelId = null;
    }
};

const subscribe = (id) => {
    if (!window.Echo || !id || id === channelId) return;
    leaveChannel();
    channelId = id;
    window.Echo.private(`conversation.${id}`).listen('.message.sent', (e) => {
        // Ignore our own echo; the local copy already shows it.
        if (e.sender_id === currentUserId) return;
        // Dedupe by id, then append for an instant feel.
        if (props.active && !props.active.messages.some((m) => m.id === e.id)) {
            props.active.messages.push({ id: e.id, body: e.body, mine: false, author: e.author, when: e.when });
            scrollToBottom();
        }
        // Reconcile read state + sidebar previews + the unread nav badge.
        router.reload({ only: ['conversations', 'auth'], preserveScroll: true });
    });
};

watch(() => props.active?.id, (id) => (id ? subscribe(id) : leaveChannel()), { immediate: true });
onBeforeUnmount(leaveChannel);

const send = () => {
    if (!form.body.trim() || !props.active) return;
    form.post(route('messages.store', props.active.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset('body');
            // Refresh the thread + sidebar to reflect the new message.
            router.reload({ only: ['active', 'conversations'] });
        },
    });
};

const openConversation = (id) => router.get(route('messages.show', id), {}, { preserveState: true, preserveScroll: true });
</script>

<template>
    <Head title="Berichten" />
    <MlmLayout>
        <section style="padding: 32px 0 8px">
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 30px; color: #473537; margin: 0 0 4px; letter-spacing: -0.4px">Berichten</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 22px">Je privégesprekken met andere leden. 💛</p>

            <div style="display: grid; grid-template-columns: 320px minmax(0, 1fr); gap: 20px; align-items: stretch; min-height: 540px">
                <!-- Conversation list -->
                <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; overflow: hidden; box-shadow: 0 10px 26px rgba(180, 150, 150, 0.08); display: flex; flex-direction: column">
                    <div style="padding: 16px 20px; border-bottom: 1px solid #f4ece8; font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 15px; color: #473537">Gesprekken</div>
                    <div style="flex: 1; overflow-y: auto">
                        <button
                            v-for="c in conversations"
                            :key="c.id"
                            @click="openConversation(c.id)"
                            :style="{
                                width: '100%',
                                textAlign: 'left',
                                display: 'flex',
                                gap: '12px',
                                alignItems: 'center',
                                padding: '14px 18px',
                                border: 'none',
                                borderBottom: '1px solid #f7f1ee',
                                cursor: 'pointer',
                                background: active && active.id === c.id ? '#fdf2f4' : '#fff',
                            }"
                        >
                            <img v-if="c.avatar_url" :src="c.avatar_url" alt="" style="width: 44px; height: 44px; border-radius: 50%; flex: none; object-fit: cover" />
                            <span v-else :style="{ width: '44px', height: '44px', borderRadius: '50%', flex: 'none', background: c.avatar_color, display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#fff', fontSize: '17px' }">{{ c.initial }}</span>
                            <div style="flex: 1; min-width: 0">
                                <div style="display: flex; justify-content: space-between; gap: 8px">
                                    <span style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14.5px; color: #473537; white-space: nowrap; overflow: hidden; text-overflow: ellipsis">{{ c.name }}</span>
                                    <span style="font-size: 11px; color: #b5a8a3; flex: none">{{ c.when }}</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px">
                                    <span :style="{ flex: 1, minWidth: 0, fontSize: '13px', color: c.unread ? '#473537' : '#9a8d88', fontWeight: c.unread ? 600 : 400, whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }">{{ c.preview }}</span>
                                    <span v-if="c.unread" style="flex: none; width: 9px; height: 9px; border-radius: 50%; background: #f28b82"></span>
                                </div>
                            </div>
                        </button>
                        <div v-if="!conversations.length" style="padding: 30px 20px; text-align: center; color: #8a7d78; font-size: 14px">Nog geen gesprekken.<br />Start er één vanaf een profiel of advertentie. 💌</div>
                    </div>
                </div>

                <!-- Active thread -->
                <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; box-shadow: 0 10px 26px rgba(180, 150, 150, 0.08); display: flex; flex-direction: column">
                    <template v-if="active">
                        <div style="display: flex; align-items: center; gap: 12px; padding: 16px 22px; border-bottom: 1px solid #f4ece8">
                            <img v-if="active.other.avatar_url" :src="active.other.avatar_url" alt="" style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover" />
                            <span v-else :style="{ width: '42px', height: '42px', borderRadius: '50%', background: active.other.avatar_color, display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#fff', fontSize: '16px' }">{{ active.other.initial }}</span>
                            <span style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 16px; color: #473537">{{ active.other.name }}</span>
                        </div>

                        <div ref="threadEl" style="flex: 1; overflow-y: auto; padding: 20px 22px; display: flex; flex-direction: column; gap: 10px; max-height: 420px">
                            <div v-for="m in active.messages" :key="m.id" :style="{ display: 'flex', justifyContent: m.mine ? 'flex-end' : 'flex-start' }">
                                <div
                                    :style="{
                                        maxWidth: '72%',
                                        padding: '10px 15px',
                                        borderRadius: m.mine ? '18px 18px 4px 18px' : '18px 18px 18px 4px',
                                        fontSize: '14.5px',
                                        lineHeight: 1.5,
                                        whiteSpace: 'pre-wrap',
                                        wordBreak: 'break-word',
                                        color: m.mine ? '#fff' : '#5d514d',
                                        background: m.mine ? 'linear-gradient(135deg, #f7a8b5, #f28b82)' : '#faf4f1',
                                        border: m.mine ? 'none' : '1px solid #f1e7e2',
                                    }"
                                >
                                    {{ m.body }}
                                    <div :style="{ fontSize: '10.5px', marginTop: '4px', textAlign: 'right', color: m.mine ? 'rgba(255,255,255,0.8)' : '#b5a8a3' }">{{ m.when }}</div>
                                </div>
                            </div>
                            <div v-if="!active.messages.length" style="margin: auto; text-align: center; color: #b5a8a3; font-size: 14px">Zeg hallo 👋</div>
                        </div>

                        <form @submit.prevent="send" style="display: flex; gap: 10px; padding: 14px 18px; border-top: 1px solid #f4ece8">
                            <input
                                v-model="form.body"
                                placeholder="Schrijf een bericht…"
                                style="flex: 1; min-width: 0; background: #faf6f3; border: 1px solid #f1e7e2; border-radius: 999px; padding: 12px 18px; color: #5d514d; font-family: 'Quicksand', sans-serif; font-size: 14px; outline: none"
                            />
                            <button type="submit" :disabled="form.processing || !form.body.trim()" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 11px 22px; cursor: pointer; flex: none">Stuur</button>
                        </form>
                    </template>

                    <div v-else style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #8a7d78; padding: 40px; text-align: center">
                        <div style="font-size: 44px; margin-bottom: 10px">💬</div>
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 16px; color: #473537">Kies een gesprek</div>
                        <p style="font-size: 14px; max-width: 280px">Selecteer links een gesprek, of start een nieuw bericht vanaf iemands profiel of advertentie.</p>
                    </div>
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
