import { mount, createLocalVue } from "@vue/test-utils";
import BaseListFilter from "@/components/base/BaseListFilter.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseListFilter.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render list filter', () => {
        const VFilter = {
            props: [
                'syncMutate',
                'filtered',
                'filters',
                'paramsFilter',
                'closeable',
                'title'
            ],
            template: '<div><slot :tile="tile" /></div>'
        }

        const wrapper = mount(BaseListFilter, {
            localVue,
            vuetify,
            stubs: {
                VFilter
            },
            propsData: {
                title: 'filter'
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})