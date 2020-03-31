<template>
  <v-app>
    <v-app-bar elevation="8" app>
      <v-toolbar-title>Nuxt + Lumen CRUD</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn
        icon
        href="https://github.com/DavidDomkar/nuxt-lumen-crud"
        target="_blank"
      >
        <v-icon>{{ mdiGithub }}</v-icon>
      </v-btn>
      <template v-if="isLoggedIn" v-slot:extension>
        <v-tabs centered>
          <v-tab to="/users" nuxt>Users</v-tab>
          <v-tab to="/rooms" nuxt>Rooms</v-tab>
          <v-tab to="/profile" nuxt>Profile</v-tab>
        </v-tabs>
      </template>
    </v-app-bar>
    <v-content>
      <v-container fluid>
        <nuxt v-if="!loading" />
        <v-progress-circular
          v-if="loading"
          size="50"
          indeterminate
          color="primary"
        />
      </v-container>
    </v-content>
    <v-sheet elevation="8">
      <v-footer>
        <v-col class="text-center" cols="12">
          {{ new Date().getFullYear() }} — <strong>David Domkář</strong>
        </v-col>
      </v-footer>
    </v-sheet>
  </v-app>
</template>

<style lang="sass" scoped>
.container
  height: 100%
  display: flex
  justify-content: center
  align-items: center
</style>

<script lang="ts">
import { defineComponent, ref, onMounted } from '@vue/composition-api';
import { mdiGithub } from '@mdi/js';
import { getProfile } from '@/utils/api';
import authStore from '@/stores/auth';

export default defineComponent({
  data() {
    return {
      mdiGithub,
    };
  },
  setup(_, { root }) {
    const loading = ref(true);

    const {
      loadTokenFromStorage,
      setProfile,
      token,
      isLoggedIn,
    } = authStore.useProvider();

    onMounted(() => {
      loadTokenFromStorage();
      loadProfileAndRedirect();
    });

    async function loadProfileAndRedirect() {
      if (token.value) {
        try {
          const profile = (await getProfile(token.value)).data;

          setProfile(profile);

          if (
            root.$router.currentRoute.path === '/login' ||
            root.$router.currentRoute.path === '/'
          ) {
            root.$router.replace('/users');
          }
        } catch (e) {
          if (root.$router.currentRoute.path !== '/login') {
            root.$router.replace('/login');
          }
        }
      } else if (root.$router.currentRoute.path !== '/login') {
        root.$router.replace('/login');
      }

      loading.value = false;
    }

    return {
      isLoggedIn,
      loading,
    };
  },
});
</script>
