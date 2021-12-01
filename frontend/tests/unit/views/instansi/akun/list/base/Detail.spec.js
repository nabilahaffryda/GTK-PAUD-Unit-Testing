import { mount, createLocalVue } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex";
import Detail from "@/views/instansi/akun/list/base/Detail.vue"
import { getDeepObj, localDate } from '@utils/format'

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
    }
})

localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $localDate(date, short, withTime, usingNumber, useSeconds) {
            if (!date) return '-';
            return localDate(date, short, withTime, usingNumber, useSeconds);
        },
        $fGender(data) {
            return data === 'L' ? 'Laki - laki' : data === 'P' ? 'Perempuan' : '-';
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
            propsData: {
                jenis: 'pengajar',
                detail: {
                    akun_id: "17209679",
                    id: 121,
                    instansi_id: 800006,
                },
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
            },
            stubs: {
                Viewer: true
            },
        })
    }
    test('test detail button ', async () => {
        const wrapper = wrapperFactory()
        window.open = jest.fn();
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-eye').exists()).toBe(true)
        const btn = wrapper.find('.mdi-eye')
        btn.trigger('click')
    })
    test('test download button', async () => {
        const wrapper = wrapperFactory()
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-download').exists()).toBe(true)
        const btn = wrapper.find('.mdi-download')
        btn.trigger('click')
    })
})
