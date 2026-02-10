import { createApp } from "vue";

import Pos from "./pos/Pos.vue";

localStorage.setItem('isOpen', false);

if (!window.__POS__) {
    window.alert("POS bootstrap data missing!");
    throw new Error("POS bootstrap data missing!");
}

const bootstrapRoute = window.__POS__.bootstrapRoute;

async function fetchBootstrapData() {
    const response = await fetch(bootstrapRoute, {
        headers: {
            Accept: "application/json",
        },
    });

    if (!response.ok) {
        throw new Error("Failed to fetch POS bootstrap data");
    }

    return await response.json();
}

const data = await fetchBootstrapData();

console.log(data);

const app = createApp(Pos, {
    pos: data.pos,
});

app.mount("#pos");
