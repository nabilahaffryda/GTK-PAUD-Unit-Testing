import { createLocalVue, mount } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex";
import Profil from "@/views/instansi/profil/formulir/Profil.vue";
import { getDeepObj, isObject, localDate } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue()

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
        profil: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                update() {
                    return true
                },
                getDiklat() {
                    return true
                },
                getBerkas() {
                    return true
                },
                setBerkas() {
                    return true
                },
                dropBerkas() {
                    return true
                },
                ajuan() {
                    return true
                },
                batalAjuan() {
                    return true
                },
            }
        }
    }
})

localVue.mixin({
    methods: {
        $isObject(data) {
            return isObject(data || {});
        },
        $allow() {
            return true;
        },
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $titleCase(kata) {
            return (kata && kata.charAt(0).toUpperCase() + kata.slice(1)) || '';
        },
        $fGender(data) {
            return data === 'L' ? 'Laki - laki' : data === 'P' ? 'Perempuan' : '-';
        },
        $localDate(date, short, withTime, usingNumber, useSeconds) {
            if (!date) return '-';
            return localDate(date, short, withTime, usingNumber, useSeconds);
        },
    }
})

describe('Profil.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Profil, {
            localVue,
            router,
            store,
            vuetify,
            data() {
                return {
                    data: this.detail
                }
            }
        })
    }
    test('call edit() when button is clicked', async () => {
        const wrapper = wrapperFactory()
        wrapper.vm.edit = jest.fn()
        wrapper.setData({
            data: [
                {
                    akun: {
                        data: {
                            nama: "Yaumil Akhir",
                            akun_id: "10002877",
                            email: "yaumil@jayantara.co.id"
                        }
                    },
                    instansi_id: 800006,
                    id: 2,
                    tahun: 2021,
                    type: "paud_petugas"
                }
            ],
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-pencil').exists()).toBe(true);
        const button = wrapper.find('.mdi-pencil')
        button.trigger('click')
        wrapper.vm.edit()
    })

})
