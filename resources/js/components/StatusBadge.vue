<template>
    <div :class="statusClass">
        {{ status }}
    </div>
</template>

<script>
    export default {
        props: ['status'],

        computed: {
            statusClass() {
                let defaults = 'uppercase text-xs font-bold tracking-widest px-2 py-1 rounded-full ';

                if (this.status === 'Sent') {
                    return defaults.concat('text-green-900 bg-green-200');
                }

                if (this.status === 'Posted') {
                    return defaults.concat('text-yellow-900 bg-yellow-200');
                }

                if (this.status === 'Failed') {
                    return defaults.concat('text-red-900 bg-red-200');
                }

                return defaults.concat('text-gray-900 bg-gray-200');
            }
        }
    }
</script>