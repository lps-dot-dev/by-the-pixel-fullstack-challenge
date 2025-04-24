<script>
// Dependencies
import { useToday } from '@/composables/today'
import moment from 'moment';

// Components
import { Button, Menu, SelectButton } from "primevue";

export default {
    inject: ['echo'],
    components: {
        Button,
        Menu,
        SelectButton
    },
    setup() {
        const { today } = useToday();
        return { today };
    },
    data: () => ({
        menu: null,
        items: [
            { label: 'Add New', icon: 'pi pi-fw pi-plus' },
            { label: 'Remove', icon: 'pi pi-fw pi-trash' }
        ],
        notifications: [
            { backgroundStyles: 'bg-blue-100 dark:bg-blue-400/10', iconStyles: 'pi pi-dollar !text-xl text-blue-500', message: 'Richard Jones has purchased a blue t-shirt for $79.00', createdAt: moment() },
            { backgroundStyles: 'bg-orange-100 dark:bg-orange-400/10', iconStyles: 'pi pi-download !text-xl text-orange-500', message: 'Your request for withdrawal of $2500.00 has been initiated.', createdAt: moment().subtract(1, 'hour') },
            { backgroundStyles: 'bg-blue-100 dark:bg-blue-400/10', iconStyles: 'pi pi-dollar !text-xl text-blue-500', message: 'Keyser Wick has purchased a black jacket for $59.00', createdAt: moment().subtract(1, 'day') },
            { backgroundStyles: 'bg-pink-100 dark:bg-pink-400/10', iconStyles: 'pi pi-question !text-xl text-pink-500', message: 'Jane Davis has posted a new questions about your product.', createdAt: moment().subtract(1, 'day').startOf('day') },
            { backgroundStyles: 'bg-green-100 dark:bg-green-400/10', iconStyles: 'pi pi-arrow-up !text-xl text-green-500', message: 'Your revenue has increased by %25.', createdAt: moment().subtract(1, 'week').add(1, 'hour') },
            { backgroundStyles: 'bg-purple-100 dark:bg-purple-400/10', iconStyles: 'pi pi-heart !text-xl text-purple-500', message: '12 users have added your products to their wishlist.', createdAt: moment().subtract(1, 'week')}
        ]
    }),
    computed: {
        groupByDateNotifications() {
            return this.notifications.reduce((object, notification, _) => {
                const { createdAt } = notification;
                const label = this.getLabel(createdAt);
                if (label in object && object[label] instanceof Array) {
                    object[label].push(notification);
                    return object;
                }

                object[label] = [notification];
                return object;
            }, {});
        }
    },
    methods: {
        getLabel(moment) {
            if (moment.isSame(this.today, 'day')) {
                return 'Today'
            } else if (moment.isSame(this.today.clone().subtract(1, 'day'), 'day')) {
                return 'Yesterday'
            } else if (moment.isAfter(this.today.clone().subtract(7, 'days'))) {
                return 'Last Week'
            } else {
                return moment.format('MMMM Do, YYYY')
            }
        }
    },
    mounted() {
        const weatherChannel = this.echo.channel('weather');
        
        weatherChannel.listen('.updating', (e) => {
            //
        });

        weatherChannel.listen('.updated', (e) => {
            // console.log(e);
        });
    },
    unmounted() {
        this.echo.leave('weather');
    }
};
</script>

<template>
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <div class="font-semibold text-xl">Notifications</div>
            <div>
                <Button icon="pi pi-ellipsis-v" class="p-button-text p-button-plain p-button-rounded" @click="$refs.menu.toggle($event)"></Button>
                <Menu ref="menu" popup :model="items" class="!min-w-40"></Menu>
            </div>
        </div>

        <template v-for="(notifications, label) in groupByDateNotifications" :key="label">
            <span class="block text-muted-color font-medium mb-4">{{ label.toUpperCase() }}</span>
            <ul class="p-0 mx-0 mt-0 mb-6 list-none">
                <li
                    v-for="(notification, index) in notifications"
                    :key="`${index}.${notification.message}`"
                    class="flex items-center py-2 border-surface"
                    :class="index === notifications.length - 1 ? '' : 'border-b'"
                >
                    <div
                        class="w-12 h-12 flex items-center justify-center rounded-full mr-4 shrink-0"
                        :class="notification.backgroundStyles"
                    >
                        <i :class="notification.iconStyles"></i>
                    </div>
                    <span class="text-surface-900 dark:text-surface-0 leading-normal">{{ notification.message }}</span>
                </li>
            </ul>
        </template>
    </div>
</template>
