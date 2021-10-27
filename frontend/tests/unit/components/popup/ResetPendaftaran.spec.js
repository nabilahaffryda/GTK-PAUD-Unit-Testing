import { createLocalVue, mount } from "@vue/test-utils";
import ResetPendaftaran from "@/components/popup/ResetPendaftaran.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('ResetPendaftaran.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    test('render popup reset pendaftaran', async () => {
        const VReset = {
            props: ['tahap'],
            template: '<div><slot :dense="dense" /></div>'
        }
        const wrapper = mount(ResetPendaftaran, {
            localVue,
            vuetify,
            stubs: {
                VReset
            },
        });
        expect(wrapper).toMatchSnapshot();
    })
})
