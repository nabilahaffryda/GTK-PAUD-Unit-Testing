import { mount, createLocalVue } from "@vue/test-utils";
import GotoModul from "@/components/popup/GotoModul.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import PopupNotifikasi from "@/components/popup/Notifikasi"

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('GotoModul.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(GotoModul, {
            localVue,
            vuetify,
            router,
            components: {
                PopupNotifikasi
            },
        });
    }
    test('call open method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.open = jest.fn();
        wrapper.vm.open();
        expect(wrapper.vm.open.mock.calls.length).toBe(1);
    })
    test('call close method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.close = jest.fn();
        wrapper.vm.close();
        expect(wrapper.vm.close.mock.calls.length).toBe(1);
    })
    test('call onConfirm method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onConfirm = jest.fn();
        wrapper.vm.onConfirm();
        expect(wrapper.vm.onConfirm.mock.calls.length).toBe(1);
    })
})
