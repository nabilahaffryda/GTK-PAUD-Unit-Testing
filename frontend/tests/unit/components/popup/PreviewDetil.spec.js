import { createLocalVue, mount } from "@vue/test-utils";
import PreviewDetil from "@/components/popup/PreviewDetil.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Viewer from 'v-viewer';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('PreviewDetil.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(PreviewDetil, {
            localVue,
            vuetify,
            router,
            components: {
                Viewer
            },
            computed: {
                images() {
                    return true
                },
                type() {
                    return true
                }
            },
            propsData: {
                url: 'https://penggerak-cdn.siap.id/s3/sekolahpenggerak/img/bg-parallax.png',
            },
        });
    }

    test('call open method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.open = jest.fn();
        wrapper.vm.open();
        expect(wrapper.vm.open.mock.calls.length).toBe(1);
    })

    test('call rotateLeft method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.rotateLeft = jest.fn();
        wrapper.vm.rotateLeft();
        expect(wrapper.vm.rotateLeft.mock.calls.length).toBe(1);
    })

    test('call rotateRight methods', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.rotateRight = jest.fn();
        wrapper.vm.rotateRight();
        expect(wrapper.vm.rotateRight.mock.calls.length).toBe(1);
    })

    test('call zoomOut methods', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.zoomOut = jest.fn();
        wrapper.vm.zoomOut();
        expect(wrapper.vm.zoomOut.mock.calls.length).toBe(1);
    })

    test('call zoomIn methods', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.zoomIn = jest.fn();
        wrapper.vm.zoomIn();
        expect(wrapper.vm.zoomIn.mock.calls.length).toBe(1);
    })
})