import { toRefs } from '@vue/composition-api';
import { createStore } from 'vue-state-composer';

type User = {
  id: number;
  first_name: string;
  last_name: string;
  position: string;
  salary: number;
};

type State = {
  loaded: boolean;
  loading: boolean;
  users: { [id: number]: User };
};

const defaultState: State = {
  loaded: false,
  loading: false,
  users: {},
};

export default createStore({
  name: 'users',
  setup({ createState }) {
    const state = createState(defaultState);

    const setLoadState = (loaded: boolean, loading: boolean) => {
      state.loaded = loaded;
      state.loading = loading;
    };

    const setUsers = (users: { [id: number]: User }) => {
      state.users = users;
    };

    return {
      ...toRefs(state),
      setUsers,
      setLoadState,
    };
  },
});
