import {shallowMount, Wrapper} from '@vue/test-utils';
import ApplicantsManager from './ApplicantsManager.vue';
import flushPromises from 'flush-promises';
import MockAdapter from 'axios-mock-adapter';
import {IApplicant} from '../types';
import axios from 'axios';

const applicants: IApplicant[] = [
    {id: 1, name: 'Nick'},
    {id: 2, name: 'Grant'},
    {id: 3, name: 'Adam'},
    {id: 4, name: 'Colin'},
    {id: 5, name: 'Dan'},
];

describe('ApplicantsManager', () => {
    let wrapper: Wrapper<ApplicantsManager>;
    let mockBackend: MockAdapter;

    beforeEach(async () => {
        mockBackend = new MockAdapter(axios);
        mockBackend.onGet('/applicants').reply(200, Object.values(applicants));
        mockBackend.onPut(new RegExp('/applicants/*')).reply(200, Object.values(applicants));
        mockBackend.onPost('/applicants').reply((config) => {
            let givenPayload = JSON.parse(config.data);
            let randomId = Math.floor(Math.random()*1000);

            return [201, {id: randomId, name: givenPayload.name}]
        });
        wrapper = shallowMount(ApplicantsManager, {
            methods: {debounce: (cb) => cb()}
        });
        await flushPromises()
    });

    afterEach(() => {
        mockBackend.restore();
    });

    it('Starts by displaying all existing applicants', async () => {
        applicants.forEach((applicant) => expect(wrapper.text()).toContain(applicant.name))
    });

    it('Updates an existing applicants name as the user types', async () => {
        let chosenApplicant = applicants[0];
        let nameInput = wrapper.find(`#applicant-input-${chosenApplicant.id}`);
        let newName = 'New Applicant Name';

        nameInput.setValue(newName);
        await flushPromises();

        let lastPutSubmission = mockBackend.history.put.pop();
        expect(lastPutSubmission.url).toEqual(`/applicants/${chosenApplicant.id}`);
        expect(JSON.parse(lastPutSubmission.data)).toEqual({name: newName});
    });

    it('Allows a new applicant to be inserted to the list', async () => {
        let newApplicantInput = wrapper.find('#new-applicant-input');
        let newApplicantName = 'John Cena';

        newApplicantInput.setValue(newApplicantName);
        newApplicantInput.trigger('keyup.enter');
        await flushPromises();

        let lastPostSubmission = mockBackend.history.post.pop();

        expect(lastPostSubmission.url).toEqual(`/applicants`);
        expect(JSON.parse(lastPostSubmission.data)).toEqual({name: newApplicantName});
        expect(wrapper.text()).toContain(newApplicantName);
    });
});
