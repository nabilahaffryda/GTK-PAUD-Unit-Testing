import { createLocalVue, mount } from "@vue/test-utils";
import Upload from "@/components/popup/Upload.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('Upload.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Upload, {
            localVue,
            vuetify,
            router,
            props: {
                title: 'Unggah'
            }
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

    test('call getFile method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.getFile = jest.fn();
        wrapper.vm.getFile();
        expect(wrapper.vm.getFile.mock.calls.length).toBe(1);
    })

    test('call reset method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.reset = jest.fn();
        wrapper.vm.reset();
        expect(wrapper.vm.reset.mock.calls.length).toBe(1);
    })

    test('call validate method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.validate = jest.fn();
        wrapper.vm.validate();
        expect(wrapper.vm.validate.mock.calls.length).toBe(1);
    })

    test('call submit methods', async () => {
        const wrapper = wrapperFactory();
        wrapper.vm.submit = jest.fn();
        wrapper.vm.submit();
        expect(wrapper.vm.submit.mock.calls.length).toBe(1);
    })
})