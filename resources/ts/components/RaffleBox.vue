<template>
    <div class="raffle-box">
        <div class="applicant-name"
             v-for="(applicant, index) in applicants"
             :key="index"
             v-text="applicant"/>
    </div>
</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator';
    import axios from 'axios';

    @Component
    export default class RaffleBox extends Vue {
        applicants: string[] = [];

        async mounted() {
            const response = await axios.get<string[]>('phpstorm/applicants');
            this.applicants = response.data;
        }

    }
</script>

<style lang="scss" scoped>
</style>
