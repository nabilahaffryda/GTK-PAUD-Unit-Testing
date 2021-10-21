import { mount, createLocalVue } from "@vue/test-utils";
import BaseTableHeader from "@/components/base/BaseTableHeader.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseTableHeader.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render table header', () => {
        const VCard = {
            props: ['options', 'searchInput', 'searchLabel', 'btnFilter', 'btnReload', 'btnAdd', 'btnUpload', 'btnDownload', 'classToolbar'],
            template: '<div><slot :label="searchLabel" /></div>'
        }

        const wrapper = mount(BaseTableHeader, {
            localVue,
            vuetify,
            stubs: {
                VCard
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})
