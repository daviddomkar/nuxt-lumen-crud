import { toRefs, reactive } from '@vue/composition-api';
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

    const setRooms = (rooms: Array<Room>) => {
      state.rooms = rooms.reduce((previous, current) => {
        // @ts-ignore
        previous[current.id] = reactive(current);

        return previous;
      }, {});
    };

    const setRoom = (room: Room) => {
      if (state.rooms[room.id]) {
        state.rooms[room.id] = room;
      } else {
        setRooms([...Object.values(state.rooms), room]);
      }
    };

    const deleteRoom = (room: Room) => {
      delete state.rooms[room.id];
      setRooms([...Object.values(state.rooms)]);
    };

    return {
      ...toRefs(state),
      setRooms,
      setRoom,
      deleteRoom,
      setLoadState,
    };
  },
});
