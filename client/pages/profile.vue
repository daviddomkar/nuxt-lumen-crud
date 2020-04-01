<template>
  <v-row class="flex-column" no-gutters justify="center" align="center">
    <v-card v-if="room" class="card flex-grow-1">
      <v-card-title class="subheading font-weight-bold">Profile</v-card-title>
      <v-divider></v-divider>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">First name:</span>
        <span>{{ profile.first_name }}</span>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Last name:</span>
        <span>{{ profile.last_name }}</span>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Position:</span>
        <span>{{ profile.position }}</span>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Salary:</span>
        <span>{{ profile.salary | currency }}</span>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Room:</span>
        <nuxt-link :to="'/room?id=' + room.id">{{ room.name }}</nuxt-link>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Username:</span>
        <span>{{ profile.username }}</span>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Is admin:</span>
        <span>{{ !!profile.admin }}</span>
      </v-row>
    </v-card>
    <v-row v-if="room" class="ma-4" justify="center" align="center">
      <v-dialog
        v-model="changeUsernameDialog"
        max-width="500px"
        @click:outside="changeUsernameDialog = false"
      >
        <template v-slot:activator="{ on }">
          <v-btn class="ma-4" color="primary" v-on="on">Change username</v-btn>
        </template>
        <change-username-dialog
          :edited-username="profile.username"
          @close="changeUsernameDialog = false"
        />
      </v-dialog>
      <v-dialog
        v-model="changePasswordDialog"
        max-width="500px"
        @click:outside="changePasswordDialog = false"
      >
        <template v-slot:activator="{ on }">
          <v-btn class="ma-4" color="primary" v-on="on">Change Password</v-btn>
        </template>
        <change-password-dialog @close="changePasswordDialog = false" />
      </v-dialog>
      <v-btn class="ma-4" color="primary" @click="logOut">Log Out</v-btn>
    </v-row>
    <v-progress-circular v-if="!room" size="50" indeterminate color="primary" />
  </v-row>
</template>

<style lang="scss" scoped>
.card {
  min-width: 360px;
}
</style>

<script lang="ts">
import {
  defineComponent,
  computed,
  onMounted,
  ref,
} from '@vue/composition-api';
import { getRoom, logout } from '@/utils/api';
import authStore from '@/stores/auth';
import { Room } from '@/stores/rooms';

import ChangePasswordDialog from '@/components/ChangePasswordDialog.vue';
import ChangeUsernameDialog from '@/components/ChangeUsernameDialog.vue';

export default defineComponent({
  components: {
    ChangePasswordDialog,
    ChangeUsernameDialog,
  },
  // @ts-ignore
  head() {
    return {
      title: 'Profile',
    };
  },
  setup(_, { root }) {
    const {
      token,
      deleteTokenFromStorage,
      profile,
      deleteProfile,
    } = authStore.useConsumer();

    const room = ref<Room | null>(null);

    const changeUsernameDialog = ref(false);
    const changePasswordDialog = ref(false);

    onMounted(() => {
      loadRoom();
    });

    async function loadRoom() {
      if (token.value && profile.value) {
        try {
          room.value = (await getRoom(token.value, profile.value.room_id)).data;
        } catch (e) {
          root.$router.replace('/login');
        }
      } else {
        root.$router.replace('/login');
      }
    }

    const fullName = computed(
      // eslint-disable-next-line camelcase
      () => profile?.value?.first_name + ' ' + profile?.value?.last_name,
    );

    function logOut() {
      root.$router.replace('/login');
      deleteTokenFromStorage();
      deleteProfile();
      logout();
    }

    return {
      fullName,
      profile,
      room,
      logOut,
      changeUsernameDialog,
      changePasswordDialog,
    };
  },
});
</script>
