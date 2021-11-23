import { createLocalVue, mount } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex";
import FormCollection from "@/views/instansi/profil/formulir/FormCollection.vue";
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
    }
})

describe('FormCollection.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(FormCollection, {
            localVue,
            router,
            store,
            vuetify,
            data() {
                return {
                    tabs: [
                        { tab: 'Diklat Berjenjang', type: 'diklat', k_tipe: 1 },
                        { tab: 'Diklat PCP', type: 'diklat', k_tipe: 2 },
                        { tab: 'Diklat MOT', type: 'diklat', k_tipe: 3 },
                        { tab: 'Diklat Lainnya', type: 'diklat_lain', k_tipe: 4 },
                    ],
                    data: this.form
                }
            },
        })
    }
    test('test initial tab 1', async () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tabs: [
                {
                    tab: 'Diklat Berjenjang', type: 'diklat', k_tipe: 1
                },
            ]
        })
        await wrapper.vm.$nextTick();
        expect(wrapper.find('[data-testid="item-tab"]').exists()).toBe(true);
        const button = wrapper.find('[data-testid="item-tab"]')
        button.trigger('click')
    })
    test('test initial tab 2', async () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tabs: [
                {
                    tab: 'Diklat PCP', type: 'diklat', k_tipe: 2
                }
            ]
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.find('[data-testid="item-tab"]').exists()).toBe(true)
        const btn = wrapper.find('[data-testid="item-tab"]')
        btn.trigger('click')
    })
    test('test initial tab 3', async () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tabs: [
                {
                    tab: 'Diklat MOT', type: 'diklat', k_tipe: 3
                }
            ]
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.find('[data-testid="item-tab"]').exists()).toBe(true)
        const button = wrapper.find('[data-testid="item-tab"]')
        button.trigger('click')
    })
    test('test initial tab 4', async () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tabs: [
                {
                    tab: 'Diklat Lainnya', type: 'diklat_lain', k_tipe: 4
                }
            ]
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.find('[data-testid="item-tab"]').exists()).toBe(true)
        const btn = wrapper.find('[data-testid="item-tab"]')
        btn.trigger('click')
    })
    test('test remove button', async () => {
        const wrapper = wrapperFactory()
        wrapper.vm.remove = jest.fn()
        wrapper.setData({
            data: [
                {
                    1: {
                        0: {
                            id: 41,
                            k_diklat_paud: 1,
                            nama: "Diklat Berjenjang",
                            nama_file: "Belajar&Pembelajaran5.pdf"
                        }
                    },
                    2: {
                        0: {
                            k_diklat_paud: 2
                        }
                    },
                    3: {
                        0: {
                            id: 34,
                            k_diklat_paud: 3,
                            nama: "Diklat MOT",
                            nama_file: "chaerul yozi.pdf"
                        }
                    },
                    4: {
                        0: {
                            k_diklat_paud: 4
                        }
                    }
                }
            ]
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.findComponent({ ref: 'remove' }).exists()).toBe(true)
        const btn = wrapper.findComponent({ ref: 'remove' })
        btn.trigger('click')
        wrapper.vm.remove()
    })
    // test('test tambah riwayat button', async () => {
    //     const wrapper = wrapperFactory()
    //     wrapper.vm.add = jest.fn()
    //     wrapper.setData({
    //         data: [
    //             {
    //                 1: {
    //                     0: {
    //                         id: 41,
    //                         k_diklat_paud: 1,
    //                         nama: "Diklat Berjenjang",
    //                         nama_file: "Belajar&Pembelajaran5.pdf"
    //                     }
    //                 },
    //                 2: {
    //                     0: {
    //                         k_diklat_paud: 2
    //                     }
    //                 },
    //                 3: {
    //                     0: {
    //                         id: 34,
    //                         k_diklat_paud: 3,
    //                         nama: "Diklat MOT",
    //                         nama_file: "chaerul yozi.pdf"
    //                     }
    //                 },
    //                 4: {
    //                     0: {
    //                         k_diklat_paud: 4
    //                     }
    //                 }
    //             }
    //         ]
    //     })
    //     await wrapper.vm.$nextTick()
    //     expect(wrapper.find('[data-testid="add-tab"]').exists()).toBe(true)
    //     const btn = wrapper.find('[data-testid="add-tab"]')
    //     btn.trigger('click')
    //     wrapper.vm.add()
    // })

})
