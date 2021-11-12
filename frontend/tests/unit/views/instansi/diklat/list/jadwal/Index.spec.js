import { createLocalVue, mount, config } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import axios from 'axios';
import Index from "@/views/instansi/diklat/list/jadwal/Index.vue";
import { getDeepObj, isObject, duration, } from '@utils/format';

config.showDeprecationWarnings = false;

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        diklatPeriode: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                getDetail() {
                    return true
                },
                create() {
                    return true
                },
                update() {
                    return true
                },
                delete() {
                    return true
                },
            }
        }
    },
})
localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $allow() {
            return true;
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
                    'diklat-periode.update': true,
                    'diklat-periode.create': true,
                    'diklat-periode.delete': true
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
                FormJadwal: true,
            },
            methods: {
                fetchData() {
                    return true
                }
            },
            data() {
                return {
                    reload: false,
                    actions: [
                        {
                            icon: 'mdi-pencil',
                            title: 'Ubah',
                            event: 'onEdit',
                            akses: 'diklat-periode.update'
                        }
                    ],
                }
            },
        })
    }
    test('call onSave method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onSave = jest.fn();
        wrapper.vm.onSave();
        expect(wrapper.vm.onSave.mock.calls.length).toBe(1);
    })
    test('test total data', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({ total: 10 });
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.total).toBe(10)
    })
    test('Enter search text and check the value of keyword', async () => {
        const wrapper = wrapperFactory();
        wrapper.find('.mdi-magnify')
        wrapper.setData({ keyword: 'Tahap 2' })
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.keyword).toBe('Tahap 2')
    })

    test('click search button and clear input', () => {
        const wrapper = wrapperFactory();
        wrapper.find('.mdi-magnify').trigger("click");
        const textInput = wrapper.find('.mdi-magnify')
        expect(textInput.text()).toMatch('')
        expect(wrapper.vm.keyword).toBe('')
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
    })
    test('calls onAdd when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onAdd = jest.fn();
        wrapper.vm.onAdd();
        wrapper.setData({
            data: [
                {
                    0: {
                        angkatan: 1,
                        id: 1,
                        is_aktif: 1,
                        nama: "Angkatan 1",
                        paud_periode_id: 1,
                        tahun: 2021,
                        tgl_daftar_mulai: "2021-02-15",
                        tgl_daftar_selesai: "2021-02-26",
                        tgl_diklat_mulai: "2021-02-15",
                        tgl_diklat_selesai: "2021-02-26",
                        type: "paud_periode"
                    },
                    1: {
                        angkatan: 1,
                        id: 2,
                        is_aktif: 1,
                        nama: "Angkatan 2",
                        paud_periode_id: 2,
                        tahun: 2021,
                        tgl_daftar_mulai: "2021-12-30",
                        tgl_daftar_selesai: "2022-01-03",
                        tgl_diklat_mulai: "2021-12-30",
                        tgl_diklat_selesai: "2022-01-03",
                        type: "paud_periode"
                    }
                }
            ],
            total: 2
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-plus').exists()).toBe(true);
        const button = wrapper.find('.mdi-plus')
        button.trigger('click')
    })
    test('call onAction when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({
            data: [
                {
                    0: {
                        angkatan: 1,
                        id: 1,
                        is_aktif: 1,
                        nama: "Angkatan 1",
                        paud_periode_id: 1,
                        tahun: 2021,
                        tgl_daftar_mulai: "2021-02-15",
                        tgl_daftar_selesai: "2021-02-26",
                        tgl_diklat_mulai: "2021-02-15",
                        tgl_diklat_selesai: "2021-02-26",
                        type: "paud_periode"
                    },
                    1: {
                        angkatan: 1,
                        id: 2,
                        is_aktif: 1,
                        nama: "Angkatan 2",
                        paud_periode_id: 2,
                        tahun: 2021,
                        tgl_daftar_mulai: "2021-12-30",
                        tgl_daftar_selesai: "2022-01-03",
                        tgl_diklat_mulai: "2021-12-30",
                        tgl_diklat_selesai: "2022-01-03",
                        type: "paud_periode"
                    }
                }
            ],
            total: 2,
            actions: [
                {
                    icon: 'mdi-pencil',
                    title: 'Ubah',
                    event: 'onEdit',
                    akses: 'diklat-periode.update'
                }
            ]
        });
        await wrapper.vm.$nextTick()
        wrapper.vm.onAction = jest.fn();
        wrapper.vm.onAction();
        expect(wrapper.find('.mdi-dots-vertical').exists()).toBe(true);
        // const button = wrapper.find('.mdi-dots-vertical')
        // button.trigger('click')
    })
})