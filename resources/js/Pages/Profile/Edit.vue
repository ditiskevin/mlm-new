<script setup>
import MlmLayout from '@/Layouts/MlmLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
    parentTypes: { type: Object, default: () => ({}) },
    genders: { type: Object, default: () => ({}) },
    parentingRoles: { type: Object, default: () => ({}) },
});
</script>

<template>
    <Head title="Mijn profiel" />
    <MlmLayout>
        <section style="max-width: 720px; margin: 0 auto; padding: 38px 0 8px">
            <Link :href="route('dashboard')" style="font-size: 13.5px; font-weight: 600; color: #c0566b; text-decoration: none">‹ Naar mijn account</Link>
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 30px; color: #473537; margin: 14px 0 4px; letter-spacing: -0.4px">Mijn profiel</h1>
            <p style="font-size: 15px; color: #7a6c67; margin: 0 0 24px">Pas je gegevens, profielkaart en wachtwoord aan.</p>

            <div style="display: grid; gap: 18px">
                <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 26px; box-shadow: 0 10px 26px rgba(180, 150, 150, 0.08)">
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" :parent-types="parentTypes" :genders="genders" :parenting-roles="parentingRoles" class="max-w-xl" />
                </div>
                <div style="background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 26px; box-shadow: 0 10px 26px rgba(180, 150, 150, 0.08)">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>
                <div style="background: #fff; border: 1px solid #f6cfca; border-radius: 22px; padding: 26px; box-shadow: 0 10px 26px rgba(180, 150, 150, 0.08)">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </section>
    </MlmLayout>
</template>
