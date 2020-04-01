<template>
  <v-card elevation="8">
    <v-card-title>
      <span class="headline">{{ formTitle }}</span>
    </v-card-title>

    <v-card-text>
      <validation-observer ref="observer" slim>
        <form>
          <v-row>
            <v-col cols="12">
              <validation-provider
                v-slot="{ errors, reset }"
                name="Number"
                rules="required|digits:3"
              >
                <v-text-field
                  v-model="room.number"
                  color="primary"
                  label="Number"
                  :error-messages="errors"
                  required
                  @click="reset"
                />
              </validation-provider>
            </v-col>
            <v-col cols="12">
              <validation-provider
                v-slot="{ errors, reset }"
                name="Name"
                rules="required|min:2|max:255"
              >
                <v-text-field
                  v-model="room.name"
                  color="primary"
                  label="Name"
                  :error-messages="errors"
                  required
                  @click="reset"
                />
              </validation-provider>
            </v-col>
            <v-col cols="12">
              <validation-provider
                v-slot="{ errors, reset }"
                name="Phone"
                rules="digits:4"
              >
                <v-text-field
                  v-model="room.phone"
                  color="primary"
                  label="Phone"
                  :error-messages="errors"
                  @click="reset"
                />
              </validation-provider>
            </v-col>
          </v-row>
        </form>
      </validation-observer>
    </v-card-text>

    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="primary" text @click="$emit('close')">Cancel</v-btn>
      <v-btn
        :loading="loading"
        color="primary"
        text
        @click="save($refs.observer)"
        @keyup.enter="save($refs.observer)"
        >Save</v-btn
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
import { defineComponent, ref, computed, watch } from '@vue/composition-api';
import { mdiClose } from '@mdi/js';
import { createRoom, updateRoom } from '@/utils/api';
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
  setup(props: { editedRoom: null | Room }, { root, emit }) {
    const { setRoom } = roomsStore.useConsumer();
    const { token } = authStore.useConsumer();

    const loading = ref(false);
    const snackbar = ref(false);
    const error = ref('');

    const room = ref<Room>(
      props.editedRoom
        ? {
            id: props.editedRoom.id,
            name: props.editedRoom.name,
            number: props.editedRoom.number,
            phone: props.editedRoom.phone ?? '',
          }
        : {
            id: 0,
            name: '',
            number: '',
            phone: '',
          },
    );

    watch(
      () => props.editedRoom,
      (editedRoom) => {
        room.value = editedRoom
          ? {
              id: editedRoom.id,
              name: editedRoom.name,
              number: editedRoom.number,
              phone: editedRoom.phone ?? '',
            }
          : {
              id: 0,
              name: '',
              number: '',
              phone: '',
            };
      },
    );

    const formTitle = computed(() =>
      props.editedRoom
        ? 'Edit room ' + room.value.number + ' - ' + room.value.name
        : 'New room',
    );

    async function save(validator: { validate: () => Promise<boolean> }) {
      if (await validator.validate()) {
        if (token.value) {
          try {
            loading.value = true;

            if (props.editedRoom) {
              const updated = await updateRoom(token.value, room.value);
              setRoom(updated.data);
            } else {
              const created = await createRoom(token.value, room.value);
              setRoom(created.data);
            }
            loading.value = false;
            emit('close');
          } catch (e) {
            loading.value = false;
            if (e.response.status === 401) {
              root.$router.replace('/login');
            } else {
              if (e.response.status === 400) {
                error.value =
                  e.response.data[Object.keys(e.response.data)[0]][0];
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
    }

    return {
      formTitle,
      room,
      save,
      loading,
      error,
      snackbar,
    };
  },
});
</script>
