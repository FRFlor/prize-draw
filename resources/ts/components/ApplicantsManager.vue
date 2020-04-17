<template>
    <form @submit.prevent>
        <ul class="w-full px-8 lg:mx-auto lg:max-w-5xl">
            <li v-for="applicant in applicants"
                class="py-2 px-4 my-2 border-orange-700 border-2 rounded flex justify-between">
                <span class="truncate text-orange-900" v-text="applicant.name"></span>
                <section class="controls flex">
                    <button class="mx-1 w-6 border-orange-300 border"><i class="fa text-orange-600 fa-pencil-square-o" aria-label="edit" aria-hidden="true"></i></button>
                    <button class="mx-1 w-6 border-red-300 border"><i class="fa text-red-600 fa-trash-o" aria-label="delete" aria-hidden="true"></i></button>
                </section>
            </li>
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
