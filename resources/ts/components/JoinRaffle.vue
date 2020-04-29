<template>
    <form @submit.prevent="join" class="mt-12 max-w-sm mx-4 md:mx-auto flex flex-col items-center">
        <h2 class="font-bold text-xl uppercase text-orange-600  mb-6">Join the Raffle</h2>

        <label class="sr-only" for="name">Name</label>
        <input placeholder="Name" class="text-orange-700 block py-2 px-4 border-orange-500 w-full border-2 rounded"
               id="name" type="text" v-model="name">

        <label class="sr-only" for="email">Email</label>
        <input placeholder="Email" class="text-orange-700 block py-2 px-4 border-orange-500 w-full border-2 rounded"
               id="email" type="email" v-model="email">

        <button class="mt-6   h-8 w-32 rounded border border-orange-600 "
                :class="isReadyToSubmit ? 'hover:bg-orange-600 bg-white text-orange-600 hover:text-gray-100' : 'bg-gray-300 text-gray-600'"
                :disabled="! isReadyToSubmit"
                type="submit">Submit
        </button>
    </form>
</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator';
    import axios from 'axios';

    @Component
    export default class RegistrationForm extends Vue {
        name: string = '';
        email: string = '';

        get isReadyToSubmit(): boolean {
            return !!this.name && !!this.email;
        }

        redirectTo(path: string) {
            window.location.assign(path);
        }

        async join() {
            try {
                await axios.post('/join', {name: this.name, email: this.email});
                this.redirectTo('/');

            } catch (e) {
                alert('Ooops! Look at the console.');
                console.error(e);
            }
        }
    }
</script>
