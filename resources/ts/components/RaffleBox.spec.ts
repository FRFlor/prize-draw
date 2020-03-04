import {shallowMount, Wrapper} from '@vue/test-utils';
import RaffleBox from './RaffleBox.vue';
import MockAdapter from 'axios-mock-adapter';
import axios from 'axios';
import flushPromises from 'flush-promises';

jest.useFakeTimers();

describe('RaffleBox', () => {
    let mockBackend: MockAdapter;
    let wrapper: Wrapper<RaffleBox>;

    const allApplicantsNames = [
        'Nick',
        'Grant',
        'Adam',
        'Colin',
        'Dan'
    ];

    beforeEach(async () => {
        mockBackend = new MockAdapter(axios);
        mockBackend.onGet('/applicants').reply(200, Object.values(allApplicantsNames));
        wrapper = shallowMount(RaffleBox);
        await flushPromises();
    });

    afterEach(() => {
       mockBackend.restore();
    });

    it('requests all the applicants from the backend upon mount', async () => {
        expect(wrapper.findAll('.applicant-name')).toHaveLength(allApplicantsNames.length)
    });

    describe('starts raffling when the raffle button is clicked', () => {
        it('removes a name every half second', async () => {
            wrapper.find('.start-raffling').trigger('click');

            jest.advanceTimersByTime(500);
            await flushPromises();
            expect(wrapper.findAll('.applicant-name')).toHaveLength(allApplicantsNames.length - 1);

            jest.advanceTimersByTime(500);
            await flushPromises();
            expect(wrapper.findAll('.applicant-name')).toHaveLength(allApplicantsNames.length - 2);
        });


        it("stops raffling when there's a single applicant left", async () => {
            wrapper.find('.start-raffling').trigger('click');

            const A_LONG_LONG_TIME_PRETTY_MUCH_HOW_LONG_IT_TAKES_FOR_PHPSTORM_TO_INDEX = 1000000;

            jest.advanceTimersByTime(A_LONG_LONG_TIME_PRETTY_MUCH_HOW_LONG_IT_TAKES_FOR_PHPSTORM_TO_INDEX);
            await flushPromises();

            expect(wrapper.findAll('.applicant-name')).toHaveLength(1);
        });

        it("Displays the winner", async () => {
            wrapper.find('.start-raffling').trigger('click');

            expect(wrapper.find('.winner-box').isVisible()).toBe(false);
            jest.advanceTimersByTime(allApplicantsNames.length * 500);
            jest.advanceTimersByTime(500);
            await flushPromises();

            expect(wrapper.find('.winner-box').isVisible()).toBe(true);
            expect(wrapper.find('.winner-box').text()).toContain('Congratulations');
        });
    });
});
