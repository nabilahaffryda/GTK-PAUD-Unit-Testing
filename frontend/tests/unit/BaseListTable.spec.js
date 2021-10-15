import { mount } from "@vue/test-utils"
import BaseListTable from "@/components/base/BaseListTable"

function getMountedComponent(Component, propsData) {
    return mount(Component, { propsData })
}
const propsData = {
    item: [{ id: 1 }]
}
describe('BaseListTable.vue', () => {
    it('show table list', () => {
        expect(getMountedComponent(BaseListTable, propsData).vm.headers);
    })
})
