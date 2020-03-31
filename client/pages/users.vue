<template>
  <v-card class="card" color="#272727" elevation="8">
    <v-data-table
      color="primary"
      :headers="headers"
      :items="Object.values(users)"
      :loading="loading"
      loading-text="Loading data..."
      hide-default-footer
    >
      <template v-slot:top>
        <v-toolbar flat color="#272727">
          <v-toolbar-title>Users</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
      </template>
    </v-data-table>
  </v-card>
</template>

<style lang="scss" scoped>
.card {
  flex: 1;
  max-width: 1400px;
}
</style>

<script lang="ts">
import { defineComponent, onMounted } from '@vue/composition-api';
import { getUsers } from '@/utils/api';
import authStore from '@/stores/auth';
import usersStore from '@/stores/users';

export default defineComponent({
  setup(_, { root }) {
    const { setLoadState, loading, setUsers, users } = usersStore.useProvider();
    const { token } = authStore.useConsumer();

    const headers = [
      {
        text: 'First name',
        align: 'start',
        value: 'first_name',
      },
      {
        text: 'Last name',
        value: 'last_name',
      },
      {
        text: 'Position',
        value: 'position',
      },
      {
        text: 'Salary',
        value: 'salary',
      },
    ];

    setLoadState(false, true);

    onMounted(() => {
      loadUsers();
    });

    async function loadUsers() {
      if (token.value) {
        try {
          const users = (await getUsers(token.value)).data;

          setUsers(users);

          setLoadState(true, false);
        } catch (e) {
          root.$router.replace('/login');
        }
      } else {
        root.$router.replace('/login');
      }
    }

    return {
      headers,
      loading,
      users,
    };
  },
});
</script>
