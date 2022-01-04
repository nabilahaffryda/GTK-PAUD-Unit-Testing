import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import Ketersediaan from "@/views/gtk/home/components/Ketersediaan.vue";
import { getDeepObj, duration } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        diklat: {
            namespaced: true,
            actions: {
                getDetail() {
                    return true
                },
                actions() {
                    return true
                },
                fetch() {
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
        $durasi(start, end, options) {
            return duration(start, end, options);
        },
    }
})

describe('Ketersediaan.vue', () => {
    let vuetify;
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(Ketersediaan, {
            localVue,
            vuetify,
            router,
            store,
            data() {
                return {
                    data: {
                        akun_id: "10002877",
                        id: 108,
                        nama: "Kelas B",
                        k_konfirmasi_paud: 3,
                        k_petugas_paud: 4,
                        tahun: 2021,
                        type: "paud_kelas_petugas"
                    }
                }
            }
        })
    }

    test('test Nama Instansi', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            data: {
                paud_kelas: {
                    data: {
                        paud_diklat: {
                            data: {
                                paud_instansi: {
                                    data: {
                                        instansi: {
                                            data: {
                                                nama: "Universitas Brawijaya Malang Melintang"
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        })
        expect(wrapper.find('[id="nama-instansi"]').exists()).toBe(true)
        expect(wrapper.vm.data.paud_kelas.data.paud_diklat.data.paud_instansi.data.instansi.data.nama).toBe("Universitas Brawijaya Malang Melintang")
    })

    test('test nama kelas', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            data: {
                nama: "Kelas B"
            }
        })
        expect(wrapper.find('[id="nama-kelas"]').exists()).toBe(true)
        expect(wrapper.vm.data.nama).toBe("Kelas B")
    })

    test('test Jadwal Pelaksanaan', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            data: {
                paud_kelas: {
                    data: {
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
                }
            }
        })
        expect(wrapper.find('[id="jadwal"]').exists()).toBe(true)
        expect(wrapper.vm.data.paud_kelas.data.paud_diklat.data.paud_periode.data.tgl_diklat_mulai).toBe("2021-02-15")
        expect(wrapper.vm.data.paud_kelas.data.paud_diklat.data.paud_periode.data.tgl_diklat_selesai).toBe("2021-02-26")
    })

    test('test status', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            data: {
                m_konfirmasi_paud: {
                    data: {
                        keterangan: "Bersedia"
                    }
                }
            }
        })
        expect(wrapper.find('[id="status"]').exists()).toBe(true)
        expect(wrapper.vm.data.m_konfirmasi_paud.data.keterangan).toBe("Bersedia")
    })
})
