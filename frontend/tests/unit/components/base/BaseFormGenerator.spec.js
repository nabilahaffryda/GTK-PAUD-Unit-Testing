import { mount, createLocalVue } from "@vue/test-utils";
import BaseFormGenerator from "@/components/base/BaseFormGenerator.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseFormGenerator.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render form generator', () => {
        const Vgen = {
            props: ['schema', 'value'],
            template: '<div><slot :value="formData" /></div>'
        }

        const wrapper = mount(BaseFormGenerator, {
            localVue,
            vuetify,
            stubs: {
                Vgen
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})