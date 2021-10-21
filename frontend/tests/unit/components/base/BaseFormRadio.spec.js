import { mount, createLocalVue } from "@vue/test-utils";
import BaseFormRadio from "@/components/base/BaseFormRadio.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseFormRadio.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('should show form radio', () => {
        const VRadio = {
            props: ['label',
                'labelColor',
                'required',
                'items',
                'value',
                'disabled',
                'row',
                'margin',
                'fontSize',
                'weight',
                'errorMessages',],
            template: '<div><slot :item="item" /></div>'
        }

        const wrapper = mount(BaseFormRadio, {
            localVue,
            vuetify,
            stubs: {
                VRadio
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})
