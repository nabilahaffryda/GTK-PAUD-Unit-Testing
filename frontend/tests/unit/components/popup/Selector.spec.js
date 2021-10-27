import { createLocalVue, mount } from "@vue/test-utils";
import Selector from "@/components/popup/Selector.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('Selector.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    test('render popup selector', async () => {
        const VSelector = {
            props: ['title', 'valueId', 'showSelect', 'fetch', 'filterSelect', 'attr'],
            template: '<div><slot :item="data" /></div>'
        }
        const wrapper = mount(Selector, {
            localVue,
            vuetify,
            router,
            stubs: {
                VSelector
            },
            props: {
                fetch: 'string',
                valueId: 'value',
            },
        });
        expect(wrapper).toMatchSnapshot();
    })
})
