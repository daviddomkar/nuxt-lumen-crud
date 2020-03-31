<template>
  <v-card class="card" color="#272727" elevation="8">
    <v-data-table
      color="primary"
      :headers="headers"
      :items="Object.values(rooms)"
      :loading="loading"
      loading-text="Loading data..."
      hide-default-footer
    >
      <template v-slot:top>
        <v-toolbar flat color="#272727">
          <v-toolbar-title>Rooms</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn color="primary" dark @click.stop="newRoom">New Room</v-btn>
          <v-dialog
            v-if="isAdmin"
            v-model="dialog"
            max-width="500px"
            @click:outside="closeDialog"
          >
            <room-dialog :edited-room="editedRoom" @close="closeDialog" />
          </v-dialog>
        </v-toolbar>
      </template>

      <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click.stop="editRoom(item)">
          {{ mdiPencil }}
        </v-icon>
        <v-icon small @click.stop="deleteRoom(item)">
          {{ mdiDelete }}
        </v-icon>
      </template>
    </v-data-table>
  </v-card>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from '@vue/composition-api';
import { mdiClose, mdiPencil, mdiDelete } from '@mdi/js';
import { getRooms } from '@/utils/api';
import authStore from '@/stores/auth';
import roomsStore, { Room } from '@/stores/rooms';

import RoomDialog from '@/components/RoomDialog.vue';

export default defineComponent({
  components: {
    RoomDialog,
  },
  data() {
    return {
      mdiClose,
      mdiPencil,
      mdiDelete,
    };
  },
  setup(_, { root }) {
    const { setLoadState, loading, setRooms, rooms } = roomsStore.useProvider();
    const { token, isAdmin } = authStore.useConsumer();

    const headers: Array<any> = [
      {
        text: 'Number',
        align: 'start',
        value: 'number',
        width: 200,
      },
      {
        text: 'Name',
        value: 'name',
        width: 200,
      },
      {
        text: 'Phone',
        value: 'phone',
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
    const editedRoom = ref<Room | null>(null);

    setLoadState(false, true);

    onMounted(() => {
      loadRooms();
    });

    async function loadRooms() {
      if (token.value) {
        try {
          const rooms = (await getRooms(token.value)).data;

          setRooms(rooms);

          setLoadState(true, false);
        } catch (e) {
          root.$router.replace('/login');
        }
      } else {
        root.$router.replace('/login');
      }
    }

    function newRoom() {
      editedRoom.value = null;
      dialog.value = true;
    }

    function editRoom(room: Room) {
      editedRoom.value = room;
      dialog.value = true;
    }

    function deleteRoom(room: Room) {
      console.log('Delete room: ' + room.name);
    }

    function closeDialog() {
      console.log('Closing dialog');
      dialog.value = false;
    }

    return {
      headers,
      loading,
      isAdmin,
      rooms,
      newRoom,
      editRoom,
      deleteRoom,
      dialog,
      editedRoom,
      closeDialog,
    };
  },
});
</script>
