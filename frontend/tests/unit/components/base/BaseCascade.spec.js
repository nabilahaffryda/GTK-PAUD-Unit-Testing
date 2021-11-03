import { createLocalVue, mount } from "@vue/test-utils";
import BaseCascade from "@/components/base/BaseCascade.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import { getDeepObj } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
    }
})

describe('BaseCascade.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseCascade, {
            localVue,
            vuetify,
            router,
            propsData: {
                configs: {
                    selector: ['k_propinsi', 'k_kota'],
                    required: [],
                    label: ['Provinsi', 'Kota/Kabupaten'],
                    options: [[], []],
                    grid: [{ cols: 6 }, { cols: 6 }],
                }
            },
        });
    }

    test('call resetAll method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.resetAll = jest.fn();
        wrapper.vm.resetAll();
        expect(wrapper.vm.resetAll.mock.calls.length).toBe(1);
    })

    test('call resetField method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.resetField = jest.fn();
        wrapper.vm.resetField();
        expect(wrapper.vm.resetField.mock.calls.length).toBe(1);
    })

    test('call update function', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.update = jest.fn();
        wrapper.vm.update();
        expect(wrapper.vm.update.mock.calls.length).toBe(1);
    })
})