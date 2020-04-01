<template>
  <v-card class="card" color="#272727" elevation="8">
    <v-data-table
      color="primary"
      :headers="headers"
      :items="Object.values(rooms)"
      :loading="loading"
      loading-text="Loading data..."
      hide-default-footer
      disable-pagination
      @click:row="handleClick"
    >
      <template v-slot:top>
        <v-toolbar flat color="#272727">
          <v-toolbar-title>Rooms</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn
            v-if="isAdmin"
            color="primary"
            dark
            @click.stop="newRoom($refs.roomDialog)"
            >New Room</v-btn
          >
          <v-dialog
            v-if="isAdmin"
            v-model="dialog"
            max-width="500px"
            @click:outside="closeDialog"
          >
            <room-dialog
              ref="roomDialog"
              :edited-room="editedRoom"
              @close="closeDialog"
            />
          </v-dialog>
          <v-dialog
            v-if="isAdmin"
            v-model="deleteDialog"
            max-width="500px"
            @click:outside="closeDeleteDialog"
          >
            <room-delete-dialog
              :edited-room="editedRoom"
              @close="closeDeleteDialog"
            />
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
import { mdiPencil, mdiDelete } from '@mdi/js';
import { getRooms } from '@/utils/api';
import authStore from '@/stores/auth';
import roomsStore, { Room } from '@/stores/rooms';

import RoomDialog from '@/components/RoomDialog.vue';
import RoomDeleteDialog from '@/components/RoomDeleteDialog.vue';

export default defineComponent({
  components: {
    RoomDialog,
    RoomDeleteDialog,
  },
  // @ts-ignore
  head() {
    return {
      title: 'Rooms',
    };
  },
  data() {
    return {
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
    const deleteDialog = ref(false);
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
          if (e.response.status === 401) {
            root.$router.replace('/login');
          }
        }
      } else {
        root.$router.replace('/login');
      }
    }

    function newRoom(dialogToReset: any) {
      editedRoom.value = null;
      dialog.value = true;
      if (dialogToReset) {
        dialogToReset.$refs.observer.reset();
      }
    }

    function editRoom(room: Room) {
      editedRoom.value = room;
      dialog.value = true;
    }

    function deleteRoom(room: Room) {
      editedRoom.value = room;
      deleteDialog.value = true;
    }

    function closeDialog() {
      dialog.value = false;
    }

    function closeDeleteDialog() {
      deleteDialog.value = false;
    }

    function handleClick(room: Room) {
      root.$router.replace('/room?id=' + room.id);
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
      deleteDialog,
      editedRoom,
      closeDialog,
      closeDeleteDialog,
      handleClick,
    };
  },
});
</script>
