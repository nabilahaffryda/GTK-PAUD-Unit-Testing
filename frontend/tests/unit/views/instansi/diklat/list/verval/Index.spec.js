import { createLocalVue, mount, } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import axios from 'axios';
import Index from "@/views/instansi/diklat/list/verval/Index.vue";
import { getDeepObj, isObject, duration, isArray, localDate } from '@utils/format';

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
        diklatVerval: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                getDetail() {
                    return true
                },
                action() {
                    return true
                },
                downloadList() {
                    return true
                },
                getKinerja() {
                    return true
                },
                getTimVerval() {
                    return true
                },
            }
        },
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
        $isObject(data) {
            return isObject(data || {});
        },
        $durasi(start, end, options) {
            return duration(start, end, options);
        },
        $mapForMaster(data, text = false) {
            // cek type data
            let temp = [];
            if (data && isObject(data)) {
                for (let key in data) {
                    temp.push({
                        text: data[key],
                        value: text ? data[key] : Number(key),
                    });
                }
            } else if (data && isArray(data)) {
                temp = data.map((value, idx) => {
                    return {
                        text: isObject(value) ? value?.keterangan ?? value.text : value,
                        value: isObject(value) ? value[text] ?? value?.value ?? idx : value,
                    };
                });
            }
            return temp;
        },
        $isArray(data) {
            return isArray(data);
        },
        $localDate(date, short, withTime, usingNumber, useSeconds) {
            if (!date) return '-';
            return localDate(date, short, withTime, usingNumber, useSeconds);
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
                    'kelas-verval.batal-verval': true,
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
            data() {
                return {
                    reload: false,
                }
            },
            stubs: {
                BaseModalFull: true,
                PopupPreviewDetail: true,
            }
        })
    }
    test('calls onReload when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({ reload: true });
        await wrapper.vm.$nextTick();
        wrapper.vm.onReload = jest.fn();
        wrapper.vm.onReload();
        expect(wrapper.find('.mdi-reload').exists()).toBe(true);
        const btn = wrapper.find('.mdi-reload')
        btn.trigger('click')
    })
    test('test total data from data tables ', async () => {
        const wrapper = wrapperFactory();
        wrapper.find('.body-1 black--text')
        wrapper.setData({ total: 2 });
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.total).toBe(2)
    })
    test('Enter search text and check the value of keyword', async () => {
        const wrapper = wrapperFactory();
        wrapper.find('.mdi-magnify')
        wrapper.setData({ keyword: 'Diklat seri GTK PAUD - Diklat Coba' })
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.keyword).toBe('Diklat seri GTK PAUD - Diklat Coba')
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
        wrapper.setData({
            data: [
                {
                    0: {
                        nama: "Kelas 1",
                        tahun: 2021,
                        type: "paud_kelas",
                        paud_kelas_id: 7,
                        id: 7
                    },
                    1: {
                        nama: "Diklat Coba",
                        tahun: 2021,
                        type: "paud_kelas",
                        paud_kelas_id: 12,
                        id: 12
                    }
                }
            ],
            total: 2
        })
        await wrapper.vm.$nextTick()
        wrapper.vm.onAction = jest.fn();
        wrapper.vm.onAction();
        expect(wrapper.find('.mdi-dots-vertical').exists()).toBe(true);
        const button = wrapper.find('.mdi-dots-vertical')
        button.trigger('click')
    })
    test('call onVerval when detail button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({
            data: [
                {
                    0: {
                        nama: "Kelas 1",
                        tahun: 2021,
                        type: "paud_kelas",
                        paud_kelas_id: 7,
                        id: 7
                    },
                    1: {
                        nama: "Diklat Coba",
                        tahun: 2021,
                        type: "paud_kelas",
                        paud_kelas_id: 12,
                        id: 12
                    }
                }
            ],
            total: 2
        })
        await wrapper.vm.$nextTick()
        wrapper.vm.onVerval = jest.fn();
        wrapper.vm.onVerval();
        expect(wrapper.find('[data-testid="btn-detail"]').exists()).toBe(true);
        const button = wrapper.find('[data-testid="btn-detail"]')
        button.trigger('click')
    })
})
