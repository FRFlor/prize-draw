<template>
    <form @submit.prevent="join" class="mt-12 max-w-sm mx-4 md:mx-auto flex flex-col items-center">
        <h2 class="font-bold text-xl uppercase text-orange-600  mb-6">Join the Raffle</h2>

        <label class="sr-only" for="name">Name</label>
        <input placeholder="Name" class="text-orange-700 block py-2 px-4 w-full border-2 rounded"
               :class="errors.name && errors.name.length > 0 ? 'border-red-500' : 'border-orange-500'"
               id="name" type="text" v-model="name">
        <strong class="text-red-500" role="alert" v-if="errors.name && errors.name.length > 0" v-text="errors.name[0]"/>

        <label class="sr-only" for="email">Email</label>
        <input placeholder="Email" class="mt-4 text-orange-700 block py-2 px-4 w-full border-2 rounded"
               :class="errors.email && errors.email.length > 0 ? 'border-red-500 bg-red-200' : 'border-orange-500'"
               id="email" type="email" v-model="email">
        <strong class="text-red-500" role="alert" v-if="errors.email && errors.email.length > 0" v-text="errors.email[0]"/>

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

    interface IErrors {
        name?: string[],
        email?: string[]
    }

    @Component
    export default class RegistrationForm extends Vue {
        name: string = '';
        email: string = '';
        errors: IErrors = {name: [], email: []};

        get isReadyToSubmit(): boolean {
            return !!this.name && !!this.email;
        }

        redirectTo(path: string) {
            window.location.assign(path);
        }

        async join() {
            try {
                await axios.post('/applicants', {name: this.name, email: this.email.toLowerCase()});
                this.redirectTo('/');

            } catch (e) {
                if (e.response.status !== 422) {
                    alert('Something went wrong, please try again');
                }

                if (e.response.status === 422) {
                    this.errors = e.response.data.errors;
                }
            }
        }
    }
</script>
