import { mount, createLocalVue } from "@vue/test-utils";
import BaseListInfo from "@/components/base/BaseListInfo.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseListInfo.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(BaseListInfo, {
            localVue,
            vuetify,
            router,
            props: {
                info: []
            }
        });
    }
    test('test base list info', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.props = jest.fn();
        wrapper.vm.props();
        expect(wrapper.vm.props.mock.calls.length).toBe(1);
    })
})
