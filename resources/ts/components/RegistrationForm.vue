<template>
    <form @submit.prevent="register">
        <label for="name">Name</label>
        <input id="name" type="text" v-model="name">
        <button type="submit">Submit</button>
    </form>
</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator';
    import axios from 'axios';

    @Component
    export default class RegistrationForm extends Vue {
            name: string = "";

            redirectTo(path: string) {
                window.location.assign(path);
            }

            async register() {
                try {
                    await axios.post('/register', {name: this.name});
                    this.redirectTo('/raffle');

                } catch (e) {
                    alert('Ooops! Look at the console.');
                    console.error(e);
                }
            }
    }
</script>

<style lang="scss" scoped>
</style>
