import { mount, createLocalVue } from "@vue/test-utils";
import BaseListInfo from "@/components/base/BaseListInfo.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseListInfo.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('should show form radio', () => {
        const wrapper = mount(BaseListInfo, {
            localVue,
            vuetify,
            propsData: {
                info: []
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})
