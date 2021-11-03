import { mount, createLocalVue } from "@vue/test-utils";
import BaseCardMenus from "@/components/base/BaseCardMenus.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseCardMenus.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseCardMenus, {
            localVue,
            vuetify,
            router,
            propsData: {
                title: 'Paud',
                desc: 'SIM Paud',
                color: '#000000',
                deepColor: '#111111'
            }
        });
    }
    test('call onAction method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onAction = jest.fn();
        wrapper.vm.onAction();
        expect(wrapper.vm.onAction.mock.calls.length).toBe(1);
    })
})
