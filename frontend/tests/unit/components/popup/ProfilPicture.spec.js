import { mount, createLocalVue } from "@vue/test-utils";
import ProfilPicture from "@/components/popup/ProfilPicture.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import AvatarCropper from 'vue-avatar-cropper';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('ProfilPicture.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(ProfilPicture, {
            localVue,
            vuetify,
            router,
            stubs: {
                AvatarCropper
            },
            propsData: {
                trigger: 'trigger'
            },
            props: {
                useBase64: true
            },
        });
    }

    test('call onUpload method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onUpload = jest.fn();
        wrapper.vm.onUpload();
        expect(wrapper.vm.onUpload.mock.calls.length).toBe(1);
    })
    test('call onUploaded method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onUploaded = jest.fn();
        wrapper.vm.onUploaded();
        expect(wrapper.vm.onUploaded.mock.calls.length).toBe(1);
    })
    test('call onChange method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onChange = jest.fn();
        wrapper.vm.onChange();
        expect(wrapper.vm.onChange.mock.calls.length).toBe(1);
    })
})
