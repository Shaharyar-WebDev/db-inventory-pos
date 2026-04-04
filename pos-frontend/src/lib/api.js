import axios from 'axios'
import { db } from './db'

const api = axios.create({
  baseURL: '/api/pos',
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
    config.headers['X-Outlet-Id']   = session.outlet_id
  }

  return config
})

// If token expired or unauthorized — clear session and reload
api.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      await db.session.clear()
      window.location.href = '/pos/login'
    }
    return Promise.reject(error)
  }
)

export default api
