import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { db } from "@/lib/db";
import { bootstrap } from "@/lib/sync";
import api from "@/lib/api";

export const useSessionStore = defineStore("session", () => {
    const user = ref(null);
    const outlet = ref(null);
    const token = ref(null);
    const booted = ref(false);

    const isLoggedIn = computed(() => !!token.value);

    // Called on app boot — restore session from IndexedDB
    async function restore() {
        const session = await db.session.get("auth");

        if (session) {
            user.value = session.user;
            outlet.value = session.outlet;
            token.value = session.token;
            booted.value = true;
        }

        return !!session;
    }

    // Called when cashier submits login form
    async function login(email, password) {

        const { data } = await api.post('/login', {
            email,
            password
        });

        user.value = data.user
        token.value = data.token

        // Save token + user + outlets to IndexedDB temporarily
        await db.session.put({
            key: 'auth',
            user: JSON.parse(JSON.stringify(data.user)),
            token: data.token,
            outlets: JSON.parse(JSON.stringify(data.outlets)),
            outlet: null,
        })

        return data.outlets

    }

    async function selectOutlet(selectedOutlet) {
        const session = await db.session.get("auth");
        const plain = JSON.parse(JSON.stringify(selectedOutlet));

        session.outlet = plain;
        session.outlet_id = plain.id;
        await db.session.put(session);

        outlet.value = plain;
        booted.value = true;

        await bootstrap();
    }

    async function logout() {
        try {
            await api.post("/logout");
        } catch { }

        // Clear everything
        user.value = null;
        outlet.value = null;
        token.value = null;
        booted.value = false;

        await db.session.clear();
        await db.products.clear();
        await db.customers.clear();
        await db.payment_methods.clear();
    }

    return {
        user,
        outlet,
        token,
        booted,
        isLoggedIn,
        restore,
        login,
        selectOutlet,
        logout,
    };
});
