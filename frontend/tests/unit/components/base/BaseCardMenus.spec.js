import { mount, createLocalVue } from "@vue/test-utils";
import BaseCardMenus from "@/components/base/BaseCardMenus.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseCardMenus.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('should show card menus', () => {
        const VCard = {
            props: ['subtitle',
                'icon',
                'akses', 'disable', 'to',
                'href', 'target', 'action'],
            template: '<div><slot name="content" /></div>'
        }

        const wrapper = mount(BaseCardMenus, {
            localVue,
            vuetify,
            stubs: {
                VCard
            },
            propsData: {
                title: 'String',
                desc: 'SIM Paud',
                color: '#000000',
                deepColor: '#111111'
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})
