import { mount, createLocalVue } from "@vue/test-utils";
import Loader from "@/components/popup/Loader.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('Loader.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    test('render loader', () => {
        const VLoader = {
            props: ['loading'],
            template: '<div><slot :loading="loading" /></div>'
        }
        const wrapper = mount(Loader, {
            localVue,
            vuetify,
            stubs: {
                VLoader
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})
