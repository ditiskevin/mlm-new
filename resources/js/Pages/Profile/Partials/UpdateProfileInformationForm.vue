<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    parentTypes: {
        type: Object,
        default: () => ({ ouder: 'Ouder' }),
    },
    genders: {
        type: Object,
        default: () => ({}),
    },
    parentingRoles: {
        type: Object,
        default: () => ({}),
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    username: user.username ?? '',
    email: user.email,
    parent_type: user.parent_type ?? 'ouder',
    gender: user.gender ?? null,
    parenting_role: user.parenting_role ?? null,
    role_label: user.role_label ?? '',
    bio: user.bio ?? '',
    avatar_color: user.avatar_color ?? '#F7A8B5',
    avatar: null,
    remove_avatar: false,
});

const toggleOptional = (field, key) => {
    form[field] = form[field] === key ? null : key;
};

const swatches = ['#F7A8B5', '#F28B82', '#E0A24A', '#7FC0A0', '#5FB07F', '#7AA8DC', '#A9CCE8', '#A87FD0', '#D3BCE6', '#C99BA2'];

const avatarPreview = ref(user.avatar_url ?? null);
const fileError = ref(null);

const onAvatarChange = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    fileError.value = null;
    if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
        fileError.value = 'Kies een JPG, PNG of WebP-bestand.';
        return;
    }
    if (file.size > 8 * 1024 * 1024) {
        fileError.value = 'De foto mag maximaal 8 MB zijn.';
        return;
    }
    form.avatar = file;
    form.remove_avatar = false;
    avatarPreview.value = URL.createObjectURL(file);
};

const removeAvatar = () => {
    form.avatar = null;
    form.remove_avatar = true;
    avatarPreview.value = null;
};

const submit = () => {
    // Inertia spoofs PATCH as multipart POST automatically when a File is present.
    form.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.avatar = null;
            form.remove_avatar = false;
        },
    });
};

const typeChipStyle = (active) =>
    "font-family:'Quicksand',sans-serif;font-weight:600;font-size:13px;border-radius:999px;padding:8px 14px;cursor:pointer;border:1.5px solid " +
    (active ? '#F28B82' : '#EFE3E4') + ';background:' + (active ? '#FCE7EB' : '#fff') + ';color:' + (active ? '#C0566B' : '#9a8d88');
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="submit"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="username" value="Gebruikersnaam (@handle)" />
                <div class="mt-1 flex items-center gap-1">
                    <span style="color: #c0566b; font-weight: 600">@</span>
                    <TextInput id="username" type="text" class="block w-full" v-model="form.username" placeholder="bijv. sanne" autocomplete="off" />
                </div>
                <p style="font-size: 12px; color: #a99b96; margin-top: 4px">Andere leden kunnen je taggen met @{{ form.username || 'jouwnaam' }}. Alleen letters, cijfers en _.</p>
                <InputError class="mt-2" :message="form.errors.username" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel value="Ik ben…" />
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px">
                    <button v-for="(lbl, key) in parentTypes" :key="key" type="button" @click="form.parent_type = key" :style="typeChipStyle(form.parent_type === key)">{{ lbl }}</button>
                </div>
                <InputError class="mt-2" :message="form.errors.parent_type" />
            </div>

            <div>
                <InputLabel value="Mijn rol als ouder (optioneel)" />
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px">
                    <button v-for="(lbl, key) in parentingRoles" :key="key" type="button" @click="toggleOptional('parenting_role', key)" :style="typeChipStyle(form.parenting_role === key)">{{ lbl }}</button>
                </div>
                <p style="font-size: 12.5px; color: #a99b96; margin: 7px 0 0">Kies “Vader” of “Aanstaande ouder” om papa-functies te zien op je account.</p>
                <InputError class="mt-2" :message="form.errors.parenting_role" />
            </div>

            <div>
                <InputLabel value="Geslacht (optioneel)" />
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px">
                    <button v-for="(lbl, key) in genders" :key="key" type="button" @click="toggleOptional('gender', key)" :style="typeChipStyle(form.gender === key)">{{ lbl }}</button>
                </div>
                <InputError class="mt-2" :message="form.errors.gender" />
            </div>

            <div>
                <InputLabel for="role_label" value="Rol / ondertitel" />

                <TextInput
                    id="role_label"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.role_label"
                    placeholder="Bijv. Mama van 2 💛"
                />

                <InputError class="mt-2" :message="form.errors.role_label" />
            </div>

            <div>
                <InputLabel for="bio" value="Bio" />

                <textarea
                    id="bio"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    rows="3"
                    maxlength="500"
                    v-model="form.bio"
                    placeholder="Vertel iets over jezelf voor je community-profiel…"
                ></textarea>

                <InputError class="mt-2" :message="form.errors.bio" />
            </div>

            <div>
                <InputLabel value="Avatarkleur" />

                <div class="mt-2 flex items-center gap-4">
                    <img
                        v-if="avatarPreview"
                        :src="avatarPreview"
                        class="h-14 w-14 flex-none rounded-full object-cover"
                        alt="avatar"
                    />
                    <span
                        v-else
                        class="flex h-14 w-14 flex-none items-center justify-center rounded-full text-xl font-bold text-white"
                        :style="{ background: form.avatar_color }"
                    >
                        {{ form.name.charAt(0) }}
                    </span>

                    <label style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 13px; color: #c0566b; background: #fce7eb; border-radius: 999px; padding: 9px 14px; cursor: pointer">
                        Foto uploaden
                        <input type="file" accept="image/jpeg,image/png,image/webp" class="hidden" @change="onAvatarChange" />
                    </label>
                    <button v-if="avatarPreview" type="button" @click="removeAvatar" style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 12.5px; color: #b4574e; background: none; border: none; cursor: pointer">Foto verwijderen</button>

                    <div class="flex flex-wrap items-center gap-2">
                        <button
                            v-for="color in swatches"
                            :key="color"
                            type="button"
                            @click="form.avatar_color = color"
                            class="h-8 w-8 rounded-full border-2 transition"
                            :style="{ background: color, borderColor: form.avatar_color?.toLowerCase() === color.toLowerCase() ? '#473537' : 'transparent' }"
                            :aria-label="color"
                        ></button>
                        <input
                            type="color"
                            v-model="form.avatar_color"
                            class="h-8 w-10 cursor-pointer rounded border border-gray-300 bg-white p-0"
                            aria-label="Eigen kleur kiezen"
                        />
                    </div>
                </div>

                <p style="font-size: 12.5px; color: #a99b96; margin: 10px 0 0">
                    JPG, PNG of WebP, max 8 MB. Je foto wordt automatisch vierkant bijgesneden. Geen foto? Dan tonen we je kleur met je initiaal.
                </p>
                <p v-if="fileError" style="font-size: 12.5px; color: #d9695f; margin: 6px 0 0">{{ fileError }}</p>
                <InputError class="mt-2" :message="form.errors.avatar" />
                <InputError class="mt-2" :message="form.errors.avatar_color" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
