import { mount, createLocalVue } from "@vue/test-utils";
import BasePhotoProfil from "@/components/base/BasePhotoProfil.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BasePhotoProfil.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(BasePhotoProfil, {
            localVue,
            vuetify,
            router,
            props: {
                photo: 'avatar.png'
            }
        });
    }
    test('call upload method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.upload = jest.fn();
        wrapper.vm.upload();
        expect(wrapper.vm.upload.mock.calls.length).toBe(1);
    })
    test('call uploaded method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.uploaded = jest.fn();
        wrapper.vm.uploaded();
        expect(wrapper.vm.uploaded.mock.calls.length).toBe(1);
    })
    test('call checkImage method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.checkImage = jest.fn();
        wrapper.vm.checkImage();
        expect(wrapper.vm.checkImage.mock.calls.length).toBe(1);
    })
})