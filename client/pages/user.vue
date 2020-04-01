<template>
  <v-row class="flex-column" no-gutters justify="center" align="center">
    <v-card v-if="user && room" class="card flex-grow-1">
      <v-card-title class="subheading font-weight-bold">{{
        'User ' + user.first_name + ' ' + user.last_name
      }}</v-card-title>
      <v-divider></v-divider>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">First name:</span>
        <span>{{ user.first_name }}</span>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Last name:</span>
        <span>{{ user.last_name }}</span>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Position:</span>
        <span>{{ user.position }}</span>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Salary:</span>
        <span>{{ user.salary | currency }}</span>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Room:</span>
        <nuxt-link :to="'/room?id=' + room.id">{{ room.name }}</nuxt-link>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Username:</span>
        <span>{{ user.username }}</span>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Is admin:</span>
        <span>{{ !!user.admin }}</span>
      </v-row>
    </v-card>
    <v-progress-circular
      v-if="!(user && room)"
      size="50"
      indeterminate
      color="primary"
    />
  </v-row>
</template>

<style lang="scss" scoped>
.card {
  min-width: 360px;
}
</style>

<script lang="ts">
import { defineComponent, onMounted, ref } from '@vue/composition-api';
import { getUser, getRoom } from '@/utils/api';
import authStore from '@/stores/auth';
import { User } from '@/stores/users';
import { Room } from '@/stores/rooms';

export default defineComponent({
  // @ts-ignore
  head() {
    return {
      title: 'User detail',
    };
  },
  setup(_, { root }) {
    const { token } = authStore.useConsumer();

    const user = ref<User | null>(null);
    const room = ref<Room | null>(null);

    onMounted(() => {
      const id = root.$router.currentRoute.query.id;

      if (id) {
        // @ts-ignore
        loadUser(id);
      }
    });

    async function loadUser(id: string) {
      if (token.value) {
        try {
          if (Number.isNaN(Number(id))) {
            root.$router.replace('/users');
            return;
          }

          const userFromServer = (await getUser(token.value, Number(id))).data;
          room.value = (
            await getRoom(token.value, userFromServer.room_id)
          ).data;
          user.value = userFromServer;
        } catch (e) {
          root.$router.replace('/login');
        }
      } else {
        root.$router.replace('/login');
      }
    }

    return {
      user,
      room,
    };
  },
});
</script>
