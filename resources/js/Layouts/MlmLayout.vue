<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const unreadMessages = computed(() => page.props.auth?.unreadMessages ?? 0);

// --- Notifications (bell + realtime) ---
const notifOpen = ref(false);
const notifications = ref([]);
const notifUnread = ref(page.props.auth?.unreadNotifications ?? 0);
watch(() => page.props.auth?.unreadNotifications, (v) => (notifUnread.value = v ?? 0));

const loadNotifications = async () => {
    try {
        const { data } = await window.axios.get(route('notifications.feed'));
        notifications.value = data.notifications;
        notifUnread.value = data.unread;
    } catch (e) {
        /* ignore */
    }
};
const toggleNotifs = () => {
    notifOpen.value = !notifOpen.value;
    if (notifOpen.value) loadNotifications();
};
const markAllRead = () => {
    router.patch(route('notifications.read-all'), {}, { preserveScroll: true, preserveState: true, only: [], onSuccess: () => (notifUnread.value = 0) });
    notifications.value = notifications.value.map((n) => ({ ...n, read: true }));
};

let notifChannel = null;
onMounted(() => {
    const uid = page.props.auth?.userId;
    if (uid && window.Echo) {
        notifChannel = window.Echo.private(`notifications.${uid}`).listen('.notification', (e) => {
            notifUnread.value = e.unread;
            notifications.value = [e.notification, ...notifications.value].slice(0, 12);
        });
    }
});
onBeforeUnmount(() => {
    const uid = page.props.auth?.userId;
    if (notifChannel && window.Echo) window.Echo.leave(`notifications.${uid}`);
});

// One-shot flash toast (success / error) shared from the server.
const toast = ref(null);
let toastTimer = null;
watch(
    () => page.props.flash,
    (flash) => {
        const message = flash?.success || flash?.error;
        if (!message) return;
        toast.value = { type: flash.success ? 'success' : 'error', message };
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => (toast.value = null), 5000);
    },
    { immediate: true, deep: true },
);

// Top-level nav: plain links + grouped dropdowns to keep the bar compact.
const audiences = computed(() => page.props.audiences ?? []);

const navItems = computed(() => [
    { label: 'Home', routeName: 'home' },
    {
        label: 'Zwangerschap',
        children: [
            { label: 'Zwangerschapskalender', routeName: 'calendar' },
            { label: 'Checklists', routeName: 'checklists' },
            { label: 'Hoera Zwanger!', routeName: 'reveal' },
            { label: 'Voor de aanstaande vader', routeName: 'father' },
        ],
    },
    {
        label: 'Voor wie',
        match: 'audiences.*',
        children: [
            { label: 'Alle doelgroepen', routeName: 'audiences.index' },
            ...audiences.value.map((a) => ({
                label: `${a.emoji} ${a.name}`,
                routeName: 'audiences.show',
                params: { audience: a.slug },
            })),
        ],
    },
    {
        label: 'Community',
        children: [
            { label: 'Tijdlijn & groepen', routeName: 'community' },
            { label: 'Forum', routeName: 'forum.index', match: 'forum.*' },
            { label: 'Marktplaats', routeName: 'marketplace.index', match: 'marketplace.*' },
            { label: 'Oppas vinden', routeName: 'babysitters.index', match: 'babysitters.*' },
        ],
    },
    { label: 'Inspiratie', routeName: 'blog.index', match: 'blog.*' },
]);

// Footer link columns — curated subset of the nav, grouped for the footer grid.
const footerColumns = [
    {
        label: 'Zwangerschap',
        children: [
            { label: 'Zwangerschapskalender', routeName: 'calendar' },
            { label: 'Checklists', routeName: 'checklists' },
            { label: 'Hoera Zwanger!', routeName: 'reveal' },
            { label: 'Voor de aanstaande vader', routeName: 'father' },
        ],
    },
    {
        label: 'Community',
        children: [
            { label: 'Tijdlijn & groepen', routeName: 'community' },
            { label: 'Forum', routeName: 'forum.index' },
            { label: 'Marktplaats', routeName: 'marketplace.index' },
            { label: 'Oppas vinden', routeName: 'babysitters.index' },
        ],
    },
    {
        label: 'Ontdekken',
        children: [
            { label: 'Alle doelgroepen', routeName: 'audiences.index' },
            { label: 'Inspiratie & blog', routeName: 'blog.index' },
            { label: 'Contact', routeName: 'contact' },
        ],
    },
];

const isActive = (item) => route().current(item.match ?? item.routeName, item.params);
const groupActive = (item) => (item.match && route().current(item.match)) || item.children?.some((c) => isActive(c));

const openMenu = ref(null);

// Mobile hamburger menu — closes automatically on navigation.
const mobileOpen = ref(false);
watch(
    () => page.url,
    () => (mobileOpen.value = false),
);

const navStyle = (active) =>
    "font-family:'Quicksand',sans-serif;font-weight:600;font-size:14px;border:none;border-radius:999px;padding:9px 16px;cursor:pointer;transition:all .15s ease;text-decoration:none;display:inline-flex;align-items:center;gap:5px;" +
    (active ? 'background:#FBE0E6;color:#C0566B' : 'background:transparent;color:#7a6c67');

const mobileLinkStyle = (active, indent = false) =>
    "display:flex;align-items:center;gap:8px;font-family:'Quicksand',sans-serif;font-weight:600;font-size:15px;border:none;border-radius:12px;padding:12px " +
    (indent ? '16px' : '12px') +
    ';width:100%;box-sizing:border-box;cursor:pointer;text-decoration:none;text-align:left;' +
    (active ? 'background:#FBE0E6;color:#C0566B' : 'background:transparent;color:#5d514d');
</script>

<template>
    <div
        style="
            min-height: 100vh;
            background: radial-gradient(1100px 560px at 88% -12%, #fceef0 0%, rgba(252, 238, 240, 0) 58%),
                radial-gradient(1000px 560px at -8% 8%, #def0e5 0%, rgba(222, 240, 229, 0) 55%),
                radial-gradient(700px 500px at 50% 122%, #e7f4ec 0%, rgba(231, 244, 236, 0) 60%), #faf8f5;
            font-family: 'Quicksand', sans-serif;
            color: #444444;
        "
    >
        <nav
            style="
                position: sticky;
                top: 0;
                z-index: 50;
                backdrop-filter: saturate(1.2) blur(8px);
                background: rgba(255, 253, 249, 0.82);
                border-bottom: 1px solid #f1e7e2;
            "
        >
            <div
                style="
                    max-width: 1180px;
                    margin: 0 auto;
                    padding: 10px 24px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 6px;
                "
            >
                <div class="mlm-topbar" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 12px">
                    <Link :href="route('home')" style="display: flex; align-items: center; text-decoration: none">
                        <img src="/images/logo.png" alt="MommyLovesMe.nl" class="mlm-logo" style="height: 150px; width: auto; display: block" />
                    </Link>
                    <button
                        type="button"
                        class="mlm-burger"
                        @click="mobileOpen = !mobileOpen"
                        aria-label="Menu"
                        style="align-items: center; justify-content: center; background: #fce7eb; border: none; border-radius: 12px; width: 44px; height: 44px; cursor: pointer; color: #c0566b; position: relative"
                    >
                        <svg v-if="!mobileOpen" width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" /></svg>
                        <svg v-else width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" /></svg>
                        <span v-if="user && unreadMessages && !mobileOpen" style="position: absolute; top: 4px; right: 4px; width: 9px; height: 9px; border-radius: 50%; background: #f28b82"></span>
                    </button>
                </div>

                <div class="mlm-desktop-nav" style="display: flex; align-items: center; gap: 4px">
                    <template v-for="item in navItems" :key="item.label">
                        <!-- plain link -->
                        <Link v-if="!item.children" :href="route(item.routeName)" :style="navStyle(isActive(item))">{{ item.label }}</Link>

                        <!-- dropdown group -->
                        <div
                            v-else
                            style="position: relative"
                            @mouseenter="openMenu = item.label"
                            @mouseleave="openMenu = null"
                        >
                            <button type="button" @click="openMenu = openMenu === item.label ? null : item.label" :style="navStyle(groupActive(item))">
                                {{ item.label }}
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            </button>
                            <transition
                                enter-active-class="transition ease-out duration-150"
                                enter-from-class="opacity-0 -translate-y-1"
                                leave-active-class="transition ease-in duration-100"
                                leave-to-class="opacity-0 -translate-y-1"
                            >
                                <div
                                    v-show="openMenu === item.label"
                                    style="position: absolute; top: 100%; left: 0; padding-top: 8px; z-index: 60; min-width: 230px"
                                >
                                    <div style="background: #fff; border: 1px solid #f1e7e2; border-radius: 16px; padding: 8px; box-shadow: 0 16px 36px rgba(180, 150, 150, 0.18)">
                                        <Link
                                            v-for="child in item.children"
                                            :key="child.label"
                                            :href="route(child.routeName, child.params)"
                                            @click="openMenu = null"
                                            :style="{
                                                display: 'block',
                                                fontFamily: 'Quicksand, sans-serif',
                                                fontWeight: 600,
                                                fontSize: '14px',
                                                borderRadius: '11px',
                                                padding: '10px 14px',
                                                textDecoration: 'none',
                                                color: isActive(child) ? '#C0566B' : '#6c5f5b',
                                                background: isActive(child) ? '#FBE9ED' : 'transparent',
                                            }"
                                            >{{ child.label }}</Link
                                        >
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </template>

                    <Link
                        v-if="user?.is_admin"
                        :href="route('admin.dashboard')"
                        :style="navStyle(route().current('admin.*'))"
                        >🛡️ Beheer</Link
                    >

                    <template v-if="user">
                        <!-- Notifications bell -->
                        <div style="position: relative" @mouseleave="notifOpen = false">
                            <button type="button" @click="toggleNotifs" :style="navStyle(notifOpen) + ';position:relative'" title="Notificaties" aria-label="Notificaties">
                                🔔<span v-if="notifUnread" style="position: absolute; top: 2px; right: 4px; min-width: 16px; height: 16px; font-size: 10px; font-weight: 700; color: #fff; background: #f28b82; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; padding: 0 4px">{{ notifUnread > 9 ? '9+' : notifUnread }}</span>
                            </button>
                            <transition enter-active-class="transition ease-out duration-150" enter-from-class="opacity-0 -translate-y-1" leave-active-class="transition ease-in duration-100" leave-to-class="opacity-0 -translate-y-1">
                                <div v-show="notifOpen" style="position: absolute; top: 100%; right: 0; padding-top: 8px; z-index: 60; width: 340px; max-width: 92vw">
                                    <div style="background: #fff; border: 1px solid #f1e7e2; border-radius: 16px; box-shadow: 0 16px 36px rgba(180, 150, 150, 0.2); overflow: hidden">
                                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-bottom: 1px solid #f4ece8">
                                            <span style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 14px; color: #473537">Notificaties</span>
                                            <button v-if="notifUnread" @click="markAllRead" style="font-size: 12px; color: #c0566b; font-weight: 600; background: none; border: none; cursor: pointer">Alles gelezen</button>
                                        </div>
                                        <div style="max-height: 380px; overflow-y: auto">
                                            <component
                                                :is="n.url ? 'a' : 'div'"
                                                v-for="n in notifications"
                                                :key="n.id"
                                                :href="n.url || undefined"
                                                :style="{ display: 'flex', gap: '11px', alignItems: 'flex-start', padding: '12px 16px', borderBottom: '1px solid #f7f1ee', textDecoration: 'none', background: n.read ? '#fff' : '#fdf2f4' }"
                                            >
                                                <span style="font-size: 19px; flex: none">{{ n.icon }}</span>
                                                <div style="flex: 1; min-width: 0">
                                                    <div style="font-family: 'Quicksand', sans-serif; font-weight: 600; font-size: 13.5px; color: #473537; line-height: 1.35">{{ n.title }}</div>
                                                    <div v-if="n.body" style="font-size: 12.5px; color: #8a7d78; line-height: 1.4">{{ n.body }}</div>
                                                    <div style="font-size: 11px; color: #b5a8a3; margin-top: 3px">{{ n.when }}</div>
                                                </div>
                                                <span v-if="!n.read" style="flex: none; width: 8px; height: 8px; border-radius: 50%; background: #f28b82; margin-top: 5px"></span>
                                            </component>
                                            <div v-if="!notifications.length" style="padding: 30px 16px; text-align: center; color: #9a8d88; font-size: 13.5px">Nog geen notificaties 🌿</div>
                                        </div>
                                        <Link :href="route('notifications.index')" @click="notifOpen = false" style="display: block; text-align: center; padding: 11px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13px; color: #c0566b; background: #faf4f1; text-decoration: none">Alle notificaties</Link>
                                    </div>
                                </div>
                            </transition>
                        </div>

                        <Link
                            :href="route('messages.index')"
                            :style="navStyle(route().current('messages.*')) + ';position:relative'"
                            title="Berichten"
                            >💬 Berichten<span
                                v-if="unreadMessages"
                                style="margin-left: 2px; min-width: 18px; height: 18px; font-size: 10.5px; font-weight: 700; color: #fff; background: #f28b82; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; padding: 0 5px"
                                >{{ unreadMessages > 9 ? '9+' : unreadMessages }}</span
                            ></Link
                        >
                        <Link
                            :href="route('dashboard')"
                            style="
                                margin-left: 10px;
                                font-family: 'Poppins', sans-serif;
                                font-weight: 600;
                                font-size: 14px;
                                color: #c0566b;
                                background: #fbe9ed;
                                border: none;
                                border-radius: 999px;
                                padding: 10px 18px;
                                text-decoration: none;
                            "
                            >Hoi, {{ user.name.split(' ')[0] }}</Link
                        >
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            style="
                                font-family: 'Quicksand', sans-serif;
                                font-weight: 600;
                                font-size: 13px;
                                color: #9a8d88;
                                background: none;
                                border: none;
                                cursor: pointer;
                                padding: 8px 10px;
                            "
                            >Uitloggen</Link
                        >
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            :style="navStyle(false)"
                            >Inloggen</Link
                        >
                        <Link
                            :href="route('register')"
                            style="
                                margin-left: 6px;
                                font-family: 'Poppins', sans-serif;
                                font-weight: 600;
                                font-size: 14px;
                                color: #fff;
                                background: linear-gradient(135deg, #f7a8b5, #f28b82);
                                border: none;
                                border-radius: 999px;
                                padding: 10px 20px;
                                text-decoration: none;
                                box-shadow: 0 6px 16px rgba(242, 139, 130, 0.32);
                            "
                            >Word lid</Link
                        >
                    </template>
                </div>

                <!-- Mobile menu -->
                <div v-show="mobileOpen" class="mlm-mobile-menu" style="width: 100%; margin-top: 6px; border-top: 1px solid #f1e7e2; padding-top: 8px; display: grid; gap: 2px">
                    <template v-for="item in navItems" :key="'m-' + item.label">
                        <Link v-if="!item.children" :href="route(item.routeName)" :style="mobileLinkStyle(isActive(item))">{{ item.label }}</Link>
                        <template v-else>
                            <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.4px; text-transform: uppercase; color: #b5a8a3; padding: 12px 12px 4px">{{ item.label }}</div>
                            <Link v-for="child in item.children" :key="'m-' + child.label" :href="route(child.routeName, child.params)" :style="mobileLinkStyle(isActive(child), true)">{{ child.label }}</Link>
                        </template>
                    </template>

                    <Link v-if="user?.is_admin" :href="route('admin.dashboard')" :style="mobileLinkStyle(route().current('admin.*'))">🛡️ Beheer</Link>

                    <div style="border-top: 1px solid #f4ece8; margin: 6px 0 4px"></div>
                    <template v-if="user">
                        <Link :href="route('notifications.index')" :style="mobileLinkStyle(route().current('notifications.*'))">
                            🔔 Notificaties
                            <span v-if="notifUnread" style="font-size: 10.5px; font-weight: 700; color: #fff; background: #f28b82; border-radius: 999px; padding: 1px 7px">{{ notifUnread > 9 ? '9+' : notifUnread }}</span>
                        </Link>
                        <Link :href="route('messages.index')" :style="mobileLinkStyle(route().current('messages.*'))">
                            💬 Berichten
                            <span v-if="unreadMessages" style="font-size: 10.5px; font-weight: 700; color: #fff; background: #f28b82; border-radius: 999px; padding: 1px 7px">{{ unreadMessages > 9 ? '9+' : unreadMessages }}</span>
                        </Link>
                        <Link :href="route('dashboard')" :style="mobileLinkStyle(route().current('dashboard'))">👤 Hoi, {{ user.name.split(' ')[0] }}</Link>
                        <Link :href="route('logout')" method="post" as="button" :style="mobileLinkStyle(false)">Uitloggen</Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" :style="mobileLinkStyle(false)">Inloggen</Link>
                        <Link :href="route('register')" :style="mobileLinkStyle(false) + ';color:#fff;background:linear-gradient(135deg,#f7a8b5,#f28b82);justify-content:center'">Word lid 💛</Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Flash toast -->
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-2"
            leave-active-class="transition ease-in duration-150"
            leave-to-class="opacity-0 translate-y-2"
        >
            <div
                v-if="toast"
                @click="toast = null"
                :style="{
                    position: 'fixed',
                    top: '20px',
                    left: '50%',
                    transform: 'translateX(-50%)',
                    zIndex: 200,
                    cursor: 'pointer',
                    maxWidth: '92vw',
                    fontFamily: 'Quicksand, sans-serif',
                    fontWeight: 600,
                    fontSize: '14px',
                    padding: '13px 22px',
                    borderRadius: '999px',
                    boxShadow: '0 12px 30px rgba(180, 150, 150, 0.28)',
                    color: '#fff',
                    background:
                        toast.type === 'success'
                            ? 'linear-gradient(135deg, #7bc89a, #5fa07c)'
                            : 'linear-gradient(135deg, #f28b82, #d9695f)',
                }"
            >
                {{ toast.type === 'success' ? '✓' : '⚠' }} {{ toast.message }}
            </div>
        </transition>

        <main style="max-width: 1180px; margin: 0 auto; padding: 0 24px 90px">
            <slot />
        </main>

        <footer
            style="
                margin-top: 64px;
                border-top: 1px solid #f1e7e2;
                background: linear-gradient(180deg, rgba(255, 253, 249, 0.7) 0%, rgba(252, 238, 240, 0.55) 100%);
            "
        >
            <!-- Dedicatie -->
            <div style="max-width: 1180px; margin: 0 auto; padding: 48px 24px 8px; text-align: center">
                <div
                    style="
                        display: inline-flex;
                        flex-direction: column;
                        align-items: center;
                        gap: 12px;
                        max-width: 620px;
                    "
                >
                    <p
                        style="
                            font-family: 'Dancing Script', cursive;
                            font-size: 23px;
                            line-height: 1.45;
                            color: #c0566b;
                            margin: 0;
                        "
                    >
                        Voor onze lieve dochter.
                    </p>
                    <p style="font-size: 14px; line-height: 1.6; color: #8a7a75; margin: 0">
                        Dankzij jou bestaat deze plek, zodat andere gezinnen weten dat ze niet alleen zijn. ❤️
                    </p>
                </div>
            </div>

            <!-- Link-kolommen -->
            <div
                style="
                    max-width: 1180px;
                    margin: 0 auto;
                    padding: 40px 24px 32px;
                    display: grid;
                    grid-template-columns: 1.4fr repeat(3, 1fr);
                    gap: 36px;
                "
            >
                <div style="display: flex; flex-direction: column; align-items: center; text-align: center">
                    <Link :href="route('home')" style="display: flex; align-items: center; text-decoration: none">
                        <img src="/images/logo.png" alt="MommyLovesMe.nl" class="mlm-logo" style="height: 150px; width: auto; display: block" />
                    </Link>
                    <p style="font-family: 'Dancing Script', cursive; font-size: 19px; color: #c99ba2; margin: 14px 0 0; line-height: 1.5; max-width: 280px">
                        Een plek om te leren, te delen en te voelen dat je er niet alleen voor staat.
                    </p>
                </div>

                <div v-for="col in footerColumns" :key="col.label">
                    <h4
                        style="
                            font-family: 'Poppins', sans-serif;
                            font-weight: 700;
                            font-size: 13px;
                            letter-spacing: 0.4px;
                            text-transform: uppercase;
                            color: #4a3b3d;
                            margin: 0 0 14px;
                        "
                    >
                        {{ col.label }}
                    </h4>
                    <ul style="list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 10px">
                        <li v-for="link in col.children" :key="link.label">
                            <Link
                                :href="route(link.routeName, link.params)"
                                style="font-size: 14px; color: #7a6c67; text-decoration: none; transition: color 0.15s ease"
                                @mouseenter="(e) => (e.target.style.color = '#c0566b')"
                                @mouseleave="(e) => (e.target.style.color = '#7a6c67')"
                                >{{ link.label }}</Link
                            >
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Onderbalk -->
            <div style="border-top: 1px solid #f1e7e2">
                <div
                    style="
                        max-width: 1180px;
                        margin: 0 auto;
                        padding: 18px 24px;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        flex-wrap: wrap;
                        gap: 8px;
                    "
                >
                    <span style="font-size: 13px; color: #a99b96">© {{ new Date().getFullYear() }} MommyLovesMe.nl · gemaakt met 💛</span>
                    <span style="font-family: 'Dancing Script', cursive; font-size: 16px; color: #c99ba2">Elk kind is uniek, elke ouder ook</span>
                </div>
            </div>
        </footer>
    </div>
</template>
