import { mount, createLocalVue } from "@vue/test-utils";
import BaseCardInfo from "@/components/base/BaseCardInfo.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseCardInfo.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseCardInfo, {
            localVue,
            vuetify,
            router,
            props: {
                size: 28,
                icon: 'mdi-pencil',
                title: 'sim paud',
                info: 'sim paud'
            }
        });
    }
    test('test base card info', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.props = jest.fn();
        wrapper.vm.props();
        expect(wrapper.vm.props.mock.calls.length).toBe(1);
    })
})
