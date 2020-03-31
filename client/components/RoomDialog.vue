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
              <v-text-field v-model="room.name" label="Name"></v-text-field>
            </v-col>
          </v-row>
        </form>
      </validation-observer>
    </v-card-text>

    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="primary" text @click="$emit('close')">Cancel</v-btn>
      <v-btn
        color="primary"
        text
        @click="save($refs.observer)"
        @keyup.enter="save($refs.observer)"
        >Save</v-btn
      >
    </v-card-actions>
  </v-card>
</template>

<script lang="ts">
import { defineComponent, ref, computed, watch } from '@vue/composition-api';
import { Room } from '@/stores/rooms';

export default defineComponent({
  props: {
    editedRoom: Object,
  },
  setup(props: { editedRoom: null | Room }, { emit }) {
    const room = ref<Room>(
      props.editedRoom
        ? {
            ...props.editedRoom,
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
        room.value = editedRoom || {
          id: 0,
          name: '',
          number: '',
          phone: '',
        };
      },
    );

    const formTitle = computed(() =>
      props.editedRoom ? 'Edit room ' + room.value.name : 'New room',
    );

    async function save(validator: { validate: () => Promise<boolean> }) {
      if (await validator.validate()) {
        console.log('uwuwuuwuwuw');
        emit('close');
      }
    }

    return {
      formTitle,
      room,
      save,
    };
  },
});
</script>
