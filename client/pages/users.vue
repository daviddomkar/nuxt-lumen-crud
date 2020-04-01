<template>
  <v-card class="card" color="#272727" elevation="8">
    <v-data-table
      color="primary"
      :headers="headers"
      :items="Object.values(users)"
      :loading="usersLoading || roomsLoading"
      loading-text="Loading data..."
      hide-default-footer
      @click:row="handleClick"
    >
      <template v-slot:top>
        <v-toolbar flat color="#272727">
          <v-toolbar-title>Users</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn
            v-if="isAdmin"
            color="primary"
            dark
            @click.stop="newUser($refs.userDialog)"
            >New User</v-btn
          >
          <v-dialog
            v-if="isAdmin"
            v-model="dialog"
            max-width="720px"
            @click:outside="closeDialog"
          >
            <user-dialog
              ref="userDialog"
              :edited-user="editedUser"
              @close="closeDialog"
            />
          </v-dialog>
          <v-dialog
            v-if="isAdmin"
            v-model="deleteDialog"
            max-width="500px"
            @click:outside="closeDeleteDialog"
          >
            <user-delete-dialog
              :edited-user="editedUser"
              @close="closeDeleteDialog"
            />
          </v-dialog>
        </v-toolbar>
      </template>

      <template v-slot:item.room="{ item }">
        {{ rooms[item.room_id].name }}
      </template>

      <template v-slot:item.salary="{ value }">
        {{ value | currency }}
      </template>

      <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click.stop="editUser(item)">
          {{ mdiPencil }}
        </v-icon>
        <v-icon small @click.stop="deleteUser(item)">
          {{ mdiDelete }}
        </v-icon>
      </template>
    </v-data-table>
  </v-card>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from '@vue/composition-api';
import { mdiPencil, mdiDelete } from '@mdi/js';
import { getUsers, getRooms } from '@/utils/api';
import authStore from '@/stores/auth';
import usersStore, { User } from '@/stores/users';
import roomsStore from '@/stores/rooms';

import UserDialog from '@/components/UserDialog.vue';
import UserDeleteDialog from '@/components/UserDeleteDialog.vue';

export default defineComponent({
  components: {
    UserDialog,
    UserDeleteDialog,
  },
  // @ts-ignore
  head() {
    return {
      title: 'Users',
    };
  },
  data() {
    return {
      mdiPencil,
      mdiDelete,
    };
  },
  setup(_, { root }) {
    const {
      setLoadState: setUsersLoadState,
      loading: usersLoading,
      setUsers,
      users,
    } = usersStore.useProvider();
    const {
      setLoadState: setRoomsLoadState,
      loading: roomsLoading,
      setRooms,
      rooms,
    } = roomsStore.useProvider();
    const { token, isAdmin } = authStore.useConsumer();

    const headers: Array<any> = [
      {
        text: 'First name',
        align: 'start',
        value: 'first_name',
        width: 200,
      },
      {
        text: 'Last name',
        value: 'last_name',
        width: 200,
      },
      {
        text: 'Room',
        value: 'room',
        width: 200,
      },
      {
        text: 'Position',
        value: 'position',
        width: 200,
      },
      {
        text: 'Salary',
        value: 'salary',
        width: 200,
      },
    ];

    if (isAdmin.value) {
      headers.push({
        text: 'Actions',
        value: 'actions',
        sortable: false,
      });
    }

    const dialog = ref(false);
    const deleteDialog = ref(false);
    const editedUser = ref<User | null>(null);

    setUsersLoadState(false, true);
    setRoomsLoadState(false, true);

    onMounted(() => {
      loadRooms().then(loadUsers);
    });

    async function loadUsers() {
      if (token.value) {
        try {
          const users = (await getUsers(token.value)).data;

          setUsers(users);

          setUsersLoadState(true, false);
        } catch (e) {
          root.$router.replace('/login');
        }
      } else {
        root.$router.replace('/login');
      }
    }

    async function loadRooms() {
      if (token.value) {
        try {
          const rooms = (await getRooms(token.value)).data;

          setRooms(rooms);

          setRoomsLoadState(true, false);
        } catch (e) {
          root.$router.replace('/login');
        }
      } else {
        root.$router.replace('/login');
      }
    }

    function newUser(dialogToReset: any) {
      editedUser.value = null;
      dialog.value = true;
      if (dialogToReset) {
        dialogToReset.$refs.observer.reset();
      }
    }

    function editUser(user: User) {
      editedUser.value = user;
      dialog.value = true;
    }

    function deleteUser(user: User) {
      editedUser.value = user;
      deleteDialog.value = true;
    }

    function closeDialog() {
      dialog.value = false;
    }

    function closeDeleteDialog() {
      deleteDialog.value = false;
    }

    function handleClick(user: User) {
      root.$router.replace('/user?id=' + user.id);
    }

    return {
      headers,
      roomsLoading,
      usersLoading,
      users,
      rooms,
      isAdmin,
      dialog,
      deleteDialog,
      editedUser,
      newUser,
      editUser,
      deleteUser,
      closeDialog,
      closeDeleteDialog,
      handleClick,
    };
  },
});
</script>
