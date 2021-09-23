<template>
	<div class="container">
		<h1>YodaBot</h1>
        <h2>“No! Try not. Speak me... Or speak me not. There is no try”</h2>
		<template v-for="message in messages">
            <message :user="message.user" :message="message.message"></message>
        </template>
		<input-message></input-message>
		<button type="button" class="btn btn-secondary" v-on:click="openDetail()"><i class="far fa-question-circle"></i> Ayuda</button>

		<div class="modal bd-example-modal-lg" id="ayuda" tabindex="-1" role="dialog" aria-modal="true">
		  	<div class="modal-dialog modal-lg">
		    	<div class="modal-content">
			      	<div class="modal-header">
			        	<h5 class="modal-title h4" id="myLargeModalLabel">Ayuda</h5>
			        	<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
			          		<span aria-hidden="true">×</span>
			        	</button>
			      	</div>
			      	<div class="modal-body">
			      		<img class="mx-auto d-block yoda-help" src="/images/YodaHelp.jpg" alt="Yoda"></img>
			        	<p>¡Hola!</p>
			        	<p>Bienvenido al YodaBot. Desde esta web podrás charlar con Yoda y aprender de él para convertirte en un Jedi. Le podrás preguntar todas las dudas que tengas. Eso sí, para hablar con él tendrás que hacerlo en inglés. Disfruta y... ¡Qué la fuerza te acompañe!</p>
			        	<p><em>P.D.: El desarrollador de esta web no se hace responsable de una mala aplicación de estas enseñanzas que pueda desembocar en el lado oscuro de la Fuerza.</em></p>
			      	</div>
			    </div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
            return {
                messages: [],
                message: {
	                user: null,
	                message: null,
	            },
            };
        },

		mounted() {
			axios.get('conversation/history/').then(({data}) => {
                this.messages = data;
            }).catch(({response}) => {
            	this.errors = response.data.errors;
                this.$root.$emit('axios-error', response.config.url, response.status, response.statusText);
            });

			this.$root.$on('create-message', (user, message) => {
				this.messages.push({'user': user, 'message': message});
 			});

 			this.$root.$on('axios-error', (url, status, text) => {
				alert('Ha ocurrido un error:\n' + status + ' - ' + text + ' al intentar acceder a ' + url + '.\nPor favor, contacte con davidvr1992@gmail.com indicando este texto. Gracias y disculpe las molestias.');
 			});
		},

		methods: {
			openDetail() {
                $('#ayuda').modal('show');
            }
		}
	};
</script>