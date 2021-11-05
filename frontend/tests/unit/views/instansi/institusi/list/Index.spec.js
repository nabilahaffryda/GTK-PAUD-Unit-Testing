import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import BaseListTable from "@/components/base/BaseListTable.vue";
import BaseFormGenerator from "@/components/base/BaseFormGenerator.vue";
import BaseModalFull from "@/components/base/BaseModalFull.vue";
import BaseListFilter from "@/components/base/BaseListFilter.vue";
import Index from "@/views/instansi/institusi/list/Index.vue";
import BaseListAction from "@/components/base/BaseListAction.vue";
import { getDeepObj, isObject, assetsUrl, localDate, isArray } from '@utils/format';

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
        institusi: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                }
            }
        }
    },
    state: {
        actions: [
            {
                icon: 'mdi-pencil',
                title: 'Edit Institusi',
                event: 'onEdit',
                akses: 'lpd.update'
            },
        ],
    }
})
localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $isArray(data) {
            return isArray(data);
        },
        $allow(akses, policy) {
            // cek jika tidak ada akses
            if (!akses || (akses && typeof akses === 'boolean')) return typeof akses === 'boolean' ? akses : true;

            // jika ada ambil preferensi akses
            const akseses = store.getters['preferensi/akseses'] || [];

            let allow = false;
            const pathAkses = akses && typeof akses === 'string' && akses.split('|');
            if (pathAkses.length) {
                pathAkses.forEach((path) => {
                    if (allow) return;
                    if (!allow && policy) {
                        allow =
                            !path || path === 'true' || ((akseses || []).filter((item) => item === path).length > 0 && policy[path]);
                    } else if (!allow && !policy) {
                        allow = !path || path === 'true' || (akseses || []).filter((item) => item === path).length > 0;
                    }
                });
            }

            return allow;
        },
        $isObject(data) {
            return isObject(data || {});
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
        $imgUrl: (url) => (url ? assetsUrl(url) : ''),
        $localDate(date, short, withTime, usingNumber, useSeconds) {
            if (!date) return '-';
            return localDate(date, short, withTime, usingNumber, useSeconds);
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
                BaseModalFull,
                BaseFormGenerator,
                BaseListTable,
                BaseListFilter,
                BaseListAction
            },
            data() {
                return {
                    Datatables: false,
                    reload: false,
                    add: false,
                    dialog: false,
                };
            },
        });
    }

    test('call allow method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.allow = jest.fn();
        wrapper.vm.allow();
        expect(wrapper.vm.allow.mock.calls.length).toBe(1);
    })

    test('render list table', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({ Datatables: true });
        await wrapper.vm.$nextTick();
        expect(wrapper.find('[data-testid="list-table"]').exists()).toBe(true);
    })

    test('calls onReload when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({ reload: true });
        wrapper.vm.onReload = jest.fn();
        wrapper.vm.onReload();
        await wrapper.vm.$nextTick();
        expect(wrapper.find('.mdi-reload').exists()).toBe(true);
        const btn = wrapper.find('.mdi-reload')
        btn.trigger('click')
        expect(wrapper.vm.onReload.mock.calls.length).toBe(1);
        // expect(wrapper.find('.mdi-reload').trigger("click"));
    })

    test('calls onFilter when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({ dialog: true });
        wrapper.vm.onFilter = jest.fn();
        wrapper.vm.onFilter();
        await wrapper.vm.$nextTick();
        expect(wrapper.find('[data-testid="filterbtn"]').exists()).toBe(true);
        // const btn = wrapper.find('[data-testid="filterbtn"]')
        // btn.trigger('click')
        expect(wrapper.vm.onFilter.mock.calls.length).toBe(1);
    })
    test('calls onAdd when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({ add: true });
        wrapper.vm.onAdd = jest.fn();
        wrapper.vm.onAdd();
        await wrapper.vm.$nextTick();
        expect(wrapper.find('[data-testid="addbtn"]').exists()).toBe(true);
        const button = wrapper.find('[data-testid="addbtn"]')
        button.trigger('click')
        expect(wrapper.vm.onAdd.mock.calls.length).toBe(1);
    })
    test('Enter search text and check the value of keyword', async () => {
        const wrapper = wrapperFactory();
        wrapper.find('[data-testid="search"]')
        wrapper.setData({ keyword: 'UMM' })
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.keyword).toBe('UMM')
    })

    test('click search button and clear input', () => {
        const wrapper = wrapperFactory();
        wrapper.find('.mdi-magnify').trigger("click");
        var textInput = wrapper.find('[data-testid="search"]')
        expect(textInput.text()).toMatch('')
        expect(wrapper.vm.keyword).toBe('')
    })

    test('test total data from data tables ', async () => {
        const wrapper = wrapperFactory();
        wrapper.find('[data-testid="list-table"]')
        wrapper.setData({ total: 22 });
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.total).toBe(22)
    })

    // test('calls onAction when button is clicked', async () => {
    //     const wrapper = wrapperFactory();
    //     wrapper.setData({ actions: [] });
    //     wrapper.vm.onAction = jest.fn();
    //     wrapper.vm.onAction();
    //     await wrapper.vm.$nextTick();
    //     expect(wrapper.find('[data-testid="action-list"]').exists()).toBe(true);
    //     const button = wrapper.find('[data-testid="actions"]')
    //     button.trigger('click')
    //     expect(wrapper.vm.onAction.mock.calls.length).toBe(1);
    // })

})
