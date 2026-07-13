<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import ReportButton from '@/Components/ReportButton.vue';
import FollowButton from '@/Components/FollowButton.vue';
import BlockButton from '@/Components/BlockButton.vue';
import BadgeList from '@/Components/BadgeList.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    member: { type: Object, required: true },
    posts: { type: Array, default: () => [] },
    isSelf: { type: Boolean, default: false },
    canMessage: { type: Boolean, default: false },
    isFollowing: { type: Boolean, default: false },
    isBlocked: { type: Boolean, default: false },
    badges: { type: Array, default: () => [] },
});

const badge = (text, color, bg) => ({ text, color, bg });
</script>

<template>
    <Head :title="member.name" />
    <MlmLayout>
        <section style="max-width: 760px; margin: 0 auto; padding: 32px 0 8px">
            <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 26px; overflow: hidden; box-shadow: 0 12px 30px rgba(180, 150, 150, 0.1)">
                <div style="height: 96px; background: linear-gradient(120deg, #f9c8d0, #b7e1c0)"></div>
                <div style="padding: 0 30px 30px; margin-top: -44px">
                    <img v-if="member.avatar_url" :src="member.avatar_url" alt="" style="width: 88px; height: 88px; border-radius: 50%; border: 5px solid #fff; object-fit: cover" />
                    <span v-else :style="{ width: '88px', height: '88px', borderRadius: '50%', background: member.avatar_color, border: '5px solid #fff', display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Poppins, sans-serif', fontWeight: 700, color: '#fff', fontSize: '34px' }">{{ member.initial }}</span>

                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 12px; flex-wrap: wrap">
                        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 27px; color: #473537; margin: 0; letter-spacing: -0.4px">{{ member.name }}</h1>
                        <span v-if="member.is_admin" style="font-size: 11.5px; font-weight: 600; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 4px 11px">🛡️ Beheerder</span>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 7px; margin-top: 10px">
                        <span v-if="member.parent_type" style="font-size: 12px; font-weight: 600; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 4px 11px">{{ member.parent_type }}</span>
                        <span v-if="member.parenting_role" style="font-size: 12px; font-weight: 600; color: #5e9e78; background: #e4f3e9; border-radius: 999px; padding: 4px 11px">{{ member.parenting_role }}</span>
                        <span v-if="member.gender" style="font-size: 12px; font-weight: 600; color: #7a6c67; background: #f3ece9; border-radius: 999px; padding: 4px 11px">{{ member.gender }}</span>
                    </div>

                    <div v-if="member.username" style="font-size: 13.5px; color: #c0566b; font-weight: 600; margin-top: 4px">@{{ member.username }}</div>

                    <BadgeList :badges="badges" style="margin-top: 12px" />

                    <div v-if="member.role" style="font-size: 13.5px; color: #9a8d88; margin-top: 8px">{{ member.role }}</div>
                    <p v-if="member.bio" style="font-size: 15px; line-height: 1.7; color: #5d514d; margin: 14px 0 0; white-space: pre-line">{{ member.bio }}</p>
                    <div v-if="member.member_since" style="font-size: 12.5px; color: #b5a8a3; margin-top: 12px">Lid sinds {{ member.member_since }}</div>

                    <!-- Stats -->
                    <div style="display: flex; gap: 28px; margin: 18px 0 4px; border-top: 1px solid #f4ece8; padding-top: 16px">
                        <div v-for="s in member.stats" :key="s.label">
                            <span style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 18px; color: #473537">{{ s.value }}</span>
                            <span style="font-size: 13px; color: #9a8d88; margin-left: 6px">{{ s.label }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div style="display: flex; gap: 12px; align-items: center; margin-top: 18px; flex-wrap: wrap">
                        <Link
                            v-if="isSelf"
                            :href="route('profile.edit')"
                            style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #c0566b; background: #fbe9ed; border-radius: 999px; padding: 11px 22px; text-decoration: none"
                            >Profiel bewerken</Link
                        >
                        <Link
                            v-else-if="canMessage"
                            :href="route('messages.start', member.id)"
                            method="post"
                            as="button"
                            style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 11px 24px; cursor: pointer; box-shadow: 0 8px 18px rgba(242, 139, 130, 0.3)"
                            >💬 Stuur bericht</Link
                        >
                        <Link
                            v-else
                            :href="route('login')"
                            style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 11px 22px; text-decoration: none"
                            >Log in om een bericht te sturen</Link
                        >
                        <FollowButton :user-id="member.id" :following="isFollowing" />
                        <ReportButton v-if="!isSelf" type="user" :id="member.id" label="Profiel melden" />
                        <BlockButton v-if="!isSelf" :user-id="member.id" :blocked="isBlocked" />
                    </div>
                </div>
            </div>

            <!-- Recent posts -->
            <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 19px; color: #473537; margin: 28px 0 14px">Recente berichten</h2>
            <div v-if="posts.length" style="display: grid; gap: 12px">
                <article v-for="p in posts" :key="p.id" style="background: #fff; border: 1px solid #f2e7e2; border-radius: 18px; padding: 16px 20px; box-shadow: 0 6px 16px rgba(180, 150, 150, 0.05)">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px">
                        <span v-if="p.tag" style="font-size: 11.5px; font-weight: 600; color: #c58a2e; background: #fbefd8; border-radius: 999px; padding: 3px 10px">{{ p.tag }}</span>
                        <span style="font-size: 12.5px; color: #9a8d88">{{ p.group || 'Community' }} · {{ p.when }}</span>
                    </div>
                    <p style="margin: 0; font-size: 14.5px; line-height: 1.6; color: #5d514d">{{ p.body }}</p>
                    <div style="font-size: 12.5px; color: #b5a8a3; margin-top: 8px">♥ {{ p.like_count }}</div>
                </article>
            </div>
            <div v-else style="background: #fff; border: 1px dashed #f0d6dc; border-radius: 16px; padding: 30px; text-align: center; color: #8a7d78">
                {{ isSelf ? 'Jij hebt nog niets geplaatst.' : member.name + ' heeft nog niets geplaatst.' }}
            </div>
        </section>
    </MlmLayout>
</template>
