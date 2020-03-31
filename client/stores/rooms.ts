import { toRefs } from '@vue/composition-api';
import { createStore } from 'vue-state-composer';

export type Room = {
  id: number;
  number: string;
  name: string;
  phone: null | string;
};

type State = {
  loaded: boolean;
  loading: boolean;
  rooms: { [id: number]: Room };
};

const defaultState: State = {
  loaded: false,
  loading: false,
  rooms: {},
};

export default createStore({
  name: 'rooms',
  setup({ createState }) {
    const state = createState(defaultState);

    const setLoadState = (loaded: boolean, loading: boolean) => {
      state.loaded = loaded;
      state.loading = loading;
    };

    const setRooms = (rooms: { [id: number]: Room }) => {
      state.rooms = rooms;
    };

    return {
      ...toRefs(state),
      setRooms,
      setLoadState,
    };
  },
});
