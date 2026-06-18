<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="mlm-guest-grid"
        style="
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            font-family: 'Quicksand', sans-serif;
            background: radial-gradient(1100px 560px at 88% -12%, #fceef0 0%, rgba(252, 238, 240, 0) 58%),
                radial-gradient(1000px 560px at -8% 8%, #def0e5 0%, rgba(222, 240, 229, 0) 55%),
                radial-gradient(700px 500px at 50% 122%, #e7f4ec 0%, rgba(231, 244, 236, 0) 60%), #faf8f5;
        "
    >
        <!-- Brand panel -->
        <div class="mlm-guest-brand" style="position: relative; overflow: hidden; padding: 56px 56px; display: flex; flex-direction: column; justify-content: space-between; background: #fce7eb">
            <!-- Background photo -->
            <span style="position: absolute; inset: 0; background-image: url('/images/auth-bg.jpg'); background-size: cover; background-position: center; z-index: 0"></span>
            <!-- Opacity overlay on top of the photo -->
            <span style="position: absolute; inset: 0; background: linear-gradient(150deg, rgba(252, 231, 235, 0.82) 0%, rgba(243, 240, 251, 0.8) 45%, rgba(234, 245, 238, 0.82) 100%); z-index: 1"></span>
            <span style="position: absolute; top: -40px; right: -30px; width: 220px; height: 220px; border-radius: 50%; background: #fbd3dc; opacity: 0.5; filter: blur(4px); z-index: 2"></span>
            <span style="position: absolute; bottom: 40px; right: 80px; width: 120px; height: 120px; border-radius: 50%; background: #cde8d6; opacity: 0.4; z-index: 2"></span>

            <Link href="/" style="display: inline-flex; align-items: center; text-decoration: none; position: relative; z-index: 3">
                <img src="/images/logo.png" alt="MommyLovesMe.nl" style="height: 74px; width: auto; display: block" />
            </Link>

            <div style="position: relative; max-width: 420px; z-index: 3">
                <span style="font-family: 'Dancing Script', cursive; font-size: 30px; color: #e0a24a; display: block; margin-bottom: 6px">Fijn dat je er bent,</span>
                <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 34px; line-height: 1.12; color: #473537; margin: 0 0 16px; letter-spacing: -0.5px">een warme plek<br />voor elke ouder. 💛</h2>
                <p style="font-size: 16px; line-height: 1.7; color: #6c5f5b; margin: 0">Deel je verhaal, vind herkenning en steun, en volg samen de bijzondere reis van zwangerschap tot het eerste jaar — zonder oordeel, van ouder tot ouder.</p>
            </div>

            <div style="position: relative; display: flex; gap: 26px; z-index: 3">
                <div><div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 22px; color: #f28b82">40</div><div style="font-size: 12.5px; color: #9a8d88">weken kalender</div></div>
                <div><div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 22px; color: #7fc0a0">6</div><div style="font-size: 12.5px; color: #9a8d88">community groepen</div></div>
                <div><div style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 22px; color: #e0a24a">100+</div><div style="font-size: 12.5px; color: #9a8d88">tips &amp; ideeën</div></div>
            </div>
        </div>

        <!-- Form panel -->
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 24px">
            <div style="width: 100%; max-width: 420px">
                <Link href="/" class="mlm-guest-mobilelogo" style="display: none; align-items: center; justify-content: center; text-decoration: none; margin-bottom: 22px">
                    <img src="/images/logo.png" alt="MommyLovesMe.nl" style="height: 64px; width: auto; display: block" />
                </Link>

                <div style="background: #fff; border: 1px solid #f1e7e2; border-radius: 26px; padding: 34px 32px; box-shadow: 0 24px 50px rgba(214, 150, 160, 0.18)">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@media (max-width: 860px) {
    .mlm-guest-grid {
        grid-template-columns: 1fr !important;
    }
    .mlm-guest-brand {
        display: none !important;
    }
    .mlm-guest-mobilelogo {
        display: flex !important;
    }
}
</style>
