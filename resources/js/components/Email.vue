<template>
    <div class="flex px-6 py-4 border border-gray-100 hover:bg-indigo-50 cursor-pointer">
        <div class="w-5/6 flex flex-col space-y-2">
            <p class="text-xl font-semibold">{{ email.subject }}</p>
            <p class="text-gray-400">
                From
                <span class="text-indigo-400">{{ email.sender }}</span>
                to
                <span class="text-indigo-400">{{ email.recipient }}</span>
            </p>
            <div class="flex space-x-8">
                <status-badge :status="email.status"></status-badge>
                <span class="text-gray-400">{{ email.attachments.length }} Attachments</span>
            </div>
        </div>

        <div class="w-1/6 flex items-center">
            <span class="text-gray-400">{{ email.posted_at }}</span>
        </div>
    </div>
</template>

<script>
    import StatusBadge from './StatusBadge';

    export default {
        components: {
            StatusBadge,
        },

        props: ['email'],
    }
</script>