<script setup lang="ts">
import ProductMegaMenu from '@/components/ProductMegaMenu.vue';
import { productMenuItems, secondaryNavLinks } from '@/data/productMenu';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, onUnmounted, ref, watch } from 'vue';
import { dashboard, login, register } from '@/routes';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const page = usePage();
const appName = computed(() => (page.props.name as string) || 'Chat Core');

const mobileNavOpen = ref(false);
const productsExpanded = ref(false);

watch(mobileNavOpen, (open) => {
    if (typeof document === 'undefined') {
        return;
    }
    document.body.style.overflow = open ? 'hidden' : '';
    if (!open) {
        productsExpanded.value = false;
    }
});

onUnmounted(() => {
    document.body.style.overflow = '';
});

function closeMobileNav() {
    mobileNavOpen.value = false;
}

function openMobileNav() {
    mobileNavOpen.value = true;
}

function toggleProductsExpanded() {
    productsExpanded.value = !productsExpanded.value;
}

const heroBg = '/assets/home-hero-bg-desktop.jpg';
const featureVideo = '/assets/features_01v1.webm';
const templatesVideo = '/assets/templates_05.webm';

const seeItActionItems = [
    {
        id: 'hi',
        title: 'Say hi to new followers',
    },
    {
        id: 'welcome',
        title: 'Send welcome messages',
    },
    {
        id: 'faqs',
        title: 'Automate FAQs',
    },
    {
        id: 'auto-dm',
        title:
            'Auto-DM people from comments and capture email or phone number',
    },
    {
        id: 'giveaways',
        title: 'Run giveaways',
    },
] as const;

const howItWorksSteps = [
    {
        title: 'Step 1: Sign up for free',
        body: 'Start your free trial — no credit card required',
    },
    {
        title: 'Step 2: Connect to your channels',
        body: 'Link all your favorite social or messaging apps',
    },
    {
        title: 'Step 3: Go live in minutes',
        body: 'Automate your selling, replying, and so much more!',
    },
] as const;

const audienceCards = [
    {
        key: 'instagram',
        title: 'Instagram',
        description:
            'Effortlessly automate DMs and comment replies',
        href: '#',
    },
    {
        key: 'tiktok',
        title: 'TikTok',
        description: 'Turn viral moments into meaningful conversations',
        href: '#',
    },
    {
        key: 'whatsapp',
        title: 'WhatsApp',
        description:
            'Transform leads into loyal customers by engaging them 1:1',
        href: '#',
    },
] as const;
</script>

<template>
    <Head title="Welcome" />

    <!-- Mobile nav drawer (shared) -->
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="mobileNavOpen"
                class="fixed inset-0 z-[100] lg:hidden"
                role="dialog"
                aria-modal="true"
                aria-label="Menu"
            >
                <button
                    type="button"
                    class="absolute inset-0 bg-black/55 backdrop-blur-sm"
                    @click="closeMobileNav"
                />
                <Transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="translate-x-full"
                    enter-to-class="translate-x-0"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="translate-x-0"
                    leave-to-class="translate-x-full"
                >
                    <div
                        v-if="mobileNavOpen"
                        class="absolute inset-y-0 right-0 flex w-[min(100%,20rem)] flex-col bg-neutral-950 pb-[max(1rem,env(safe-area-inset-bottom))] pt-[max(1rem,env(safe-area-inset-top))] shadow-2xl"
                    >
                        <div
                            class="flex items-center justify-between border-b border-white/10 px-5 py-4"
                        >
                            <span class="text-lg font-bold text-white">{{
                                appName
                            }}</span>
                            <button
                                type="button"
                                class="flex h-11 w-11 touch-manipulation items-center justify-center rounded-full text-white/90 hover:bg-white/10"
                                aria-label="Close menu"
                                @click="closeMobileNav"
                            >
                                <svg
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                        <nav
                            class="flex flex-1 flex-col gap-1 overflow-y-auto px-3 py-4"
                        >
                            <div>
                                <button
                                    type="button"
                                    class="flex w-full items-center justify-between rounded-xl px-4 py-3.5 text-left text-[13px] font-semibold uppercase tracking-[0.12em] text-white/90 active:bg-white/10"
                                    @click="toggleProductsExpanded"
                                >
                                    Products
                                    <svg
                                        class="h-4 w-4 shrink-0 transition-transform"
                                        :class="productsExpanded ? 'rotate-180' : ''"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M19 9l-7 7-7-7"
                                        />
                                    </svg>
                                </button>
                                <div
                                    v-show="productsExpanded"
                                    class="border-l border-white/15 py-1 pl-3 ml-3 mt-1 space-y-0.5"
                                >
                                    <a
                                        v-for="p in productMenuItems"
                                        :key="`drawer-p-${p.title}`"
                                        :href="p.href"
                                        class="block rounded-lg px-3 py-2.5 text-[12px] font-medium leading-snug text-white/80 active:bg-white/10"
                                        @click="closeMobileNav"
                                    >
                                        <span class="font-semibold text-white">{{
                                            p.title
                                        }}</span>
                                        <span
                                            class="mt-0.5 block text-[11px] font-normal text-white/55"
                                            >{{ p.description }}</span
                                        >
                                    </a>
                                </div>
                            </div>
                            <a
                                v-for="item in secondaryNavLinks"
                                :key="`drawer-${item.label}`"
                                :href="item.href"
                                class="rounded-xl px-4 py-3.5 text-[13px] font-semibold uppercase tracking-[0.12em] text-white/90 active:bg-white/10"
                                @click="closeMobileNav"
                            >
                                {{ item.label }}
                            </a>
                        </nav>
                        <div class="border-t border-white/10 px-4 py-4">
                            <template v-if="$page.props.auth.user">
                                <Link
                                    :href="dashboard()"
                                    class="flex min-h-[48px] w-full touch-manipulation items-center justify-center rounded-full border border-white/35 text-sm font-semibold uppercase tracking-wide text-white active:bg-white/10"
                                    @click="closeMobileNav"
                >
                    Dashboard
                </Link>
                            </template>
                <template v-else>
                                <Link
                                    v-if="canRegister"
                                    :href="register()"
                                    class="flex min-h-[48px] w-full touch-manipulation items-center justify-center rounded-full bg-[#FF00E5] text-sm font-bold uppercase tracking-wide text-white active:opacity-90"
                                    @click="closeMobileNav"
                                >
                                    Get started
                                </Link>
                                <Link
                                    :href="login()"
                                    class="mt-3 flex min-h-[48px] w-full touch-manipulation items-center justify-center rounded-xl text-sm font-semibold uppercase tracking-wide text-white/90 active:bg-white/10"
                                    @click="closeMobileNav"
                                >
                                    Sign in
                                </Link>
                            </template>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>

    <div>
        <!-- ========== Hero (dark) ========== -->
        <div
            class="relative flex min-h-screen min-h-[100dvh] flex-col text-white"
        >
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div
                    class="absolute inset-0 scale-105 bg-cover bg-[center_top] bg-no-repeat sm:bg-center"
                    :style="{ backgroundImage: `url('${heroBg}')` }"
                />
                <div
                    class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/55 to-black/70 sm:bg-gradient-to-r sm:from-black/85 sm:via-black/50 sm:to-black/25"
                />
                <div
                    class="absolute inset-0 bg-[radial-gradient(ellipse_at_70%_35%,transparent_0%,rgba(0,0,0,0.55)_100%)]"
                />
                <div
                    class="absolute inset-0 shadow-[inset_0_0_100px_36px_rgba(0,0,0,0.4)] sm:shadow-[inset_0_0_120px_40px_rgba(0,0,0,0.45)]"
                />
            </div>

            <div
                class="relative z-10 flex min-h-0 flex-1 flex-col"
            >
                <header
                    class="relative z-50 flex items-center justify-between gap-3 px-4 py-4 sm:px-6 sm:py-5 lg:px-12 lg:py-6"
                >
                    <Link
                        href="/"
                        class="min-h-11 min-w-0 max-w-[55%] truncate text-lg font-extrabold tracking-tight sm:text-xl"
                    >
                        {{ appName }}
                    </Link>

                    <nav
                        class="hidden items-center gap-7 text-[11px] font-semibold uppercase tracking-[0.12em] text-white/95 xl:flex"
                    >
                        <ProductMegaMenu variant="dark" />
                        <a
                            v-for="item in secondaryNavLinks"
                            :key="item.label"
                            :href="item.href"
                            class="transition hover:text-white"
                        >
                            {{ item.label }}
                        </a>
                    </nav>

                    <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                        <template v-if="$page.props.auth.user">
                            <Link
                                :href="dashboard()"
                                class="hidden min-h-[44px] items-center rounded-full border border-white/35 px-5 py-2.5 text-[11px] font-semibold uppercase tracking-wide text-white transition hover:bg-white/10 sm:inline-flex"
                            >
                                Dashboard
                            </Link>
                        </template>
                        <template v-else>
                    <Link
                        v-if="canRegister"
                        :href="register()"
                                class="hidden min-h-[44px] items-center rounded-full border border-white/40 px-5 py-2.5 text-[11px] font-semibold uppercase tracking-wide text-white transition hover:bg-white/10 md:inline-flex"
                            >
                                Get started
                            </Link>
                            <Link
                                :href="login()"
                                class="hidden min-h-[44px] items-center text-[11px] font-semibold uppercase tracking-wide text-white/90 transition hover:text-white sm:inline-flex"
                            >
                                Sign in
                    </Link>
                </template>

                        <button
                            type="button"
                            class="flex h-11 w-11 touch-manipulation items-center justify-center rounded-full text-white/95 hover:bg-white/10 xl:hidden"
                            aria-label="Open menu"
                            :aria-expanded="mobileNavOpen"
                            @click="openMobileNav"
                        >
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                            </svg>
                        </button>
                    </div>
                </header>

                <div
                    class="flex flex-1 flex-col justify-center items-start px-4 pb-[max(2.5rem,env(safe-area-inset-bottom))] pt-2 sm:px-6 sm:pb-16 sm:pt-4 lg:px-12 lg:pb-20 lg:pt-0 xl:px-16"
                >
                    <div
                        class="relative z-10 w-full max-w-xl text-left"
                    >
                        <h1
                            class="text-[clamp(1.75rem,5.5vw+0.5rem,3.5rem)] font-extrabold leading-[1.06] tracking-tight text-white"
                        >
                            Make the most out of every conversation
                        </h1>
                        <p
                            class="mt-4 max-w-xl text-[15px] leading-relaxed text-white/85 sm:mt-5 sm:text-lg"
                        >
                            Sell more, engage better, and grow your audience with
                            powerful automations for Instagram, WhatsApp, TikTok,
                            and Messenger.
                        </p>
                        <div class="mt-7 flex flex-wrap items-center gap-3 sm:mt-8 sm:gap-4">
                            <Link
                                v-if="canRegister"
                                :href="register()"
                                class="inline-flex min-h-[48px] min-w-[140px] touch-manipulation items-center justify-center rounded-full bg-[#FF00E5] px-7 text-xs font-bold uppercase tracking-[0.14em] text-white shadow-[0_12px_40px_-8px_rgba(255,0,229,0.55)] transition hover:bg-[#e010cf] active:scale-[0.98] sm:px-8 sm:py-3.5"
                            >
                                Get started
                            </Link>
                            <Link
                                v-else
                                :href="login()"
                                class="inline-flex min-h-[48px] min-w-[140px] touch-manipulation items-center justify-center rounded-full bg-[#FF00E5] px-7 text-xs font-bold uppercase tracking-[0.14em] text-white shadow-[0_12px_40px_-8px_rgba(255,0,229,0.55)] transition hover:bg-[#e010cf] sm:px-8 sm:py-3.5"
                            >
                                Get started
                            </Link>
                        </div>

                        <div
                            class="mt-10 flex flex-col gap-3 border-t border-white/10 pt-8 text-left text-[10px] font-medium uppercase tracking-[0.08em] text-white/55 sm:mt-14 sm:flex-row sm:flex-wrap sm:items-center sm:justify-start sm:gap-x-6 sm:gap-y-2 sm:text-[11px]"
                        >
                            <span class="text-white/70"
                                >Meta Business Partner</span
                            >
                            <span class="text-white/70"
                                >TikTok Marketing Partner</span
                        >
                            <span
                                class="flex items-center gap-2 normal-case tracking-normal text-white/80"
                            >
                                <span
                                    class="rounded bg-white/15 px-1.5 py-0.5 text-[10px] font-bold text-white"
                                    >G2</span
                                >
                                <span class="text-xs text-white/60"
                                    >4.6/5 stars</span
                                >
                            </span>
                        </div>
                    </div>
                </div>

                <div
                    class="pointer-events-none absolute bottom-[max(1rem,env(safe-area-inset-bottom))] left-4 z-10 hidden sm:left-6 lg:left-12 xl:left-16 lg:block"
                >
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full border border-white/25 text-white/70"
                    >
                        <svg
                            class="h-4 w-4 animate-bounce"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========== Features (yellow) ========== -->
        <section
            class="relative bg-[#FEFD42] pb-[max(1rem,env(safe-area-inset-bottom))] text-black"
        >
            <!-- Grid: full width subtle on mobile, right half on desktop -->
            <div
                class="pointer-events-none absolute inset-0 opacity-[0.35] sm:opacity-50 lg:hidden"
                style="
                    background-image:
                        linear-gradient(
                            to right,
                            rgba(0, 0, 0, 0.07) 1px,
                            transparent 1px
                        ),
                        linear-gradient(
                            to bottom,
                            rgba(0, 0, 0, 0.07) 1px,
                            transparent 1px
                        );
                    background-size: 20px 20px;
                "
            />
            <div
                class="pointer-events-none absolute inset-y-0 right-0 hidden w-1/2 opacity-100 lg:block"
                style="
                    background-image:
                        linear-gradient(
                            to right,
                            rgba(0, 0, 0, 0.07) 1px,
                            transparent 1px
                        ),
                        linear-gradient(
                            to bottom,
                            rgba(0, 0, 0, 0.07) 1px,
                            transparent 1px
                        );
                    background-size: 24px 24px;
                "
            />

            <div class="relative z-10">
                <div
                    class="mx-auto grid max-w-7xl grid-cols-1 items-start gap-8 px-4 pb-12 pt-8 sm:gap-10 sm:px-6 sm:pb-16 sm:pt-10 lg:grid-cols-2 lg:items-center lg:gap-12 lg:px-12 lg:pb-24 lg:pt-12 xl:gap-16 xl:px-16"
                >
                    <!-- Text first on mobile for readability -->
                    <div
                        class="order-1 flex flex-col justify-center lg:order-1"
                    >
                        <div class="mb-6 flex items-end gap-2 sm:mb-8">
                            <span
                                class="h-1 w-8 rounded-full bg-black sm:w-10"
                            />
                            <span
                                class="h-0.5 w-7 rounded-full bg-neutral-400 sm:w-8"
                            />
                            <span
                                class="h-0.5 w-7 rounded-full bg-neutral-400 sm:w-8"
                            />
                            <span
                                class="h-0.5 w-7 rounded-full bg-neutral-400 sm:w-8"
                            />
                        </div>

                        <h2
                            class="text-[clamp(1.5rem,4vw+0.75rem,3.25rem)] font-black uppercase leading-[0.98] tracking-[-0.02em] text-black"
                        >
                            Turn comments into conversations that sell
                        </h2>

                        <p
                            class="mt-5 max-w-xl text-[15px] font-medium leading-relaxed text-black/85 sm:mt-6 sm:text-lg"
                        >
                            “How much is this?” or “Do you ship to Mars?” Instant
                            reply. Boom — wallets open, money lands, and you
                            didn't even blink.
                        </p>

                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="mt-8 flex min-h-[52px] w-full max-w-md touch-manipulation items-center justify-center rounded-full bg-black px-6 text-xs font-bold uppercase tracking-[0.16em] text-white transition hover:bg-neutral-900 active:bg-neutral-800 sm:mt-10 sm:py-[1.125rem]"
                        >
                            Get started
                        </Link>
                        <Link
                            v-else
                            :href="login()"
                            class="mt-8 flex min-h-[52px] w-full max-w-md touch-manipulation items-center justify-center rounded-full bg-black px-6 text-xs font-bold uppercase tracking-[0.16em] text-white transition hover:bg-neutral-900 active:bg-neutral-800 sm:mt-10 sm:py-[1.125rem]"
                        >
                            Get started
                        </Link>
                    </div>

                    <div
                        class="order-2 w-full lg:order-2 lg:pl-4 xl:pl-8"
                    >
                        <div
                            class="mx-auto w-full max-w-sm overflow-hidden rounded-2xl shadow-[0_24px_70px_-20px_rgba(0,0,0,0.4)] ring-1 ring-black/10 sm:max-w-md sm:rounded-[2rem] lg:max-w-md"
                        >
                            <video
                                class="block w-full bg-black object-cover max-h-[min(58vh,420px)] sm:max-h-[min(68vh,520px)] lg:max-h-[min(72vh,560px)]"
                                autoplay
                                muted
                                loop
                                playsinline
                            >
                                <source
                                    :src="featureVideo"
                                    type="video/webm"
                                />
                            </video>
                            <div
                                class="bg-[#FF00FF] px-4 py-3 text-white sm:px-6 sm:py-4"
                            >
                                <div
                                    class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-4"
                                >
                                    <span
                                        class="text-[13px] font-semibold leading-snug sm:text-[0.9375rem]"
                                        >Auto-reply on every comment</span
                                    >
                                    <span
                                        class="shrink-0 self-end text-sm tabular-nums text-white/95 sm:self-auto sm:text-base"
                                        >1/2</span
                                    >
                                </div>
                                <div
                                    class="mt-3 h-0.5 w-full overflow-hidden rounded-full bg-white/35"
                                >
                                    <div
                                        class="h-full w-1/2 rounded-full bg-white"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Everywhere your audience is -->
                <div
                    class="mx-auto max-w-7xl px-4 pb-14 pt-4 sm:px-6 sm:pb-20 sm:pt-6 lg:px-12 lg:pb-24 xl:px-16"
                >
                    <h2
                        class="mx-auto max-w-4xl text-center text-[clamp(1.875rem,5vw,3.25rem)] font-black leading-[1.05] tracking-[-0.03em] text-black"
                    >
                        Everywhere your<br />
                        audience is
                    </h2>

                    <div
                        class="mt-10 grid grid-cols-1 gap-5 sm:mt-12 sm:gap-6 md:grid-cols-3 md:gap-5 lg:mt-14 lg:gap-6"
                    >
                        <article
                            v-for="card in audienceCards"
                            :key="card.key"
                            class="flex h-full min-h-[260px] flex-col bg-white p-6 shadow-sm ring-1 ring-black/5 sm:min-h-[280px] sm:p-8"
                        >
                            <div class="flex items-start gap-3 sm:gap-4">
                                <!-- Instagram -->
                                <div
                                    v-if="card.key === 'instagram'"
                                    class="h-11 w-11 shrink-0 sm:h-12 sm:w-12"
                                >
                                    <svg
                                        class="h-full w-full"
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >
                                        <defs>
                                            <linearGradient
                                                id="aud-ig"
                                                x1="0%"
                                                y1="100%"
                                                x2="100%"
                                                y2="0%"
                                            >
                                                <stop
                                                    offset="0%"
                                                    stop-color="#f09433"
                                                />
                                                <stop
                                                    offset="50%"
                                                    stop-color="#e6683c"
                                                />
                                                <stop
                                                    offset="100%"
                                                    stop-color="#bc1888"
                                                />
                                            </linearGradient>
                                        </defs>
                                        <path
                                            fill="url(#aud-ig)"
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.334 3.608 1.308.974.974 1.246 2.242 1.308 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.334 2.633-1.308 3.608-.974.974-2.242 1.246-3.608 1.308-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.334-3.608-1.308-.974-.974-1.246-2.242-1.308-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.334-2.633 1.308-3.608.974-.974 2.242-1.246 3.608-1.308 1.266-.058 1.646-.07 4.85-.07zM12 0C8.741 0 8.332.014 7.052.072 5.775.13 4.602.39 3.608 1.384 2.614 2.378 2.354 3.551 2.296 4.828.238 6.108.224 6.517.224 12s.014 5.892.072 7.172c.058 1.277.318 2.45 1.312 3.444.994.994 2.167 1.254 3.444 1.312 1.28.058 1.689.072 7.172.072s5.892-.014 7.172-.072c1.277-.058 2.45-.318 3.444-1.312.994-.994 1.254-2.167 1.312-3.444.058-1.28.072-1.689.072-7.172s-.014-5.892-.072-7.172c-.058-1.277-.318-2.45-1.312-3.444-.994-.994-2.167-1.254-3.444-1.312C18.892.014 18.483 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"
                                        />
                                    </svg>
                                </div>
                                <!-- TikTok -->
                                <div
                                    v-else-if="card.key === 'tiktok'"
                                    class="h-11 w-11 shrink-0 sm:h-12 sm:w-12"
                                >
                                    <svg
                                        class="h-full w-full"
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill="#000000"
                                            d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"
                                        />
                                    </svg>
                                </div>
                                <!-- WhatsApp -->
                                <div
                                    v-else
                                    class="h-11 w-11 shrink-0 sm:h-12 sm:w-12"
                                >
                                    <svg
                                        class="h-full w-full"
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill="#25D366"
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
                                        />
                                    </svg>
                                </div>
                                <h3
                                    class="pt-0.5 text-lg font-bold leading-tight text-black sm:text-xl"
                                >
                                    {{ card.title }}
                                </h3>
                            </div>

                            <p
                                class="mt-4 flex-1 text-[15px] font-normal leading-relaxed text-black sm:text-base"
                            >
                                {{ card.description }}
                            </p>

                            <a
                                :href="card.href"
                                class="mt-auto inline-flex items-center gap-1.5 self-start pt-6 font-mono text-[11px] font-medium uppercase tracking-[0.12em] text-black transition hover:opacity-70 sm:text-xs"
                            >
                                Learn more
                                <span aria-hidden="true" class="-mt-px">→</span>
                            </a>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========== Automations intro (white) ========== -->
        <section
            class="bg-white pb-[max(3.5rem,env(safe-area-inset-bottom))] pt-14 text-black sm:pb-20 sm:pt-20 lg:pb-24 lg:pt-24"
        >
            <div
                class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:max-w-4xl lg:px-8"
            >
                <p
                    class="text-[clamp(1.25rem,3.5vw+0.5rem,2rem)] font-black leading-snug tracking-[-0.02em] text-black"
                >
                    From simple welcome messages to optimized sales &amp;
                    marketing funnels
                </p>
                <p
                    class="mx-auto mt-5 max-w-2xl text-[15px] font-medium leading-relaxed text-black/75 sm:mt-6 sm:text-lg"
                >
                    Launch fast with Quick Automations or build custom flows.
                </p>
            </div>

            <!-- “See it in action” — dark panel (reference layout) -->
            <div
                class="mx-auto mt-12 max-w-7xl px-4 sm:mt-16 sm:px-6 lg:mt-20 lg:px-8"
            >
                <div
                    class="relative overflow-hidden rounded-[1.75rem] bg-[#0b1f3f] px-4 py-10 shadow-xl ring-1 ring-black/10 sm:rounded-[2rem] sm:px-6 sm:py-12 lg:px-10 lg:py-14 xl:px-12 xl:py-16"
                >
                    <div
                        class="pointer-events-none absolute inset-0 opacity-[0.14]"
                        style="
                            background-image:
                                linear-gradient(
                                    to right,
                                    rgba(255, 255, 255, 0.12) 1px,
                                    transparent 1px
                                ),
                                linear-gradient(
                                    to bottom,
                                    rgba(255, 255, 255, 0.12) 1px,
                                    transparent 1px
                                );
                            background-size: 48px 48px;
                        "
                    />
                    <div
                        class="relative z-10 grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-8 xl:gap-10"
                    >
                        <!-- Left: white card -->
                        <div
                            class="flex flex-col rounded-2xl bg-white p-6 shadow-[0_20px_60px_-24px_rgba(0,0,0,0.45)] ring-1 ring-black/5 sm:p-8 lg:col-span-4"
                        >
                            <h3
                                class="text-xl font-black leading-tight text-black sm:text-2xl"
                            >
                                See it in action...
                            </h3>
                            <ul class="mt-6 divide-y divide-neutral-200">
                                <li
                                    v-for="(item, index) in seeItActionItems"
                                    :key="item.id"
                                    :class="[
                                        'py-3.5 first:pt-0 last:pb-0 sm:py-4',
                                        index === 0
                                            ? '-mx-3 rounded-xl bg-neutral-100 px-3 sm:-mx-2 sm:px-4'
                                            : '',
                                    ]"
                                >
                                    <div
                                        class="flex flex-col gap-1.5 sm:flex-row sm:items-start sm:justify-between sm:gap-4"
                                    >
                                        <span
                                            class="text-left text-[14px] font-semibold leading-snug text-black sm:text-[15px]"
                                            >{{ item.title }}</span
                                        >
                                        <a
                                            href="#"
                                            class="shrink-0 text-left text-[10px] font-bold uppercase tracking-[0.14em] text-black/60 transition hover:text-black sm:text-[11px]"
                                        >
                                            Check it out
                                            <span aria-hidden="true" class="ml-0.5"
                                                >→</span
                                            >
                                        </a>
                                    </div>
                                </li>
                            </ul>
                            <Link
                                v-if="canRegister"
                                :href="register()"
                                class="mt-8 flex min-h-[48px] w-full touch-manipulation items-center justify-center rounded-full bg-black px-6 text-[11px] font-bold uppercase tracking-[0.16em] text-white transition hover:bg-neutral-900 active:opacity-95"
                            >
                                Get started
                            </Link>
                            <Link
                                v-else
                                :href="login()"
                                class="mt-8 flex min-h-[48px] w-full touch-manipulation items-center justify-center rounded-full bg-black px-6 text-[11px] font-bold uppercase tracking-[0.16em] text-white transition hover:bg-neutral-900 active:opacity-95"
                            >
                                Get started
                            </Link>
                        </div>

                        <!-- Center: phone + video -->
                        <div class="flex justify-center lg:col-span-4">
                            <div
                                class="relative w-full max-w-[260px] sm:max-w-[280px] lg:max-w-[300px]"
                            >
                                <div
                                    class="rounded-[2.35rem] border-[11px] border-neutral-950 bg-neutral-950 shadow-[0_32px_80px_-20px_rgba(0,0,0,0.65)]"
                                >
                                    <div
                                        class="overflow-hidden rounded-[1.65rem] bg-black"
                                    >
                                        <video
                                            class="block aspect-[9/19] w-full object-cover object-top"
                                            autoplay
                                            muted
                                            loop
                                            playsinline
                                        >
                                            <source
                                                :src="templatesVideo"
                                                type="video/webm"
                                            />
                                        </video>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: copy + vertical rule -->
                        <div class="lg:col-span-4 lg:self-start lg:pt-2">
                            <div class="flex gap-4 sm:gap-5">
                                <div
                                    class="mt-1 w-px shrink-0 self-stretch bg-white/35 sm:mt-1.5"
                                    aria-hidden="true"
                                />
                                <p
                                    class="max-w-md text-left text-[14px] font-medium leading-relaxed text-white/95 sm:text-[15px] lg:text-base"
                                >
                                    Automatically DM new followers — say hi,
                                    share a freebie, or make an offer. Turn
                                    followers into fans, and fans into
                                    customers.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========== How it works (3 steps) ========== -->
        <section
            class="bg-white pb-[max(3.5rem,env(safe-area-inset-bottom))] pt-16 text-black sm:pb-24 sm:pt-20 lg:pb-28 lg:pt-24"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-4xl text-center">
                    <h2
                        class="text-[clamp(1.875rem,5vw+0.5rem,3.25rem)] font-normal leading-[1.08] tracking-[-0.02em] text-black [font-family:'Poller_One',cursive]"
                    >
                        Get up and running in 3 simple steps
                    </h2>
                    <p
                        class="mx-auto mt-5 max-w-2xl text-[15px] font-medium leading-relaxed text-black/75 sm:mt-6 sm:text-lg"
                    >
                        We make it easy to make it easy. No degree in computer
                        science required.
                    </p>
                </div>

                <div
                    class="mt-16 grid grid-cols-1 gap-10 sm:mt-20 sm:gap-12 md:grid-cols-3 md:gap-8 lg:mt-24 lg:gap-10"
                >
                    <div
                        v-for="(item, index) in howItWorksSteps"
                        :key="index"
                        class="text-center"
                    >
                        <h3
                            class="text-lg font-bold leading-snug text-black sm:text-xl"
                        >
                            {{ item.title }}
                        </h3>
                        <p
                            class="mt-3 text-[15px] font-normal leading-relaxed text-black/75 sm:text-base"
                        >
                            {{ item.body }}
                        </p>
                    </div>
                </div>

                <div
                    class="mt-14 flex flex-col items-center justify-center gap-4 sm:mt-16 sm:flex-row sm:gap-5 lg:mt-20"
                >
                    <Link
                        v-if="canRegister"
                        :href="register()"
                        class="inline-flex min-h-[52px] w-full max-w-xs touch-manipulation items-center justify-center rounded-full bg-black px-8 text-xs font-bold uppercase tracking-[0.16em] text-white transition hover:bg-neutral-900 active:opacity-95 sm:w-auto sm:min-w-[240px] sm:px-10"
                    >
                        Get started free
                    </Link>
                    <Link
                        v-else
                        :href="login()"
                        class="inline-flex min-h-[52px] w-full max-w-xs touch-manipulation items-center justify-center rounded-full bg-black px-8 text-xs font-bold uppercase tracking-[0.16em] text-white transition hover:bg-neutral-900 active:opacity-95 sm:w-auto sm:min-w-[240px] sm:px-10"
                    >
                        Get started free
                    </Link>
                    <a
                        href="#"
                        class="inline-flex min-h-[52px] w-full max-w-xs touch-manipulation items-center justify-center rounded-full border-2 border-black bg-transparent px-8 text-xs font-bold uppercase tracking-[0.16em] text-black transition hover:bg-black/5 active:opacity-95 sm:w-auto sm:min-w-[240px] sm:px-10"
                    >
                        See plans
                    </a>
                </div>
        </div>
        </section>
    </div>
</template>
