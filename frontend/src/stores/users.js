import { ref, computed } from "vue";
import { defineStore } from "pinia";

export const useUserStore = defineStore("user", () => {
  const users = ref([]);
  const count = computed(() => users.value.length);

  /** @param {object} user */
  function addUser(user) {
    users.value.push(user);
  }

  /** @param {object[]} newUsers */
  function setUsers(newUsers) {
    users.value = newUsers;
  }

  return { count, users, addUser, setUsers };
});
