<template>
    <div class="flex flex-col h-screen">
        <div class="flex">
            <div class="w-1/3 px-12 py-8 space-y-2 tracking-wide">
                <a class="text-4xl font-bold" href="/">
                    <span class="text-gray-700">mini</span><span class="text-indigo-600">send</span>
                </a>

                <p>The Remote Company - MailerSend Code Test</p>
            </div>
            <div class="flex flex-col items-center space-x-8 w-2/3 px-12 pt-8 bg-gray-100 tracking-wide">
                <div class="flex items-center w-full">
                    <input
                        class="px-6 py-4 rounded-lg w-full outline-none text-xl"
                        type="search"
                        id="search"
                        name="search"
                        placeholder="Search by a sender, recipient or/and subject..."
                        v-model="searchQuery"
                        @keyup="search(this)"
                    >
                    <svg class="w-6 h-6 -m-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <p class="mt-2 text-lg text-gray-400">Example: from:johndoe@mail.com to:sarahdoe@mail.com subject:"Live Concert"</p>
            </div>
        </div>
        <div class="flex h-full">
            <div class="flex flex-col w-1/3 h-full">
                <template v-if="emails.length > 0">
                    <div v-for="email in emails" :key="email.id">
                        <email :email="email" @click="showEmail(email)"></email>
                    </div>
                </template>
                <div class="flex flex-col items-center px-6 py-4 space-y-8 h-full justify-center" v-else>
                    <img class="h-48 w-auto" src="/img/mailbox_undraw.svg" alt="">
                    <p class="text-2xl text-center">
                        Ups! Looks like there are no emails yet. Try out the API or search for something else.
                    </p>
                </div>
            </div>
            <div class="flex w-2/3 h-full">
                <email-content :email="selectedEmail"></email-content>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';
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
                selectedEmail: null,
                searchQuery: '',
            }
        },

        created() {
            this.getEmails();
        },

        methods: {
            getEmails() {
                axios.get('/emails', { params: { searchQuery: this.searchQuery }})
                    .then((response) => {
                        this.emails = response.data;
                        console.log(response);
                    })
                    .catch((error) => console.log(error.response));
            },

            search: _.debounce((context) => {
                context.getEmails();
            }, 500),

            showEmail(email) {
                this.selectedEmail = email;
            }
        }
    }
</script>