import { createLocalVue, mount } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex";
import Main from "@/views/instansi/profil/base/Main.vue";
import BaseModalFull from "@/components/base/BaseModalFull.vue";
import { getDeepObj, isObject } from '@utils/format';

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
    }
})

describe('Main.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Main, {
            localVue,
            router,
            store,
            vuetify,
            components: {
                BaseModalFull
            },
            data() {
                return {
                    data: this.detail,
                }
            },
        })
    }
    test('test account name', async () => {
        const wrapper = wrapperFactory()
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
        });
        await wrapper.vm.$nextTick();
        expect(wrapper.find('[id="akun-nama"]').exists()).toBe(true);
    })
    test('test account type', async () => {
        const wrapper = wrapperFactory()
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
        });
        await wrapper.vm.$nextTick();
        expect(wrapper.find('[id="akun-tipe"]').exists()).toBe(true);
    })
})
