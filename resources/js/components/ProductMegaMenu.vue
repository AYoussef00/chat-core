<script setup lang="ts">
import { computed, useId } from 'vue';
import { productMenuItems } from '@/data/productMenu';

const uid = useId().replace(/[^a-zA-Z0-9]/g, '');
const igGradId = `pm-ig-${uid}`;
const msgGradId = `pm-msg-${uid}`;

const props = withDefaults(
    defineProps<{
        /** dark = hero (white text), light = yellow section (black text) */
        variant?: 'dark' | 'light';
    }>(),
    {
        variant: 'dark',
    },
);

const triggerClass = computed(() =>
    props.variant === 'dark'
        ? 'text-white/95 hover:text-white'
        : 'text-black/90 hover:text-black',
);

const items = productMenuItems;
</script>

<template>
    <div class="group relative flex items-center">
        <button
            type="button"
            class="flex items-center gap-1.5 py-2 text-[11px] font-semibold uppercase tracking-[0.12em] transition"
            :class="triggerClass"
            aria-expanded="false"
            aria-haspopup="true"
        >
            Products
            <svg
                class="h-3.5 w-3.5 shrink-0 opacity-80 transition duration-200 group-hover:rotate-180"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2.5"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M19 9l-7 7-7-7"
                />
            </svg>
        </button>

        <!-- Invisible bridge keeps hover while moving to panel -->
        <div
            class="pointer-events-none invisible absolute left-1/2 top-full z-[80] w-[min(calc(100vw-2rem),72rem)] -translate-x-1/2 pt-3 opacity-0 transition-[opacity,visibility] duration-200 group-hover:pointer-events-auto group-hover:visible group-hover:opacity-100"
        >
            <div
                class="pointer-events-auto rounded-2xl border border-neutral-200/90 bg-white p-6 shadow-[0_24px_80px_-12px_rgba(0,0,0,0.18),0_0_0_1px_rgba(0,0,0,0.04)] sm:p-8"
            >
                <div
                    class="grid grid-cols-2 gap-6 sm:grid-cols-3 sm:gap-8 lg:grid-cols-5 lg:gap-6 xl:gap-8"
                >
                    <a
                        v-for="item in items"
                        :key="item.title"
                        :href="item.href"
                        class="group/item flex flex-col items-center rounded-xl p-3 text-center transition hover:bg-neutral-50 sm:p-4"
                    >
                        <div
                            class="mb-4 flex h-14 w-14 items-center justify-center transition group-hover/item:scale-105 sm:h-16 sm:w-16"
                        >
                            <!-- Instagram -->
                            <svg
                                v-if="item.title === 'Instagram'"
                                class="h-12 w-12 sm:h-14 sm:w-14"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <defs>
                                    <linearGradient
                                        :id="igGradId"
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
                                            offset="50%"
                                            stop-color="#e6683c"
                                        />
                                        <stop
                                            offset="100%"
                                            stop-color="#bc1888"
                                        />
                                    </linearGradient>
                                </defs>
                                <path
                                    :fill="`url(#${igGradId})`"
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.334 3.608 1.308.974.974 1.246 2.242 1.308 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.334 2.633-1.308 3.608-.974.974-2.242 1.246-3.608 1.308-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.334-3.608-1.308-.974-.974-1.246-2.242-1.308-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.334-2.633 1.308-3.608.974-.974 2.242-1.246 3.608-1.308 1.266-.058 1.646-.07 4.85-.07zM12 0C8.741 0 8.332.014 7.052.072 5.775.13 4.602.39 3.608 1.384 2.614 2.378 2.354 3.551 2.296 4.828.238 6.108.224 6.517.224 12s.014 5.892.072 7.172c.058 1.277.318 2.45 1.312 3.444.994.994 2.167 1.254 3.444 1.312 1.28.058 1.689.072 7.172.072s5.892-.014 7.172-.072c1.277-.058 2.45-.318 3.444-1.312.994-.994 1.254-2.167 1.312-3.444.058-1.28.072-1.689.072-7.172s-.014-5.892-.072-7.172c-.058-1.277-.318-2.45-1.312-3.444-.994-.994-2.167-1.254-3.444-1.312C18.892.014 18.483 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"
                                />
                            </svg>
                            <!-- WhatsApp -->
                            <svg
                                v-else-if="item.title === 'WhatsApp'"
                                class="h-12 w-12 sm:h-14 sm:w-14"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path
                                    fill="#25D366"
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
                                />
                            </svg>
                            <!-- Messenger -->
                            <svg
                                v-else-if="item.title === 'Messenger'"
                                class="h-12 w-12 sm:h-14 sm:w-14"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <defs>
                                    <linearGradient
                                        :id="msgGradId"
                                        x1="0%"
                                        y1="0%"
                                        x2="100%"
                                        y2="100%"
                                    >
                                        <stop offset="0%" stop-color="#00B2FF" />
                                        <stop offset="100%" stop-color="#006AFF" />
                                    </linearGradient>
                                </defs>
                                <path
                                    :fill="`url(#${msgGradId})`"
                                    d="M12 2C6.477 2 2 6.145 2 11.252c0 2.57 1.179 4.867 3.021 6.396v3.116l2.85-1.565c.838.232 1.723.358 2.629.358 5.523 0 10-4.145 10-9.252S17.523 2 12 2zm1.193 12.428l-2.554-2.705-4.983 2.705 5.474-5.842 2.615 2.705 4.923-2.705-5.475 5.842z"
                                />
                            </svg>
                            <!-- TikTok -->
                            <svg
                                v-else-if="item.title === 'TikTok'"
                                class="h-12 w-12 sm:h-14 sm:w-14"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path
                                    fill="#000000"
                                    d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"
                                />
                            </svg>
                            <!-- Chat Core AI -->
                            <svg
                                v-else
                                class="h-12 w-12 sm:h-14 sm:w-14"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <circle cx="12" cy="13" r="7" fill="#FF6B35" />
                                <circle cx="12" cy="11" r="4" fill="#fff" opacity="0.25" />
                                <path
                                    fill="#FFB020"
                                    d="M14 5l1 2h2l-1.5 1.5L16 12l-2-1.5L12 12l.5-2.5L11 8h2l1-3z"
                                />
                                <circle cx="10" cy="12" r="1.2" fill="#1a1a1a" />
                                <circle cx="14" cy="12" r="1.2" fill="#1a1a1a" />
                            </svg>
                        </div>
                        <span
                            class="mb-2 text-[13px] font-bold leading-tight text-neutral-950 sm:text-sm"
                        >
                            {{ item.title }}
                        </span>
                        <span
                            class="max-w-[11rem] text-[11px] leading-snug text-neutral-500 sm:max-w-none sm:text-xs"
                        >
                            {{ item.description }}
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
