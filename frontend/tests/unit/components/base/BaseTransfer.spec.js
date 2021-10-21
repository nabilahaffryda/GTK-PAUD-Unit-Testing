import { mount, createLocalVue } from "@vue/test-utils";
import BaseTransfer from "@/components/base/BaseTransfer.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import BaseListTable from "@/components/base/BaseListTable.vue";
import BaseTableFooter from "@/components/base/BaseTableFooter.vue";

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseTransfer.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render transfer', () => {
        const VCard = {
            props: [
                'dataLoading',
                'targetLoading',
                'dataList',
                'targetList',
                'totalData',
                'totalTarget',
                'pageTotalData',
                'pageTotalTarget'],
            template: '<div><slot :item="dataList" /></div>'
        }

        const wrapper = mount(BaseTransfer, {
            localVue,
            vuetify,
            stubs: {
                VCard, BaseListTable, BaseTableFooter
            },
            propsData: {
                title: 'transfer',
                keyAttr: 'key1',
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})
