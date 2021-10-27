import { mount, createLocalVue } from "@vue/test-utils";
import BaseCascade from "@/components/base/BaseCascade.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
// import mixins from "@/mixins/global"
import * as getDeepObj from '@/mixins/global';

Vue.use(Vuetify)
const localVue = createLocalVue();

getDeepObj.getAll = jest.fn()

describe('BaseCascade.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render cascade', () => {
        const VCascade = {
            props: [
                'value',
                'configs',
                'labelColor'],
            template: '<div><slot :items="items[field]" /></div>'
        }
        resetAll();
        const wrapper = mount(BaseCascade, {
            localVue,
            vuetify,
            stubs: {
                VCascade
            },
            getDeepObj
            // mixins: [
            //     mixins
            // ],

        });
        expect(wrapper).toMatchSnapshot();
    })
})
