import axios from "axios";
import router from "./router";

const axiosClient = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL,
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    }
});

axiosClient.interceptors.request.use(config => {
    const token = localStorage.getItem('AUTH_TOKEN');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

axiosClient.interceptors.response.use(
    res => res,
    err => {
        if (err.response?.status === 401) {
            localStorage.removeItem('AUTH_TOKEN');
            router.push({ name: 'Login' });
        } else if (err.response?.status === 403) {
            alert('Acesso negado.');
        } else if (err.response?.status === 404) {
            alert('Rota não encontrada.');
        } else if (err.response?.status >= 500) {
            alert('Erro interno no servidor.');
        }

        throw err;
    }
);

export default axiosClient;