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
    const VUpload = {
        props: ['title', 'min', 'max', 'format', 'rules', 'labelOk'],
        template: '<div><slot :format="format" /></div>'
    }
    const wrapper = mount(Upload, {
        localVue,
        vuetify,
        router,
        VUpload,
        props: {
            title: 'Unggah'
        }
    });
    test('call open method', () => {
        wrapper.vm.open = jest.fn();
        wrapper.vm.open();
        expect(wrapper.vm.open.mock.calls.length).toBe(1);
    })
    test('call close method', () => {
        wrapper.vm.close = jest.fn();
        wrapper.vm.close();
        expect(wrapper.vm.close.mock.calls.length).toBe(1);

        // const closeBtn = '.close-btn';
        // wrapper.find(closeBtn).trigger('click');
        // expect(wrapper.vm.close).toBeCalled();
    })

    test('call getFile method', () => {
        wrapper.vm.getFile = jest.fn();
        wrapper.vm.getFile();
        expect(wrapper.vm.getFile.mock.calls.length).toBe(1);
    })

    test('call reset method', () => {
        wrapper.vm.reset = jest.fn();
        wrapper.vm.reset();
        expect(wrapper.vm.reset.mock.calls.length).toBe(1);

        // const resetBtn = '.reset-btn';
        // wrapper.find(resetBtn).trigger('click');
        // expect(wrapper.vm.reset).toBeCalled();
    })

    test('call validate method', () => {
        wrapper.vm.validate = jest.fn();
        wrapper.vm.validate();
        expect(wrapper.vm.validate.mock.calls.length).toBe(1);
    })

    test('call submit methods', async () => {
        wrapper.vm.submit = jest.fn();
        wrapper.vm.submit();
        expect(wrapper.vm.submit.mock.calls.length).toBe(1);

        // const box = wrapper.find(".submit-btn");
        // await box.trigger("click");
        // expect(wrapper.vm.submit).toHaveBeenCalled();
    })
    expect(wrapper).toMatchSnapshot();

    // test('emit events when close-btn clicked', () => {
    //     const closeBtn = wrapper.find('.close-btn');
    //     closeBtn.trigger('click');
    //     expect(wrapper.emitted().close.length).toBe(1);
    // });
    // test('clicking submit button triggers submit()', () => {
    //     wrapper
    //         .find('[data-testid="popup-upload-submit-button"]')
    //         .trigger('click');
    //     window.open = jest.fn();
    //     wrapper.vm.submit = jest.fn();
    //     const submitBtn = '.submit-btn';
    //     wrapper.find(submitBtn).trigger('click');
    //     expect(wrapper.vm.submit).toBeCalled();
    // });
})