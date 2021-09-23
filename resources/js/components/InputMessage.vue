<template>
    <div class="py-3">
        <div v-if="!loading">
            <form @submit.prevent="onSubmit">
                <input type="text" class="p-1" id="message" v-model="message" placeholder="So...  What you say, hmm?">
                <input type="submit" class="p-1" value="Send!">
            </form>
        </div>
        <div v-else>
            <i class="fab fa-jedi-order"></i> <em>YodaBot is writing...</em>
        </div>
    </div>
</template>

<script>
	export default {
        props: {
            errors: Object,
        },

        data() {
            return {
                message: null,
                loading: false,
                failCount: 0
            }
        },

        mounted() {
            this.$on('get-list', (petition) => {
                this.getList(petition);
            });
        },

        methods: {
            onSubmit() {
                this.loading = true;
                this.$root.$emit('create-message', 'user', this.message);

                axios.post('conversation/message/' + this.message).then(({data}) => {
                    if (! this.message.toLowerCase().includes("force")) {
                        if (data['answers'][0].flags[0] != 'no-results') {
                            this.$root.$emit('create-message', 'bot', data['answers'][0].message);
                        }
                        else {
                            this.failCount = this.failCount + 1;
                            if (this.failCount == 2) {
                                this.failCount = 0;
                                this.$emit('get-list', 'characters');
                            }
                            else {
                                this.$root.$emit('create-message', 'bot', data['answers'][0].message);
                            }
                        }
                    }
                    else {
                        this.$emit('get-list', 'films');
                    }

                    this.message = "";
                    this.loading = false;
                }).catch(({response}) => {
                    this.errors = response.data.errors;
                    this.$root.$emit('axios-error', response.config.url, response.status, response.statusText);
                });
            },

            getList(petition) {
                axios.post('conversation/graphql/' + petition).then(({data}) => {
                    let graphqlMessage = "The <strong>force</strong> is in this movies:<br><ul>";
                    switch (petition) {
                        case 'characters':
                            graphqlMessage = "I haven't found any results, but here is a list of some Star Wars characters:<br><ul>";
                            let characters = this.$root.shuffle(data.data['allPeople']['people']).slice(0, 10);
                            characters.forEach(function(element) {
                                graphqlMessage = graphqlMessage + "<li>" + element.name + "</li>";
                            });
                            graphqlMessage = graphqlMessage + "</ul>";
                            break;
                        default:
                            data.data['allFilms']['films'].forEach(function(element) {
                                graphqlMessage = graphqlMessage + "<li>" + element.title + "</li>";
                            });
                            graphqlMessage = graphqlMessage + "</ul>";
                    }
                    this.$root.$emit('create-message', 'bot', graphqlMessage);
                }).catch(({response}) => {
                    this.errors = response.data.errors;
                    this.$root.$emit('axios-error', response.config.url, response.status, response.statusText);
                });
            }
        }
    };
</script>