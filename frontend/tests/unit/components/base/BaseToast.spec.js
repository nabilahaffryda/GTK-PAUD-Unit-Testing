import { mount, createLocalVue } from "@vue/test-utils";
import BaseToast from "@/components/base/BaseToast.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseToast.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render toast', () => {
        const wrapper = mount(BaseToast, {
            localVue,
            vuetify,
            data() {
                return {
                    active: false,
                    text: '',
                    icon: '',
                    color: 'info',
                    timeout: 4000,
                    dismissible: true,
                    button: [{ label: 'Tutup' }],
                }
            }
        });
        wrapper.findAll('#data').exists();
        expect(wrapper).toMatchSnapshot();
    })
})
