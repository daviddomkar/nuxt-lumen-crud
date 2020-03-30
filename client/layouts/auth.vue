<template>
  <v-app>
    <v-app-bar app>
      <v-toolbar-title>Nuxt Lumen CRUD</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn
        icon
        href="https://github.com/DavidDomkar/nuxt-lumen-crud"
        target="_blank"
      >
        <v-icon>{{ mdiGithub }}</v-icon>
      </v-btn>
      <template v-slot:extension>
        <v-row no-gutters>
          <v-tabs centered>
            <v-tab>Users</v-tab>
            <v-tab>Rooms</v-tab>
          </v-tabs>
        </v-row>
      </template>
    </v-app-bar>
    <v-content>
      <v-container fluid>
        <nuxt />
      </v-container>
    </v-content>
  </v-app>
</template>

<script lang="ts">
import { defineComponent } from '@vue/composition-api';
import { mdiGithub } from '@mdi/js';

export default defineComponent({
  data() {
    return {
      mdiGithub,
    };
  },
});
</script>
