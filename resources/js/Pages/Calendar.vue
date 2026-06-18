<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    weeks: { type: Array, required: true },
});

const idx = ref(Math.min(13, props.weeks.length - 1)); // default week 14
const sliderMax = props.weeks.length - 1;

const current = computed(() => props.weeks[idx.value]);

const triFirst = { 1: 0, 2: 12, 3: 25 };

const selectWeek = (i) => (idx.value = i);
const prevWeek = () => (idx.value = Math.max(0, idx.value - 1));
const nextWeek = () => (idx.value = Math.min(sliderMax, idx.value + 1));
const goTrimester = (t) => (idx.value = triFirst[t]);

const chipStyle = (active) =>
    "flex:none;font-family:'Quicksand',sans-serif;font-weight:600;font-size:13px;min-width:44px;padding:8px 5px;border-radius:13px;cursor:pointer;border:1.5px solid " +
    (active ? '#F28B82' : '#EFE3E4') +
    ';background:' +
    (active ? '#F28B82' : '#fff') +
    ';color:' +
    (active ? '#fff' : '#a89c97') +
    ';transition:all .12s';

const triStyle = (active) =>
    "font-family:'Poppins',sans-serif;font-weight:600;font-size:13.5px;border-radius:999px;padding:9px 18px;cursor:pointer;border:1px solid " +
    (active ? 'transparent' : '#F0E6E2') +
    ';' +
    (active ? 'background:#FCE7EB;color:#C0566B' : 'background:#fff;color:#9a8d88');
</script>

<template>
    <Head title="Zwangerschapskalender" />
    <MlmLayout>
        <section style="padding: 42px 0 8px">
            <span style="font-family: 'Dancing Script', cursive; font-size: 26px; color: #e0a24a">Hoera, zwanger!</span>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 34px; color: #473537; margin: 2px 0 8px; letter-spacing: -0.4px">Zwangerschapskalender</h1>
            <p style="font-size: 15.5px; line-height: 1.65; color: #7a6c67; max-width: 620px; margin: 0">
                Volg week voor week wat er gebeurt: hoe groot je kindje is, wat er in jouw lichaam verandert en welke tips er bij deze week horen.
            </p>

            <!-- trimester tabs -->
            <div style="display: flex; gap: 8px; margin: 22px 0 18px">
                <button v-for="t in [1, 2, 3]" :key="t" @click="goTrimester(t)" :style="triStyle(current.trimester === t)">{{ t }}e trimester</button>
            </div>

            <!-- slider -->
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 14px">
                <button @click="prevWeek" style="width: 40px; height: 40px; border-radius: 50%; border: 1.5px solid #eedfe0; background: #fff; color: #c0566b; font-size: 20px; cursor: pointer; flex: none; display: flex; align-items: center; justify-content: center">‹</button>
                <div style="flex: 1">
                    <input type="range" min="0" :max="sliderMax" :value="idx" @input="idx = parseInt($event.target.value, 10)" style="width: 100%; accent-color: #f28b82; cursor: pointer" />
                    <div style="display: flex; justify-content: space-between; font-size: 11px; color: #b5a8a3; margin-top: 2px"><span>Week 1</span><span>Week 20</span><span>Week 40</span></div>
                </div>
                <button @click="nextWeek" style="width: 40px; height: 40px; border-radius: 50%; border: 1.5px solid #eedfe0; background: #fff; color: #c0566b; font-size: 20px; cursor: pointer; flex: none; display: flex; align-items: center; justify-content: center">›</button>
            </div>

            <!-- week chips -->
            <div class="mlm-scroll" style="display: flex; gap: 7px; overflow-x: auto; padding: 4px 2px 12px; scroll-behavior: smooth">
                <button v-for="(wk, i) in weeks" :key="wk.position" @click="selectWeek(i)" :style="chipStyle(i === idx)">{{ wk.label }}</button>
            </div>

            <!-- detail -->
            <div style="display: grid; grid-template-columns: 340px minmax(0, 1fr); gap: 36px; margin-top: 10px; align-items: start">
                <div style="background: linear-gradient(160deg, #fce7eb, #f3f0fb); border-radius: 28px; padding: 30px; text-align: center; box-shadow: 0 16px 36px rgba(214, 150, 160, 0.18); position: sticky; top: 90px">
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13px; letter-spacing: 2px; text-transform: uppercase; color: #c99ba2">{{ current.triLabel }}</div>
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 60px; line-height: 1; color: #473537; margin: 6px 0 2px">{{ current.label }}</div>
                    <div style="font-size: 13px; color: #9a8d88; margin-bottom: 14px">week</div>
                    <div :key="current.position" style="font-size: 80px; line-height: 1; animation: pop 0.4s ease">{{ current.emoji }}</div>
                    <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 20px; color: #4a3b3d; margin-top: 10px">Zo groot als een<br />{{ current.fruit }}</div>
                    <div style="display: inline-block; margin-top: 12px; background: #fff; border-radius: 999px; padding: 7px 16px; font-weight: 600; font-size: 14px; color: #e0a24a; box-shadow: 0 4px 12px rgba(224, 162, 74, 0.18)">{{ current.size }}</div>
                </div>

                <div style="display: grid; gap: 14px">
                    <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; padding: 20px 22px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)">
                        <div style="display: flex; align-items: center; gap: 9px; margin-bottom: 8px">
                            <span style="width: 30px; height: 30px; border-radius: 9px; background: #fce7eb; display: flex; align-items: center; justify-content: center"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 20s-7-4.6-9.3-9.1C1 7.6 2.7 4.5 6 4.5c2 0 3.2 1.1 4 2.3.8-1.2 2-2.3 4-2.3 3.3 0 5 3.1 3.3 6.4C19 15.4 12 20 12 20Z" fill="#F28B82" /></svg></span>
                            <span style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537">Je kindje</span>
                        </div>
                        <p style="margin: 0; font-size: 14.5px; line-height: 1.65; color: #6c5f5b">{{ current.baby }}</p>
                    </div>
                    <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; padding: 20px 22px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)">
                        <div style="display: flex; align-items: center; gap: 9px; margin-bottom: 8px">
                            <span style="width: 30px; height: 30px; border-radius: 9px; background: #e4f3e9; display: flex; align-items: center; justify-content: center"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="7" r="4" fill="#5FB07F" /><path d="M4 21c0-4 3.6-6 8-6s8 2 8 6" fill="#9AD3AC" /></svg></span>
                            <span style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537">Jij als (aanstaande) moeder</span>
                        </div>
                        <p style="margin: 0; font-size: 14.5px; line-height: 1.65; color: #6c5f5b">{{ current.mom }}</p>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px">
                        <div v-if="current.milestone" style="background: linear-gradient(150deg, #fbefd8, #fce7eb); border-radius: 20px; padding: 18px 20px">
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13px; color: #c58a2e; margin-bottom: 5px">✨ Mijlpaal</div>
                            <p style="margin: 0; font-size: 14px; line-height: 1.55; color: #6c5f5b">{{ current.milestone }}</p>
                        </div>
                        <div v-if="current.echo" style="background: linear-gradient(150deg, #e1eefb, #eaf5ee); border-radius: 20px; padding: 18px 20px">
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13px; color: #5a87bd; margin-bottom: 5px">🩺 Echo &amp; screening</div>
                            <p style="margin: 0; font-size: 14px; line-height: 1.55; color: #6c5f5b">{{ current.echo }}</p>
                        </div>
                    </div>
                    <div style="background: linear-gradient(150deg, #fff6f8, #f4faf6); border: 1.5px dashed #f0cdd4; border-radius: 20px; padding: 20px 22px">
                        <div style="display: flex; align-items: center; gap: 9px; margin-bottom: 7px">
                            <span style="font-size: 18px">💡</span>
                            <span style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537">Tip voor deze week</span>
                        </div>
                        <p style="margin: 0; font-size: 14.5px; line-height: 1.65; color: #6c5f5b">{{ current.tip }}</p>
                    </div>

                    <div v-if="current.courseTip" style="background: #fff; border: 1px solid #f2e7e2; border-radius: 20px; padding: 20px 22px; box-shadow: 0 8px 20px rgba(180, 150, 150, 0.06)">
                        <div style="display: flex; align-items: center; gap: 9px; margin-bottom: 8px">
                            <span style="width: 30px; height: 30px; border-radius: 9px; background: #eee6f6; display: flex; align-items: center; justify-content: center; font-size: 15px">🧘</span>
                            <span style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537">Zwangerschapscursus</span>
                        </div>
                        <p style="margin: 0; font-size: 14.5px; line-height: 1.65; color: #6c5f5b">{{ current.courseTip }}</p>
                    </div>

                    <div v-if="current.nursery" style="background: linear-gradient(150deg, #eaf5ee, #e4f3e9); border-radius: 20px; padding: 18px 22px">
                        <div style="display: flex; align-items: center; gap: 9px; margin-bottom: 6px">
                            <span style="font-size: 17px">🧸</span>
                            <span style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #473537">Babykamer</span>
                        </div>
                        <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #6c5f5b">{{ current.nursery }}</p>
                    </div>

                    <div v-if="current.warning" style="background: #fdf0ef; border: 1px solid #f6cfca; border-radius: 20px; padding: 16px 20px">
                        <div style="display: flex; align-items: center; gap: 9px">
                            <span style="font-size: 16px">⚠️</span>
                            <span style="font-size: 13.5px; line-height: 1.5; color: #b4574e"><b style="font-family: 'Poppins', sans-serif">Let op:</b> {{ current.warning }} - neem bij twijfel contact op met je verloskundige.</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
