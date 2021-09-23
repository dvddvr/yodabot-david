<template>
    <div class="mensaje">
        <strong v-html="labelUser(user).name"></strong> <span v-html="message"></span>
    </div>
</template>

<script>
    export default {
        props: {
            user: null,
            message: null
        },

        data() {
            return {
                users: [
                    {'id': 'user', 'name': '<i class="far fa-comment"></i> Yo:'},
                    {'id': 'bot', 'name': '<i class="far fa-comment-alt"></i> YodaBot:'},
                ],
            }
        },

        computed: {
            labelUser: function () {
                return id => this.users.find(element => element.id === id);
            },
        },
    };
</script>