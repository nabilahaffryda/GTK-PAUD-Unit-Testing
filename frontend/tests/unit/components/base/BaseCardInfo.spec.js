import { mount, createLocalVue } from "@vue/test-utils";
import BaseCardInfo from "@/components/base/BaseCardInfo.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseCardInfo.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('should show card info', () => {
        const VCard = {
            props: ['size', 'icon', 'title', 'info'],
            template: '<div><slot :size="size" /></div>'
        }

        const wrapper = mount(BaseCardInfo, {
            localVue,
            vuetify,
            stubs: {
                VCard
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})
