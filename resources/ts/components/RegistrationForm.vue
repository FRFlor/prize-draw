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

            async register() {
                try {
                    await axios.post('/phpstorm/register', {name: this.name});
                    window.location.assign('/phpstorm/raffle');
                } catch (e) {
                    alert('Ooops! Look at the console.');
                    console.error(e);
                }
            }
    }
</script>

<style lang="scss" scoped>
</style>
