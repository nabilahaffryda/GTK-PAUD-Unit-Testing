import { createLocalVue, mount } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex"
import Detail from "@/views/instansi/petugas/Detail.vue"
import { isObject, getDeepObj, localDate } from '@utils/format'

Vue.use(Vuetify)
const localVue = createLocalVue()
document.body.setAttribute('data-app', true)

localVue.use(VueRouter)
const router = new VueRouter()

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
        petugas: {
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
    }
})

localVue.mixin({
    methods: {
        $isObject(data) {
            return isObject(data || {});
        },
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $fGender(data) {
            return data === 'L' ? 'Laki - laki' : data === 'P' ? 'Perempuan' : '-';
        },
        $localDate(date, short, withTime, usingNumber, useSeconds) {
            if (!date) return '-';
            return localDate(date, short, withTime, usingNumber, useSeconds);
        },
        $titleCase(kata) {
            return (kata && kata.charAt(0).toUpperCase() + kata.slice(1)) || '';
        },
        $downloadFile(sUrl) {
            // Creating new link node.
            const link = document.createElement('a');
            link.href = sUrl;

            // Dispatching click event.
            // if (document.createEvent) {
            //   const e = document.createEvent('MouseEvents')
            //   e.initEvent('click', true, true)
            //   link.dispatchEvent(e)
            //   return true
            // }
            window.open(sUrl);
            return true;
        },
    }
})

describe('Detail.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Detail, {
            localVue,
            router,
            store,
            vuetify,
            stubs: {
                Viewer: true
            },
            propsData: {
                detail: {
                    akun: {
                        data: {
                            email: "pengajar1@gmail.com",
                            nama: "Akun Pengajar 1",

                        }
                    },
                    akun_id: "17209679"
                },
                jenis: 'pengajar'
            },
            computed: {
                diklats() {
                    return [
                        {
                            akun_id: "17209679",
                            file: "2021/1/17209679-diklat-1-210812044059.png",
                            k_diklat_paud: 1,
                            nama: "Diklat Berjenjang",
                            nama_file: "Admin kelas.png",
                            penyelenggara: "diklat dasar",
                            url: "https://upload.dev.siap.id/gpo/paud/petugas-berkas/2021/1/17209679-diklat-1-210812044059.png"
                        }
                    ]
                }
            }
        })
    }

    test('test detail button', () => {
        const wrapper = wrapperFactory()
        wrapper.vm.onDetilDiklat = jest.fn()
        expect(wrapper.find('.mdi-eye').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const detail = wrapper.find('.mdi-eye')
        detail.trigger('click')
        wrapper.vm.onDetilDiklat()
    })

    test('test download button ', () => {
        const wrapper = wrapperFactory()
        window.open = jest.fn()
        expect(wrapper.find('.mdi-download').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const download = wrapper.find('.mdi-download')
        download.trigger('click')
    })

})