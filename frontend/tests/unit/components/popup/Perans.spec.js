import { mount, createLocalVue } from "@vue/test-utils";
import Perans from "@/components/popup/Perans.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('Perans.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(Perans, {
            localVue,
            vuetify,
            router,
        });
    }
    test('call open method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.open = jest.fn();
        wrapper.vm.open();
        expect(wrapper.vm.open.mock.calls.length).toBe(1);
    })
    test('call onSwitch method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onSwitch = jest.fn();
        wrapper.vm.onSwitch();
        expect(wrapper.vm.onSwitch.mock.calls.length).toBe(1);
    })
})
