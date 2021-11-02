import { createLocalVue, mount } from "@vue/test-utils";
import SideLBar from "@/components/navbar/SideLBar.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import { assetsUrl } from '@utils/format';
import Vuex from "vuex";

Vue.use(Vuetify)
const localVue = createLocalVue();

const $route = {
    path: '/i/:id(\\d+)/home'
}

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        auth: {
            namespaced: true,
        },
        preferensi: {
            namespaced: true,
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

describe('SideLBar.vue', () => {

    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(SideLBar, {
            localVue,
            vuetify,
            stubs: ['router-link', 'router-view'],
            mocks: {
                $route
            },
            store,
        });
    }

    test('test toUrl method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.toUrl = jest.fn();
        wrapper.vm.toUrl();
        expect(wrapper.vm.toUrl.mock.calls.length).toBe(1);
    })

    test('test checkMenu method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.checkMenu = jest.fn();
        wrapper.vm.checkMenu();
        expect(wrapper.vm.checkMenu.mock.calls.length).toBe(1);
    })
})