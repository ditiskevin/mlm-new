<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    member: { type: Object, required: true },
    parentTypes: { type: Object, default: () => ({}) },
    genders: { type: Object, default: () => ({}) },
    parentingRoles: { type: Object, default: () => ({}) },
});

const form = useForm({
    name: props.member.name,
    email: props.member.email,
    role_label: props.member.role_label ?? '',
    bio: props.member.bio ?? '',
    parent_type: props.member.parent_type ?? 'ouder',
    gender: props.member.gender ?? null,
    parenting_role: props.member.parenting_role ?? null,
    is_admin: props.member.is_admin,
});

const label = "display:block;font-family:'Poppins',sans-serif;font-weight:600;font-size:13.5px;color:#473537;margin-bottom:6px";
const field = "width:100%;box-sizing:border-box;font-family:'Quicksand',sans-serif;font-size:14px;color:#5d514d;background:#fff;border:1px solid #f0e6e2;border-radius:14px;padding:11px 14px;outline:none";
const err = 'color:#b4574e;font-size:12.5px;margin-top:4px';
const chip = (active) =>
    "font-family:'Quicksand',sans-serif;font-weight:600;font-size:13px;border-radius:999px;padding:8px 14px;cursor:pointer;border:1.5px solid " +
    (active ? '#F28B82' : '#EFE3E4') + ';background:' + (active ? '#FCE7EB' : '#fff') + ';color:' + (active ? '#C0566B' : '#9a8d88');
const toggleOptional = (f, k) => (form[f] = form[f] === k ? null : k);

const submit = () => form.patch(route('admin.users.update', props.member.id));
</script>

<template>
    <Head :title="'Lid bewerken · ' + member.name" />
    <AdminLayout>
        <Link :href="route('admin.users.index')" style="font-size: 13px; color: #9a8d88; text-decoration: none">← Terug naar leden</Link>
        <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 26px; color: #473537; margin: 8px 0 18px; letter-spacing: -0.4px">{{ member.name }} bewerken</h1>

        <form @submit.prevent="submit" style="display: grid; gap: 18px; max-width: 640px; background: #fff; border: 1px solid #f2e7e2; border-radius: 22px; padding: 26px; box-shadow: 0 10px 26px rgba(180, 150, 150, 0.08)">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px">
                <div>
                    <label :style="label">Naam</label>
                    <input v-model="form.name" type="text" :style="field" />
                    <div v-if="form.errors.name" :style="err">{{ form.errors.name }}</div>
                </div>
                <div>
                    <label :style="label">E-mail</label>
                    <input v-model="form.email" type="email" :style="field" />
                    <div v-if="form.errors.email" :style="err">{{ form.errors.email }}</div>
                </div>
            </div>

            <div>
                <label :style="label">Rol / ondertitel</label>
                <input v-model="form.role_label" type="text" :style="field" placeholder="Bijv. Mama van 2 💛" />
                <div v-if="form.errors.role_label" :style="err">{{ form.errors.role_label }}</div>
            </div>

            <div>
                <label :style="label">Bio</label>
                <textarea v-model="form.bio" rows="3" maxlength="500" :style="field"></textarea>
                <div v-if="form.errors.bio" :style="err">{{ form.errors.bio }}</div>
            </div>

            <div>
                <label :style="label">Ik ben…</label>
                <div style="display: flex; flex-wrap: wrap; gap: 8px">
                    <button v-for="(lbl, key) in parentTypes" :key="key" type="button" @click="form.parent_type = key" :style="chip(form.parent_type === key)">{{ lbl }}</button>
                </div>
                <div v-if="form.errors.parent_type" :style="err">{{ form.errors.parent_type }}</div>
            </div>

            <div>
                <label :style="label">Rol als ouder</label>
                <div style="display: flex; flex-wrap: wrap; gap: 8px">
                    <button v-for="(lbl, key) in parentingRoles" :key="key" type="button" @click="toggleOptional('parenting_role', key)" :style="chip(form.parenting_role === key)">{{ lbl }}</button>
                </div>
            </div>

            <div>
                <label :style="label">Geslacht</label>
                <div style="display: flex; flex-wrap: wrap; gap: 8px">
                    <button v-for="(lbl, key) in genders" :key="key" type="button" @click="toggleOptional('gender', key)" :style="chip(form.gender === key)">{{ lbl }}</button>
                </div>
            </div>

            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-family: 'Quicksand', sans-serif; font-size: 14.5px; color: #5d514d">
                <input v-model="form.is_admin" type="checkbox" :disabled="member.is_self" style="width: 18px; height: 18px; accent-color: #f28b82" />
                Beheerder
                <span v-if="member.is_self" style="font-size: 12px; color: #b5a8a3">(je kunt je eigen rechten niet intrekken)</span>
            </label>

            <div style="display: flex; gap: 12px; align-items: center">
                <button type="submit" :disabled="form.processing" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; color: #fff; background: linear-gradient(135deg, #f7a8b5, #f28b82); border: none; border-radius: 999px; padding: 13px 26px; cursor: pointer; box-shadow: 0 10px 22px rgba(242, 139, 130, 0.32)">Opslaan</button>
                <Link :href="route('admin.users.index')" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 14px; color: #9a8d88; text-decoration: none">Annuleren</Link>
            </div>
        </form>
    </AdminLayout>
</template>
