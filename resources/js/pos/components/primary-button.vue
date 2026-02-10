<template>
    <div class="fi-ac fi-align-start">
        <button
            @click="increment"
            class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-900 hover:fi-text-color-900 dark:fi-text-color-950 dark:hover:fi-text-color-950 fi-btn fi-size-md fi-ac-btn-action"
        >
            New sale
        </button>
    </div>
</template>

<script>
export default {
    name: "primary-button",
};
</script>
