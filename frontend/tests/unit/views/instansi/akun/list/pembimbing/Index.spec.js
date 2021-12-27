import { createLocalVue, mount, } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import axios from 'axios';
import { getDeepObj, isObject, assetsUrl, localDate } from '@utils/format';
import Index from "@/views/instansi/akun/list/pembimbing/Index.vue";

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

document.body.setAttribute('data-app', true)

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        master: {
            namespaced: true,
            actions: {
                getMasters() {
                    return true
                },
            }
        },
        institusi: {
            namespaced: true,
            listInstansis: {
                fetch() {
                    return true
                }
            }
        },
        akun: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                create() {
                    return true
                },
                update() {
                    return true
                },
                listGroups() {
                    return true
                },
                action() {
                    return true
                },
                lookup() {
                    return true
                },
                getDetail() {
                    return true
                },
                downloadList() {
                    return true
                },
                templateUpload() {
                    return true
                },
                upload() {
                    return true
                },
                setStatus() {
                    return true
                },
            }
        }
    },
})

localVue.mixin({
    methods: {
        $titleCase(kata) {
            return (kata && kata.charAt(0).toUpperCase() + kata.slice(1)) || '';
        },
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $imgUrl: (url) => (url ? assetsUrl(url) : ''),
        $localDate(date, short, withTime, usingNumber, useSeconds) {
            if (!date) return '-';
            return localDate(date, short, withTime, usingNumber, useSeconds);
        },
        $allow() {
            return true;
        },
        $isObject(data) {
            return isObject(data || {});
        },
    }
})

jest.mock('axios');

describe('Index.vue', () => {
    let vuetify;
    beforeEach(() => {
        vuetify = new Vuetify()
        const responseGet = {
            data: {
                policies: {
                    'akun-${akses}.download-aktivasi': true,
                    'akun-${akses}.download': true
                }
            },
        }
        axios.get.mockResolvedValue(responseGet);
    })
    afterEach(() => {
        jest.resetModules()
        jest.clearAllMocks()
    })
    function wrapperFactory({ } = {}) {
        return mount(Index, {
            localVue,
            vuetify,
            router,
            store,
            stubs: {
                BaseModalFull: true,
            },
            // methods: {
            //     onDownload() {
            //         return this.$confirm = true
            //     }
            // }
        })
    }

    test('test reload button', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onReload = jest.fn();
        wrapper.vm.$nextTick();
        expect(wrapper.find('.mdi-reload').exists()).toBe(true);
        const btn = wrapper.find('.mdi-reload')
        btn.trigger('click')
        wrapper.vm.onReload();
    })

    test('test filter button', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onFilter = jest.fn();
        wrapper.vm.$nextTick();
        expect(wrapper.find('.mdi-filter-variant').exists()).toBe(true);
        const button = wrapper.find('.mdi-filter-variant')
        button.trigger('click')
        wrapper.vm.onFilter();
    })

    test('test search button', () => {
        const wrapper = wrapperFactory()
        wrapper.vm.onSearch = jest.fn();
        expect(wrapper.find('.mdi-magnify').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const search = wrapper.find('.mdi-magnify')
        search.trigger('click')
        wrapper.vm.onSearch()
    })

    test('test download button', () => {
        const wrapper = wrapperFactory()
        wrapper.vm.onDownload = jest.fn();
        expect(wrapper.find('.mdi-download').exists()).toBe(true)
        wrapper.vm.onDownload()
    })

    test('test set pembimbing praktik button', () => {
        const wrapper = wrapperFactory()
        wrapper.setProps({
            akses: 'pembimbing-praktik',
        })
        wrapper.vm.$nextTick()
        expect(wrapper.find('[id="set-pembimbing"]').exists()).toBe(true);
        const button = wrapper.find('[id="set-pembimbing"]')
        button.trigger('click')
        wrapper.findAll('[id="set-pembimbing"]').at(0).trigger('click');
    })
})
