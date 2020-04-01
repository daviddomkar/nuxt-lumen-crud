<template>
  <v-card elevation="8">
    <v-card-title>
      <span class="headline">{{ formTitle }}</span>
    </v-card-title>

    <v-card-text>
      <validation-observer ref="observer" slim>
        <form>
          <v-row>
            <v-col cols="6">
              <validation-provider
                v-slot="{ errors, reset }"
                name="First name"
                rules="required|min:3|max:255"
              >
                <v-text-field
                  v-model="user.first_name"
                  color="primary"
                  label="First name"
                  :error-messages="errors"
                  required
                  @click="reset"
                />
              </validation-provider>
            </v-col>
            <v-col cols="6">
              <validation-provider
                v-slot="{ errors, reset }"
                name="Last name"
                rules="required|min:3|max:255"
              >
                <v-text-field
                  v-model="user.last_name"
                  color="primary"
                  label="Last name"
                  :error-messages="errors"
                  required
                  @click="reset"
                />
              </validation-provider>
            </v-col>
            <v-col cols="4">
              <validation-provider
                v-slot="{ errors, reset }"
                name="Position"
                rules="required|min:3|max:255"
              >
                <v-text-field
                  v-model="user.position"
                  color="primary"
                  label="Position"
                  :error-messages="errors"
                  required
                  @click="reset"
                />
              </validation-provider>
            </v-col>
            <v-col cols="4">
              <validation-provider
                v-slot="{ errors, reset }"
                name="Salary"
                rules="required|numeric|integer"
              >
                <v-text-field
                  v-model="user.salary"
                  color="primary"
                  label="Salary"
                  :error-messages="errors"
                  required
                  type="number"
                  @click="reset"
                />
              </validation-provider>
            </v-col>
            <v-col cols="4">
              <validation-provider
                v-slot="{ errors, reset }"
                name="Room"
                rules="required"
              >
                <v-autocomplete
                  v-model="user.room_id"
                  :items="Object.values(rooms)"
                  label="Room"
                  :error-messages="errors"
                  item-text="name"
                  item-value="id"
                  placeholder="Select room"
                  required
                  @click="reset"
                />
              </validation-provider>
            </v-col>
            <v-col cols="4">
              <validation-provider
                v-slot="{ errors, reset }"
                name="Username"
                rules="required|min:3|max:255"
              >
                <v-text-field
                  v-model="user.username"
                  color="primary"
                  label="Username"
                  :error-messages="errors"
                  required
                  @click="reset"
                />
              </validation-provider>
            </v-col>
            <v-col cols="4">
              <validation-provider
                v-slot="{ errors, reset }"
                name="Password"
                rules="min:8|max:255"
              >
                <v-text-field
                  v-model="user.password"
                  color="primary"
                  label="Password"
                  type="password"
                  :error-messages="errors"
                  required
                  @click="reset"
                />
              </validation-provider>
            </v-col>
            <v-col v-if="profile.id != user.id" cols="4">
              <v-checkbox v-model="user.admin" label="Is admin?" required />
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
import { createUser, updateUser } from '@/utils/api';
import usersStore, { User } from '@/stores/users';
import roomsStore from '@/stores/rooms';
import authStore from '@/stores/auth';

export default defineComponent({
  props: {
    editedUser: Object,
  },
  data() {
    return {
      mdiClose,
    };
  },
  setup(props: { editedUser: null | User }, { root, emit }) {
    const { rooms } = roomsStore.useConsumer();
    const { setUser } = usersStore.useConsumer();
    const { token, profile } = authStore.useConsumer();

    const loading = ref(false);
    const snackbar = ref(false);
    const error = ref('');

    const user = ref<User>(
      props.editedUser
        ? {
            id: props.editedUser.id,
            first_name: props.editedUser.first_name,
            last_name: props.editedUser.last_name,
            position: props.editedUser.position,
            salary: props.editedUser.salary,
            room_id: props.editedUser.room_id,
            username: props.editedUser.username,
            password: props.editedUser.password,
            admin: props.editedUser.admin,
          }
        : {
            id: 0,
            first_name: '',
            last_name: '',
            position: '',
            salary: 0,
            room_id: Object.values(rooms.value)[0].id,
            username: '',
            password: '',
            admin: false,
          },
    );

    watch(
      () => props.editedUser,
      (editedUser) => {
        user.value = editedUser
          ? {
              id: editedUser.id,
              first_name: editedUser.first_name,
              last_name: editedUser.last_name,
              position: editedUser.position,
              salary: editedUser.salary,
              room_id: editedUser.room_id,
              username: editedUser.username,
              password: editedUser.password,
              admin: editedUser.admin,
            }
          : {
              id: 0,
              first_name: '',
              last_name: '',
              position: '',
              salary: 0,
              room_id: Object.values(rooms.value)[0].id,
              username: '',
              password: '',
              admin: false,
            };
      },
    );

    const formTitle = computed(() =>
      props.editedUser
        ? 'Edit user ' + user.value.first_name + ' ' + user.value.last_name
        : 'New user',
    );

    async function save(validator: { validate: () => Promise<boolean> }) {
      if (await validator.validate()) {
        if (token.value) {
          try {
            loading.value = true;

            if (props.editedUser) {
              const updated = await updateUser(token.value, user.value);
              setUser(updated.data);
            } else {
              const created = await createUser(token.value, user.value);
              setUser(created.data);
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
      user,
      save,
      loading,
      error,
      snackbar,
      rooms,
      profile,
    };
  },
});
</script>
