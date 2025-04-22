import { useUserStore } from "@/stores/users"

/**
 * Retrieves users from the data store or fetches them from the backend (if necessary)
 * 
 * @param {import('axios').Axios} httpClient 
 * @returns {Promise<object[]|Error>}
 */
async function getUsers(httpClient) {
    const userStore = useUserStore();
    if (userStore.users.length > 0) {
        return Promise.resolve(userStore.users);
    }

    try {
        const response = await httpClient.get('/user');
        if (response.data instanceof Array && response.data.length > 0) {
            userStore.setUsers(response.data);
        }
    } catch (error) {
        return Promise.reject(error);
    }

    return Promise.resolve(userStore.users);
}

export { getUsers };