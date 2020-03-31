import axios from 'axios';

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

export const getProfile = async (token: string) => {
  return await client.get('/auth/profile', {
    headers: {
      Authorization: 'Bearer ' + token,
    },
  });
};

export const getUsers = async (token: string) => {
  return await client.get('/users', {
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
