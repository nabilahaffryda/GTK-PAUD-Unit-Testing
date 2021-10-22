import { mount, createLocalVue } from "@vue/test-utils";
import BaseChips from "@/components/base/BaseChips.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseChips.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    test('should show chip', () => {
        const VChip = {
            props: ['color', 'colorIdx', 'chip'],
            template: '<div><slot :chip="chip"/></div>'
        }
        const wrapper = mount(BaseChips, {
            localVue,
            vuetify,
            stubs: {
                VChip
            },
            propsData: {
                chip: {}
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})
