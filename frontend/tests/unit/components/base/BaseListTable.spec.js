import { mount } from "@vue/test-utils";
import BaseListTable from "@/components/base/BaseListTable.vue";

describe('BaseListTable.vue', () => {
    test('should show data in list', () => {
        const VDataTable = {
            props: ['items', 'loading', 'usePaging', 'hideHeader',
                'showSelect', 'keyword', 'title', 'limit', 'opt', 'total', 'headers'],
            template: '<div><slot :item="items[0]" name="item.name" /></div>'
        }

        const wrapper = mount(BaseListTable, {
            stubs: {
                VDataTable
            }
        })
        expect(wrapper).toMatchSnapshot();
    })
})
