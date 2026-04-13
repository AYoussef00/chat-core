<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { home, login } from '@/routes';

const page = usePage();
const appName = computed(() => (page.props.name as string) || 'Chat Core');
</script>

<template>
    <Head title="Sign up" />

    <div class="grid min-h-dvh bg-white lg:grid-cols-2">
        <!-- Left: brand + illustration (reference: light grey panel) -->
        <div
            class="order-2 flex flex-col bg-[#F7F7F7] px-6 pb-10 pt-8 lg:order-1 lg:px-12 lg:pb-12 lg:pt-10"
        >
            <Link
                :href="home()"
                class="text-xl font-extrabold tracking-tight text-black"
            >
                {{ appName }}
            </Link>

            <div
                class="flex flex-1 flex-col items-center justify-center px-2 py-10 lg:py-16"
            >
                <!-- Simple illustration: door + unicorn vibe (no external asset) -->
                <div
                    class="relative flex h-[220px] w-[180px] items-center justify-center sm:h-[260px] sm:w-[220px]"
                    aria-hidden="true"
                >
                    <div
                        class="absolute inset-0 rounded-3xl bg-gradient-to-b from-[#FF4FA8] to-[#E91E8C] shadow-lg"
                    />
                    <div
                        class="absolute inset-[12%] rounded-2xl bg-black/20"
                    />
                    <span
                        class="relative z-10 text-7xl drop-shadow-md sm:text-8xl"
                        >🦄</span
                    >
                </div>

                <h1
                    class="mt-8 max-w-md text-center text-2xl font-black leading-tight tracking-tight text-black sm:text-3xl"
                >
                    Sign up for {{ appName }}
                </h1>
                <p
                    class="mt-3 max-w-md text-center text-[15px] font-medium text-neutral-600 sm:text-base"
                >
                    Here for the first time? Let’s get you settled in
                </p>
            </div>

            <Link
                :href="home()"
                class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 transition hover:text-blue-700"
            >
                <span aria-hidden="true" class="text-lg leading-none">‹</span>
                Back
            </Link>
        </div>

        <!-- Right: social sign-up -->
        <div
            class="order-1 flex flex-col bg-white px-6 pb-10 pt-8 lg:order-2 lg:px-14 lg:pb-12 lg:pt-10"
        >
            <div
                class="flex items-center justify-end gap-3 text-sm text-neutral-700"
            >
                <span
                    class="inline-flex items-center gap-1.5 text-neutral-600"
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    English
                </span>
                <Link
                    :href="login()"
                    class="rounded-full border border-blue-600 px-4 py-2 text-xs font-bold uppercase tracking-wide text-blue-600 transition hover:bg-blue-50"
                >
                    Sign in
                </Link>
            </div>

            <div
                class="mx-auto flex w-full max-w-[400px] flex-1 flex-col justify-center py-8 lg:py-12"
            >
                <div class="flex flex-col gap-3">
                    <a
                        href="/auth/google"
                        class="flex min-h-[52px] items-center justify-center gap-3 rounded-xl border border-neutral-300 bg-white px-4 text-sm font-semibold text-neutral-800 transition hover:bg-neutral-50"
                    >
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path
                                fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            />
                            <path
                                fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            />
                            <path
                                fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                            />
                            <path
                                fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            />
                        </svg>
                        Continue with Google
                    </a>
                    <a
                        href="#"
                        class="flex min-h-[52px] items-center justify-center gap-3 rounded-xl border border-neutral-300 bg-white px-4 text-sm font-semibold text-neutral-800 transition hover:bg-neutral-50"
                        @click.prevent
                    >
                        <svg
                            class="h-5 w-5 text-[#1877F2]"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                            />
                        </svg>
                        Continue with Facebook
                    </a>
                </div>

                <p
                    class="mt-6 text-center text-xs leading-relaxed text-neutral-500"
                >
                    By signing up, you agree to {{ appName }}’s
                    <a href="#" class="font-medium text-blue-600 hover:underline"
                        >Terms of Service</a
                    >
                    and
                    <a href="#" class="font-medium text-blue-600 hover:underline"
                        >Privacy Policy</a
                    >.
                </p>
            </div>
        </div>
    </div>
</template>
