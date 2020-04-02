import axios from 'axios';
import { User } from '@/stores/users';
import { Room } from '@/stores/rooms';

const client = axios.create({
  baseURL:
    process.env.NODE_ENV !== 'production'
      ? 'http://localhost/api'
      : 'http://a2017domkda.delta-studenti.cz/api',
});

export const login = async (username: string, password: string) => {
  return await client.post('/auth/login', {
    username,
    password,
  });
};

export const changeUsername = async (token: string, username: string) => {
  return await client.post(
    '/auth/change-username',
    {
      username,
    },
    {
      headers: {
        Authorization: 'Bearer ' + token,
      },
    },
  );
};

export const changePassword = async (token: string, password: string) => {
  return await client.post(
    '/auth/change-password',
    {
      password,
    },
    {
      headers: {
        Authorization: 'Bearer ' + token,
      },
    },
  );
};

export const getProfile = async (token: string) => {
  return await client.get('/auth/profile', {
    headers: {
      Authorization: 'Bearer ' + token,
    },
  });
};

export const logout = async () => {
  return await client.post('/auth/logout');
};

export const getUsers = async (token: string, roomId: number | null = null) => {
  if (roomId) {
    return await client.get('/users?room_id=' + roomId, {
      headers: {
        Authorization: 'Bearer ' + token,
      },
    });
  } else {
    return await client.get('/users', {
      headers: {
        Authorization: 'Bearer ' + token,
      },
    });
  }
};

export const getUsersFromKeys = async (token: string, roomId: number) => {
  return await client.get('/users?room_id_keys=' + roomId, {
    headers: {
      Authorization: 'Bearer ' + token,
    },
  });
};

export const getUser = async (token: string, id: number) => {
  return await client.get('/users/' + id, {
    headers: {
      Authorization: 'Bearer ' + token,
    },
  });
};

export const createUser = async (token: string, user: User) => {
  return await client.post('/users', user, {
    headers: {
      Authorization: 'Bearer ' + token,
    },
  });
};

export const updateUser = async (token: string, user: User) => {
  return await client.put('/users/' + user.id, user, {
    headers: {
      Authorization: 'Bearer ' + token,
    },
  });
};

export const deleteUser = async (token: string, user: User) => {
  return await client.delete('/users/' + user.id, {
    headers: {
      Authorization: 'Bearer ' + token,
    },
  });
};

export const getRooms = async (token: string) => {
  return await client.get('/rooms', {
    headers: {
      Authorization: 'Bearer ' + token,
    },
  });
};

export const getRoom = async (token: string, id: number) => {
  return await client.get('/rooms/' + id, {
    headers: {
      Authorization: 'Bearer ' + token,
    },
  });
};

export const createRoom = async (token: string, room: Room) => {
  return await client.post('/rooms', room, {
    headers: {
      Authorization: 'Bearer ' + token,
    },
  });
};

export const updateRoom = async (token: string, room: Room) => {
  return await client.put('/rooms/' + room.id, room, {
    headers: {
      Authorization: 'Bearer ' + token,
    },
  });
};

export const deleteRoom = async (token: string, room: Room) => {
  return await client.delete('/rooms/' + room.id, {
    headers: {
      Authorization: 'Bearer ' + token,
    },
  });
};

export const createKey = async (
  token: string,
  userId: number,
  roomId: number,
) => {
  return await client.post(
    '/keys',
    { user_id: userId, room_id: roomId },
    {
      headers: {
        Authorization: 'Bearer ' + token,
      },
    },
  );
};

export const deleteKey = async (
  token: string,
  userId: number,
  roomId: number,
) => {
  return await client.delete('/keys', {
    headers: {
      Authorization: 'Bearer ' + token,
    },
    data: {
      user_id: userId,
      room_id: roomId,
    },
  });
};
