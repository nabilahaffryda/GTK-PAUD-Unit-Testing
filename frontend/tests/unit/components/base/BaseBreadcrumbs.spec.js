import { mount, createLocalVue } from "@vue/test-utils";
import BaseBreadcrumbs from "@/components/base/BaseBreadcrumbs.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseBreadcrumbs.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('should show breadcrumbs', () => {
        const VBreadcrumbs = {
            props: ['items', 'icon', 'useIcon'],
            template: '<div><slot :items="items" /></div>'
        }

        const wrapper = mount(BaseBreadcrumbs, {
            localVue,
            vuetify,
            stubs: {
                VBreadcrumbs
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})
