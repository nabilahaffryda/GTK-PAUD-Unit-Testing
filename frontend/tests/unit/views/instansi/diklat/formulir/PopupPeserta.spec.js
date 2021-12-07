import { mount, createLocalVue, config } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import PopupPeserta from "@/views/instansi/diklat/formulir/PopupPeserta.vue";
import { getDeepObj, isObject } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue()

config.showDeprecationWarnings = false
document.body.setAttribute('data-app', true)

localVue.use(VueRouter)
const router = new VueRouter()

localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $isObject(data) {
            return isObject(data || {});
        },
    }
})

describe('PopupPeserta.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(PopupPeserta, {
            localVue,
            vuetify,
            router,
            data() {
                return {
                    dialog: true,
                    tab: [],
                    items: [
                        {
                            url: 'peserta/kandidat',
                            text: 'SIMPKB PAUD',
                        },
                        {
                            url: 'peserta/kandidat-sd',
                            text: 'SIMPKB SD',
                        },
                        {
                            url: 'peserta/kandidat-simpatika',
                            text: 'SIMPKB RA',
                        },
                    ],
                }
            },
            methods: {
                fetchData() {
                    return true
                }
            }
        })
    }
    test('test close button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('.mdi-close').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const close = wrapper.find('.mdi-close')
        close.trigger('click')
    })

    test('test reload button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('.mdi-reload').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const reload = wrapper.find('.mdi-reload')
        reload.trigger('click')
        wrapper.vm.onReload()
    })

    test('test save button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('[id="save"]').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const save = wrapper.find('[id="save"]')
        save.trigger('click')
    })

    test('test initial tab 1', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: [
                {
                    url: 'peserta/kandidat',
                    text: 'SIMPKB PAUD',
                }
            ]
        })
        expect(wrapper.find('[id="tab"]').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const tab1 = wrapper.find('[id="tab"]')
        tab1.trigger('click')
    })

    test('test initial tab 2', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: [
                {
                    url: 'peserta/kandidat-sd',
                    text: 'SIMPKB SD',
                }
            ]
        })
        expect(wrapper.find('[id="tab"]').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const tab2 = wrapper.find('[id="tab"]')
        tab2.trigger('click')
    })

    test('test initial tab 3', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: [
                {
                    url: 'peserta/kandidat-simpatika',
                    text: 'SIMPKB RA',
                }
            ]
        })
        expect(wrapper.find('[id="tab"]').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const tab3 = wrapper.find('[id="tab"]')
        tab3.trigger('click')
    })

    test('Input keyword and check the value of keyword', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('.mdi-magnify').exists()).toBe(true);
        wrapper.setData({ keyword: 'DWI HIDAYATI SP' })
        wrapper.find('.mdi-magnify').trigger("click");
        wrapper.vm.onSearch();
        expect(wrapper.vm.keyword).toBe('DWI HIDAYATI SP')
    })

    test('Clear input after search button is clicked', () => {
        const wrapper = wrapperFactory();
        const textInput = wrapper.find('.mdi-magnify')
        expect(textInput.text()).toMatch('')
        expect(wrapper.vm.keyword).toBe('')
    })
})
