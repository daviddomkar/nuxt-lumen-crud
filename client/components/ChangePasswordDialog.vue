<template>
  <v-card elevation="8">
    <v-card-title>
      <span class="headline">Change pasword</span>
    </v-card-title>

    <v-card-text>
      <v-row>
        <v-col cols="12">
          <validation-observer ref="observer" slim>
            <form>
              <validation-provider
                ref="provider"
                v-slot="{ errors, reset }"
                name="Password"
                rules="required|min:8|max:255"
              >
                <v-text-field
                  v-model="password"
                  color="primary"
                  label="Password"
                  type="password"
                  :error-messages="errors"
                  required
                  @click="reset"
                />
              </validation-provider>
            </form>
          </validation-observer>
        </v-col>
      </v-row>
    </v-card-text>

    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="primary" text @click="$emit('close')">Cancel</v-btn>
      <v-btn
        :loading="loading"
        color="primary"
        text
        @click="submit($refs.observer)"
        @keyup.enter="submit($refs.observer)"
        >Submit</v-btn
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
import { defineComponent, ref } from '@vue/composition-api';
import { mdiClose } from '@mdi/js';
import { changePassword } from '@/utils/api';
import authStore from '@/stores/auth';

export default defineComponent({
  data() {
    return {
      mdiClose,
    };
  },
  setup(_, { root, emit }) {
    const { token } = authStore.useConsumer();

    const password = ref('');
    const loading = ref(false);

    const snackbar = ref(false);
    const error = ref('');

    async function submit(validator: { validate: () => Promise<boolean> }) {
      if (await validator.validate()) {
        if (token.value) {
          try {
            loading.value = true;

            await changePassword(token.value, password.value);

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
      password,
      submit,
      loading,
      snackbar,
      error,
    };
  },
});
</script>
