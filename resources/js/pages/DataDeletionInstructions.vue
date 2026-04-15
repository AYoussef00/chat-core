<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const appName = computed(() => (page.props.name as string) || 'Chat Core');
</script>

<template>
    <Head title="User Data Deletion Instructions" />

    <div class="min-h-screen bg-[#F7F8FA] px-4 py-10 text-neutral-900 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-3xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5 sm:p-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-xl font-semibold text-neutral-950 font-sans sm:text-2xl">
                    User Data Deletion Instructions
                </h1>
                <Link
                    href="/"
                    class="text-sm font-medium text-blue-600 transition hover:text-blue-700 font-sans"
                >
                    Back to Home
                </Link>
            </div>

            <p class="text-sm leading-6 text-neutral-700 font-sans sm:text-base">
                If you want your data removed from <strong>{{ appName }}</strong>,
                follow the steps below.
            </p>

            <ol class="mt-5 list-decimal space-y-3 pl-5 text-sm leading-6 text-neutral-700 font-sans sm:text-base">
                <li>
                    Send a deletion request to:
                    <a
                        href="mailto:support@itap-tech.com?subject=User%20Data%20Deletion%20Request"
                        class="font-medium text-blue-600 hover:underline"
                    >
                        support@itap-tech.com
                    </a>
                </li>
                <li>
                    Include the email address used in your account and mention
                    that this request is for Facebook-connected data deletion.
                </li>
                <li>
                    Our team verifies ownership and processes the deletion
                    request.
                </li>
                <li>
                    Your account data and related Facebook integration data are
                    deleted from our systems, except records we must keep for
                    legal compliance.
                </li>
            </ol>

            <p class="mt-6 text-sm leading-6 text-neutral-600 font-sans sm:text-base">
                Expected processing time: up to 7 business days.
            </p>
        </div>
    </div>
</template>
