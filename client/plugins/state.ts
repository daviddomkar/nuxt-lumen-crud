import { Plugin } from '@nuxt/types';
import { installDevtools } from 'vue-state-composer';

const myPlugin: Plugin = () => {
  if (process.env.NODE_ENV !== 'production') {
    installDevtools();
  }
};

export default myPlugin;
