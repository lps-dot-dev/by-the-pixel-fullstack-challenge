<script setup>
import { useLayout } from '@/layout/composables/layout';
import AppConfigurator from './AppConfigurator.vue';

const { toggleMenu, toggleDarkMode, isDarkTheme } = useLayout();
</script>

<template>
    <div class="layout-topbar">
        <div class="layout-topbar-logo-container">
            <button class="layout-menu-button layout-topbar-action" @click="toggleMenu">
                <i class="pi pi-bars"></i>
            </button>
            <router-link to="/" class="layout-topbar-logo">
                <svg class="page-nav__logo" width="120" height="29" viewBox="0 0 120 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_3136_2816)">
                    <path d="M19.6838 2.28663C18.7736 1.51412 17.7092 0.942461 16.506 0.571657C15.2873 0.185402 14.0069 0 12.6494 0H0V29H5.78481V18.7256H12.1558C13.6367 18.7256 15.0251 18.5557 16.3054 18.1849C17.5858 17.8295 18.6965 17.2579 19.6375 16.4853C20.5785 15.7128 21.3035 14.724 21.828 13.5189C22.3525 12.3138 22.6147 10.8769 22.6147 9.19286C22.6147 7.64784 22.3525 6.30368 21.828 5.16036C21.3035 4.01705 20.5939 3.05914 19.6838 2.28663ZM12.526 13.0863H5.78481V5.63932H12.4489C13.7447 5.63932 14.7782 5.93287 15.5341 6.53543C16.29 7.12254 16.6602 8.065 16.6602 9.33191C16.6602 11.8348 15.2873 13.0863 12.526 13.0863Z" fill="var(--primary-color)"></path>
                    <path d="M33.7516 0.015625H27.9668V28.9693H33.7516V0.015625Z" fill="var(--primary-color)"></path>
                    <path d="M78.6422 16.3001H86.9415V10.6763H78.6422V5.67039H92.9268V0.015625H72.8574V28.9538H93.5439V23.2991H78.6422V16.3001Z" fill="var(--primary-color)"></path>
                    <path d="M105.515 0.015625H99.7461V28.9538H120.001V23.2527H105.515V0.015625Z" fill="var(--primary-color)"></path>
                    <path d="M52.0472 7.07619C52.0009 6.99893 47.9593 0 47.9593 0H41.0638L45.4448 7.07619H52.0472ZM65.5142 0.0154502H58.6187C58.6187 0.0154502 54.5925 6.99893 54.5462 7.09164H61.1178L65.5142 0.0154502ZM54.5462 21.2595C54.5925 21.3367 59.0198 28.9536 59.0198 28.9536H65.9153L61.1332 21.2595H54.5462ZM40.6627 28.9536H47.5582C47.5582 28.9536 51.677 22.0011 51.9546 21.4758C52.0009 21.3985 52.0318 21.3367 52.0626 21.2595H45.4294L40.6627 28.9536ZM43.3468 18.6329C43.7633 18.1076 43.9793 17.3506 43.9793 16.3926C43.9793 15.2339 43.5628 14.4459 42.7452 14.0442C43.5165 13.6425 43.9022 12.9009 43.9022 11.8348C43.9022 9.99627 43.1 9.06926 41.4957 9.06926H39.7988V19.4363H41.4957C42.2979 19.4209 42.9303 19.1737 43.3468 18.6329ZM41.0946 10.3207H41.542C42.2207 10.3207 42.5601 10.8306 42.5601 11.8503C42.5601 12.9164 42.2053 13.4571 41.5266 13.4571H41.0946V10.3207ZM41.0946 14.6468H41.4803C42.267 14.6468 42.6527 15.2339 42.6527 16.3926C42.6527 17.0107 42.5601 17.4742 42.3904 17.7523C42.2207 18.0304 41.9276 18.1694 41.5266 18.1694H41.0946V14.6468ZM47.8667 19.4209V14.8631L49.6253 9.05381H48.2061L47.6045 11.6649C47.4193 12.4838 47.2959 13.1636 47.2188 13.6889C47.1262 12.9782 47.0028 12.391 46.8023 11.6649L46.1235 9.05381H44.6272L46.5401 14.9403V19.4209H47.8667Z" fill="var(--primary-color)"></path>
                    <path d="M55.9822 9.06836H51.7246V10.3662H53.1901V19.42H54.5167V10.3662H55.9822V9.06836Z" fill="var(--primary-color)"></path>
                    <path d="M61.5963 19.42V9.06836H60.2542V13.2245H58.5727V9.06836H57.2461V19.42H58.5727V14.5223H60.2542V19.42H61.5963Z" fill="var(--primary-color)"></path>
                    <path d="M65.053 14.615H66.8116V13.3172H65.053V10.3662H67.3206V9.06836H63.7109V19.42H67.5058V18.1222H65.053V14.615Z" fill="var(--primary-color)"></path>
                    </g>
                    <defs>
                    <clipPath id="clip0_3136_2816">
                    <rect width="120" height="29" fill="white"></rect>
                    </clipPath>
                    </defs>
                </svg>

                <span>WEATHER</span>
            </router-link>
        </div>

        <div class="layout-topbar-actions">
            <div class="layout-config-menu">
                <button type="button" class="layout-topbar-action" @click="toggleDarkMode">
                    <i :class="['pi', { 'pi-moon': isDarkTheme, 'pi-sun': !isDarkTheme }]"></i>
                </button>
                <div class="relative">
                    <button
                        v-styleclass="{ selector: '@next', enterFromClass: 'hidden', enterActiveClass: 'animate-scalein', leaveToClass: 'hidden', leaveActiveClass: 'animate-fadeout', hideOnOutsideClick: true }"
                        type="button"
                        class="layout-topbar-action layout-topbar-action-highlight"
                    >
                        <i class="pi pi-palette"></i>
                    </button>
                    <AppConfigurator />
                </div>
            </div>

            <button
                class="layout-topbar-menu-button layout-topbar-action"
                v-styleclass="{ selector: '@next', enterFromClass: 'hidden', enterActiveClass: 'animate-scalein', leaveToClass: 'hidden', leaveActiveClass: 'animate-fadeout', hideOnOutsideClick: true }"
            >
                <i class="pi pi-ellipsis-v"></i>
            </button>

            <div class="layout-topbar-menu hidden lg:block">
                <div class="layout-topbar-menu-content">
                    <button type="button" class="layout-topbar-action">
                        <i class="pi pi-calendar"></i>
                        <span>Calendar</span>
                    </button>
                    <button type="button" class="layout-topbar-action">
                        <i class="pi pi-inbox"></i>
                        <span>Messages</span>
                    </button>
                    <button type="button" class="layout-topbar-action">
                        <i class="pi pi-user"></i>
                        <span>Profile</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
