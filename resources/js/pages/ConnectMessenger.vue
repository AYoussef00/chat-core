<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import { computed } from 'vue';
import MessengerConnectIllustration from '@/components/MessengerConnectIllustration.vue';
import { connect } from '@/routes/channels';

type FacebookPage = {
    id: string;
    name: string;
    access_token: string;
};

type FacebookAccount = {
    id: string;
    name: string;
    email?: string | null;
};

const props = withDefaults(
    defineProps<{
        facebookAccount?: FacebookAccount | null;
        facebookPages?: FacebookPage[];
        selectedFacebookPageId?: string | null;
        connectedPages?: Array<{
            id: number;
            page_id: string;
            page_name: string;
            status: string;
            updated_at: string;
        }>;
    }>(),
    {
        facebookAccount: null,
        facebookPages: () => [],
        selectedFacebookPageId: null,
        connectedPages: () => [],
    },
);

const page = usePage();
const appName = computed(() => (page.props.name as string) || 'Manychat');
const hasPages = computed(() => props.facebookPages.length > 0);

const form = useForm({
    page_id: props.selectedFacebookPageId || props.facebookPages[0]?.id || '',
});

const selectPage = () => {
    form.post('/channels/connect/messenger/facebook/page');
};
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
                    class="mb-3 max-w-md text-2xl font-bold leading-tight text-neutral-950 font-sans sm:text-3xl"
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
            <div class="flex w-full max-w-xl flex-col">
                <template v-if="!hasPages">
                    <div class="flex flex-col items-center text-center">
                        <h2
                            class="mb-3 text-xl font-bold leading-snug text-neutral-950 font-sans sm:text-2xl"
                        >
                            Sign in with your Facebook Account
                        </h2>
                        <p
                            class="mb-8 max-w-sm text-sm leading-relaxed text-neutral-500"
                        >
                            To start building automation in Facebook Messenger,
                            sign in with your Facebook account.
                        </p>

                        <a
                            href="/channels/connect/messenger/facebook"
                            class="inline-flex w-full max-w-sm items-center justify-center rounded-full bg-[#0078FF] px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0068E6] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"
                        >
                            Sign in with Facebook
                        </a>
                    </div>
                </template>

                <template v-else>
                    <h2
                        class="mb-2 text-xl font-bold leading-snug text-neutral-950 font-sans sm:text-2xl"
                    >
                        Choose a Facebook Page
                    </h2>
                    <p class="mb-6 text-sm leading-relaxed text-neutral-500">
                        Connected as
                        <span class="font-semibold text-neutral-700">{{
                            facebookAccount?.name || 'Facebook user'
                        }}</span
                        >. Select one page to connect with Messenger.
                    </p>

                    <div class="space-y-3">
                        <label
                            v-for="facebookPage in facebookPages"
                            :key="facebookPage.id"
                            class="flex cursor-pointer items-center gap-3 rounded-xl border border-neutral-200 p-3 transition hover:border-blue-300"
                        >
                            <input
                                v-model="form.page_id"
                                type="radio"
                                name="facebook_page"
                                :value="facebookPage.id"
                                class="size-4 accent-blue-600"
                            />
                            <div class="flex size-10 items-center justify-center rounded-lg bg-blue-100 text-xs font-bold text-blue-700">
                                FB
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-neutral-900">
                                    {{ facebookPage.name }}
                                </p>
                                <p class="text-xs text-neutral-500">Facebook Page</p>
                            </div>
                        </label>
                    </div>

                    <button
                        type="button"
                        :disabled="form.processing || !form.page_id"
                        class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-[#0078FF] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0068E6] disabled:cursor-not-allowed disabled:opacity-60"
                        @click="selectPage"
                    >
                        {{ form.processing ? 'Saving...' : 'Continue with selected page' }}
                    </button>

                    <div
                        v-if="connectedPages.length > 0"
                        class="mt-8 rounded-xl border border-neutral-200 bg-neutral-50 p-4"
                    >
                        <h3 class="mb-2 text-sm font-semibold text-neutral-800">
                            Connected pages
                        </h3>
                        <ul class="space-y-2">
                            <li
                                v-for="connectedPage in connectedPages"
                                :key="connectedPage.id"
                                class="flex items-center justify-between rounded-lg bg-white px-3 py-2 text-sm"
                            >
                                <span class="font-medium text-neutral-800">
                                    {{ connectedPage.page_name }}
                                </span>
                                <span
                                    class="rounded-full px-2 py-0.5 text-xs"
                                    :class="
                                        connectedPage.status === 'active'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-neutral-200 text-neutral-600'
                                    "
                                >
                                    {{ connectedPage.status }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </template>
            </div>
        </main>
    </div>
</template>
