<script>
// Dependencies
import { inject } from 'vue';
import { getUsers } from '@/service/UserService';

// Components
import Skeleton from 'primevue/skeleton';

export default {
    inject: ['backendHttpClient'],
    data: () => ({
        failedLoadingUsers: false,
        isLoadingUsers: false,
        users: []
    }),
    methods: {
        fetchUsers() {
            this.failedLoadingUsers = false;
            this.isLoadingUsers = true;
            getUsers(this.backendHttpClient)
                .then(response => {
                    this.users = response;
                })
                .catch(error => {
                    console.error(error);
                    this.failedLoadingUsers = true;
                })
                .finally(() => {
                    this.isLoadingUsers = false;
                });
        }
    },
    created() {
        this.fetchUsers();
    }
};
</script>

<template>
    <div class="col-span-12 lg:col-span-6 xl:col-span-3">
        <div class="card mb-0">
            <div class="flex justify-between mb-4">
                <div>
                    <span class="block text-muted-color font-medium mb-4">Users</span>
                    <template v-if="isLoadingUsers">
                        <Skeleton width="2rem" height="1.5rem"></Skeleton>
                    </template>
                    <template v-else>
                        <div class="text-surface-900 dark:text-surface-0 font-medium text-xl">{{ users.length }}</div>
                    </template>
                </div>
                <div class="flex items-center justify-center bg-blue-100 dark:bg-blue-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                    <i class="pi pi-user text-blue-500 !text-xl"></i>
                </div>
            </div>
            <template v-if="isLoadingUsers">
                <Skeleton width="100%"></Skeleton>
            </template>
            <template v-else-if="!isLoadingUsers && failedLoadingUsers">
                <span class="text-primary font-medium">Unable to load users! </span>
            </template>
            <template v-else>
                <span class="text-primary font-medium">No new </span>
                <span class="text-muted-color">since last visit</span>
            </template>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-6 xl:col-span-3">
        <div class="card mb-0">
            <div class="flex justify-between mb-4">
                <div>
                    <span class="block text-muted-color font-medium mb-4">Revenue</span>
                    <div class="text-surface-900 dark:text-surface-0 font-medium text-xl">$2.100</div>
                </div>
                <div class="flex items-center justify-center bg-orange-100 dark:bg-orange-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                    <i class="pi pi-dollar text-orange-500 !text-xl"></i>
                </div>
            </div>
            <span class="text-primary font-medium">%52+ </span>
            <span class="text-muted-color">since last week</span>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-6 xl:col-span-3">
        <div class="card mb-0">
            <div class="flex justify-between mb-4">
                <div>
                    <span class="block text-muted-color font-medium mb-4">Customers</span>
                    <div class="text-surface-900 dark:text-surface-0 font-medium text-xl">28441</div>
                </div>
                <div class="flex items-center justify-center bg-cyan-100 dark:bg-cyan-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                    <i class="pi pi-users text-cyan-500 !text-xl"></i>
                </div>
            </div>
            <span class="text-primary font-medium">520 </span>
            <span class="text-muted-color">newly registered</span>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-6 xl:col-span-3">
        <div class="card mb-0">
            <div class="flex justify-between mb-4">
                <div>
                    <span class="block text-muted-color font-medium mb-4">Comments</span>
                    <div class="text-surface-900 dark:text-surface-0 font-medium text-xl">152 Unread</div>
                </div>
                <div class="flex items-center justify-center bg-purple-100 dark:bg-purple-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                    <i class="pi pi-comment text-purple-500 !text-xl"></i>
                </div>
            </div>
            <span class="text-primary font-medium">85 </span>
            <span class="text-muted-color">responded</span>
        </div>
    </div>
</template>
