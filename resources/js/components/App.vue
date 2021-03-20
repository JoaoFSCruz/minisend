<template>
    <div class="flex flex-col h-screen">
        <div class="px-12 py-8">
            <a class="text-4xl font-bold tracking-wide" href="">
                <span class="text-gray-700">mini</span><span class="text-indigo-600">send</span>
            </a>
        </div>
        <div class="flex h-full">
            <div class="flex flex-col w-1/3 h-full">
                <div v-for="email in emails" :key="email.id">
                    <email :email="email" @click="showEmail(email)"></email>
                </div>
            </div>
            <div class="flex w-2/3 h-full overflow-y-auto">
                <email-content :email="selectedEmail"></email-content>
            </div>
        </div>
    </div>
</template>

<script>
    import Email from './Email';
    import EmailContent from './EmailContent';

    export default {
        components: {
            Email,
            EmailContent
        },

        data() {
            return {
                emails: [],
                selectedEmail: null
            }
        },

        created() {
            this.getEmails()
        },

        methods: {
            getEmails() {
                axios.get('/emails')
                    .then((response) => {
                        this.emails = response.data;
                        console.log(response);
                    })
                    .catch((error) => console.log(error.response));
            },

            showEmail(email) {
                this.selectedEmail = email;
            }
        }
    }
</script>