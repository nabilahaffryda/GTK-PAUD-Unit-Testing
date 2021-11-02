import { mount, createLocalVue } from "@vue/test-utils";
import Loader from "@/components/popup/Loader.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('Loader.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Loader, {
            localVue,
            vuetify,
            router,
        });
    }
    test('call setDialog method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.setDialog = jest.fn();
        wrapper.vm.setDialog();
        expect(wrapper.vm.setDialog.mock.calls.length).toBe(1);
    })
})
