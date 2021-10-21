import { mount, createLocalVue } from "@vue/test-utils";
import BaseListMap from "@/components/base/BaseListMap.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseListMap.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render list map', () => {
        const VMap = {
            props: [
                'title',
                'location',
                'width',
                'zoom',
                'height',
                'markers',
                'icon'
            ],
            template: '<div><slot :zoom="zoom" /></div>'
        }

        const wrapper = mount(BaseListMap, {
            localVue,
            vuetify,
            stubs: {
                VMap
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})