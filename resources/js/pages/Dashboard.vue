<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { CircleHelp, Globe, LogOut, Plus } from 'lucide-vue-next';
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

const user = computed(() => page.props.auth?.user);
const activeTab = ref<'accounts' | 'templates' | 'api' | 'reports'>('templates');

const displayName = computed(() => user.value?.name ?? 'User');
const initials = computed(() => getInitials(user.value?.name));

const handleLogout = () => {
    router.flushAll();
};
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex min-h-screen flex-col bg-[#F4F5F7] text-neutral-900 antialiased">
        <header class="border-b border-neutral-200/90 bg-[#F4F5F7]">
            <div class="mx-auto flex h-[76px] w-full max-w-[1220px] items-center justify-between px-5 lg:px-6">
                <span class="text-[34px] font-black leading-none tracking-tight text-neutral-950 font-sans">
                    Manychat
                </span>

                <div class="flex items-center gap-4">
                    <button
                        type="button"
                        class="flex items-center gap-2 text-[24px] font-medium text-neutral-700 transition hover:text-neutral-900 font-sans"
                    >
                        <Globe class="size-4 text-neutral-700" stroke-width="2" />
                        <span>English</span>
                    </button>

                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button
                                type="button"
                                class="flex max-w-[260px] items-center gap-2 rounded-md px-1 py-1 transition hover:bg-neutral-100"
                            >
                                <span
                                    class="flex size-7 shrink-0 items-center justify-center rounded-full bg-violet-600 text-xs font-semibold text-white font-sans"
                                >
                                    {{ initials }}
                                </span>
                                <span class="truncate text-[25px] font-medium text-neutral-800 font-sans">
                                    {{ displayName }}
                                </span>
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
            </div>
        </header>

        <div class="mx-auto flex w-full max-w-[1220px] flex-1 flex-col px-5 lg:px-6">
            <nav class="border-b border-neutral-300/80 pt-5" aria-label="Dashboard sections">
                <ul class="-mb-px flex gap-7 overflow-x-auto pb-0 text-[23px] font-medium font-sans text-neutral-600">
                    <li>
                        <button
                            type="button"
                            class="inline-block border-b-2 pb-3.5 transition"
                            :class="
                                activeTab === 'accounts'
                                    ? 'border-blue-600 text-neutral-900'
                                    : 'border-transparent hover:text-neutral-900'
                            "
                            @click="activeTab = 'accounts'"
                        >
                            Accounts
                        </button>
                    </li>
                    <li>
                        <button
                            type="button"
                            class="inline-block border-b-2 pb-3.5 transition"
                            :class="
                                activeTab === 'templates'
                                    ? 'border-blue-600 text-neutral-900'
                                    : 'border-transparent hover:text-neutral-900'
                            "
                            @click="activeTab = 'templates'"
                        >
                            My Templates
                        </button>
                    </li>
                    <li>
                        <button
                            type="button"
                            class="inline-block border-b-2 pb-3.5 transition"
                            :class="
                                activeTab === 'api'
                                    ? 'border-blue-600 text-neutral-900'
                                    : 'border-transparent hover:text-neutral-900'
                            "
                            @click="activeTab = 'api'"
                        >
                            API Settings
                        </button>
                    </li>
                    <li>
                        <button
                            type="button"
                            class="inline-block border-b-2 pb-3.5 transition"
                            :class="
                                activeTab === 'reports'
                                    ? 'border-blue-600 text-neutral-900'
                                    : 'border-transparent hover:text-neutral-900'
                            "
                            @click="activeTab = 'reports'"
                        >
                            Message reports
                        </button>
                    </li>
                </ul>
            </nav>

            <main class="flex flex-1 flex-col py-10">
                <section v-if="activeTab === 'accounts'" class="flex flex-1 flex-col">
                    <div
                        class="flex flex-1 items-center justify-center rounded-md border border-neutral-200/80 bg-white px-6 py-12"
                    >
                        <div class="flex max-w-[460px] flex-col items-center text-center">
                            <div class="mb-8 w-full max-w-[190px]" aria-hidden="true">
                                <ChannelEmptyIllustration />
                            </div>

                            <h1 class="mb-3 text-[46px] font-semibold leading-tight text-neutral-900 font-sans">
                                You don't have a channel.
                            </h1>
                            <p class="mb-7 text-[32px] leading-8 text-neutral-500 font-sans">
                                To add your account in Manychat, click Add
                                Account.
                            </p>
                            <Link
                                :href="connect.url()"
                                class="inline-flex h-10 items-center justify-center rounded-md bg-[#007BFF] px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0066DD] font-sans"
                            >
                                + Add New Account
                            </Link>
                        </div>
                    </div>
                </section>

                <section v-else-if="activeTab === 'templates'" class="flex flex-1 flex-col">
                    <div class="mb-5 flex items-center justify-between">
                        <h1 class="text-[41px] font-semibold text-neutral-900 font-sans">
                            My Templates
                        </h1>
                        <button
                            type="button"
                            class="inline-flex h-10 items-center gap-2 rounded-md bg-[#007BFF] px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0066DD] font-sans"
                        >
                            <Plus class="size-4" stroke-width="2.6" />
                            New Template
                        </button>
                    </div>

                    <div class="flex flex-1 items-center justify-center rounded-md bg-white px-6 py-12">
                        <div class="flex max-w-[460px] flex-col items-center text-center">
                            <div class="mb-7" aria-hidden="true">
                                <svg width="138" height="123" viewBox="0 0 138 123" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M26 98c15-6 26-20 33-41 8-24 21-39 39-46h13c-14 8-24 20-31 36-8 17-19 32-34 45-7 6-14 9-20 11z" fill="#37A84F"/>
                                    <path d="M26 98c5-2 9-6 13-10 7 9 15 15 25 18-13 8-26 8-38-8z" fill="#1F7F3A"/>
                                    <path d="M49 15h43c8 0 14 6 14 14v6H35v-6c0-8 6-14 14-14z" fill="#E83EC8"/>
                                    <path d="M23 99h53" stroke="#BFC5CE" stroke-width="3" stroke-linecap="round"/>
                                    <path d="M113 17l3 9 9 3-9 3-3 9-3-9-9-3 9-3 3-9z" fill="#DF4CF2"/>
                                    <path d="M98 57l2 6 6 2-6 2-2 6-2-6-6-2 6-2 2-6z" fill="#DF4CF2"/>
                                    <path d="M16 70l2 5 5 2-5 2-2 5-2-5-5-2 5-2 2-5z" fill="#DF4CF2"/>
                                </svg>
                            </div>

                            <h2 class="mb-2 text-[40px] font-semibold text-neutral-900 font-sans">
                                You haven't created any templates yet
                            </h2>
                            <p class="mb-6 text-[30px] leading-8 text-neutral-500 font-sans">
                                With Manychat Templates you can share and reuse a whole
                                bot or a particular set of elements.
                            </p>
                            <button
                                type="button"
                                class="inline-flex h-10 items-center justify-center rounded-md bg-[#007BFF] px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0066DD] font-sans"
                            >
                                Create My First Template
                            </button>
                        </div>
                    </div>
                </section>

                <section
                    v-else
                    class="flex flex-1 items-center justify-center rounded-md bg-white"
                >
                    <p class="text-base text-neutral-500 font-sans">
                        This section is coming soon.
                    </p>
                </section>
            </main>

            <footer class="py-6">
                <button
                    type="button"
                    class="inline-flex items-center gap-2 text-sm font-medium text-neutral-700 transition hover:text-neutral-900 font-sans"
                >
                    <CircleHelp class="size-4 shrink-0" stroke-width="2" />
                    Help
                </button>
            </footer>
        </div>
    </div>
</template>
