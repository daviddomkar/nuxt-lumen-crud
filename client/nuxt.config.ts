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
  loading: { color: '#1EB980' },
  /*
   ** Global CSS
   */
  css: ['@/assets/overrides.sass'],
  /*
   ** Plugins to load before mounting the App
   */
  plugins: ['@/plugins/core.ts'],
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
   ** Nuxt.js modules
   */
  modules: ['@nuxtjs/axios', '@nuxtjs/dotenv'],
  /*
   ** TypeScript module configuration
   */
  typescript: {
    typeCheck: {
      eslint: true,
    },
  },
  /*
   ** Axios module configuration
   ** See https://axios.nuxtjs.org/options
   */
  axios: {},
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
    /*
     ** You can extend webpack config here
     */
    // extend(config, ctx) {},
  },
};

export default config;
