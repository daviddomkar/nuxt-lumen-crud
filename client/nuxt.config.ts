import { Configuration } from '@nuxt/types';

const config: Configuration = {
  mode: 'universal',
  /*
   ** Headers of the page
   */
  head: {
    titleTemplate: '%s | Nuxt Lumen CRUD',
    title: 'Home',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      {
        hid: 'description',
        name: 'description',
        content: 'CRUD System using Nuxt and Lumen',
      },
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      {
        rel: 'stylesheet',
        href:
          'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,500,700',
      },
      {
        rel: 'stylesheet',
        href: 'https://fonts.googleapis.com/css?family=Eczar:400',
      },
    ],
  },
  /*
   ** Customize the progress-bar color
   */
  loading: { color: '#B15DFF' },
  /*
   ** Global CSS
   */
  css: ['@/assets/overrides.sass', '@/assets/main.scss'],
  /*
   ** Plugins to load before mounting the App
   */
  plugins: [
    '@/plugins/core.ts',
    '@/plugins/vee-validate.ts',
    { src: '@/plugins/state.ts', mode: 'client' },
  ],
  modules: [
    [
      'vue-currency-filter/nuxt',
      {
        symbol: 'Kč',
        thousandsSeparator: ',',
        fractionCount: 0,
        symbolPosition: 'back',
        symbolSpacing: true,
      },
    ],
  ],
  /*
   ** Nuxt.js dev-modules
   */
  buildModules: [
    '@nuxt/typescript-build',
    '@nuxtjs/eslint-module',
    '@nuxtjs/stylelint-module',
    '@nuxtjs/vuetify',
  ],
  /*
   ** TypeScript module configuration
   */
  typescript: {
    typeCheck: {
      eslint: true,
    },
  },
  /*
   ** vuetify module configuration
   ** https://github.com/nuxt-community/vuetify-module
   */
  vuetify: {
    customVariables: ['@/assets/variables.scss'],
    optionsPath: '@/config/vuetify.ts',
    treeShake: true,
    defaultAssets: false,
  },
  /*
   ** Build configuration
   */
  build: {
    transpile: ['vee-validate/dist/rules'],
    /*
     ** You can extend webpack config here
     */
    // extend(config, ctx) {},
  },
};

export default config;
