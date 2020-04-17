<template>
    <form @submit.prevent>
        <ul>
            <li v-for="applicant in applicants" v-text="applicant.name"></li>
        </ul>
    </form>
</template>

<script lang=ts>
    import {Component, Vue} from 'vue-property-decorator';
    import {IApplicant} from '../types';
    import axios from 'axios';

    @Component
    export default class ApplicantsManager extends Vue {
        applicants: IApplicant[] = [];

        async created() {
            const response = await axios.get<IApplicant[]>('/applicants');
            this.applicants = response.data;
        }
    }
</script>

<style lang="scss" scoped>
</style>
