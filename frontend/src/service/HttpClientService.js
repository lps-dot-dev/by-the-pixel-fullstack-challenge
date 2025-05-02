import axios from 'axios';

const backendHttpClient = axios.create({
  baseURL: import.meta.env.VITE_BACKEND_URL,
  timeout: 500
});

export { backendHttpClient };
