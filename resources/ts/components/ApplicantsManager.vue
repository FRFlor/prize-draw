<template>
    <form @submit.prevent>
        <ul class="w-full px-8 lg:mx-auto lg:max-w-5xl">
            <li v-for="applicant in applicants"
                class="applicant py-2 px-4 my-2 border-orange-700 border-2 rounded flex justify-between">
                <span class="sr-only" v-text="applicant.name"/>
                <input :aria-label="`Applicant ${applicant.id} name input`"
                       :id="`applicant-input-${applicant.id}`"
                       @input="updateApplicant(applicant)"
                       type="text"
                       class="truncate mr-2 flex-1 text-orange-900"
                       v-model="applicant.name"/>
                <section class="controls flex">
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

        async updateApplicant(applicant: IApplicant) {
            await axios.put(`/applicants/${applicant.id}`, {'name': applicant.name});
        }
    }
</script>

<style lang="scss" scoped>
</style>
