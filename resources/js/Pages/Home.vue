<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const tiles = [
    {
        title: 'Zwangerschapskalender',
        desc: 'Volg week voor week wat er gebeurt - van speldenknop tot pompoen.',
        bg: '#FCE7EB',
        href: route('calendar'),
        path: 'M12 17.3 6.2 21l1.6-6.6L3 9.8l6.7-.5L12 3l2.3 6.3 6.7.5-4.8 4.6L17.8 21z',
        color: '#F28B82',
    },
    {
        title: 'Community',
        desc: 'Profielen, groepen en een tijdlijn. Deel je verhaal van ouder tot ouder.',
        bg: '#E4F3E9',
        href: route('community'),
        path: 'M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3Zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5Z',
        color: '#5FB07F',
    },
    {
        title: 'Checklists',
        desc: 'Baby uitzet en vluchttas - afvinkbaar en automatisch bewaard.',
        bg: '#FBEFD8',
        href: route('checklists'),
        path: 'M9 16.2 4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2Z',
        color: '#E0A24A',
    },
    {
        title: 'Hoera Zwanger!',
        desc: 'Honderden ideeën om het te vertellen, gender reveals en kaartjes.',
        bg: '#EEE6F6',
        href: route('reveal'),
        path: 'M20 12v9H4v-9M2 7h20v5H2zM12 22V7M12 7C12 7 11 3 8.5 3 7 3 6 4 6 5.5 6 7 8 7 12 7ZM12 7s1-4 3.5-4C17 3 18 4 18 5.5 18 7 16 7 12 7Z',
        color: '#A87FD0',
    },
    {
        title: 'Babynamen',
        desc: 'Inspiratie voor meisjes, jongens en tweelingen - sla je favorieten op.',
        bg: '#E1EEFB',
        href: route('reveal') + '?tab=namen',
        path: 'M12 2 9.2 8.6 2 9.2l5.4 4.7L5.8 21 12 17.3 18.2 21l-1.6-7.1L22 9.2l-7.2-.6L12 2Z',
        color: '#7AA8DC',
    },
    {
        title: 'Voor de aanstaande vader',
        desc: 'Een kalender en tips speciaal voor papa’s rol tijdens de zwangerschap.',
        bg: '#FCE7EB',
        href: route('father'),
        path: 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4Zm0 2c-3.31 0-6 2.24-6 5v1h12v-1c0-2.76-2.69-5-6-5Z',
        color: '#F28B82',
    },
];
</script>

<template>
    <Head title="Home" />
    <MlmLayout>
        <!-- Hero -->
        <section
            style="display: grid; grid-template-columns: 1.05fr 0.95fr; gap: 40px; align-items: center; padding: 56px 0 30px; animation: fadeUp 0.6s ease both"
        >
            <div>
                <span
                    style="display: inline-flex; align-items: center; gap: 7px; background: #e4f3e9; border: 1px solid #cde8d6; border-radius: 999px; padding: 6px 14px; margin-bottom: 14px"
                >
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #7fc0a0"></span>
                    <span style="font-weight: 600; font-size: 12.5px; color: #5e9e78">Van ouder tot ouder · zonder oordeel</span>
                </span>
                <span style="font-family: 'Dancing Script', cursive; font-size: 30px; color: #e0a24a; display: block; margin-bottom: 6px">Uniek in elk lachje,</span>
                <h1
                    style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 46px; line-height: 1.08; margin: 0 0 18px; color: #473537; letter-spacing: -0.5px"
                >
                    bijzonder in<br />elk moment.
                </h1>
                <p style="font-size: 17px; line-height: 1.7; color: #6c5f5b; max-width: 480px; margin: 0 0 26px">
                    Een warme, liefdevolle community voor (aanstaande) ouders. Deel je verhaal, vind herkenning en steun, en volg samen de bijzondere reis van
                    zwangerschap tot het eerste jaar - zonder oordeel, van ouder tot ouder.
                </p>
                <div style="display: flex; gap: 12px; flex-wrap: wrap">
                    <Link
                        :href="route('community')"
                        style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 14px 28px; text-decoration: none; box-shadow: 0 10px 22px rgba(242, 139, 130, 0.32)"
                        >Word lid van de community</Link
                    >
                    <Link
                        :href="route('calendar')"
                        style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #5fa07c; background: #e4f3e9; border: 1.5px solid #b7e1c0; border-radius: 999px; padding: 14px 28px; text-decoration: none"
                        >Zwangerschapskalender</Link
                    >
                </div>
                <div style="display: flex; gap: 26px; margin-top: 34px">
                    <div>
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 24px; color: #f28b82">40</div>
                        <div style="font-size: 13px; color: #9a8d88">weken kalender</div>
                    </div>
                    <div>
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 24px; color: #7fc0a0">5</div>
                        <div style="font-size: 13px; color: #9a8d88">community groepen</div>
                    </div>
                    <div>
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 24px; color: #e0a24a">100+</div>
                        <div style="font-size: 13px; color: #9a8d88">tips &amp; ideeën</div>
                    </div>
                </div>
            </div>
            <div style="position: relative; animation: fadeUp 0.7s ease both">
                <div style="position: absolute; inset: -18px -10px auto auto; width: 120px; height: 120px; background: #dcefe3; border-radius: 50%; filter: blur(2px); opacity: 0.7"></div>
                <div style="position: relative; background: linear-gradient(150deg, #fce7eb, #f3f0fb); border-radius: 34px; padding: 18px; box-shadow: 0 24px 50px rgba(214, 150, 160, 0.22)">
                    <div style="border-radius: 24px; background: #fff; overflow: hidden">
                        <img src="/images/hero-ouder-kindje.png" alt="Ouder en kindje" style="display: block; width: 100%; height: 300px; object-fit: cover" />
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px; padding: 16px 10px 8px">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #b7e1c0, #9ad3ac); flex: none"></div>
                        <div style="flex: 1">
                            <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #4a3b3d">Welkom, lieve ouder 💛</div>
                            <div style="font-size: 12.5px; color: #9a8d88">Je staat er niet alleen voor.</div>
                        </div>
                    </div>
                </div>
                <div style="position: absolute; left: -20px; bottom: 96px; background: #fff; border-radius: 18px; padding: 12px 16px; box-shadow: 0 14px 30px rgba(0, 0, 0, 0.08); display: flex; align-items: center; gap: 10px; animation: floaty 4s ease-in-out infinite">
                    <span style="width: 34px; height: 34px; border-radius: 50%; background: #fbe3c9; display: flex; align-items: center; justify-content: center">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M12 17.3 6.2 21l1.6-6.6L3 9.8l6.7-.5L12 3l2.3 6.3 6.7.5-4.8 4.6L17.8 21z" fill="#E0A24A" /></svg>
                    </span>
                    <div style="font-size: 12px; color: #6c5f5b">Week 14 · jouw kindje<br /><b style="color: #4a3b3d">zo groot als een citroen 🍋</b></div>
                </div>
            </div>
        </section>

        <!-- Tiles -->
        <section style="margin-top: 44px; background: linear-gradient(160deg, #eef8f1, #faf8f5); border: 1px solid #dcefe3; border-radius: 28px; padding: 30px 30px 34px">
            <span style="display: inline-block; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 12px; letter-spacing: 1px; text-transform: uppercase; color: #5fa07c; background: #fff; border: 1px solid #cde8d6; border-radius: 999px; padding: 5px 13px; margin-bottom: 12px">Ontdek</span>
            <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 24px; color: #473537; margin: 0 0 4px">Alles op één plek</h2>
            <p style="color: #8a7d78; margin: 0 0 22px; font-size: 15px">Ontdek wat MommyLovesMe voor jou en je gezin kan betekenen.</p>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px">
                <Link
                    v-for="t in tiles"
                    :key="t.title"
                    :href="t.href"
                    style="text-align: left; background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 22px; text-decoration: none; box-shadow: 0 8px 22px rgba(180, 150, 150, 0.07)"
                >
                    <span :style="{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: '50px', height: '50px', borderRadius: '15px', background: t.bg, marginBottom: '14px' }">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path :d="t.path" :fill="t.color" /></svg>
                    </span>
                    <span style="display: block; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 16.5px; color: #473537; margin-bottom: 5px">{{ t.title }}</span>
                    <span style="display: block; font-size: 13.5px; line-height: 1.55; color: #8a7d78">{{ t.desc }}</span>
                </Link>
            </div>
        </section>

        <!-- About -->
        <section style="margin-top: 46px; background: #fff; border: 1px solid #f2e7e2; border-radius: 28px; padding: 34px; display: grid; grid-template-columns: 230px 1fr; gap: 34px; align-items: center; box-shadow: 0 10px 30px rgba(180, 150, 150, 0.08)">
            <div style="position: relative">
                <div style="aspect-ratio: 1; border-radius: 24px; background: linear-gradient(160deg, #fce7eb, #eaf5ee); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: #c9a7ae">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" fill="#F6C6CF" /><path d="M4 20c0-4 3.6-6 8-6s8 2 8 6" fill="#CFE8D8" /></svg>
                    <span style="font-size: 12px">Portret Stephanie</span>
                </div>
            </div>
            <div>
                <span style="font-family: 'Dancing Script', cursive; font-size: 24px; color: #e0a24a">Over mij</span>
                <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 22px; color: #473537; margin: 2px 0 12px">Hoi, ik ben Stephanie 👋</h3>
                <p style="font-size: 15px; line-height: 1.7; color: #6c5f5b; margin: 0 0 12px">
                    35 jaar en moeder van vier kinderen (12, 9, 6 en 4). Mijn jongste dochter heeft het zeldzame Caprin1-syndroom en werd geboren met een
                    gedeeltelijke schisis. Haar reis inspireerde mij om MommyLovesMe.nl te starten.
                </p>
                <p style="font-size: 15px; line-height: 1.7; color: #6c5f5b; margin: 0">
                    Ik geloof dat ieder kind uniek is en dat iedere ouder steun verdient. Ik hoop dat deze plek je inspireert, geruststelt en helpt op jouw eigen
                    ouderreis.
                </p>
            </div>
        </section>
    </MlmLayout>
</template>
