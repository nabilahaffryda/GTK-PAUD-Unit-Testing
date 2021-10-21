import { mount, createLocalVue } from "@vue/test-utils";
import BaseTableFooter from "@/components/base/BaseTableFooter.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseTableFooter.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render table footer', () => {
        const VFooter = {
            props: ['pageTotal', 'total', 'circle', 'useNumber'],
            template: '<div><slot :circle="circle" /></div>'
        }

        const wrapper = mount(BaseTableFooter, {
            localVue,
            vuetify,
            stubs: {
                VFooter
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})
