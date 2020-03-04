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
        it('displays the start raffling button and not the winner box until the raffle begins', () => {
           expect(wrapper.find('.winner-box').isVisible()).toBe(false);
           expect(wrapper.find('.start-raffling').isVisible()).toBe(true);
        });

        it('hides the raffling button and displays the winner box with no name as soon as the raffling begins', async () =>{
            wrapper.find('.start-raffling').trigger('click');

            jest.advanceTimersByTime(500);
            await flushPromises();

            expect(wrapper.find('.winner-box').isVisible()).toBe(true);
            expect(wrapper.find('.start-raffling').isVisible()).toBe(false);
            expect(wrapper.find('.winner-box').text()).toBe("???");
        });

        it('removes a name from the running list every half second', async () => {
            wrapper.find('.start-raffling').trigger('click');

            jest.advanceTimersByTime(500);
            await flushPromises();
            expect(wrapper.find('.running-list').findAll('.applicant-name')).toHaveLength(allApplicantsNames.length - 1);

            jest.advanceTimersByTime(500);
            await flushPromises();
            expect(wrapper.find('.running-list').findAll('.applicant-name')).toHaveLength(allApplicantsNames.length - 2);
        });

        it('adds the most recently removed name into the eliminated list', async () => {
            wrapper.find('.start-raffling').trigger('click');

            jest.advanceTimersByTime(500);
            await flushPromises();
            expect(wrapper.find('.eliminated-list').findAll('.applicant-name')).toHaveLength(1);

            jest.advanceTimersByTime(500);
            await flushPromises();
            expect(wrapper.find('.eliminated-list').findAll('.applicant-name')).toHaveLength(2);
        });

        it('displays the placement number of the drawn applicant', async () => {
            wrapper.find('.start-raffling').trigger('click');

            jest.advanceTimersByTime(500);
            await flushPromises();
            expect(wrapper.find('.eliminated-list').findAll('.applicant-name').at(0).text()).toContain(allApplicantsNames.length);

            jest.advanceTimersByTime(500);
            await flushPromises();
            expect(wrapper.find('.eliminated-list').findAll('.applicant-name').at(0).text()).toContain(allApplicantsNames.length - 1);

            jest.advanceTimersByTime(500);
            await flushPromises();
            expect(wrapper.find('.eliminated-list').findAll('.applicant-name').at(0).text()).toContain(allApplicantsNames.length - 2);
        });

        it('stops raffling when there are no running applicants left', async () => {
            wrapper.find('.start-raffling').trigger('click');

            const A_LONG_LONG_TIME_PRETTY_MUCH_HOW_LONG_IT_TAKES_FOR_PHPSTORM_TO_INDEX = 1000000;

            jest.advanceTimersByTime(A_LONG_LONG_TIME_PRETTY_MUCH_HOW_LONG_IT_TAKES_FOR_PHPSTORM_TO_INDEX);
            await flushPromises();

            expect(wrapper.find('.running-list').findAll('.applicant-name')).toHaveLength(0);
        });

        it('Displays the winner', async () => {
            jest.spyOn(global.Math, 'random').mockReturnValue(0);
            wrapper.find('.start-raffling').trigger('click');

            expect(wrapper.find('.winner-box').isVisible()).toBe(false);
            jest.advanceTimersByTime(allApplicantsNames.length * 500);
            jest.advanceTimersByTime(500);
            await flushPromises();

            expect(wrapper.find('.winner-box').isVisible()).toBe(true);
            const lastName = allApplicantsNames[allApplicantsNames.length - 1];
            expect(wrapper.find('.winner-box').text()).toContain(lastName);

            //@ts-ignore
            global.Math.random.mockRestore();
        });
    });
});
