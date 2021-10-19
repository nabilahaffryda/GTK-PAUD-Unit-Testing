import { mount, createLocalVue } from "@vue/test-utils";
import BaseCardSelected from "@/components/base/BaseCardSelected.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseCardSelected.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('should show card selected', () => {
        const VCard = {
            props: ['title', 'subtitle', 'desc',
                'icon', 'color', 'deepColor',
                'akses', 'disable', 'to',
                'href', 'target', 'action'],
            template: '<div><slot name="button" /></div>'
        }

        const wrapper = mount(BaseCardSelected, {
            localVue,
            vuetify,
            stubs: {
                VCard
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})
