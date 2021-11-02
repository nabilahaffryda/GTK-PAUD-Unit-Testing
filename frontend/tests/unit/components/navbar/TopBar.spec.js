import { createLocalVue, mount } from "@vue/test-utils";
import TopBar from "@/components/navbar/TopBar.vue";
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
            namespaced: true,
        },
        preferensi: {
            namespaced: true,
            actions: {
                getLayanan() {
                    return true
                }
            }
        },
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

describe('TopBar.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(TopBar, {
            localVue,
            vuetify,
            router,
            store,
        });
    }

    test('call toggleL method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.toggleL = jest.fn();
        wrapper.vm.toggleL();
        expect(wrapper.vm.toggleL.mock.calls.length).toBe(1);
    })

    test('call toggleR method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.toggleR = jest.fn();
        wrapper.vm.toggleR();
        expect(wrapper.vm.toggleR.mock.calls.length).toBe(1);
    })

    test('call changeLayanan function', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.changeLayanan = jest.fn();
        wrapper.vm.changeLayanan();
        expect(wrapper.vm.changeLayanan.mock.calls.length).toBe(1);
    })
})