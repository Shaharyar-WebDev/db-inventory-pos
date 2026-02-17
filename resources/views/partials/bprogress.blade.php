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
        speed: 180,
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

    window.onbeforeunload = function() {
        BProgress.configure({
            showSpinner: true,
        });
        BProgress.start();
    };

    // Hide loader after page loads
    window.addEventListener('load', function() {
        BProgress.start();
        BProgress.done();
    });
</script>


