import axios from 'axios';

export const client = axios.create({
    baseURL: 'http://imperius.home:8090/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});