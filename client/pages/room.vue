<template>
  <v-row class="flex-column" no-gutters justify="center" align="center">
    <v-card v-if="room && users" class="card flex-grow-1">
      <v-card-title class="subheading font-weight-bold">{{
        'Room ' + room.number
      }}</v-card-title>
      <v-divider></v-divider>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Number:</span>
        <span>{{ room.number }}</span>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Name:</span>
        <span>{{ room.name }}</span>
      </v-row>
      <v-row v-if="room.phone" class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Phone:</span>
        <span>{{ room.phone }}</span>
      </v-row>
      <div v-if="users.length > 0">
        <v-row
          v-for="(user, index) in users"
          :key="user.id"
          class="ma-4"
          no-gutters
          justify="space-between"
        >
          <span class="font-weight-bold">{{
            index === 0 ? 'Users:' : ''
          }}</span>
          <nuxt-link :to="'/user?id=' + user.id">{{
            user.first_name + ' ' + user.last_name
          }}</nuxt-link>
        </v-row>
      </div>
    </v-card>
    <v-progress-circular
      v-if="!(room && users)"
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
import { getRoom, getUsers } from '@/utils/api';
import authStore from '@/stores/auth';
import { Room } from '@/stores/rooms';
import { User } from '@/stores/users';

export default defineComponent({
  // @ts-ignore
  head() {
    return {
      title: 'Room detail',
    };
  },
  setup(_, { root }) {
    const { token } = authStore.useConsumer();

    const users = ref<Array<User> | null>(null);
    const room = ref<Room | null>(null);

    onMounted(() => {
      const id = root.$router.currentRoute.query.id;

      if (id) {
        // @ts-ignore
        loadRoom(id);
      }
    });

    async function loadRoom(id: string) {
      if (token.value) {
        try {
          if (Number.isNaN(Number(id))) {
            root.$router.replace('/rooms');
            return;
          }

          const roomFromServer = (await getRoom(token.value, Number(id))).data;
          users.value = (await getUsers(token.value, roomFromServer.id)).data;
          room.value = roomFromServer;
        } catch (e) {
          root.$router.replace('/login');
        }
      } else {
        root.$router.replace('/login');
      }
    }

    return {
      room,
      users,
    };
  },
});
</script>
