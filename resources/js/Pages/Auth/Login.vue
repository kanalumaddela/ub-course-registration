<template>
    <jet-authentication-card>
        <template #logo>
            <inertia-link :href="route('index')">
                <div class="h-36 w-36 bg-purple-700 rounded-xl text-7xl font-black text-white text-center" style="line-height: 9rem;">UB</div>
            </inertia-link>
        </template>

        <div v-if="$page.props.demo_mode" class="mb-2">
            <h1>Select a type of user to login as:</h1>
            <v-select :options="demoAccounts" label="type" @input="updateForm">

            </v-select>
        </div>

        <jet-validation-errors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus />
            </div>

            <div class="mt-4">
                <jet-label for="password" value="Password" />
                <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <jet-checkbox name="remember" v-model="form.remember" />
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <inertia-link :href="route('register')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Create an account
                </inertia-link>

                <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Login
                </jet-button>
            </div>
        </form>
    </jet-authentication-card>
</template>

<script>
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetCheckbox from '@/Jetstream/Checkbox'
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

const demoAccounts = [
    {
        type: 'student',
        email: 'student@bridgeport.edu',
        password: 'student123',
    },
    {
        type: 'advisor',
        email: 'advisor@bridgeport.edu',
        password: 'advisor123',
    },
    {
        type: 'admin',
        email: 'admin@bridgeport.edu',
        password: 'admin123',
    },
]

export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors,
            vSelect
        },

        props: {
            canResetPassword: Boolean,
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                })
            }
        },

        computed: {
            demoAccounts() {
                return this.$page.props.demo_mode ? demoAccounts : {};
            }
        },

        methods: {
            submit() {
                this.form
                    .transform(data => ({
                        ... data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('login'), {
                        onFinish: () => this.form.reset('password'),
                    })
            },
            updateForm(val) {
                this.form.email = val ? val.email : null;
                this.form.password = val ? val.password : null;
            }
        }
    }
</script>
