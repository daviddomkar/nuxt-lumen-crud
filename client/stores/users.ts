import { toRefs, reactive } from '@vue/composition-api';
import { createStore } from 'vue-state-composer';

export type User = {
  id: number;
  first_name: string;
  last_name: string;
  position: string;
  salary: number;
  room_id: number;
  username: string;
  password: string | null;
  admin: boolean;
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

    const setUsers = (users: Array<User>) => {
      state.users = users.reduce((previous, current) => {
        // @ts-ignore
        previous[current.id] = reactive(current);

        return previous;
      }, {});
    };

    const setUser = (user: User) => {
      if (state.users[user.id]) {
        state.users[user.id] = user;
      } else {
        setUsers([...Object.values(state.users), user]);
      }
    };

    const deleteUser = (user: User) => {
      delete state.users[user.id];
      setUsers([...Object.values(state.users)]);
    };

    return {
      ...toRefs(state),
      setUsers,
      setUser,
      deleteUser,
      setLoadState,
    };
  },
});
