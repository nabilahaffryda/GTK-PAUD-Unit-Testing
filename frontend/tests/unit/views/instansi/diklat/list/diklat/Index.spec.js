import { createLocalVue, mount } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex";
import Index from "@/views/instansi/diklat/list/diklat/Index.vue"
import { getDeepObj, isObject } from '@utils/format';
import BaseModalFull from "@/components/base/BaseModalFull"

document.body.setAttribute('data-app', true)
Vue.use(Vuetify)
const localVue = createLocalVue()

localVue.use(VueRouter)
const router = new VueRouter()

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        diklat: {
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
                getPeriode() {
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
                }
            }
        },
        master: {
            namespaced: true,
            actions: {
                getMasters() {
                    return true
                }
            }
        },
        preferensi: {
            namespaced: true
        }
    }
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
        $titleCase(kata) {
            return (kata && kata.charAt(0).toUpperCase() + kata.slice(1)) || '';
        },
    }
})

describe('Index.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Index, {
            localVue,
            vuetify,
            router,
            store,
            computed: {
                configs() {
                    return true
                }
            },
            components: {
                BaseModalFull
            },
            stubs: {
                BaseFormGenerator: true
            }
        })
    }

    test('Input keyword and check the value of keyword', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('.mdi-magnify').exists()).toBe(true);
        wrapper.setData({ keyword: 'Diklat Berjenjang Tingkat Dasar Moda Daring Kombinasi - Jaksel' })
        wrapper.find('.mdi-magnify').trigger("click");
        wrapper.vm.onSearch();
        expect(wrapper.vm.keyword).toBe('Diklat Berjenjang Tingkat Dasar Moda Daring Kombinasi - Jaksel')
    })

    test('Clear input after search button is clicked', () => {
        const wrapper = wrapperFactory();
        const textInput = wrapper.find('.mdi-magnify')
        expect(textInput.text()).toMatch('')
        expect(wrapper.vm.keyword).toBe('')
    })

    test('calls onReload when button is clicked', () => {
        const wrapper = wrapperFactory();
        expect(wrapper.find('.mdi-reload').exists()).toBe(true);
        wrapper.vm.$nextTick();
        const btn = wrapper.find('.mdi-reload')
        btn.trigger('click')
        wrapper.vm.onReload();
    })

    test('call onAdd when button is clicked', () => {
        const wrapper = wrapperFactory();
        expect(wrapper.find('.mdi-plus').exists()).toBe(true);
        wrapper.vm.$nextTick();
        const button = wrapper.find('.mdi-plus')
        button.trigger('click')
        wrapper.vm.onAddDiklat();
    })
    test('call Onsave when button is clicked ', () => {
        const wrapper = wrapperFactory()
        wrapper.vm.onSave = jest.fn()
        expect(wrapper.findComponent({ ref: 'modal' }).exists()).toBe(true);
        wrapper.vm.$nextTick();
        const btn = wrapper.findComponent({ ref: 'modal' })
        btn.trigger('click')
        wrapper.vm.onSave()
    })

})
