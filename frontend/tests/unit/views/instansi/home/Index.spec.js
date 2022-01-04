import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import BaseModalFull from "@/components/base/BaseModalFull.vue";
import Index from "@/views/instansi/home/Index.vue";
import { isObject, getDeepObj, duration, isArray } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

document.body.setAttribute('data-app', true)

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        petugasKonfirmasi: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
            }
        },
        preferensi: {
            namespaced: true,
            actions: {
                is_kesediaan() {
                    return true
                },
                groups() {
                    return true
                },
                is_kesediaan_luring() {
                    return true
                },
            }
        },
    },
})

localVue.mixin({
    methods: {
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
        $akseses(item, key) {
            let temp = [];
            item[key].forEach((key) => {
                if (this.$allow(key.akses)) temp.push(key);
            });

            return temp || [];
        },
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $durasi(start, end, options) {
            return duration(start, end, options);
        },
        $isArray(data) {
            return isArray(data);
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
            components: {
                BaseModalFull
            },
        })
    }

    test('test ketersediaan diklat daring button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('[id="konfirmasi-daring"]').exists()).toBe(true)
        const btn = wrapper.find('[id="konfirmasi-daring"]')
        btn.trigger('click')
    })

    test('test ketersediaan diklat luring button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('[id="konfirmasi-luring"]').exists()).toBe(true)
        const button = wrapper.find('[id="konfirmasi-luring"]')
        button.trigger('click')
    })
})
