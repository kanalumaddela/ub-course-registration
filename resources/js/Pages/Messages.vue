<template>
    <site-layout>
        <div class="py-12 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            <div class="relative bg-white rounded shadow" style="height: 50rem">
                <div class="col-span-full p-3 h-14 flex items-center justify-between">
                    <inertia-link :href="route('messages.index')">
                        <h1 class="text-2xl font-bold">Inbox</h1>
                    </inertia-link>
                    <button class="flex items-center" @click="creatingMessage = true">
                        <svg class="h-6 w-6 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </svg>
                        Start a conversation
                    </button>

                    <jet-dialog-modal :show="creatingMessage" @close="resetMessageForm">
                        <template #title>
                            <h1>Send a Message</h1>
                        </template>

                        <template #content>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">
                                    <span>Send To</span>
                                </label>
                                <v-select v-model="startConversationForm.user" :appendToBody="true" :clearable="false" :filterable="false" :options="userList" label="name" placeholder="Start typing to find a user..." @search="queryForUsers">
                                    <template #no-options="{ search, searching, loading }">
                                        <div class="py-2">
                                            <span v-show="!searching && !loading">Start typing to find a user</span>
                                            <span v-show="searching && !loading">Sorry, no users matching could be found</span>
                                            <span v-show="searching && loading">Searching users...</span>
                                            <span v-show="loading">Loading results...</span>
                                        </div>
                                    </template>

                                    <template slot="option" slot-scope="option">
                                        <div class="py-2 px-4 flex items-center">
                                            <img :alt="option.name" :src="option.profile_photo_url" class="flex-shrink-0 h-6 w-6 rounded-full">
                                            <span class="font-normal ml-3 block truncate">
                                                {{ option.name }}
                                            </span>
                                        </div>
                                    </template>
                                    <template slot="selected-option" slot-scope="option">
                                        <div class="flex items-center">
                                            <img :alt="option.name" :src="option.profile_photo_url" class="flex-shrink-0 h-6 w-6 rounded-full">
                                            <span class="semibold ml-3 block truncate">
                                                {{ option.name }}
                                            </span>
                                        </div>
                                    </template>
                                </v-select>
                                <div v-show="startConversationForm.errors.user_id" class="mt-2">
                                    <p class="text-sm text-red-600">
                                        {{ startConversationForm.errors.user_id === 'The user id has already been taken.' ? 'A conversation with this user already exists' : startConversationForm.errors.user_id }}
                                    </p>
                                </div>
                            </div>


                            <div class="mt-3">
                                <jet-label for="message" value="Message" />
                                <textarea id="message" v-model="startConversationForm.message" class="w-full h-36 border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                                <jet-input-error :message="startConversationForm.errors.message" class="mt-2" />
                            </div>
                        </template>

                        <template #footer>
                            <jet-secondary-button @click.native="resetMessageForm">
                                Cancel
                            </jet-secondary-button>
                            <jet-button @click.native="startConversation">
                                {{ startConversationForm.processing ? 'Sending...' : 'Send' }}
                            </jet-button>
                        </template>
                    </jet-dialog-modal>
                </div>
                <div class="grid grid-cols-4 grid-rows-6" style="height: calc(50rem - 3.5rem)">
                    <div v-show="conversations.length" id="convoListScroller" class="col-span-1 row-span-6 border-r border-gray-300 overflow-auto">
                        <inertia-link v-for="(conversation, i) in conversations" :id="`convoList-${conversation.id}`" :key="`conversation-${conversation.id}`" :href="route('messages.index', conversation.id)" preserveState replace @click="activeConvo = conversation.id">
                            <div :class="{'bg-gray-100 shadow-inner': conversation.id === activeConvo}" class="flex p-3 border-t border-gray-300 cursor-pointer">
                                <img v-if="$page.props.jetstream.managesProfilePhotos" :alt="getRealMessagePartner(conversation).name" :src="getRealMessagePartner(conversation).profile_photo_url" class="mr-2 h-8 w-8 rounded-full object-cover" />
                                <div class="flex-grow">
                                    <h1 :class="{'font-bold': activeConvo === conversation.id}">{{ getRealMessagePartner(conversation).name }}</h1>
                                    <p class="text-sm text-gray-500">
                                        {{ truncate(conversation.latest_message.content) }}
                                    </p>
                                    <div class="text-right text-xs text-gray-400">
                                        {{ luxonFormatFriendly(conversation.latest_message.created_at) }}
                                    </div>
                                </div>
                            </div>
                        </inertia-link>
                    </div>

                    <div id="convoScroller" :class="!conversations.length ? 'col-span-full' : 'col-span-3'" class="row-span-5 py-3 px-6 border-t border-gray-300 overflow-auto shadow-inner">
                        <div v-show="!activeConvo" class="flex items-center h-full text-gray-400">
                            <div class="flex-grow text-center">
                                <button v-if="!conversations.length" class="outline-none" @click="creatingMessage = true">
                                    <svg class="inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                                    </svg>
                                    <h1 class="text-2xl">Start a conversation</h1>
                                </button>

                                <template v-else>
                                    <svg class="inline-block h-1/5 w-1/5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                                    </svg>
                                    <h1 class="text-2xl">No conversation selected</h1>
                                </template>
                            </div>
                        </div>
                        <div>
                            <conversation v-for="conversation in conversations" v-show="conversation.id === activeConvo" :key="`conversation-messages-${conversation.id}`" v-observe-visibility="convoScrollTo" :messages="conversation.messages" @load="convoScrollTo(conversation.id === activeConvo)"></conversation>
                        </div>
                    </div>
                    <div v-show="activeConvo" class="col-span-3 row-span-1 flex items-center px-6">
                        <div class="flex w-full">
                            <input v-model="sendMessageForm.message" class="flex-grow border border-gray-300 rounded-tl-md rounded-bl-md outline-none" placeholder="Send a message..." type="text" v-on:keyup.enter="replyToConversation" />
                            <jet-button class="rounded-tl-none rounded-bl-none rounded-tr-md rounded-br-md" type="button" @click.native="replyToConversation">Send</jet-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </site-layout>
</template>

<script>
import SiteLayout from "@/Layouts/SiteLayout";
import JetButton from "@/Jetstream/Button";
import Button from "@/Jetstream/Button";
import Conversation from "@/components/Conversation";
import {DateTime} from 'luxon';
import _truncate from 'lodash/truncate';
import Modal from "@/Jetstream/Modal";
import JetDialogModal from '@/Jetstream/DialogModal';
import Label from "@/Jetstream/Label";
import JetLabel from "@/Jetstream/Label";
import JetInput from '@/Jetstream/Input';
import JetInputError from '@/Jetstream/InputError';
import JetSecondaryButton from '@/Jetstream/SecondaryButton';
// import SelectList from "@/components/forms/SelectList";
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
    name: "Messages",
    components: {
        // SelectList,
        Label,
        Button,
        Modal,
        JetDialogModal,
        JetInput,
        Conversation,
        JetButton,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
        vSelect,
        SiteLayout
    },
    props: {
        initialConvo: {
            type: Number,
            default: 0,
        },
        conversations: {
            type: Array,
            default: [],
        },
        updatedNotificationCount: {
            type: Number,
            default: null,
        },
        toUser: null,
    },
    data() {
        return {
            activeConvo: 0,
            convoScroller: null,
            creatingMessage: false,
            userList: [],
            startConversationForm: this.$inertia.form({
                user: null,
                message: null,
            }),
            sendMessageForm: this.$inertia.form({
                message: null,
            }),
        }
    },
    methods: {
        getRealMessagePartner(conversation) {
            return conversation.author_id !== this.$page.props.user.id ? conversation.author : conversation.recipient;
        },
        truncate(str, options = {}) {
            return _truncate(str, options);
        },
        convoScrollTo(isVisible, entry) {
            if (!isVisible) {
                return;
            }

            this.convoScroller.scrollTop = entry.boundingClientRect.height;
        },
        luxonFormatFriendly(timestamp) {
            return DateTime.fromISO(timestamp).toRelative();
        },
        resetMessageForm() {
            this.creatingMessage = false;
            this.startConversationForm.user = null;
        },
        queryForUsers(search, loading) {
            if (search.length === 0) {
                return;
            }

            this.search(search, loading, this);
        },
        search: _.debounce((search, loading , vm) => {
            loading(true);
            axios.post(route('messages.userSearch'), {search}).then(res => {
                vm.userList = res.data;
            })
                .catch(err => console.log)
                .finally(() => loading(false));
        }, 250),
        startConversation() {
            this.startConversationForm
                .transform(data => {
                    return {
                        user_id: data.user.id,
                        content: data.message,
                    }
                })
                .post(route('messages.create'), {
                    onSuccess: (page) => {
                        this.activeConvo = page.props.initialConvo;
                        this.creatingMessage = false;
                        this.startConversationForm.reset();
                    }
                })
        },
        replyToConversation() {
            this.sendMessageForm.post(route('messages.reply', this.activeConvo), {
                onSuccess: () => this.sendMessageForm.reset(),
            })
        },
    },
    mounted() {
        this.convoScroller = document.getElementById('convoScroller');

        if (this.initialConvo) {
            this.activeConvo = this.initialConvo;
            document.getElementById('convoList-' + this.initialConvo).scrollIntoView();
        }

        if (this.toUser && !this.initialConvo) {
            this.userList = [this.toUser]
            this.startConversationForm.user = this.toUser;
            this.creatingMessage = true;
        }
    }
}
</script>

<style>
.vs__dropdown-toggle, .vs__selected-options, .vs__dropdown-menu, .vs__dropdown-option, .vs__selected {
    padding: 0;

}
.vs__selected {
    margin: 0;
}
.vs__selected-options {
    padding: .5rem 1rem;
}

.vs__dropdown-option {
    color: #3f3f46;
    transition: background-color .1s ease, color .1s ease;
}

.vs__dropdown-option--highlight {
    background-color: #9333ea;
    color: #fff;
}
</style>
