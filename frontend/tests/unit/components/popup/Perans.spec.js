import { mount, createLocalVue } from "@vue/test-utils";
import Perans from "@/components/popup/Perans.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('Perans.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    test('render popup peran', () => {
        const wrapper = mount(Perans, {
            localVue,
            vuetify,
            data() {
                return {
                    roles: [
                        { key: 'instansi', label: 'Admin Instansi' },
                        { key: 'gtk', label: 'GTK' },
                    ],
                };
            },
        });
        expect(wrapper).toMatchSnapshot();
    });
})
