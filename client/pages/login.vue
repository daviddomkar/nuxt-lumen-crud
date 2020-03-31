<template>
  <v-card color="#272727" elevation="8">
    <validation-observer ref="observer" slim>
      <form class="pa-8">
        <v-row no-gutters justify="center" align="center">
          <v-col class="pa-0" cols="12">
            <h1 class="headline pb-2">LOGIN</h1>
            <validation-provider
              v-slot="{ errors, reset }"
              name="Username"
              rules="required"
            >
              <v-text-field
                v-model="username"
                color="primary"
                label="Username"
                :error-messages="errors"
                type="name"
                required
                @click="reset"
              />
            </validation-provider>
            <validation-provider
              v-slot="{ errors, reset }"
              name="Password"
              rules="required"
            >
              <v-text-field
                v-model="password"
                color="primary"
                label="Password"
                :error-messages="errors"
                type="password"
                required
                @click="reset"
              />
            </validation-provider>
          </v-col>
          <v-btn
            class="mt-6"
            color="primary"
            block
            @click="submit($refs.observer)"
            @keyup.enter="submit($refs.observer)"
          >
            Log in
          </v-btn>
        </v-row>
      </form>
    </validation-observer>
    <v-snackbar v-model="snackbar" color="tertiary" :timeout="20000000">
      Přihlášení se nezdařilo, zkontrolujte své údaje!
      <v-btn icon @click="snackbar = false">
        <v-icon>{{ mdiClose }}</v-icon>
      </v-btn>
    </v-snackbar>
  </v-card>
</template>

<style lang="scss" scoped>
form {
  width: 400px;
}
</style>

<script lang="ts">
import { defineComponent, ref } from '@vue/composition-api';
import { mdiClose } from '@mdi/js';
import { login, getProfile } from '@/utils/api';
import authStore from '@/stores/auth';

export default defineComponent({
  data() {
    return {
      mdiClose,
    };
  },
  setup(_, { root }) {
    const username = ref('');
    const password = ref('');

    const snackbar = ref(false);

    const { setToken, setProfile } = authStore.useConsumer();

    const submit = async (validator: { validate: () => Promise<boolean> }) => {
      if (await validator.validate()) {
        try {
          const data = (await login(username.value, password.value)).data;
          const token: string = data.token;

          setToken(token);

          const profile = (await getProfile(token)).data;

          setProfile(profile);

          root.$router.replace('/users');
        } catch (e) {
          snackbar.value = true;
        }
      }
    };

    return {
      username,
      password,
      snackbar,
      submit,
    };
  },
});
</script>
