import { mount, createLocalVue } from "@vue/test-utils";
import BaseChips from "@/components/base/BaseChips.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseChips.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseChips, {
            localVue,
            vuetify,
            router,
            propsData: {
                chip: {}
            }
        });
    }
    test('test base chips', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.propsData = jest.fn();
        wrapper.vm.propsData();
        expect(wrapper.vm.propsData.mock.calls.length).toBe(1);
    })
})
