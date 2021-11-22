import { createLocalVue, mount } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex";
import { getDeepObj, isObject, } from '@utils/format';
import FormProfil from "@/views/instansi/profil/formulir/FormProfil.vue";

Vue.use(Vuetify)
const localVue = createLocalVue()

localVue.use(VueRouter)
const router = new VueRouter()

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        master: {
            namespaced: true,
            actions: {
                getMasters() {
                    return true
                },
            }
        },
        profil: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                update() {
                    return true
                },
                getDiklat() {
                    return true
                },
                getBerkas() {
                    return true
                },
                setBerkas() {
                    return true
                },
                dropBerkas() {
                    return true
                },
                ajuan() {
                    return true
                },
                batalAjuan() {
                    return true
                },
            }
        }
    }
})

localVue.mixin({
    methods: {
        $isObject(data) {
            return isObject(data || {});
        },
        $allow() {
            return true;
        },
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $titleCase(kata) {
            return (kata && kata.charAt(0).toUpperCase() + kata.slice(1)) || '';
        },
        $mapForMaster(data, text = false) {
            // cek type data
            let temp = [];
            if (data && isObject(data)) {
                for (let key in data) {
                    temp.push({
                        text: data[key],
                        value: text ? data[key] : Number(key),
                    });
                }
            } else if (data && isArray(data)) {
                temp = data.map((value, idx) => {
                    return {
                        text: isObject(value) ? value?.keterangan ?? value.text : value,
                        value: isObject(value) ? value[text] ?? value?.value ?? idx : value,
                    };
                });
            }
            return temp;
        },
    }
})

describe('FormProfil.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(FormProfil, {
            localVue,
            router,
            store,
            vuetify,
            data() {
                return {
                    data: this.detail,
                }
            },
            computed: {
                mKualifikasi() {
                    return true
                },
                configs() {
                    return true
                },
                schema() {
                    return true
                }
            }
        })
    }
    test('test unggah foto button', async () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            data: [
                {
                    akun: {
                        data: {
                            akun_id: "10002877",
                            foto: "3531/35/10002877-210715091903.jpeg",
                            foto_url: "https://upload.dev.siap.id/gpo/foto-akun/3531/35/10002877-210715091903.jpeg",
                            nama: "Yaumil Akhir"
                        }
                    }
                }
            ]
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.find('[id="edit-profpic"]').exists()).toBe(true)
        const button = wrapper.find('[id="edit-profpic"]')
        button.trigger('click')
    })
})
