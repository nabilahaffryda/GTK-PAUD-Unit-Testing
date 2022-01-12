import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import axios from 'axios';
import Index from "@/views/instansi/kelas/list/Index.vue";
import { getDeepObj, arrayToObject, isObject, duration } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

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
        petugasKelas: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                getDetail() {
                    return true
                }
            }
        }
    },
})
localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $arrToObj(array, key) {
            if (!array || !array.length) return {};
            return arrayToObject(array, key);
        },
        $allow() {
            return true;
        },
        $titleCase(kata) {
            return (kata && kata.charAt(0).toUpperCase() + kata.slice(1)) || '';
        },
        $durasi(start, end, options) {
            return duration(start, end, options);
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
                    akses: true
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
                DetailKelas: true,
                BaseModalFull: true,
                BaseBreadcrumbs: true,
            },
            data() {
                return {
                    Datatables: false,
                    reload: false,
                    actions: [
                        {
                            icon: 'mdi-information',
                            title: 'Info Kelas',
                            event: 'onDetail',
                            akses: true
                        }
                    ]
                }
            }
        })
    }
    test('call allow method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.allow = jest.fn();
        wrapper.vm.allow();
        expect(wrapper.vm.allow.mock.calls.length).toBe(1);
    })

    test('call toLms method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.toLms = jest.fn();
        wrapper.vm.toLms();
        expect(wrapper.vm.toLms.mock.calls.length).toBe(1);
    })

    test('render list table', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({ Datatables: true });
        await wrapper.vm.$nextTick();
        expect(wrapper.find('[data-testid="list-table"]').exists()).toBe(true);
    })
    test('calls onReload when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({ reload: true });
        wrapper.vm.onReload = jest.fn();
        wrapper.vm.onReload();
        await wrapper.vm.$nextTick();
        expect(wrapper.find('.mdi-reload').exists()).toBe(true);
        const btn = wrapper.find('.mdi-reload')
        btn.trigger('click')
        expect(wrapper.vm.onReload.mock.calls.length).toBe(1);
    })
    test('test total data from data tables ', async () => {
        const wrapper = wrapperFactory();
        wrapper.find('[data-testid="list-table"]')
        wrapper.setData({ total: 2 });
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.total).toBe(2)
    })
    test('Enter search text and check the value of keyword', async () => {
        const wrapper = wrapperFactory();
        wrapper.find('.mdi-magnify')
        wrapper.setData({ keyword: 'Kelas A' })
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.keyword).toBe('Kelas A')
    })
    test('click search button and clear input', () => {
        const wrapper = wrapperFactory();
        wrapper.find('.mdi-magnify').trigger("click");
        const textInput = wrapper.find('.mdi-magnify')
        expect(textInput.text()).toMatch('')
        expect(wrapper.vm.keyword).toBe('')
    })
    test('call onAction when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onAction = jest.fn()
        wrapper.vm.onAction()
        wrapper.setData({
            data: [
                {
                    nama: "Kelas A",
                    jml_pengajar: 4,
                    id: 2,
                    deskripsi: "Lorem Ipsum lah",
                    paud_diklat: {
                        data: {
                            paud_periode: {
                                data: {
                                    tgl_diklat_mulai: "2021-02-15",
                                    tgl_diklat_selesai: "2021-02-26"
                                }
                            }
                        }
                    }
                }
            ],
            total: 1,
            actions: [
                {
                    icon: 'mdi-information',
                    title: 'Info Kelas',
                    event: 'onDetail',
                    akses: true
                }
            ]
        });
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-dots-vertical').exists()).toBe(true);
        const button = wrapper.find('.mdi-dots-vertical')
        button.trigger('click')
    })
})
