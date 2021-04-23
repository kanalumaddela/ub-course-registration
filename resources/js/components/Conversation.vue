<template>
    <div v-if="messages.length" class="grid grid-cols-12 gap-y-2.5 py-2">
        <div v-for="(message, i) in messages" :key="message.id" :class="{'col-start-2 flex flex-row-reverse': message.user_id === $page.props.user.id}" class="col-span-11">
            <div class="flex items-center">
                <span class="text-gray-300 text-xs mr-2 ">
                    {{ luxonFormatFriendly(message.created_at) }}
                </span>
                <div :class="getMessageClass(message)" class="py-3 px-5 rounded-tl-3xl rounded-tr-3xl">
                    {{ message.content }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {DateTime} from "luxon";

export default {
    name: "Conversation",
    props: {
        messages: {
            type: Array,
            default: () => {
                return [];
            }
        }
    },
    methods: {
        isOwnMessage(message) {
            return message.user_id === this.$page.props.user.id;
        },
        getMessageClass(message) {
            return this.isOwnMessage(message) ? 'rounded-bl-3xl bg-gray-300' : 'rounded-br-3xl text-white bg-purple-500';
        },
        luxonFormatFriendly(timestamp) {
            return DateTime.fromISO(timestamp).toRelative();
        },
    },
}
</script>

<style scoped>

</style>
