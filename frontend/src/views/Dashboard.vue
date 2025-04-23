<script setup>
import NotificationsWidget from '@/components/dashboard/NotificationsWidget.vue';
import UserWeatherWidget from '@/components/dashboard/UserWeatherWidget.vue';
import StatsWidget from '@/components/dashboard/StatsWidget.vue';
</script>

<template>
    <div class="grid grid-cols-12 gap-8">
        <div class="col-span-12 xl:col-span-8 grid grid-cols-12 gap-8">
            <StatsWidget />
            <UserWeatherWidget />
        </div>
        <div class="col-span-12 xl:col-span-4">
            <NotificationsWidget />
        </div>
    </div>
</template>
