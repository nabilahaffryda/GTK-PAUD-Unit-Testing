import { createLocalVue, mount } from "@vue/test-utils";
import Unggah from "@/components/form/Unggah.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('Unggah.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    test('call onCheck method', () => {
        const wrapper = mount(Unggah, {
            localVue,
            vuetify,
            router,
            computed: {
                accept() {
                    return true
                },
                data() {
                    return true
                }
            }
        });
        wrapper.vm.onCheck = jest.fn();
        wrapper.vm.onCheck();
        expect(wrapper.vm.onCheck.mock.calls.length).toBe(1);
    })

    test('call initForm method', () => {
        const wrapper = mount(Unggah, {
            localVue,
            vuetify,
            router,
            computed: {
                accept() {
                    return true
                },
                data() {
                    return true
                }
            }
        });
        wrapper.vm.initForm = jest.fn();
        wrapper.vm.initForm();
        expect(wrapper.vm.initForm.mock.calls.length).toBe(1);
    })

    test('call reset methods', () => {
        const wrapper = mount(Unggah, {
            localVue,
            vuetify,
            router,
            computed: {
                accept() {
                    return true
                },
                data() {
                    return true
                }
            }
        });
        wrapper.vm.reset = jest.fn();
        wrapper.vm.reset();
        expect(wrapper.vm.reset.mock.calls.length).toBe(1);
    })

    test('call onRemoveFile methods', () => {
        const wrapper = mount(Unggah, {
            localVue,
            vuetify,
            router,
            computed: {
                accept() {
                    return true
                },
                data() {
                    return true
                }
            }
        });
        wrapper.vm.onRemoveFile = jest.fn();
        wrapper.vm.onRemoveFile();
        expect(wrapper.vm.onRemoveFile.mock.calls.length).toBe(1);
    })

    test('call roundDecimal methods', () => {
        const wrapper = mount(Unggah, {
            localVue,
            vuetify,
            router,
            computed: {
                accept() {
                    return true
                },
                data() {
                    return true
                }
            }
        });
        wrapper.vm.roundDecimal = jest.fn();
        wrapper.vm.roundDecimal();
        expect(wrapper.vm.roundDecimal.mock.calls.length).toBe(1);
    })
})