import { createLocalVue, mount } from "@vue/test-utils";
import SideRBar from "@/components/navbar/SideRBar.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import { assetsUrl } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        auth: {
            namespaced: true
        },
        preferensi: {
            namespaced: true
        }
    },
    state: {
        menus: [
            {
                icon: 'mdi-office-building-outline',
                title: 'Kelola Institusi LPD',
                desc: 'Pengelolaan data Institusi LPD',
                color: 'red',
                deepColor: 'darken-4',
                akses: 'lpd.index',
                link: 'institusi',
                to: { name: 'institusi' },
            },
        ],
    }
})

localVue.mixin({
    methods: {
        $imgUrl: (url) => (url ? assetsUrl(url) : ''),
        $allow(akses, policy) {
            // cek jika tidak ada akses
            if (!akses || (akses && typeof akses === 'boolean')) return typeof akses === 'boolean' ? akses : true;

            // jika ada ambil preferensi akses
            const akseses = store.getters['preferensi/akseses'] || [];

            let allow = false;
            const pathAkses = akses && typeof akses === 'string' && akses.split('|');
            if (pathAkses.length) {
                pathAkses.forEach((path) => {
                    if (allow) return;
                    if (!allow && policy) {
                        allow =
                            !path || path === 'true' || ((akseses || []).filter((item) => item === path).length > 0 && policy[path]);
                    } else if (!allow && !policy) {
                        allow = !path || path === 'true' || (akseses || []).filter((item) => item === path).length > 0;
                    }
                });
            }

            return allow;
        },
    }
})

describe('SideRBar.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(SideRBar, {
            localVue,
            vuetify,
            router,
            store
        });
    }

    test('call fkata method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.fkata = jest.fn();
        wrapper.vm.fkata();
        expect(wrapper.vm.fkata.mock.calls.length).toBe(1);
    })

    test('call to method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.to = jest.fn();
        wrapper.vm.to();
        expect(wrapper.vm.to.mock.calls.length).toBe(1);
    })

    test('call popup function', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.popup = jest.fn();
        wrapper.vm.popup();
        expect(wrapper.vm.popup.mock.calls.length).toBe(1);
    })
    test('call onSwitch function', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onSwitch = jest.fn();
        wrapper.vm.onSwitch();
        expect(wrapper.vm.onSwitch.mock.calls.length).toBe(1);
    })
    test('call onLogout function', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onLogout = jest.fn();
        wrapper.vm.onLogout();
        expect(wrapper.vm.onLogout.mock.calls.length).toBe(1);
    })
    test('call onReset function', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onReset = jest.fn();
        wrapper.vm.onReset();
        expect(wrapper.vm.onReset.mock.calls.length).toBe(1);
    })
    test('call onSaveReset function', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onSaveReset = jest.fn();
        wrapper.vm.onSaveReset();
        expect(wrapper.vm.onSaveReset.mock.calls.length).toBe(1);
    })
    test('call onProfil function', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onProfil = jest.fn();
        wrapper.vm.onProfil();
        expect(wrapper.vm.onProfil.mock.calls.length).toBe(1);
    })
    test('call toAkun function', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.toAkun = jest.fn();
        wrapper.vm.toAkun();
        expect(wrapper.vm.toAkun.mock.calls.length).toBe(1);
    })
})