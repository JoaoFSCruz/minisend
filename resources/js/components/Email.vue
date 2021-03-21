<template>
    <div class="flex px-6 py-4 border border-gray-100 hover:bg-indigo-50 cursor-pointer" :class="bgColor">
        <div class="w-5/6 flex flex-col space-y-2 tracking-wide">
            <p class="text-xl font-semibold">{{ email.subject }}</p>
            <p class="text-gray-400">
                From
                <span class="text-indigo-400">{{ email.sender }}</span>
                to
                <span class="text-indigo-400">{{ email.recipient }}</span>
            </p>
            <div class="flex space-x-8">
                <status-badge :status="email.status"></status-badge>
                <span v-if="email.attachments.length > 0" class="text-gray-400">{{ email.attachments.length }} Attachments</span>
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

        props: ['email', 'selectedEmail'],

        computed: {
            bgColor() {
                return this.selectedEmail && this.selectedEmail.id === this.email.id ? 'bg-indigo-100' : 'bg-gray-50';
            }
        }
    }
</script>