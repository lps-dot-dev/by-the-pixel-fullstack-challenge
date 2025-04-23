import { ref } from "vue";
import { useUserStore } from "@/stores/users"

export function useUserService() {
    const failedLoadingUsers = ref(false);
    const isLoadingUsers = ref(false);

    /**
     * Retrieves users from the data store or fetches them from the backend (if necessary)
     * 
     * @param {import('axios').Axios} httpClient 
     * @returns {Promise<object[]|Error>}
     */
    async function getUsers(httpClient) {
        failedLoadingUsers.value = false;
        isLoadingUsers.value = true;

        const userStore = useUserStore();
        if (userStore.users.length > 0) {
            isLoadingUsers.value = true;
            return Promise.resolve(userStore.users);
        }

        try {
            const response = await httpClient.get('/user');
            if (response.data instanceof Array && response.data.length > 0) {
                userStore.setUsers(response.data);
            }
        } catch (error) {
            failedLoadingUsers.value = true;
            isLoadingUsers.value = false;
            return Promise.reject(error);
        }

        isLoadingUsers.value = false;
        return Promise.resolve(userStore.users);
    }

    return {
        failedLoadingUsers,
        isLoadingUsers,
        getUsers
    };
}
