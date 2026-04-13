<template>
    <svg
        viewBox="0 0 280 200"
        class="h-auto w-full max-w-[280px]"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
    >
        <!-- soft grid -->
        <defs>
            <pattern
                id="msg-grid"
                width="20"
                height="20"
                patternUnits="userSpaceOnUse"
            >
                <path
                    d="M 20 0 L 0 0 0 20"
                    fill="none"
                    stroke="#E5E7EB"
                    stroke-width="0.5"
                />
            </pattern>
        </defs>
        <rect width="280" height="200" fill="#F3F4F6" rx="12" />
        <rect
            x="8"
            y="8"
            width="264"
            height="184"
            fill="url(#msg-grid)"
            rx="8"
        />

        <!-- decorative shapes -->
        <circle cx="52" cy="48" r="18" fill="#BFDBFE" opacity="0.9" />
        <rect
            x="190"
            y="28"
            width="36"
            height="36"
            rx="8"
            fill="#FED7AA"
            opacity="0.95"
        />
        <circle cx="220" cy="130" r="22" fill="#93C5FD" opacity="0.7" />
        <rect
            x="32"
            y="130"
            width="40"
            height="28"
            rx="6"
            fill="#FDBA74"
            opacity="0.85"
        />

        <!-- Messenger bubble (center) -->
        <g transform="translate(90, 58)">
            <rect
                width="100"
                height="84"
                rx="22"
                fill="#0084FF"
                filter="drop-shadow(0 4px 12px rgb(0 0 0 / 0.12))"
            />
            <!-- bolt -->
            <path
                fill="#fff"
                d="M52 18 34 48h11l-3 22 24-36H65l3-16z"
            />
        </g>
    </svg>
</template>
