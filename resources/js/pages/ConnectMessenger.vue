<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import { computed } from 'vue';
import MessengerConnectIllustration from '@/components/MessengerConnectIllustration.vue';
import { connect } from '@/routes/channels';

const page = usePage();
const appName = computed(() => (page.props.name as string) || 'Manychat');
</script>

<template>
    <Head title="Connect Facebook Page" />

    <div
        class="flex min-h-screen flex-col bg-white text-neutral-900 antialiased lg:flex-row"
    >
        <!-- Left -->
        <aside
            class="flex flex-col bg-[#F9F9F9] px-6 py-8 lg:w-[42%] lg:min-h-screen lg:shrink-0 lg:px-10 lg:py-10"
        >
            <div class="mb-8 text-lg font-bold tracking-tight text-neutral-950">
                {{ appName }}
            </div>

            <div
                class="flex flex-1 flex-col items-center justify-center text-center lg:px-4"
            >
                <div class="mb-8 flex w-full justify-center">
                    <MessengerConnectIllustration />
                </div>

                <h1
                    class="mb-3 max-w-md text-2xl font-bold leading-tight text-neutral-950 sm:text-3xl"
                >
                    Connect Facebook Page
                </h1>
                <p class="max-w-md text-sm leading-relaxed text-neutral-500">
                    Follow the instruction to create your first Messenger
                    automation.
                </p>
            </div>

            <div class="mt-8 lg:mt-auto">
                <Link
                    :href="connect.url()"
                    class="inline-flex items-center gap-1 text-sm font-medium text-[#007BFF] transition hover:text-[#0066DD]"
                >
                    <ChevronLeft class="size-4" stroke-width="2" />
                    Choose another channel
                </Link>
            </div>
        </aside>

        <!-- Right -->
        <main
            class="flex flex-1 flex-col items-center justify-center bg-white px-6 py-12 lg:px-12 lg:py-16"
        >
            <div
                class="flex w-full max-w-md flex-col items-center text-center"
            >
                <h2
                    class="mb-3 text-xl font-bold leading-snug text-neutral-950 sm:text-2xl"
                >
                    Sign in with your Facebook Account
                </h2>
                <p
                    class="mb-8 max-w-sm text-sm leading-relaxed text-neutral-500"
                >
                    To start building automation in Facebook Messenger, sign in
                    with your Facebook account.
                </p>

                <a
                    href="/channels/connect/messenger/facebook"
                    class="inline-flex w-full max-w-sm items-center justify-center rounded-full bg-[#0078FF] px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0068E6] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"
                >
                    Sign in with Facebook
                </a>
            </div>
        </main>
    </div>
</template>
