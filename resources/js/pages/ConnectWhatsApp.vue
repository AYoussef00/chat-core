<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { dashboard } from '@/routes';

declare global {
    interface Window {
        FB?: {
            init: (options: Record<string, unknown>) => void;
            login: (
                callback: (response: { authResponse?: { code?: string } }) => void,
                options: Record<string, unknown>,
            ) => void;
        };
        fbAsyncInit?: () => void;
    }
}

const props = defineProps<{
    metaAppId?: string | null;
    embeddedSignupConfigId?: string | null;
    connections: Array<{
        id: number;
        waba_id: string;
        phone_number_id: string;
        display_phone_number: string | null;
        verified_name: string | null;
        status: string;
        updated_at: string;
    }>;
}>();

const sdkLoaded = ref(false);
const launching = ref(false);
const pendingSessionInfo = ref<{ waba_id: string; phone_number_id: string } | null>(null);
const errorMessage = ref<string | null>(null);

const hasSdkConfig = computed(() => Boolean(props.metaAppId && props.embeddedSignupConfigId));

const onMessage = (event: MessageEvent) => {
    if (event.origin !== 'https://www.facebook.com' && event.origin !== 'https://web.facebook.com') {
        return;
    }

    try {
        const data = typeof event.data === 'string' ? JSON.parse(event.data) : event.data;

        if (data?.type !== 'WA_EMBEDDED_SIGNUP') {
            return;
        }

        if (data.event === 'FINISH' || data.event === 'FINISH_WHATSAPP_BUSINESS_APP_ONBOARDING') {
            pendingSessionInfo.value = {
                waba_id: String(data.data?.waba_id ?? ''),
                phone_number_id: String(data.data?.phone_number_id ?? ''),
            };
        }
    } catch {
        // Ignore non-JSON postMessage events.
    }
};

const loadSdk = () => {
    if (document.getElementById('facebook-jssdk')) {
        sdkLoaded.value = true;
        return;
    }

    window.fbAsyncInit = () => {
        window.FB?.init({
            appId: props.metaAppId,
            cookie: true,
            xfbml: false,
            version: 'v21.0',
        });

        sdkLoaded.value = true;
    };

    const script = document.createElement('script');
    script.id = 'facebook-jssdk';
    script.async = true;
    script.defer = true;
    script.crossOrigin = 'anonymous';
    script.src = 'https://connect.facebook.net/en_US/sdk.js';
    document.head.appendChild(script);
};

const launchEmbeddedSignup = () => {
    errorMessage.value = null;

    if (!hasSdkConfig.value) {
        errorMessage.value = 'WhatsApp Embedded Signup is not configured yet.';
        return;
    }

    if (!window.FB) {
        errorMessage.value = 'Meta SDK is still loading. Please try again.';
        return;
    }

    launching.value = true;
    pendingSessionInfo.value = null;

    window.FB.login(
        (response) => {
            const code = response.authResponse?.code;
            const sessionInfo = pendingSessionInfo.value;

            if (!code || !sessionInfo?.waba_id || !sessionInfo?.phone_number_id) {
                launching.value = false;
                errorMessage.value = 'Meta signup did not return complete WhatsApp connection data.';
                return;
            }

            router.post('/channels/connect/whatsapp', {
                code,
                waba_id: sessionInfo.waba_id,
                phone_number_id: sessionInfo.phone_number_id,
            }, {
                preserveScroll: true,
                onFinish: () => {
                    launching.value = false;
                },
            });
        },
        {
            config_id: props.embeddedSignupConfigId,
            response_type: 'code',
            override_default_response_type: true,
            extras: {
                version: 'v3',
                sessionInfoVersion: '3',
                featureType: 'whatsapp_business_app_onboarding',
            },
        },
    );
};

onMounted(() => {
    window.addEventListener('message', onMessage);

    if (hasSdkConfig.value) {
        loadSdk();
    }
});

onBeforeUnmount(() => {
    window.removeEventListener('message', onMessage);
});
</script>

<template>
    <Head title="Connect WhatsApp" />

    <div class="flex min-h-screen flex-col bg-white text-neutral-900 antialiased lg:flex-row">
        <aside class="flex flex-col bg-[#F9F9F9] px-6 py-8 lg:w-[42%] lg:min-h-screen lg:shrink-0 lg:px-10 lg:py-10">
            <div class="mb-8 text-lg font-bold tracking-tight text-neutral-950">
                Manychat
            </div>

            <div class="flex flex-1 flex-col items-center justify-center text-center lg:px-4">
                <div class="mb-8 flex size-24 items-center justify-center rounded-3xl bg-[#25D366] text-white">
                    <svg class="size-12" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884" />
                    </svg>
                </div>

                <h1 class="mb-3 max-w-md text-2xl font-bold leading-tight text-neutral-950 sm:text-3xl">
                    Connect WhatsApp
                </h1>
                <p class="max-w-sm text-sm leading-relaxed text-neutral-500">
                    Use Meta Embedded Signup to connect your WhatsApp Business Account and phone number in one flow.
                </p>
            </div>

            <div class="mt-8 lg:mt-auto">
                <Link :href="dashboard.url()" class="inline-flex items-center gap-1 text-sm font-medium text-[#007BFF] transition hover:text-[#0066DD]">
                    <ChevronLeft class="size-4" stroke-width="2" />
                    Back
                </Link>
            </div>
        </aside>

        <main class="flex flex-1 flex-col justify-center bg-white px-6 py-12 lg:px-12 lg:py-16">
            <div class="mx-auto w-full max-w-2xl">
                <div class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-neutral-950">Embedded Signup</h2>
                    <p class="mt-2 text-sm leading-relaxed text-neutral-500">
                        This is the recommended SaaS flow for WhatsApp Cloud API. Your customer connects their Business account, WABA, and phone number directly through Meta.
                    </p>

                    <div v-if="errorMessage" class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ errorMessage }}
                    </div>

                    <div v-if="!hasSdkConfig" class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                        Add `WHATSAPP_APP_ID`, `WHATSAPP_APP_SECRET`, and `WHATSAPP_EMBEDDED_SIGNUP_CONFIG_ID` to enable this flow.
                    </div>

                    <button
                        type="button"
                        :disabled="!hasSdkConfig || !sdkLoaded || launching"
                        class="mt-6 inline-flex h-11 items-center justify-center rounded-md bg-[#25D366] px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1fbb59] disabled:cursor-not-allowed disabled:opacity-60"
                        @click="launchEmbeddedSignup"
                    >
                        {{ launching ? 'Connecting...' : 'Connect with Meta' }}
                    </button>
                </div>

                <div class="mt-8 rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-neutral-950">Connected Numbers</h3>

                    <div v-if="connections.length === 0" class="mt-4 text-sm text-neutral-500">
                        No WhatsApp numbers connected yet.
                    </div>

                    <div v-else class="mt-4 space-y-3">
                        <div
                            v-for="connection in connections"
                            :key="connection.id"
                            class="flex items-center justify-between rounded-xl border border-neutral-200 bg-neutral-50 px-4 py-4"
                        >
                            <div>
                                <p class="font-semibold text-neutral-900">
                                    {{ connection.verified_name || 'WhatsApp Business' }}
                                </p>
                                <p class="mt-1 text-sm text-neutral-500">
                                    {{ connection.display_phone_number || connection.phone_number_id }}
                                </p>
                            </div>
                            <span
                                class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide"
                                :class="
                                    connection.status === 'active'
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : connection.status === 'error'
                                          ? 'bg-red-100 text-red-700'
                                          : 'bg-neutral-200 text-neutral-700'
                                "
                            >
                                {{ connection.status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

