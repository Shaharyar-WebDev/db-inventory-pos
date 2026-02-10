<template>
    <div class="w-full h-full bg-amber-50">Hello World</div>
</template>

<script>
import PrimaryButton from "./components/primary-button.vue";

export default {
    name: "Pos",
    components: {
        PrimaryButton,
    },
    data() {
        return {
            count: 0,
        };
    },
    methods: {
        increment() {
            this.count++;
        },
    },
    props: {
        pos: {
            type: Object,
            required: true,
        },
    },

    mounted() {
        console.log("Window POS:", this.pos);
    },
};
</script>

<style scoped></style>
