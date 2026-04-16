import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Facebook sometimes appends "#_=_" to redirect URLs. It's harmless but can look broken.
if (window.location.hash === '#_=_') {
    history.replaceState(null, document.title, window.location.pathname + window.location.search);
}

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return null;
            case name === 'Pricing':
                return null;
            case name === 'DataDeletionInstructions':
                return null;
            case name === 'Dashboard':
                return null;
            case name === 'ConnectChannel':
                return null;
            case name === 'ConnectMessenger':
                return null;
            case name === 'auth/Register':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
