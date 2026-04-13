<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ChevronDown, CircleHelp, Globe, LogOut, Plus } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import ChannelEmptyIllustration from '@/components/ChannelEmptyIllustration.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { getInitials } from '@/composables/useInitials';
import { logout } from '@/routes';
import { connect } from '@/routes/channels';

const page = usePage();

const appName = computed(() => (page.props.name as string) || 'Manychat');
const user = computed(() => page.props.auth?.user);
const activeTab = ref<'accounts' | 'templates' | 'api' | 'reports'>('accounts');

const displayName = computed(() => user.value?.name ?? 'User');
const initials = computed(() => getInitials(user.value?.name));

const handleLogout = () => {
    router.flushAll();
};
</script>

<template>
    <Head title="Dashboard" />

    <div
        class="flex min-h-screen flex-col bg-[#F9FAFB] text-neutral-900 antialiased"
    >
        <!-- Header -->
        <header
            class="flex items-center justify-between border-b border-neutral-200/90 bg-[#F9FAFB] px-4 py-4 sm:px-8"
        >
            <span class="text-lg font-bold tracking-tight text-neutral-950">{{
                appName
            }}</span>

            <div class="flex items-center gap-5 sm:gap-8">
                <button
                    type="button"
                    class="flex items-center gap-2 text-sm font-medium text-neutral-700 transition hover:text-neutral-900"
                >
                    <Globe class="size-4 text-neutral-600" stroke-width="2" />
                    <span class="hidden sm:inline">English</span>
                </button>

                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button
                            type="button"
                            class="flex max-w-[min(100%,14rem)] items-center gap-2.5 rounded-lg px-1.5 py-1 transition hover:bg-neutral-100 sm:max-w-none"
                        >
                            <span
                                class="flex size-9 shrink-0 items-center justify-center rounded-full bg-violet-600 text-sm font-semibold text-white"
                            >
                                {{ initials }}
                            </span>
                            <span
                                class="truncate text-sm font-medium text-neutral-800"
                                >{{ displayName }}</span
                            >
                            <ChevronDown class="size-4 shrink-0 text-neutral-500" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end" class="w-44">
                        <DropdownMenuItem :as-child="true">
                            <Link
                                :href="logout()"
                                @click="handleLogout"
                                as="button"
                                class="flex w-full cursor-pointer items-center"
                                data-test="dashboard-logout-button"
                            >
                                <LogOut class="mr-2 size-4" />
                                Log out
                            </Link>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </header>

        <!-- Sub-navigation -->
        <nav
            class="border-b border-neutral-200 bg-[#F9FAFB] px-4 sm:px-8"
            aria-label="Dashboard sections"
        >
            <ul
                class="-mb-px flex gap-6 overflow-x-auto pb-0 text-sm sm:gap-10"
            >
                <li>
                    <button
                        type="button"
                        class="inline-block border-b-2 pb-3 transition"
                        :class="
                            activeTab === 'accounts'
                                ? 'border-blue-600 font-semibold text-blue-600'
                                : 'border-transparent font-medium text-neutral-600 hover:text-neutral-900'
                        "
                        @click="activeTab = 'accounts'"
                    >
                        Accounts
                    </button>
                </li>
                <li>
                    <button
                        type="button"
                        class="inline-block border-b-2 pb-3 transition"
                        :class="
                            activeTab === 'templates'
                                ? 'border-blue-600 font-semibold text-blue-600'
                                : 'border-transparent font-medium text-neutral-600 hover:text-neutral-900'
                        "
                        @click="activeTab = 'templates'"
                    >
                        My Templates
                    </button>
                </li>
                <li>
                    <button
                        type="button"
                        class="inline-block border-b-2 pb-3 transition"
                        :class="
                            activeTab === 'api'
                                ? 'border-blue-600 font-semibold text-blue-600'
                                : 'border-transparent font-medium text-neutral-600 hover:text-neutral-900'
                        "
                        @click="activeTab = 'api'"
                    >
                        API Settings
                    </button>
                </li>
                <li>
                    <button
                        type="button"
                        class="inline-block border-b-2 pb-3 transition"
                        :class="
                            activeTab === 'reports'
                                ? 'border-blue-600 font-semibold text-blue-600'
                                : 'border-transparent font-medium text-neutral-600 hover:text-neutral-900'
                        "
                        @click="activeTab = 'reports'"
                    >
                        Message reports
                    </button>
                </li>
            </ul>
        </nav>

        <!-- Main empty state -->
        <main
            class="flex flex-1 flex-col items-center justify-center px-4 py-10 sm:px-8"
        >
            <div
                v-if="activeTab === 'accounts'"
                class="w-full max-w-lg rounded-xl border border-neutral-200/90 bg-white px-6 py-12 shadow-sm sm:px-12 sm:py-14"
            >
                <div class="flex flex-col items-center text-center">
                    <!-- Illustration: toaster + speech bubbles -->
                    <div class="mb-8 w-full max-w-[220px]" aria-hidden="true">
                        <ChannelEmptyIllustration />
                    </div>

                    <h1 class="mb-2 text-xl font-bold text-neutral-950 sm:text-2xl">
                        You don't have a channel.
                    </h1>
                    <p class="mb-8 max-w-sm text-sm leading-relaxed text-neutral-500">
                        To add your account in {{ appName }}, click Add Account.
                    </p>

                    <Link
                        :href="connect.url()"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#007BFF] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0066DD] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"
                    >
                        <Plus class="size-4" stroke-width="2.5" />
                        Add New Account
                    </Link>
                </div>
            </div>

            <div
                v-else-if="activeTab === 'api'"
                class="w-full max-w-5xl rounded-xl border border-neutral-200/90 bg-white shadow-sm"
            >
                <div class="grid grid-cols-1 md:grid-cols-[120px,1fr]">
                    <aside
                        class="border-b border-neutral-100 px-6 py-6 md:border-b-0 md:border-r"
                    >
                        <span class="text-sm font-semibold text-emerald-600">API</span>
                    </aside>

                    <section class="px-6 py-8 sm:px-10">
                        <h1 class="mb-8 text-3xl font-semibold text-neutral-900 font-sans">
                            Profile Scoped Public API
                        </h1>

                        <label
                            for="api-key"
                            class="mb-3 block text-sm font-semibold text-neutral-800"
                        >
                            Get API Key
                        </label>

                        <div
                            class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center"
                        >
                            <input
                                id="api-key"
                                type="text"
                                placeholder="Your API Key"
                                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-700 placeholder:text-neutral-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 sm:max-w-md"
                            />
                            <button
                                type="button"
                                class="inline-flex h-10 items-center justify-center rounded-md bg-[#007BFF] px-4 text-sm font-semibold text-white transition hover:bg-[#0066DD]"
                            >
                                Generate Your API Key
                            </button>
                        </div>

                        <p class="text-sm leading-relaxed text-neutral-500">
                            Generate an API Key to start using Manychat API.
                            <br />
                            Here is the
                            <a href="#" class="text-blue-600 hover:underline"
                                >link to Swagger</a
                            >
                            where you can try our API.
                            <br />
                            Help article is available
                            <a href="#" class="text-blue-600 hover:underline"
                                >here</a
                            >.
                        </p>
                    </section>
                </div>
            </div>

            <div
                v-else
                class="w-full max-w-lg rounded-xl border border-neutral-200/90 bg-white px-6 py-12 text-center shadow-sm sm:px-12 sm:py-14"
            >
                <h2 class="mb-2 text-xl font-semibold text-neutral-900 font-sans">
                    This section is coming soon
                </h2>
                <p class="text-sm text-neutral-500">
                    Select another tab to continue.
                </p>
            </div>
        </main>

        <!-- Footer help -->
        <footer class="mt-auto px-4 py-4 sm:px-8">
            <a
                href="#"
                class="inline-flex items-center gap-2 text-sm font-medium text-neutral-600 transition hover:text-neutral-900"
            >
                <CircleHelp class="size-4 shrink-0" stroke-width="2" />
                Help
            </a>
        </footer>
    </div>
</template>
