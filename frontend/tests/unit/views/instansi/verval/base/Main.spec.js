import { createLocalVue, mount, config } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import axios from 'axios';
import Main from "@/views/instansi/verval/base/Main.vue";
import { getDeepObj, isObject, isArray, arrayToObject, } from '@utils/format';

config.showDeprecationWarnings = false;
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
        verval: {
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
        preferensi: {
            namespaced: true
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
        $isObject(data) {
            return isObject(data || {});
        },
        $isArray(data) {
            return isArray(data);
        },
        $arrToObj(array, key) {
            if (!array || !array.length) return {};
            return arrayToObject(array, key);
        },
        $titleCase(kata) {
            return (kata && kata.charAt(0).toUpperCase() + kata.slice(1)) || '';
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
                    'lpd-verval-kunci.update': true,
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
        return mount(Main, {
            localVue,
            vuetify,
            router,
            store,
            props: {
                paramsTipe: {},
                actions: []
            },
            methods: {
                allowMenu() {
                    return true
                }
            },
            stubs: {
                BaseModalFull: true,
                PopupPreviewDetail: true,
            },
            data() {
                return {
                    reload: false,
                }
            },
        })
    }

    test('render list table', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({
            data: [
                {
                    0: {
                        instansi: {
                            data: {
                                email: "jayakarta@gmail.com",
                                id: 720003,
                                nama: "Universitas Jayakarta",
                                no_telpon: "324232423",
                                k_jenis_instansi: 23
                            }
                        },
                        m_verval_paud: {
                            data: {
                                id: 2,
                                k_verval_paud: 2,
                                keterangan: "Diajukan",
                                singkat: "Diajukan",
                                type: "m_verval_paud"
                            }
                        },
                        instansi_id: 720003,
                        is_aktif: 1,
                        jml_admin_program: 4,
                        k_lpd_paud: 3,
                        k_verval_paud: 2,
                        kodepos: "12330",
                        type: "paud_instansi",
                        id: 5
                    },
                    1: {
                        akun_id_verval: "10002877",
                        akun_verval: {
                            data: {
                                akun_id: "10002877",
                                email: "yaumil@jayantara.co.id",
                                id: "10002877",
                                nama: "Yaumil Akhir",
                                no_hp: "09876543212",
                                no_telpon: "12312321321",
                                type: "akun"
                            }
                        },
                        diklat: {
                            0: {
                                nama: "Diklat Pengkelasan Mandiri",
                                tahun: "2020"
                            }
                        },
                        instansi: {
                            data: {
                                alamat: "Jl. jayakarta",
                                email: "lpdsatu@gmail.com",
                                nama: "Lembaga Penyelenggara Diklat 1",
                                id: 720004,
                                instansi_id: 720004
                            }
                        }
                    }
                }
            ],
            total: 2
        });
        await wrapper.vm.$nextTick();
        expect(wrapper.find('[ data-testid="list-table-verval-main"]').exists()).toBe(true);
    })
    test('test total data from data tables ', async () => {
        const wrapper = wrapperFactory();
        wrapper.find('[data-testid="list-table-verval-main"]')
        wrapper.setData({ total: 6 });
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.total).toBe(6)
    })
    test('Enter search text and check the value of keyword', async () => {
        const wrapper = wrapperFactory();
        wrapper.find('.mdi-magnify')
        wrapper.setData({ keyword: 'Universitas Jayakarta' })
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.keyword).toBe('Universitas Jayakarta')
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
        await wrapper.vm.$nextTick();
        wrapper.vm.onReload = jest.fn();
        wrapper.vm.onReload();
        expect(wrapper.find('.mdi-reload').exists()).toBe(true);
        const btn = wrapper.find('.mdi-reload')
        btn.trigger('click')
    })
    test('calls onFilter when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({
            data: [
                {
                    0: {
                        text: "Diajukan",
                        value: 2
                    },
                    1: {
                        text: "Ditolak",
                        value: 4
                    },
                    2: {
                        text: "Revisi",
                        value: 5
                    },
                    3: {
                        text: "Disetujui",
                        value: 6
                    }
                }
            ]
        });
        wrapper.vm.onFilter = jest.fn();
        wrapper.vm.onFilter();
        await wrapper.vm.$nextTick();
        expect(wrapper.find('.mdi-filter-variant').exists()).toBe(true);
        const button = wrapper.find('.mdi-filter-variant')
        button.trigger('click')
    })
    test('call onKunci when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({
            data: [
                {
                    0: {
                        instansi: {
                            data: {
                                email: "jayakarta@gmail.com",
                                id: 720003,
                                nama: "Universitas Jayakarta",
                                no_telpon: "324232423",
                                k_jenis_instansi: 23
                            }
                        },
                        m_verval_paud: {
                            data: {
                                id: 2,
                                k_verval_paud: 2,
                                keterangan: "Diajukan",
                                singkat: "Diajukan",
                                type: "m_verval_paud"
                            }
                        },
                        instansi_id: 720003,
                        is_aktif: 1,
                        jml_admin_program: 4,
                        k_lpd_paud: 3,
                        k_verval_paud: 2,
                        kodepos: "12330",
                        type: "paud_instansi",
                        id: 5
                    },
                    1: {
                        akun_id_verval: "10002877",
                        akun_verval: {
                            data: {
                                akun_id: "10002877",
                                email: "yaumil@jayantara.co.id",
                                id: "10002877",
                                nama: "Yaumil Akhir",
                                no_hp: "09876543212",
                                no_telpon: "12312321321",
                                type: "akun"
                            }
                        },
                        diklat: {
                            0: {
                                nama: "Diklat Pengkelasan Mandiri",
                                tahun: "2020"
                            }
                        },
                        instansi: {
                            data: {
                                alamat: "Jl. jayakarta",
                                email: "lpdsatu@gmail.com",
                                nama: "Lembaga Penyelenggara Diklat 1",
                                id: 720004,
                                instansi_id: 720004
                            }
                        }
                    }
                }
            ],
            total: 2
        })
        await wrapper.vm.$nextTick()
        wrapper.vm.onKunci = jest.fn();
        wrapper.vm.onKunci();
        expect(wrapper.find('.mdi-account-arrow-left').exists()).toBe(true);
        const button = wrapper.find('.mdi-account-arrow-left')
        button.trigger('click')
    })
    test('call onAction when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({
            data: [
                {
                    0: {
                        instansi: {
                            data: {
                                email: "jayakarta@gmail.com",
                                id: 720003,
                                nama: "Universitas Jayakarta",
                                no_telpon: "324232423",
                                k_jenis_instansi: 23
                            }
                        },
                        m_verval_paud: {
                            data: {
                                id: 2,
                                k_verval_paud: 2,
                                keterangan: "Diajukan",
                                singkat: "Diajukan",
                                type: "m_verval_paud"
                            }
                        },
                        instansi_id: 720003,
                        is_aktif: 1,
                        jml_admin_program: 4,
                        k_lpd_paud: 3,
                        k_verval_paud: 2,
                        kodepos: "12330",
                        type: "paud_instansi",
                        id: 5
                    },
                    1: {
                        akun_id_verval: "10002877",
                        akun_verval: {
                            data: {
                                akun_id: "10002877",
                                email: "yaumil@jayantara.co.id",
                                id: "10002877",
                                nama: "Yaumil Akhir",
                                no_hp: "09876543212",
                                no_telpon: "12312321321",
                                type: "akun"
                            }
                        },
                        diklat: {
                            0: {
                                nama: "Diklat Pengkelasan Mandiri",
                                tahun: "2020"
                            }
                        },
                        instansi: {
                            data: {
                                alamat: "Jl. jayakarta",
                                email: "lpdsatu@gmail.com",
                                nama: "Lembaga Penyelenggara Diklat 1",
                                id: 720004,
                                instansi_id: 720004
                            }
                        }
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
                        instansi: {
                            data: {
                                email: "jayakarta@gmail.com",
                                id: 720003,
                                nama: "Universitas Jayakarta",
                                no_telpon: "324232423",
                                k_jenis_instansi: 23
                            }
                        },
                        m_verval_paud: {
                            data: {
                                id: 2,
                                k_verval_paud: 2,
                                keterangan: "Diajukan",
                                singkat: "Diajukan",
                                type: "m_verval_paud"
                            }
                        },
                        instansi_id: 720003,
                        is_aktif: 1,
                        jml_admin_program: 4,
                        k_lpd_paud: 3,
                        k_verval_paud: 2,
                        kodepos: "12330",
                        type: "paud_instansi",
                        id: 5
                    },
                    1: {
                        akun_id_verval: "10002877",
                        akun_verval: {
                            data: {
                                akun_id: "10002877",
                                email: "yaumil@jayantara.co.id",
                                id: "10002877",
                                nama: "Yaumil Akhir",
                                no_hp: "09876543212",
                                no_telpon: "12312321321",
                                type: "akun"
                            }
                        },
                        diklat: {
                            0: {
                                nama: "Diklat Pengkelasan Mandiri",
                                tahun: "2020"
                            }
                        },
                        instansi: {
                            data: {
                                alamat: "Jl. jayakarta",
                                email: "lpdsatu@gmail.com",
                                nama: "Lembaga Penyelenggara Diklat 1",
                                id: 720004,
                                instansi_id: 720004
                            }
                        }
                    }
                }
            ],
            total: 2
        })
        await wrapper.vm.$nextTick()
        wrapper.vm.onVerval = jest.fn();
        wrapper.vm.onVerval();
        expect(wrapper.find('[data-testid="detail-btn"]').exists()).toBe(true);
        const button = wrapper.find('[data-testid="detail-btn"]')
        button.trigger('click')
    })
})