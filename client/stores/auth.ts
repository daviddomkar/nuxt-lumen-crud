import { toRefs, computed } from '@vue/composition-api';
import { createStore } from 'vue-state-composer';

type Profile = {
  id: string;
  first_name: string;
  last_name: string;
  position: string;
  salary: number;
  room_id: number;
  username: string;
  admin: boolean;
};

type State = {
  token: null | string;
  profile: null | Profile;
};

const defaultState: State = {
  token: null,
  profile: null,
};

export default createStore({
  name: 'auth',
  setup({ createState }) {
    const state = createState(defaultState);

    const loadTokenFromStorage = () => {
      state.token = localStorage.getItem('token');
    };

    const deleteTokenFromStorage = () => {
      state.token = null;
      localStorage.removeItem('token');
    };

    const setToken = (token: string) => {
      localStorage.setItem('token', token);
      state.token = token;
    };

    const setProfile = (profile: Profile) => {
      state.profile = profile;
    };

    const deleteProfile = () => {
      state.profile = null;
    };

    const isLoggedIn = computed(() => !!state.profile);
    const isAdmin = computed(() => !!state.profile?.admin);

    return {
      ...toRefs(state),
      loadTokenFromStorage,
      deleteTokenFromStorage,
      setProfile,
      deleteProfile,
      setToken,
      isLoggedIn,
      isAdmin,
    };
  },
});
