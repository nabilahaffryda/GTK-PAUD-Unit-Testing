import { mount, createLocalVue } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex";
import Lpd from "@/views/instansi/institusi/formulir/Lpd.vue";
import { getDeepObj, isObject, isArray } from '@utils/format'

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
        institusi: {
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
                lookup() {
                    return true
                },
                getDetail() {
                    return true
                },
                fetch() {
                    return true
                },
                downloadList() {
                    return true
                },
            }
        }
    },
})

localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
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
    }
})

describe('Lpd.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Lpd, {
            localVue,
            vuetify,
            router,
            store,
            computed: {
                configs() {
                    return true
                }
            },
            stubs: {
                BaseFormGenerator: true
            }
        })
    }
    test('test form input', async () => {
        const wrapper = wrapperFactory()
        await wrapper.vm.$nextTick()
        expect(wrapper.find('[id="email-input"]').exists()).toBe(true)
        const emailInput = wrapper.find('[id="email-input"]')
        emailInput.element.value = 'sehun@gmail.com'
        emailInput.trigger('input')
    })

    test('test next button', async () => {
        const wrapper = wrapperFactory()
        wrapper.vm.onValidate = jest.fn()
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.text-md-right').exists()).toBe(true)
        const btn = wrapper.find('.text-md-right')
        btn.trigger('click')
        wrapper.vm.onValidate()
    })

    test('test back button', async () => {
        const wrapper = wrapperFactory()
        await wrapper.vm.$nextTick()
        expect(wrapper.find('[id="back"]').exists()).toBe(true)
        const button = wrapper.find('[id="back"]')
        button.trigger('click')
        wrapper.vm.back()
    })
})
