import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import axios from 'axios';
import Index from "@/views/instansi/institusi/list/Index.vue";
import { getDeepObj, isObject, assetsUrl, localDate, isArray } from '@utils/format';

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
        institusi: {
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
                lookup() {
                    return true
                },
                getDetail() {
                    return true
                },
                fetch() {
                    return true
                },
                downloadList() {
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
        $isArray(data) {
            return isArray(data);
        },
        $allow() {
            return true;
        },
        $isObject(data) {
            return isObject(data || {});
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
        $imgUrl: (url) => (url ? assetsUrl(url) : ''),
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
                    'lpd.update': true,
                    'akun-admin-program-lpd.create': true
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
                FormLpd: true,
                BaseFormGenerator: true,
                Akun: true,
            },
            data() {
                return {
                    Datatables: false,
                    reload: false,
                    actions: [
                        {
                            icon: 'mdi-pencil',
                            title: 'Edit Institusi',
                            event: 'onEdit',
                            akses: 'lpd.update'
                        },
                    ]
                };
            },
        });
    }

    test('call allow method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.allow = jest.fn();
        wrapper.vm.allow();
        expect(wrapper.vm.allow.mock.calls.length).toBe(1);
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
    })

    test('calls onFilter when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onFilter = jest.fn();
        wrapper.vm.onFilter();
        wrapper.setData({
            data: [
                {
                    is_aktif: {
                        0: "Tidak Aktif",
                        1: "Aktif"
                    },
                    k_kota: {
                        1101: "Kab. Aceh Besar",
                        1102: "Kab. Pidie"
                    },
                    k_propinsi: {
                        11: "Aceh",
                        12: "Sumatera Utara"
                    }
                }
            ]
        });
        await wrapper.vm.$nextTick();
        expect(wrapper.find('.mdi-filter-variant').exists()).toBe(true);
        const button = wrapper.find('.mdi-filter-variant')
        button.trigger('click')
    })

    test('Enter search text and check the value of keyword', async () => {
        const wrapper = wrapperFactory();
        wrapper.find('.mdi-magnify')
        wrapper.setData({ keyword: 'UMM' })
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.keyword).toBe('UMM')
    })

    test('click search button and clear input', () => {
        const wrapper = wrapperFactory();
        wrapper.find('.mdi-magnify').trigger("click");
        const textInput = wrapper.find('.mdi-magnify')
        expect(textInput.text()).toMatch('')
        expect(wrapper.vm.keyword).toBe('')
    })

    test('test total data from data tables ', async () => {
        const wrapper = wrapperFactory();
        wrapper.find('[data-testid="list-table"]')
        wrapper.setData({ total: 22 });
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.total).toBe(22)
    })

    test('call onAction when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onAction = jest.fn();
        wrapper.vm.onAction();
        wrapper.setData({
            data: [
                {
                    instansi_id: 600001,
                    is_aktif: 1,
                    instansi: {
                        data: {
                            instansi_id: 600001,
                            nama: "Universitas Brawijaya Malang Melintang",
                            alamat: "Jl. Veteran No. 15",
                            email: "ub@ub.ac.id"
                        }
                    },
                    jml_admin_program: 3,
                    jml_operator: 4,
                    jml_pembimbing: 4,
                    nama_bendahara: "Katrina",
                    nama_penanggung_jawab: "Bruno",
                    nama_sekretaris: "Valir",
                    telp_bendahara: "085246985217",
                    telp_penanggung_jawab: "081287634287",
                    telp_sekretaris: "08145725631"
                }
            ],
            total: 1,
            actions: [
                {
                    icon: 'mdi-pencil',
                    title: 'Edit Institusi',
                    event: 'onEdit',
                    akses: true
                }
            ]
        });

        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-dots-vertical').exists()).toBe(true);
        const button = wrapper.find('.mdi-dots-vertical')
        button.trigger('click')
    })

    test('calls onAdd when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onAdd = jest.fn();
        wrapper.vm.onAdd();
        wrapper.setData({
            data: [
                {
                    instansi_id: 600001,
                    instansi: {
                        data: {
                            instansi_id: 600001,
                            nama: "Universitas Brawijaya Malang Melintang",
                            alamat: "Jl. Veteran No. 15",
                            email: "ub@ub.ac.id"
                        }
                    },
                    k_kota: "Kota Malang",
                    k_propinsi: "Jawa Timur",
                    jml_pembimbing: 4,
                    kodepos: 314618,
                    k_lpd_paud: "LPD Provinsi",
                    ratio_pengajar_tambahan: 40,
                    nama_penanggung_jawab: "Bruno",
                    telp_penanggung_jawab: "081287634287",
                }
            ],
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-plus').exists()).toBe(true);
        const button = wrapper.find('.mdi-plus')
        button.trigger('click')
    })
})
