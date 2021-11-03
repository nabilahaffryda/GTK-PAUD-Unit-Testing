import { createLocalVue, mount } from "@vue/test-utils";
import Akun from "@/components/cetak/Akun.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import { assetsUrl, getDeepObj, localDate } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

localVue.mixin({
    methods: {
        $imgUrl: (url) => (url ? assetsUrl(url) : ''),
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $localDate(date, short, withTime, usingNumber, useSeconds) {
            if (!date) return '-';
            return localDate(date, short, withTime, usingNumber, useSeconds);
        },
    }
})

describe('Akun.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Akun, {
            localVue,
            vuetify,
            router,
            propsData: {
                akun: {
                    nama: 'bila',
                    email: 'bila@gmail.com',
                }
            }

        });
    }
    test('call print method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.print = jest.fn();
        wrapper.vm.print();
        expect(wrapper.vm.print.mock.calls.length).toBe(1);
    })
})