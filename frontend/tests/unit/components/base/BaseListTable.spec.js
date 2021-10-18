import { mount, createLocalVue } from "@vue/test-utils";
import BaseListTable from "@/components/base/BaseListTable.vue";

describe('BaseListTable.vue', () => {
    test('should show data in list', () => {
        const VDataTable = {
            props: ['items', 'loading'],
            template: '<div><slot :item="items[0]" name="item.name" /></div>'
        }

        mount(BaseListTable, {
            stubs: {
                VDataTable
            }
        })
    })
})
