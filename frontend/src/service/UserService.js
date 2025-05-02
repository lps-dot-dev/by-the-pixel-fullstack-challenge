import { ref } from "vue";
import { useUserStore } from "@/stores/users"

export function useUserService() {
    const failedLoadingUsers = ref(false);
    const isLoadingUsers = ref(false);
    const userStore = useUserStore();

    /**
     * Retrieves users from the data store or fetches them from the backend (if necessary)
     * 
     * @param {import('axios').Axios} httpClient
     * @param {Number} pageNumber
     * @returns {Promise<object[]|Error>}
     */
    async function getUsers(httpClient, pageNumber) {
        failedLoadingUsers.value = false;
        isLoadingUsers.value = true;

        try {
            const response = await httpClient.get('/user', { params: { page: pageNumber }});
            if ('data' in response.data && response.data.data instanceof Array && response.data.data.length > 0) {
                userStore.setUsers(response.data.data);
            }

            if ('total' in response.data && Number.isInteger(response.data.total)) {
                userStore.setUserCount(response.data.total);
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
