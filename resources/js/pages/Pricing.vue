<script setup lang="ts">
import ProductMegaMenu from '@/components/ProductMegaMenu.vue';
import { secondaryNavLinks } from '@/data/productMenu';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
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

const plans = [
    {
        name: 'Free',
        subtitle: 'Explore basic automation for free',
        price: '$0',
        oldPrice: null,
        contacts: '25',
        button: 'Start for free',
        accent: false,
        features: [
            'Connect any 2 channels',
            'Basic automations (up to 4 active automations)',
            '1 user',
            'Basic unified inbox',
            'Self-serve Support',
            'Manychat branding',
        ],
    },
    {
        name: 'Essential',
        subtitle: 'For creators and growing teams',
        price: '$14/mo',
        oldPrice: '$16.8 billed annually (16% off)',
        contacts: '250',
        button: 'Try 14 days for free',
        accent: false,
        features: [
            'Connect any 2 channels',
            'Unlimited custom automations',
            '2 users',
            'Basic Inbox + organization & reminders',
            'Email Support',
            'No Manychat branding',
        ],
    },
    {
        name: 'Pro',
        subtitle: 'For scaling creators and growing businesses',
        price: '$29/mo',
        oldPrice: '$34.8 billed annually (25% off)',
        contacts: '2500',
        button: 'Try 14 days for free',
        accent: true,
        features: [
            'Connect any 3 channels',
            'Advanced automations, broadcasts, and AI-powered convos',
            '3 users',
            'Custom inbox labels & rules',
            'Email Support',
            'No Manychat branding',
        ],
    },
    {
        name: 'Business',
        subtitle: 'For scaling businesses and high-growth creators',
        price: '$69/mo',
        oldPrice: '$82.8 billed annually (16% off)',
        contacts: '7500',
        button: 'Get started',
        accent: false,
        features: [
            'Unlimited channels',
            'Advanced automations, broadcasts and AI-powered convos',
            '5 users',
            'Shared team inbox & assignments',
            'Priority Support',
            'No Manychat branding',
        ],
    },
    {
        name: 'Advanced',
        subtitle: 'For teams and high-volume operators',
        price: '$139/mo',
        oldPrice: '$166.8 billed annually (16% off)',
        contacts: '25000',
        button: 'Get started',
        accent: false,
        features: [
            'Unlimited channels',
            'Advanced automations, broadcasts and AI-powered convos',
            '8 users',
            'Shared team inbox & assignments',
            'Priority Support',
            'No Manychat branding',
        ],
    },
] as const;
</script>

<template>
    <Head title="Pricing" />

    <div class="min-h-screen bg-[#EEF3EF] text-neutral-900">
        <header class="bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/70">
            <div
                class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8"
            >
                <Link
                    href="/"
                    class="text-lg font-extrabold tracking-tight text-neutral-950 font-sans"
                >
                    {{ appName }}
                </Link>

                <nav
                    class="hidden items-center gap-7 text-[11px] font-semibold uppercase tracking-[0.12em] text-neutral-700 xl:flex"
                >
                    <ProductMegaMenu variant="light" />
                    <a
                        v-for="item in secondaryNavLinks"
                        :key="item.label"
                        :href="item.href"
                        class="transition hover:text-black"
                        :class="item.label === 'Pricing' ? 'text-black' : ''"
                    >
                        {{ item.label }}
                    </a>
                </nav>

                <div class="flex items-center gap-3">
                    <template v-if="$page.props.auth.user">
                        <Link
                            :href="dashboard()"
                            class="inline-flex min-h-[42px] items-center rounded-full border border-neutral-300 px-5 text-[11px] font-semibold uppercase tracking-wide text-neutral-900 transition hover:bg-neutral-100"
                        >
                            Dashboard
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="hidden min-h-[42px] items-center rounded-full bg-black px-5 text-[11px] font-semibold uppercase tracking-wide text-white transition hover:bg-neutral-900 sm:inline-flex"
                        >
                            Get started
                        </Link>
                        <Link
                            :href="login()"
                            class="hidden min-h-[42px] items-center text-[11px] font-semibold uppercase tracking-wide text-neutral-700 transition hover:text-black sm:inline-flex"
                        >
                            Sign in
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-[1320px] px-4 pb-10 pt-8 sm:px-6 lg:px-8">
            <h1
                class="text-center text-3xl font-semibold tracking-tight text-neutral-950 font-sans sm:text-5xl"
            >
                Plans that scale when you do
            </h1>

            <div class="mt-6 flex items-center justify-center">
                <div class="rounded-full bg-neutral-200 p-1">
                    <button
                        type="button"
                        class="rounded-full px-5 py-2 text-[10px] font-bold uppercase tracking-[0.14em] text-neutral-600 font-sans"
                    >
                        Monthly
                    </button>
                    <button
                        type="button"
                        class="rounded-full bg-black px-5 py-2 text-[10px] font-bold uppercase tracking-[0.14em] text-white font-sans"
                    >
                        Annual
                    </button>
                </div>
            </div>
            <p
                class="mx-auto mt-1 w-fit rounded-full bg-white px-3 py-1 text-[10px] font-semibold text-neutral-700 shadow-sm font-sans"
            >
                Save up to 30%
            </p>

            <div class="mt-8 grid grid-cols-1 gap-5 lg:grid-cols-5">
                <article
                    v-for="plan in plans"
                    :key="plan.name"
                    class="relative rounded-3xl bg-white px-5 pb-5 pt-4 shadow-sm ring-1 ring-black/5"
                >
                    <div
                        v-if="plan.accent"
                        class="absolute inset-x-3 -top-3 rounded-t-2xl bg-[#f8d7ff] px-3 py-1 text-center text-[10px] font-bold uppercase tracking-[0.14em] text-[#c027d6] font-sans"
                    >
                        ✦ Manychat AI
                    </div>

                    <h2 class="mt-3 text-center text-xl font-semibold text-neutral-900 font-sans">
                        {{ plan.name }}
                    </h2>
                    <p class="mt-2 text-center text-xs leading-5 text-neutral-500 font-sans">
                        {{ plan.subtitle }}
                    </p>

                    <div class="mt-5 text-center">
                        <p
                            v-if="plan.oldPrice"
                            class="text-[11px] text-neutral-400 line-through font-sans"
                        >
                            {{ plan.oldPrice }}
                        </p>
                        <p class="mt-1 text-4xl font-semibold text-neutral-950 font-sans">
                            {{ plan.price }}
                        </p>
                    </div>

                    <p class="mt-5 text-center text-3xl font-semibold text-neutral-900 font-sans">
                        {{ plan.contacts }}
                    </p>
                    <p class="text-center text-xs text-neutral-500 font-sans">
                        Active Contacts per month
                    </p>

                    <button
                        type="button"
                        class="mt-5 inline-flex h-11 w-full items-center justify-center rounded-full bg-black text-[11px] font-bold uppercase tracking-[0.14em] text-white transition hover:bg-neutral-900 font-sans"
                    >
                        {{ plan.button }}
                    </button>

                    <ul class="mt-5 space-y-2.5">
                        <li
                            v-for="feature in plan.features"
                            :key="feature"
                            class="text-xs leading-5 text-neutral-700 font-sans"
                        >
                            {{ feature }}
                        </li>
                    </ul>
                </article>
            </div>
        </main>
    </div>
</template>
