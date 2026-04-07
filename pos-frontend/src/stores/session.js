import api from "@/lib/api";
import { db } from "@/lib/db";
import { defineStore } from "pinia";
import { ref, computed } from "vue";
import '@bprogress/core/css';
import { BProgress } from '@bprogress/core';
import { useCartStore } from "./cart";

export const useSessionStore = defineStore('session', () => {

    const user = ref(null);
    const outlets = ref([]);
    const token = ref(null);
    const outlet = ref(null);

    const customers = ref([])
    const accounts = ref([])

    const refreshing = ref(false)

    const isLoggedIn = computed(() => !!token.value);

    const products = ref([])

    const appIsOnline = ref(navigator.onLine)

    window.addEventListener('online', async () => {
        appIsOnline.value = true
        await restore()
    })

    window.addEventListener('offline', () => appIsOnline.value = false)

    async function login(email, password) {
        const { data } = await api.post('/login', { email, password });

        await requestPersistentStorage()

        user.value = data.user
        token.value = data.token
        outlets.value = data.outlets

        await db.session.put({
            key: 'auth',
            user: JSON.parse(JSON.stringify(data.user)),
            token: data.token,
            outlets: JSON.parse(JSON.stringify(data.outlets)),
            outlet: null,
        })
    }

    async function logout() {
        try {
            await api.post('/logout')
        } catch (error) {
            console.error('Logout failed:', error)
        }

        user.value = null
        token.value = null
        outlets.value = []
        outlet.value = null

        await db.session.clear()
        window.location.href = '/pos/login'
    }

    async function getLoggedInUserOutlets() {
        if (!outlet.value) {
            console.warn("No outlet selected, skipping outlets fetch")
            return
        }
        try {
            const sessionData = (await db.session.get("auth")) || {};

            try {
                const response = await api.get("/getLoggedInUserOutlets");
                const fresh = response.data;
                sessionData.outlets = JSON.parse(JSON.stringify(fresh));
                await db.session.put(sessionData, "auth");
                outlets.value = fresh;

                if (fresh.length === 0) { // ✅ only when online + API confirmed empty
                    alert('No Outlets Assigned')
                    await logout()
                }

            } catch (networkErr) {
                console.warn("Network unavailable, falling back to cache:", networkErr);
                outlets.value = sessionData.outlets || [];
            }

        } catch (err) {
            console.error("Failed to load outlets:", err);
        }
    }

    async function fetchProducts() {
        if (!outlet.value) {
            console.warn("No outlet selected, skipping product fetch")
            return
        }
        try {
            if (appIsOnline.value) {
                // Online: always get fresh from server
                const response = await api.get("/getProducts")
                const fresh = response.data.data ?? response.data
                await db.products.clear()
                await db.products.bulkPut(fresh)
                products.value = fresh
            } else {
                // Offline: serve from cache
                products.value = await db.products.toArray()
            }
        } catch (err) {
            // Any failure: fall back to cache
            console.warn("fetchProducts failed, falling back to cache:", err)
            products.value = await db.products.toArray()
        }
    }

    async function fetchCustomers() {
        if (!outlet.value) {
            console.warn("No outlet selected, skipping product fetch")
            return
        }
        try {
            if (appIsOnline.value) {
                const response = await api.get("/getCustomers")
                const fresh = response.data.data ?? response.data
                await db.customers.clear()
                await db.customers.bulkPut(fresh)
                customers.value = fresh
            } else {
                customers.value = await db.customers.toArray()
            }
        } catch (err) {
            console.warn("fetchCustomers failed, falling back to cache:", err)
            customers.value = await db.customers.toArray()
        }

        const cart = useCartStore()
        if (!cart.customerId) {
            const walkIn = customers.value.find(c => c.customer_type === 'walk_in')
            if (walkIn) cart.customerId = walkIn.id
        }
    }

    async function fetchAccounts() {
        try {
            if (appIsOnline.value) {
                const response = await api.get("/getAccounts")
                const fresh = response.data.data ?? response.data
                await db.accounts.clear()
                await db.accounts.bulkPut(fresh)
                accounts.value = fresh
            } else {
                accounts.value = await db.accounts.toArray()
            }
        } catch (err) {
            accounts.value = await db.accounts.toArray()
        }
    }

    async function createCustomer(payload) {
        const response = await api.post('/customers', payload)
        const newCustomer = response.data

        // Add to local list immediately so it's selectable right away
        customers.value.push(newCustomer)

        // Also persist to IndexedDB
        await db.customers.put(newCustomer)

        return newCustomer
    }

    async function syncPendingSales() {
        const pending = await db.pending_sales.toArray()

        for (const sale of pending) {
            try {
                await api.post('/sales', sale)
                await db.pending_sales.delete(sale.id)
            } catch (err) {
                // If it's a server validation error (4xx), skip it — retrying won't help
                // If it's a network error (5xx or no response), stop — server might be down
                if (err.response && err.response.status < 500) {
                    console.error('Sale permanently failed, flagging:', sale.id, err)
                    await db.pending_sales.put({ ...sale, status: 'failed', error: err.response?.data?.message })
                } else {
                    console.warn('Network issue, stopping sync:', err)
                    break
                }
            }
        }
    }

    async function restore() {
        BProgress.start()
        try {
            const sessionData = await db.session.get("auth");
            if (sessionData) {
                user.value = sessionData.user
                token.value = sessionData.token
                outlets.value = sessionData.outlets
                outlet.value = sessionData.outlet
            }
            if (!token.value) return false
            await getLoggedInUserOutlets()
            await fetchProducts()
            await fetchCustomers()
            await fetchAccounts()
            await syncPendingSales()
            return !!sessionData
        } finally {
            BProgress.done()
        }
    }

    async function requestPersistentStorage() {
        if (navigator.storage && navigator.storage.persist) {
            const granted = await navigator.storage.persist()
            console.log(granted ? 'Storage is persistent' : 'Storage may be evicted')
        }
    }

    async function selectOutlet(selectedOutlet) {
        outlet.value = selectedOutlet

        const sessionData = (await db.session.get("auth")) || { key: 'auth' };
        sessionData.outlet = JSON.parse(JSON.stringify(selectedOutlet));
        await db.session.put(sessionData, "auth");
    }

    return { login, user, outlets, token, outlet, selectOutlet, restore, isLoggedIn, logout, getLoggedInUserOutlets, appIsOnline, products, customers, fetchCustomers, accounts, createCustomer, refreshing }
});
