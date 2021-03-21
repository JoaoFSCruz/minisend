<template>
    <div class="flex flex-col h-screen">
        <div class="flex flex-col lg:flex-row">
            <div class="w-full px-4 py-4 space-y-2 tracking-wide lg:w-1/3 lg:px-12 lg:py-8">
                <a class="text-4xl font-bold" href="/">
                    <span class="text-gray-700">mini</span><span class="text-indigo-600">send</span>
                </a>

                <p>The Remote Company - MailerSend Code Test</p>
            </div>
            <div class="flex flex-col w-full px-4 py-4 items-center space-x-8 bg-gray-100 tracking-wide lg:w-2/3 lg:px-12 lg:pt-8">
                <div class="w-full space-y-2">
                    <label class="text-base" for="search">Search by a sender, recipient or/and subject...</label>
                    <div class="flex items-center w-full">
                        <input
                            class="px-3 py-2 rounded-lg w-full outline-none placeholder-transparent lg:placeholder-gray-600 lg:text-xl lg:px-6 lg:py-4"
                            type="search"
                            id="search"
                            name="search"
                            placeholder="Search by a sender, recipient or/and subject..."
                            v-model="searchQuery"
                            @keyup="search(this)"
                        >
                        <svg class="w-6 h-6 -m-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-400 lg:text-lg">Example: from:johndoe@mail.com to:sarahdoe@mail.com subject:"Live Concert"</p>
            </div>
        </div>
        <div class="flex flex-col h-full lg:overflow-y-hidden lg:flex-row">
            <div class="flex flex-col w-full h-2/3 overflow-y-auto border-t border-b lg:border-0 lg:w-1/3 lg:h-full">
                <template v-if="emails.length > 0">
                    <div v-for="email in emails" :key="email.id">
                        <email :email="email" :selected-email="selectedEmail" @click="showEmail(email)"></email>
                    </div>
                </template>
                <div class="flex flex-col items-center px-6 py-4 space-y-8 justify-center lg:h-full" v-else>
                    <img class="h-48 w-auto" src="/img/mailbox_undraw.svg" alt="">
                    <p class="text-2xl text-center">
                        Oops! Looks like there are no emails yet. Try out the API or search for something else.
                    </p>
                </div>
            </div>
            <div class="flex w-full h-2/3 overflow-y-auto bg-gray-100 lg:w-2/3 lg:h-full lg:pt-8">
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
                    })
                    .catch((error) => console.error(error.response));
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