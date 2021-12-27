import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import Detail from "@/views/gtk/kelas/Detail.vue";
import { getDeepObj, } from '@utils/format';

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
                getSertifikat() {
                    return true
                },
                getHasil() {
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
    }
})

describe('Detail.vue', () => {
    let vuetify;
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Detail, {
            localVue,
            vuetify,
            router,
            store,
            data() {
                return {
                    detail: {
                        angkatan: 1,
                        is_lulus: 1,
                        is_survey: 1,
                        medali: "Silver",
                        nilai: 80,
                        predikat: "Baik",
                        ptk_id: "201500000007",
                        tahun: 2021
                    }
                }
            }
        })
    }

    test('test download certificate button', () => {
        const wrapper = wrapperFactory()
        window.open = jest.fn()
        expect(wrapper.find('.mdi-certificate').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const download = wrapper.find('.mdi-certificate')
        download.trigger('click')
        wrapper.vm.onSertifikat()
    })

    test('test lulus icon', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            detail: {
                is_lulus: 1
            }
        })
        expect(wrapper.find('.mdi-checkbox-marked-circle').exists()).toBe(true)
        expect(wrapper.vm.detail.is_lulus).toBe(1)
    })

    test('test detail nilai', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            detail: {
                nilai: 80
            }
        })
        expect(wrapper.find('[id="detail-nilai"]').exists()).toBe(true)
        expect(wrapper.vm.detail.nilai).toBe(80)
    })

    test('test detail medali', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            detail: {
                medali: "Silver"
            }
        })
        expect(wrapper.find('[id="detail-medali"]').exists()).toBe(true)
        expect(wrapper.vm.detail.medali).toBe("Silver")
    })

})
