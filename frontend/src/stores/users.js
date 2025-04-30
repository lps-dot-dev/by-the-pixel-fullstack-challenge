import { ref } from "vue";
import { defineStore } from "pinia";

export const useUserStore = defineStore("user", () => {
  const users = ref([]);
  const count = ref(0);

  /** @param {object} user */
  function addUser(user) {
    users.value.push(user);
  }

  /** @param {object[]} newUsers */
  function setUsers(newUsers) {
    users.value = newUsers;
  }

  function setUserCount(newCount) {
    count.value = newCount;
  }

  return { count, users, addUser, setUsers, setUserCount };
});
