import Vue from 'vue';
import {
  ValidationObserver,
  ValidationProvider,
  extend,
  setInteractionMode,
} from 'vee-validate';
import {
  required,
  min,
  max,
  digits,
  numeric,
  integer,
} from 'vee-validate/dist/rules';

setInteractionMode('eager');

extend('required', {
  ...required,
  message: 'This field is required!',
});

extend('min', {
  ...min,
  message: (fieldName, placeholders) => {
    return `The ${fieldName} field must have at least ${placeholders?.length} characters!`;
  },
});

extend('max', {
  ...max,
  message: (fieldName, placeholders) => {
    return `The ${fieldName} field must not have more than ${placeholders?.length} characters!`;
  },
});

extend('digits', {
  ...digits,
  message: (fieldName, placeholders) => {
    return `The ${fieldName} field must have at least ${placeholders?.length} digits!`;
  },
});

extend('numeric', {
  ...numeric,
  message: (fieldName) => {
    return `The ${fieldName} field must be a number!`;
  },
});

extend('integer', {
  ...integer,
  message: (fieldName) => {
    return `The ${fieldName} field must be a whole number!`;
  },
});

Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);
