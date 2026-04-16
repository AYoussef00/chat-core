<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    connection: {
        id: number;
        page_id: string;
        page_name: string;
        status: string;
        updated_at: string | null;
    };
    facebookAccountName: string;
    botSettings: {
        enabled: boolean;
        reply_mode: 'auto' | 'smart' | 'manual';
        default_reply: string;
        delay_seconds: 0 | 5 | 10;
        welcome_message: string;
    };
}>();

const form = useForm({
    enabled: props.botSettings.enabled,
    reply_mode: props.botSettings.reply_mode,
    default_reply: props.botSettings.default_reply,
    delay_seconds: props.botSettings.delay_seconds,
    welcome_message: props.botSettings.welcome_message,
});

const saveSettings = () => {
    form.put(`/dashboard/accounts/${props.connection.id}/bot-settings`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Bot Settings - ${connection.page_name}`" />

    <div class="min-h-screen bg-[#F4F5F7] px-5 py-8 text-neutral-900 antialiased lg:px-8">
        <div class="mx-auto max-w-[1220px]">
            <div class="mb-6 flex items-center justify-between gap-4">
                <div>
                    <Link href="/dashboard" class="text-sm font-medium text-[#007BFF] transition hover:text-[#0066DD]">
                        &larr; Back to Dashboard
                    </Link>
                    <h1 class="mt-3 text-4xl font-semibold text-neutral-950 font-sans">
                        Bot Settings
                    </h1>
                    <p class="mt-2 text-base text-neutral-500 font-sans">
                        Manage your Messenger bot behavior for <span class="font-semibold text-neutral-700">{{ connection.page_name }}</span>.
                    </p>
                </div>

                <button
                    type="button"
                    class="inline-flex h-10 items-center justify-center rounded-md bg-[#007BFF] px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0066DD] disabled:opacity-60"
                    :disabled="form.processing"
                    @click="saveSettings"
                >
                    {{ form.processing ? 'Saving...' : 'Save Changes' }}
                </button>
            </div>

            <div class="grid gap-6">
                <section class="rounded-xl border border-neutral-200 bg-white p-6">
                    <h2 class="text-2xl font-semibold text-neutral-950 font-sans">Connection Status</h2>
                    <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-lg bg-neutral-50 p-4">
                            <p class="text-sm text-neutral-500 font-sans">Connected page</p>
                            <p class="mt-2 text-lg font-semibold text-neutral-900 font-sans">{{ connection.page_name }}</p>
                        </div>
                        <div class="rounded-lg bg-neutral-50 p-4">
                            <p class="text-sm text-neutral-500 font-sans">Facebook account</p>
                            <p class="mt-2 text-lg font-semibold text-neutral-900 font-sans">{{ facebookAccountName }}</p>
                        </div>
                        <div class="rounded-lg bg-neutral-50 p-4">
                            <p class="text-sm text-neutral-500 font-sans">Status</p>
                            <p class="mt-2 text-lg font-semibold text-emerald-600 font-sans">
                                {{ connection.status === 'active' ? 'Active / Connected' : connection.status }}
                            </p>
                        </div>
                        <div class="rounded-lg bg-neutral-50 p-4">
                            <p class="text-sm text-neutral-500 font-sans">Last sync</p>
                            <p class="mt-2 text-lg font-semibold text-neutral-900 font-sans">
                                {{ connection.updated_at ? new Date(connection.updated_at).toLocaleString() : 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <Link
                            :href="`/dashboard/accounts/${connection.id}`"
                            as="button"
                            method="delete"
                            class="inline-flex h-10 items-center justify-center rounded-md border border-red-200 bg-red-50 px-4 text-sm font-semibold text-red-600 transition hover:bg-red-100"
                        >
                            Disconnect Page
                        </Link>
                        <Link
                            href="/channels/connect/messenger/facebook?fresh=1"
                            class="inline-flex h-10 items-center justify-center rounded-md border border-neutral-300 bg-white px-4 text-sm font-semibold text-neutral-800 transition hover:bg-neutral-50"
                        >
                            Reconnect
                        </Link>
                    </div>
                </section>

                <section class="rounded-xl border border-neutral-200 bg-white p-6">
                    <h2 class="text-2xl font-semibold text-neutral-950 font-sans">Bot Activation</h2>

                    <div class="mt-6 space-y-6">
                        <div class="flex items-center justify-between rounded-lg bg-neutral-50 p-4">
                            <div>
                                <p class="text-base font-semibold text-neutral-900 font-sans">Enable Bot</p>
                                <p class="mt-1 text-sm text-neutral-500 font-sans">
                                    Turn the Messenger bot on or off for this page.
                                </p>
                            </div>

                            <label class="inline-flex cursor-pointer items-center">
                                <input
                                    v-model="form.enabled"
                                    type="checkbox"
                                    class="peer sr-only"
                                />
                                <div class="relative h-7 w-12 rounded-full bg-neutral-300 transition peer-checked:bg-[#007BFF]">
                                    <div
                                        class="absolute left-1 top-1 h-5 w-5 rounded-full bg-white transition peer-checked:translate-x-5"
                                    />
                                </div>
                            </label>
                        </div>

                        <div>
                            <p class="text-base font-semibold text-neutral-900 font-sans">Reply Mode</p>
                            <p class="mt-1 text-sm text-neutral-500 font-sans">
                                Choose how the bot should respond to incoming messages.
                            </p>

                            <div class="mt-4 grid gap-3 md:grid-cols-3">
                                <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-neutral-200 p-4 transition hover:border-blue-300">
                                    <input v-model="form.reply_mode" type="radio" value="auto" class="mt-1 size-4 accent-blue-600" />
                                    <div>
                                        <p class="font-semibold text-neutral-900 font-sans">Auto Reply</p>
                                        <p class="mt-1 text-sm text-neutral-500 font-sans">Reply to every message automatically.</p>
                                    </div>
                                </label>

                                <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-neutral-200 p-4 transition hover:border-blue-300">
                                    <input v-model="form.reply_mode" type="radio" value="smart" class="mt-1 size-4 accent-blue-600" />
                                    <div>
                                        <p class="font-semibold text-neutral-900 font-sans">Smart Reply</p>
                                        <p class="mt-1 text-sm text-neutral-500 font-sans">Use AI or rules-based automation.</p>
                                    </div>
                                </label>

                                <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-neutral-200 p-4 transition hover:border-blue-300">
                                    <input v-model="form.reply_mode" type="radio" value="manual" class="mt-1 size-4 accent-blue-600" />
                                    <div>
                                        <p class="font-semibold text-neutral-900 font-sans">Manual Mode</p>
                                        <p class="mt-1 text-sm text-neutral-500 font-sans">Disable automatic replies.</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-xl border border-neutral-200 bg-white p-6">
                    <h2 class="text-2xl font-semibold text-neutral-950 font-sans">Core Bot Logic</h2>
                    <div class="mt-6 grid gap-6">
                        <div>
                            <label for="default_reply" class="text-base font-semibold text-neutral-900 font-sans">
                                Default Reply
                            </label>
                            <p class="mt-1 text-sm text-neutral-500 font-sans">
                                Used when the bot does not understand the user's message.
                            </p>
                            <textarea
                                id="default_reply"
                                v-model="form.default_reply"
                                rows="4"
                                class="mt-3 w-full rounded-lg border border-neutral-300 px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            />
                        </div>

                        <div>
                            <label for="delay_seconds" class="text-base font-semibold text-neutral-900 font-sans">
                                Delay Reply
                            </label>
                            <p class="mt-1 text-sm text-neutral-500 font-sans">
                                Choose how long the bot should wait before sending a reply.
                            </p>
                            <select
                                id="delay_seconds"
                                v-model="form.delay_seconds"
                                class="mt-3 w-full rounded-lg border border-neutral-300 px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            >
                                <option :value="0">0 seconds</option>
                                <option :value="5">5 seconds</option>
                                <option :value="10">10 seconds</option>
                            </select>
                        </div>

                        <div>
                            <label for="welcome_message" class="text-base font-semibold text-neutral-900 font-sans">
                                Welcome Message
                            </label>
                            <p class="mt-1 text-sm text-neutral-500 font-sans">
                                First message sent to a new customer.
                            </p>
                            <textarea
                                id="welcome_message"
                                v-model="form.welcome_message"
                                rows="4"
                                class="mt-3 w-full rounded-lg border border-neutral-300 px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            />
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

