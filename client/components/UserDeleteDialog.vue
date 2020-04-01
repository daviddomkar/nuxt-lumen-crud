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
import { deleteUser as deleteUserFromServer } from '@/utils/api';
import usersStore, { User } from '@/stores/users';
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
  setup(props: { editedUser: User }, { root, emit }) {
    const { deleteUser } = usersStore.useConsumer();
    const { token } = authStore.useConsumer();

    const loading = ref(false);
    const snackbar = ref(false);
    const error = ref('');

    const subject = ref(
      'user ' + props.editedUser.first_name + ' ' + props.editedUser.last_name,
    );
    const userToDelete = ref<User>(props.editedUser);

    watch(
      () => props.editedUser,
      (editedUser) => {
        subject.value =
          'user ' + editedUser.first_name + ' ' + editedUser.last_name;
        userToDelete.value = editedUser;
      },
    );

    async function del() {
      if (token.value) {
        try {
          loading.value = true;
          await deleteUserFromServer(token.value, userToDelete.value);
          deleteUser(userToDelete.value);
          loading.value = false;
          emit('close');
        } catch (e) {
          loading.value = false;
          if (e.response.status === 401) {
            root.$router.replace('/login');
          } else {
            if (e.response.status === 403) {
              error.value = 'You cannot delete yourself.';
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
