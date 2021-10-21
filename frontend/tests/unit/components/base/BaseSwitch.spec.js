import { mount, createLocalVue } from "@vue/test-utils";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseSwitch.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render switch', () => {
        const VSwitch = {
            props: ['value', 'isAktif', 'isTutup'],
            template: '<div><slot :disabled="isTutup" /></div>'
        }

        const wrapper = mount(BaseSwitch, {
            localVue,
            vuetify,
            stubs: {
                VSwitch
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})