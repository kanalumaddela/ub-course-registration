<template>
    <button @click="doAction" class="disabled:opacity-75 disabled:cursor-default" :class="buttonClass">
        {{ toggleText[currentState] }}
    </button>
</template>

<script>
export default {
    name: "ActionButton",
    props: {
        action: {
            type: String,
            required: true
        },
        actionMethod: {
            type: String,
            default: 'get'
        },
        actionData: {
            type: Object,
            default: {}
        },
        csrf: {
            type: Boolean,
            default: false
        },
        toggleData: {
            type: Array,
            default: [{}, {}],
            validator: function (value) {
                return value.length === 2;
            }
        },
        toggleText: {
            validator: function (value) {
                return value.length === 2;
            }
        },
        toggleClass: {
            default: function () {
                return ['success', 'error'];
            },
            validator: function (value) {
                return value.length === 2;
            }
        },
        initialState: {
            type: Number,
            default: 0,
        },
        internalData: {
            type: Object,
        }
    },
    data() {
        return {
            success: null,
            currentState: null,
            data: {}
        }
    },
    computed: {
        buttonClass() {
            let cssClass = '';

            cssClass += this.success ? 'success' : (this.success === false ? 'false' : '');
            cssClass += ' ' + this.toggleClass[this.currentState];

            return cssClass;
        }
    },
    methods: {
        toggleCurrentState() {
            this.currentState = 1 - this.currentState;
        },
        doAction() {
            const method = this.actionMethod;
            let data = {...this.actionData, ...this.toggleData[this.currentState]};

            this.$el.disabled = true;

            if (this.csrf) {
                data._token = this.$page.props.csrf_token;
            }

            axios[method](this.action, data)
                .then(res => {
                    this.success = res.data.success;
                    this.data = res.data;

                    if (this.success) {
                        this.toggleCurrentState();
                    } else {
                        console.error(res.data);
                    }
                })
                .catch(err => {
                    console.error(err)
                })
                .finally(_ => {
                    this.$emit('state-changed');
                    this.$el.disabled = false;
                });
        }
    },
    mounted() {
        this.currentState = this.initialState;
    }
}
</script>

<style scoped>

</style>
