<template>
    <div class="bg-gray-100 p-8 w-full h-full lg:px-24 lg:py-16">
        <div class="flex flex-col space-y-16" v-if="email">
            <div>
                <div class="flex justify-between items-center tracking-wide">
                    <h1 class="text-4xl text-gray-500">{{ email.subject }}</h1>
                    <div class="flex space-x-4 items-center">
                        <status-badge :status="email.status"></status-badge>
                        <span class="text-xl">{{ email.posted_at }}</span>
                    </div>
                </div>

                <p class="mt-2 text-xl text-gray-400">
                    From
                    <span class="text-indigo-400">{{ email.sender }}</span>
                    to
                    <span class="text-indigo-400">{{ email.recipient }}</span>
                </p>
            </div>

            <div class="text-2xl text-gray-600">
                <p class="text-base uppercase text-gray-400 font-semibold">Text content:</p>
                {{ email.text }}
            </div>

            <div>
                <p class="text-base uppercase text-gray-400 font-semibold">HTML content:</p>
                <div class="text-2xl text-gray-600" v-html="email.html"></div>
            </div>

            <div>
                <p class="text-base uppercase text-gray-400 font-semibold">{{ email.attachments.length }} Attachments:</p>
                <div class="flex space-x-8 flex-wrap">
                    <div
                        class="flex px-6 py-4 border-2 border-gray-200 shadow-sm bg-gray-50 rounded-lg items-center space-x-4 cursor-pointer mb-4"
                        v-for="attachment in email.attachments"
                        :key="attachment.id"
                        @click="download(attachment)"
                    >
                        <div class="flex flex-col">
                            <p>{{ attachment.filename }}</p>
                            <p>{{ attachment.filesize }}</p>
                        </div>
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-full flex justify-center items-center" v-else>
            <div class="flex flex-col items-center space-y-16">
                <img class="h-64 w-auto" src="/img/envelope_undraw.svg" alt="">
                <p class="text-2xl text-center">
                    You have not selected an email yet. Click on one of them at your left to see its content.
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import StatusBadge from "./StatusBadge";
    export default {
        components: {StatusBadge},
        props: ['email'],

        methods: {
            download(attachment) {
                axios.get('/attachments/download', {
                    responseType: 'arraybuffer',
                    params: {
                        attachment_id: attachment.id
                    }
                })
                    .then((response) => {
                        let blob = new Blob([response.data], { type: 'application/pdf' });
                        let link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = attachment.filename;
                        link.click();
                    })
                    .catch((error) => console.error(error.response));
            },
        }
    }
</script>