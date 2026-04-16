<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

const props = withDefaults(
    defineProps<{
        accountFor?: 'client' | 'employer' | 'self' | null;
    }>(),
    {
        accountFor: null,
    },
);

const form = useForm<{ account_for: 'client' | 'employer' | 'self' | '' }>({
    account_for: props.accountFor || '',
});

const submit = () => {
    if (!form.account_for) return;
    form.post('/onboarding/strategy');
};
</script>

<template>
    <Head title="Onboarding" />

    <div class="flex min-h-screen flex-col bg-white text-neutral-900 antialiased lg:flex-row">
        <!-- Left -->
        <aside
            class="flex flex-col bg-[#F9F9F9] px-6 py-8 lg:w-[42%] lg:min-h-screen lg:shrink-0 lg:px-10 lg:py-10"
        >
            <div class="mb-8 text-lg font-bold tracking-tight text-neutral-950">
                Manychat
            </div>

            <div class="flex flex-1 flex-col items-center justify-center text-center lg:px-4">
                <div class="mb-8 flex w-full justify-center">
                    <div class="relative h-44 w-44">
                        <div class="absolute inset-0 rounded-full bg-white shadow-sm" />
                        <svg
                            class="absolute inset-0"
                            viewBox="0 0 220 220"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M44 156c22 20 54 30 96 30 38 0 66-9 84-26"
                                stroke="#E5E7EB"
                                stroke-width="10"
                                stroke-linecap="round"
                            />
                            <path
                                d="M64 140c5-28 23-48 54-60 30-12 61-9 92 8"
                                stroke="#111827"
                                stroke-width="10"
                                stroke-linecap="round"
                            />
                            <path
                                d="M140 62l20-26 8 34"
                                stroke="#A855F7"
                                stroke-width="12"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M86 150c12 4 24 6 38 6 14 0 27-2 38-6"
                                stroke="#111827"
                                stroke-width="10"
                                stroke-linecap="round"
                            />
                            <circle cx="92" cy="108" r="8" fill="#111827" />
                            <circle cx="144" cy="108" r="8" fill="#111827" />
                            <path
                                d="M110 120c10 6 20 6 30 0"
                                stroke="#111827"
                                stroke-width="8"
                                stroke-linecap="round"
                            />
                        </svg>
                    </div>
                </div>

                <h1 class="mb-3 max-w-md text-2xl font-bold leading-tight text-neutral-950 font-sans sm:text-3xl">
                    Let's find the right strategy<br />
                    for you
                </h1>
                <p class="max-w-md text-sm leading-relaxed text-neutral-500">
                    Tell us a little about your business, so we can match the right
                    marketing approach.
                </p>
            </div>
        </aside>

        <!-- Right -->
        <main class="flex flex-1 items-center justify-center bg-white px-6 py-12 lg:px-12 lg:py-16">
            <div class="w-full max-w-xl">
                <h2 class="mb-4 text-base font-semibold text-neutral-900">
                    Who are you setting up this account for?
                </h2>

                <div class="space-y-3">
                    <label
                        class="flex cursor-pointer items-center justify-between rounded-xl border border-neutral-200 bg-white px-4 py-4 transition hover:border-blue-300"
                    >
                        <div class="flex items-center gap-3">
                            <div class="flex size-9 items-center justify-center rounded-lg bg-neutral-100 text-neutral-700">
                                <span class="text-xs font-bold">CL</span>
                            </div>
                            <span class="text-sm font-medium text-neutral-900">For a client</span>
                        </div>
                        <input
                            v-model="form.account_for"
                            type="radio"
                            name="account_for"
                            value="client"
                            class="size-4 accent-blue-600"
                        />
                    </label>

                    <label
                        class="flex cursor-pointer items-center justify-between rounded-xl border border-neutral-200 bg-white px-4 py-4 transition hover:border-blue-300"
                    >
                        <div class="flex items-center gap-3">
                            <div class="flex size-9 items-center justify-center rounded-lg bg-neutral-100 text-neutral-700">
                                <span class="text-xs font-bold">EM</span>
                            </div>
                            <span class="text-sm font-medium text-neutral-900">For my employer</span>
                        </div>
                        <input
                            v-model="form.account_for"
                            type="radio"
                            name="account_for"
                            value="employer"
                            class="size-4 accent-blue-600"
                        />
                    </label>

                    <label
                        class="flex cursor-pointer items-center justify-between rounded-xl border border-neutral-200 bg-white px-4 py-4 transition hover:border-blue-300"
                    >
                        <div class="flex items-center gap-3">
                            <div class="flex size-9 items-center justify-center rounded-lg bg-neutral-100 text-neutral-700">
                                <span class="text-xs font-bold">ME</span>
                            </div>
                            <span class="text-sm font-medium text-neutral-900">For myself</span>
                        </div>
                        <input
                            v-model="form.account_for"
                            type="radio"
                            name="account_for"
                            value="self"
                            class="size-4 accent-blue-600"
                        />
                    </label>
                </div>

                <div class="mt-8 flex justify-end">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-md bg-[#0078FF] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0068E6] disabled:cursor-not-allowed disabled:opacity-60"
                        :disabled="form.processing || !form.account_for"
                        @click="submit"
                    >
                        {{ form.processing ? 'Loading...' : 'Next' }}
                    </button>
                </div>
            </div>
        </main>
    </div>
</template>

