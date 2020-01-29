import {shallowMount} from '@vue/test-utils';
import RaffleBox from './RaffleBox.vue';
import MockAdapter from 'axios-mock-adapter';
import axios from 'axios';
import flushPromises from 'flush-promises';

describe('RaffleBox', () => {
    it('requests all the applicants from the backend upon mount', async () => {
        const mockBackend = new MockAdapter(axios);
        const allApplicantsNames = [
            'Nick',
            'Felipe',
            'Colin'
        ];
        mockBackend.onGet('/phpstorm/applicants').reply(200, allApplicantsNames);
        const wrapper = shallowMount(RaffleBox);
        await flushPromises();


        expect(wrapper.findAll('.applicant-name')).toHaveLength(allApplicantsNames.length)
    });
});
