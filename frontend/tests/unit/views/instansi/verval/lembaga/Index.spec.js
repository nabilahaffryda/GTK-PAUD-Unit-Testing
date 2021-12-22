import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import Index from "@/views/instansi/verval/lembaga/Index.vue";
import { createLocalVue, mount } from "@vue/test-utils";
import { getDeepObj, isObject, isArray, arrayToObject, } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

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
        verval: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                getDetail() {
                    return true
                },
                action() {
                    return true
                },
                downloadList() {
                    return true
                },
                getKinerja() {
                    return true
                },
                getTimVerval() {
                    return true
                },
            }
        },
        preferensi: {
            namespaced: true
        }
    },
})

document.body.setAttribute('data-app', true)

localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $allow() {
            return true;
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
        $arrToObj(array, key) {
            if (!array || !array.length) return {};
            return arrayToObject(array, key);
        },
    }
})

describe('Index.vue', () => {
    let vuetify;
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Index, {
            localVue,
            vuetify,
            router,
            store,
            stubs: {
                Viewer: true,
                BaseModalFull: true,
            },
        })
    }

    test('test search button', () => {
        const wrapper = wrapperFactory()
        wrapper.vm.onSearch = jest.fn();
        expect(wrapper.find('.mdi-magnify').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const search = wrapper.find('.mdi-magnify')
        search.trigger('click')
        wrapper.vm.onSearch()
    })

    test('test filter button', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onFilter = jest.fn();
        wrapper.vm.$nextTick();
        expect(wrapper.find('.mdi-filter-variant').exists()).toBe(true);
        const button = wrapper.find('.mdi-filter-variant')
        button.trigger('click')
        wrapper.vm.onFilter();
    })

    test('test reload button', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onReload = jest.fn();
        wrapper.vm.$nextTick();
        expect(wrapper.find('.mdi-reload').exists()).toBe(true);
        const btn = wrapper.find('.mdi-reload')
        btn.trigger('click')
        wrapper.vm.onReload();
    })
})
