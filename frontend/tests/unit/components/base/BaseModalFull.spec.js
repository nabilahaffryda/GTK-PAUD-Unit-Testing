import { mount, createLocalVue } from "@vue/test-utils";
import BaseModalFull from "@/components/base/BaseModalFull.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseModalFull.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render modal full', () => {
        const VModal = {
            props: [
                'title',
                'tabHeader',
                'mode',
                'generalError',
                'useSave',
                'colorBtn',
                'lblBtn',
                'autoClose',
                'fluid'
            ],
            template: '<div><slot :fluid="fluid" /></div>'
        }
        const wrapper = mount(BaseModalFull, {
            localVue,
            vuetify,
            stubs: {
                VModal
            },
        });
        expect(wrapper).toMatchSnapshot();
    })
})