import axios from 'axios'
import { db } from './db'

const api = axios.create({
    baseURL: '/api/terminal',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }
})

// Before every request — attach token + outlet_id from IndexedDB
api.interceptors.request.use(async (config) => {
    const session = await db.session.get('auth')

    if (session) {
        config.headers['Authorization'] = `Bearer ${session.token}`
        config.headers['X-Outlet-Id'] = session.outlet.id
    }
    
    

    return config
})

// If token expired or unauthorized — clear session and reload
api.interceptors.response.use(
    (response) => response,
    async (error) => {
        if (error.response?.status === 401) {
            const session = await db.session.get('auth')
            // Only force-logout if there was an existing session (token expired)
            // Not if this is just a failed login attempt
            if (session?.token) {
                await db.session.clear()
                window.location.href = '/pos/login'
            }
        }
        if (error.response?.status === 403 && !window.location.pathname.includes('/login')) {
            await db.session.clear()
            window.location.href = '/terminal/login'
        }
        return Promise.reject(error)
    }
)

export default api

