<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ChevronDown, CircleHelp, Globe, LogOut, Plus } from 'lucide-vue-next';
import { computed } from 'vue';
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
                    <span
                        class="inline-block border-b-2 border-blue-600 pb-3 font-semibold text-blue-600"
                    >
                        Accounts
                    </span>
                </li>
                <li>
                    <button
                        type="button"
                        class="inline-block border-b-2 border-transparent pb-3 font-medium text-neutral-600 transition hover:text-neutral-900"
                    >
                        My Templates
                    </button>
                </li>
                <li>
                    <button
                        type="button"
                        class="inline-block border-b-2 border-transparent pb-3 font-medium text-neutral-600 transition hover:text-neutral-900"
                    >
                        API Settings
                    </button>
                </li>
                <li>
                    <button
                        type="button"
                        class="inline-block border-b-2 border-transparent pb-3 font-medium text-neutral-600 transition hover:text-neutral-900"
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
