import { mount, createLocalVue } from "@vue/test-utils";
import BaseListTable from "@/components/base/BaseListTable.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseListTable.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('should show data in list', () => {
        const VDataTable = {
            props: ['items', 'loading', 'usePaging', 'hideHeader',
                'showSelect', 'keyword', 'title', 'limit', 'opt', 'total', 'headers'],
            template: '<div><slot :item="items[0]" name="item.name" /></div>'
        }

        const wrapper = mount(BaseListTable, {
            stubs: {
                VDataTable
            },
            localVue,
            vuetify
        })
        expect(wrapper).toMatchSnapshot();
    })
})
