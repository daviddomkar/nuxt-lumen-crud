<template>
  <v-row class="flex-column" no-gutters justify="center" align="center">
    <v-card v-if="user" class="card flex-grow-1">
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
        <nuxt-link :to="'/room?id=' + rooms[user.room_id].id">{{
          rooms[user.room_id].name
        }}</nuxt-link>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Username:</span>
        <span>{{ user.username }}</span>
      </v-row>
      <v-row class="ma-4" no-gutters justify="space-between">
        <span class="font-weight-bold">Is admin:</span>
        <span>{{ !!user.admin }}</span>
      </v-row>
      <div v-if="user.keys.length > 0">
        <v-row
          v-for="(key, index) in user.keys"
          :key="key"
          class="ma-4"
          no-gutters
          justify="space-between"
        >
          <span class="font-weight-bold">{{ index === 0 ? 'Keys:' : '' }}</span>
          <nuxt-link :to="'/room?id=' + rooms[key].id">{{
            rooms[key].name
          }}</nuxt-link>
        </v-row>
      </div>
    </v-card>
    <v-progress-circular v-if="!user" size="50" indeterminate color="primary" />
  </v-row>
</template>

<style lang="scss" scoped>
.card {
  min-width: 360px;
}
</style>

<script lang="ts">
import { defineComponent, onMounted, ref } from '@vue/composition-api';
import { getUser, getRooms } from '@/utils/api';
import authStore from '@/stores/auth';
import { User } from '@/stores/users';
import roomsStore from '@/stores/rooms';

export default defineComponent({
  // @ts-ignore
  head() {
    return {
      title: 'User detail',
    };
  },
  setup(_, { root }) {
    const { token } = authStore.useConsumer();
    const { setRooms, rooms } = roomsStore.useProvider();

    const user = ref<User | null>(null);

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
          setRooms((await getRooms(token.value)).data);
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
      rooms,
    };
  },
});
</script>
