<template>
  <v-card elevation="8">
    <v-card-title>
      <span class="headline">Delete {{ subject }}</span>
    </v-card-title>

    <v-card-text>
      <p class="subtitle-1">Are you sure you want to delete {{ subject }} ?</p>
    </v-card-text>

    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="primary" text @click="$emit('close')">Cancel</v-btn>
      <v-btn
        :loading="loading"
        color="primary"
        text
        @click="del"
        @keyup.enter="del"
        >Yes</v-btn
      >
    </v-card-actions>
    <v-snackbar v-model="snackbar" color="tertiary" :timeout="20000000">
      {{ error }}
      <v-btn icon @click="snackbar = false">
        <v-icon>{{ mdiClose }}</v-icon>
      </v-btn>
    </v-snackbar>
  </v-card>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from '@vue/composition-api';
import { mdiClose } from '@mdi/js';
import { deleteRoom as deleteRoomFromServer } from '@/utils/api';
import roomsStore, { Room } from '@/stores/rooms';
import authStore from '@/stores/auth';

export default defineComponent({
  props: {
    editedRoom: Object,
  },
  data() {
    return {
      mdiClose,
    };
  },
  setup(props: { editedRoom: Room }, { root, emit }) {
    const { deleteRoom } = roomsStore.useConsumer();
    const { token } = authStore.useConsumer();

    const loading = ref(false);
    const snackbar = ref(false);
    const error = ref('');

    const subject = ref(
      'room ' + props.editedRoom.number + ' - ' + props.editedRoom.name,
    );
    const roomToDelete = ref<Room>(props.editedRoom);

    watch(
      () => props.editedRoom,
      (editedRoom) => {
        subject.value = 'room ' + editedRoom.number + ' - ' + editedRoom.name;
        roomToDelete.value = editedRoom;
      },
    );

    async function del() {
      if (token.value) {
        try {
          loading.value = true;
          await deleteRoomFromServer(token.value, roomToDelete.value);
          deleteRoom(roomToDelete.value);
          loading.value = false;
          emit('close');
        } catch (e) {
          loading.value = false;
          if (e.response.status === 401) {
            root.$router.replace('/login');
          } else {
            if (e.response.status === 403) {
              error.value = 'Cannot delete room which is occupied by a user.';
            } else {
              error.value = 'Uknown error occurred.';
            }
            snackbar.value = true;
          }
        }
      } else {
        root.$router.replace('/login');
      }
    }

    return {
      subject,
      del,
      loading,
      error,
      snackbar,
    };
  },
});
</script>
