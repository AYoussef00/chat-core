<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import { computed } from 'vue';
import ChannelEmptyIllustration from '@/components/ChannelEmptyIllustration.vue';
import { dashboard } from '@/routes';
import { connect } from '@/routes/channels';

const page = usePage();
const appName = computed(() => (page.props.name as string) || 'Manychat');

const platforms = [
    {
        id: 'instagram',
        title: 'Instagram',
        description:
            'Supercharge your social media marketing with Instagram Automation.',
    },
    {
        id: 'tiktok',
        title: 'TikTok',
        description:
            "Elevate your marketing with TikTok's seamless automation.",
    },
    {
        id: 'whatsapp',
        title: 'WhatsApp',
        description:
            'Reach a global audience on the most popular messaging app.',
    },
    {
        id: 'messenger',
        title: 'Facebook Messenger',
        description:
            'Create Facebook Messenger automation to keep customers happy.',
    },
    {
        id: 'telegram',
        title: 'Telegram',
        description: 'Power up your business with Telegram automation.',
    },
] as const;
</script>

<template>
    <Head title="Connect a channel" />

    <div
        class="flex min-h-screen flex-col bg-white text-neutral-900 antialiased lg:flex-row"
    >
        <!-- Left: intro -->
        <aside
            class="flex flex-col bg-[#F9F9F9] px-6 py-8 lg:w-[42%] lg:min-h-screen lg:shrink-0 lg:px-10 lg:py-10"
        >
            <div class="mb-8 text-lg font-bold tracking-tight text-neutral-950">
                {{ appName }}
            </div>

            <div
                class="flex flex-1 flex-col items-center justify-center text-center lg:px-4"
            >
                <div class="mb-8 w-full max-w-[220px]">
                    <ChannelEmptyIllustration />
                </div>

                <h1
                    class="mb-3 max-w-md text-2xl font-bold leading-tight text-neutral-950 sm:text-3xl"
                >
                    Where would you like to start?
                </h1>
                <p class="max-w-sm text-sm leading-relaxed text-neutral-500">
                    Don't worry, you can connect other channels later.
                </p>
            </div>

            <div class="mt-8 lg:mt-auto">
                <Link
                    :href="dashboard.url()"
                    class="inline-flex items-center gap-1 text-sm font-medium text-[#007BFF] transition hover:text-[#0066DD]"
                >
                    <ChevronLeft class="size-4" stroke-width="2" />
                    Back
                </Link>
            </div>
        </aside>

        <!-- Right: platform cards -->
        <main
            class="flex flex-1 flex-col justify-center bg-white px-4 py-8 lg:px-12 lg:py-12 xl:px-16"
        >
            <ul class="mx-auto flex w-full max-w-xl flex-col gap-4">
                <li v-for="p in platforms" :key="p.id">
                    <component
                        :is="p.id === 'messenger' ? Link : 'button'"
                        v-bind="
                            p.id === 'messenger'
                                ? { href: connect.messenger.url() }
                                : { type: 'button' }
                        "
                        class="flex w-full items-start gap-4 rounded-xl border border-neutral-100 bg-white p-5 text-left shadow-md transition hover:border-blue-200 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"
                    >
                        <!-- Instagram -->
                        <span
                            v-if="p.id === 'instagram'"
                            class="flex size-12 shrink-0 items-center justify-center"
                            aria-hidden="true"
                        >
                            <svg
                                class="size-12"
                                viewBox="0 0 48 48"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <defs>
                                    <linearGradient
                                        id="ig-grad"
                                        x1="0%"
                                        y1="100%"
                                        x2="100%"
                                        y2="0%"
                                    >
                                        <stop
                                            offset="0%"
                                            stop-color="#f09433"
                                        />
                                        <stop
                                            offset="0.5"
                                            stop-color="#e6683c"
                                        />
                                        <stop
                                            offset="1"
                                            stop-color="#bc1888"
                                        />
                                    </linearGradient>
                                </defs>
                                <rect
                                    width="48"
                                    height="48"
                                    rx="12"
                                    fill="url(#ig-grad)"
                                />
                                <path
                                    fill="#fff"
                                    d="M24 17a7 7 0 1 0 0 14 7 7 0 0 0 0-14zm0 11.5a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9zm9-12.3a1.6 1.6 0 1 1-3.2 0 1.6 1.6 0 0 1 3.2 0z"
                                />
                            </svg>
                        </span>

                        <!-- TikTok -->
                        <span
                            v-else-if="p.id === 'tiktok'"
                            class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-black"
                            aria-hidden="true"
                        >
                            <svg
                                class="size-8 text-white"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64v-3.5a6.33 6.33 0 0 0-1.41-.16 6.37 6.37 0 1 0 6.37 6.37V8.72a8.16 8.16 0 0 0 4.77 1.55v-3.5c-.17 0-.33-.01-.5-.03z"
                                />
                            </svg>
                        </span>

                        <!-- WhatsApp -->
                        <span
                            v-else-if="p.id === 'whatsapp'"
                            class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-[#25D366]"
                            aria-hidden="true"
                        >
                            <svg
                                class="size-7 text-white"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
                                />
                            </svg>
                        </span>

                        <!-- Messenger -->
                        <span
                            v-else-if="p.id === 'messenger'"
                            class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-[#0084FF]"
                            aria-hidden="true"
                        >
                            <svg
                                class="size-8 text-white"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M12 2C6.48 2 2 6.03 2 10.89c0 2.55 1.28 4.83 3.32 6.34V22l5.18-2.85c.38.1.78.16 1.2.16 5.52 0 10-4.03 10-8.89S17.52 2 12 2zm1.07 11.48l-2.55-2.7-4.98 2.7 5.48-5.82 2.55 2.7 4.98-2.7-5.48 5.82z"
                                />
                            </svg>
                        </span>

                        <!-- Telegram -->
                        <span
                            v-else-if="p.id === 'telegram'"
                            class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-[#26A5E4]"
                            aria-hidden="true"
                        >
                            <svg
                                class="size-7 text-white"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 1.332-.61 2.292-.914 2.942-.929.065-.007.13-.015.193-.015z"
                                />
                            </svg>
                        </span>

                        <span class="min-w-0 flex-1">
                            <span
                                class="block font-semibold text-neutral-950"
                                >{{ p.title }}</span
                            >
                            <span
                                class="mt-1 block text-sm leading-snug text-neutral-500"
                                >{{ p.description }}</span
                            >
                        </span>
                    </component>
                </li>
            </ul>
        </main>
    </div>
</template>
