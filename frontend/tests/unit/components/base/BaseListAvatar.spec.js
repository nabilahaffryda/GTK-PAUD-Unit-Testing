import { mount, createLocalVue } from "@vue/test-utils";
import BaseListAvatar from "@/components/base/BaseListAvatar.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseListAvatar.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseListAvatar, {
            localVue,
            vuetify,
            router,
            props: {
                title: 'avatar'
            }
        });
    }
    test('call onDetail method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onDetail = jest.fn();
        wrapper.vm.onDetail();
        expect(wrapper.vm.onDetail.mock.calls.length).toBe(1);
    })
})