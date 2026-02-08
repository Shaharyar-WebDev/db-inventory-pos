<link rel="stylesheet" type="text/css" href="https://unpkg.com/@bprogress/core/dist/index.css" />
<style>
    :root {
        --bprogress-color: #000;
    }
</style>
<script type="module">
    import {
        BProgress
    } from 'https://unpkg.com/@bprogress/core/dist/index.js';

    BProgress.configure({
         speed: 120,
         showSpinner: false,
    });

    Livewire.hook('commit', ({
        component,
        commit,
        respond,
        succeed,
        fail
    }) => {
        BProgress.start();

        succeed(({
            snapshot,
            effect
        }) => {
            queueMicrotask(() => {
                BProgress.done();
                // BProgress.remove();
            });
        });
    });
</script>
