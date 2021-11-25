import { createLocalVue, mount } from "@vue/test-utils";
import Vue from "vue";
import Vuetify from "vuetify";
import VueRouter from "vue-router";
import Vuex from "vuex";
import Akun from "@/views/instansi/akun/formulir/Akun.vue";
import { getDeepObj, isObject, isArray } from '@utils/format';

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
        institusi: {
            namespaced: true,
            listInstansis: {
                fetch() {
                    return true
                }
            }
        },
        akun: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                create() {
                    return true
                },
                update() {
                    return true
                },
                listGroups() {
                    return true
                },
                action() {
                    return true
                },
                lookup() {
                    return true
                },
                getDetail() {
                    return true
                },
                downloadList() {
                    return true
                },
                templateUpload() {
                    return true
                },
                upload() {
                    return true
                },
                setStatus() {
                    return true
                },
            }
        }
    }
})

localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $allow() {
            return true;
        },
        $isObject(data) {
            return isObject(data || {});
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
        $isArray(data) {
            return isArray(data);
        },
    }
})

describe('Akun.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Akun, {
            localVue,
            router,
            store,
            vuetify,
            propsData: {
                jenis: 'pengajar',
                masters: {
                    "m_propinsi": {
                        "11": "Aceh",
                        "12": "Sumatera Utara",
                        "13": "Sumatera Barat",
                        "14": "Riau",
                        "15": "Jambi",
                        "21": "Sumatera Selatan",
                        "22": "Bengkulu",
                        "23": "Lampung",
                        "24": "Kepulauan Bangka Belitung",
                        "25": "Kepulauan Riau",
                        "31": "DKI Jakarta",
                        "32": "Jawa Barat",
                        "33": "Jawa Tengah",
                        "34": "DI Yogyakarta",
                        "35": "Jawa Timur",
                        "36": "Banten",
                        "41": "Kalimantan Barat",
                        "42": "Kalimantan Tengah",
                        "43": "Kalimantan Selatan",
                        "44": "Kalimantan Timur",
                        "45": "Kalimantan Utara",
                        "51": "Sulawesi Utara",
                        "52": "Sulawesi Tengah",
                        "53": "Sulawesi Selatan",
                        "54": "Sulawesi Tenggara",
                        "55": "Gorontalo",
                        "56": "Sulawesi Barat",
                        "61": "Bali",
                        "62": "Nusa Tenggara Barat",
                        "63": "Nusa Tenggara Timur",
                        "71": "Maluku",
                        "72": "Maluku Utara",
                        "73": "Papua Barat",
                        "74": "Papua Barat"
                    },
                    "m_kota": {
                        "1101": "Kab. Aceh Besar",
                        "1102": "Kab. Pidie",
                        "1103": "Kab. Aceh Utara",
                        "1104": "Kab. Aceh Timur",
                        "1105": "Kab. Aceh Tengah",
                        "1106": "Kab. Aceh Barat",
                        "1107": "Kab. Aceh Selatan",
                        "1108": "Kab. Aceh Tenggara",
                        "1109": "Kab. Simeulue",
                        "1110": "Kab. Bireuen",
                        "1111": "Kab. Aceh Singkil",
                        "1112": "Kab. Aceh Tamiang",
                        "1113": "Kab. Gayo Lues",
                        "1114": "Kab. Nagan Raya",
                        "1115": "Kab. Aceh Barat Daya",
                        "1116": "Kab. Aceh Jaya",
                        "1117": "Kab. Bener Meriah",
                        "1118": "Kota Sabang",
                        "1119": "Kota Banda Aceh",
                        "1120": "Kota Lhokseumawe",
                        "1121": "Kota Langsa",
                        "1122": "Kab. Pidie Jaya",
                        "1123": "Kota Subulussalam",
                        "1201": "Kab. Deli Serdang",
                        "1202": "Kab. Langkat",
                        "1203": "Kab. Karo",
                        "1204": "Kab. Simalungun",
                        "1205": "Kab. Dairi",
                        "1206": "Kab. Asahan",
                        "1207": "Kab. Labuhanbatu",
                        "1208": "Kab. Tapanuli Utara",
                        "1209": "Kab. Tapanuli Tengah",
                        "1210": "Kab. Tapanuli Selatan",
                        "1211": "Kab. Nias",
                        "1212": "Kab. Mandailing Natal",
                        "1213": "Kab. Toba Samosir",
                        "1214": "Kab. Nias Selatan",
                        "1215": "Kab. Pakpak Bharat",
                        "1216": "Kab. Humbang Hasundutan",
                        "1217": "Kab. Samosir",
                        "1218": "Kab. Serdang Bedagai",
                        "1219": "Kota Medan",
                        "1220": "Kota Binjai",
                        "1221": "Kota Tebing Tinggi",
                        "1222": "Kota Pematang Siantar",
                        "1223": "Kota Tanjung Balai",
                        "1224": "Kota Sibolga",
                        "1225": "Kota Padang Sidimpuan",
                        "1227": "Kab. Batubara",
                        "1228": "Kab. Padang Lawas",
                        "1229": "Kab. Padang Lawas Utara",
                        "1230": "Kab. Labuhanbatu Utara",
                        "1231": "Kab. Labuhanbatu Selatan",
                        "1232": "Kab. Nias Barat",
                        "1233": "Kab. Nias Utara",
                        "1234": "Kota Gunung Sitoli",
                        "1235": "Kab. Angkola Sipirok",
                        "1301": "Kab. Agam",
                        "1302": "Kab. Pasaman",
                        "1303": "Kab. Lima Puluh Kota",
                        "1304": "Kab. Solok",
                        "1305": "Kab. Padang Pariaman",
                        "1306": "Kab. Pesisir Selatan",
                        "1307": "Kab. Tanah Datar",
                        "1308": "Kab. Sijunjung",
                        "1309": "Kab. Kepulauan Mentawai",
                        "1310": "Kab. Pasaman Barat",
                        "1311": "Kab. Dharmasraya",
                        "1312": "Kab. Solok Selatan",
                        "1313": "Kota Bukittinggi",
                        "1314": "Kota Padang",
                        "1315": "Kota Padang Panjang",
                        "1316": "Kota Sawahlunto",
                        "1317": "Kota Solok",
                        "1318": "Kota Payakumbuh",
                        "1319": "Kota Pariaman",
                        "1401": "Kab. Kampar",
                        "1402": "Kab. Bengkalis",
                        "1403": "Kab. Indragiri Hulu",
                        "1404": "Kab. Indragiri Hilir",
                        "1405": "Kab. Pelalawan",
                        "1406": "Kab. Rokan Hulu",
                        "1407": "Kab. Rokan Hilir",
                        "1408": "Kab. Siak",
                        "1409": "Kab. Kuantan Singingi",
                        "1410": "Kota Pekanbaru",
                        "1411": "Kota Dumai",
                        "1412": "Kab. Kepulauan Meranti",
                        "1501": "Kab. Batanghari",
                        "1502": "Kab. Bungo",
                        "1503": "Kab. Merangin",
                        "1504": "Kab. Tanjung Jabung Barat",
                        "1505": "Kab. Kerinci",
                        "1506": "Kab. Muaro Jambi",
                        "1507": "Kab. Tebo",
                        "1508": "Kab. Sarolangun",
                        "1509": "Kab. Tanjung Jabung Timur",
                        "1510": "Kota Jambi",
                        "1511": "Kota Sungai Penuh",
                        "2101": "Kab. Musi Banyuasin",
                        "2102": "Kab. Ogan Komering Ilir",
                        "2103": "Kab. Ogan Komering Ulu",
                        "2104": "Kab. Muara Enim",
                        "2105": "Kab. Lahat",
                        "2106": "Kab. Musi Rawas",
                        "2107": "Kab. Banyuasin",
                        "2108": "Kab. Ogan Ilir",
                        "2109": "Kab. Ogan Komering Ulu Selatan",
                        "2110": "Kab. Ogan Komering Ulu Timur",
                        "2111": "Kota Palembang",
                        "2112": "Kota Lubuk Linggau",
                        "2113": "Kota Prabumulih",
                        "2114": "Kota Pagar Alam",
                        "2115": "Kab. Empat Lawang",
                        "2116": "Kab. Penukal Abab Lematang Ilir",
                        "2117": "Kab. Musi Rawas Utara",
                        "2201": "Kab. Bengkulu Utara",
                        "2202": "Kab. Rejang Lebong",
                        "2203": "Kab. Bengkulu Selatan",
                        "2204": "Kab. Muko-Muko",
                        "2205": "Kab. Seluma",
                        "2206": "Kab. Kaur",
                        "2207": "Kab. Lebong",
                        "2208": "Kab. Kepahiang",
                        "2209": "Kota Bengkulu",
                        "2210": "Kab. Bengkulu Tengah",
                        "2301": "Kab. Lampung Selatan",
                        "2302": "Kab. Lampung Tengah",
                        "2303": "Kab. Lampung Utara",
                        "2304": "Kab. Lampung Barat",
                        "2305": "Kab. Tulang Bawang",
                        "2306": "Kab. Tanggamus",
                        "2307": "Kab. Lampung Timur",
                        "2308": "Kab. Way Kanan",
                        "2309": "Kota Bandar Lampung",
                        "2310": "Kota Metro",
                        "2311": "Kab. Pesawaran",
                        "2312": "Kab. Mesuji",
                        "2313": "Kab. Pringsewu",
                        "2314": "Kab. Tulang Bawang Barat",
                        "2315": "Kab. Pesisir Barat",
                        "2401": "Kab. Bangka Barat",
                        "2402": "Kab. Bangka Tengah",
                        "2403": "Kab. Bangka Selatan",
                        "2405": "Kab. Belitung Timur",
                        "2406": "Kab. Bangka",
                        "2407": "Kab. Belitung",
                        "2408": "Kota Pangkal Pinang",
                        "2501": "Kab. Kepulauan Riau",
                        "2502": "Kab. Karimun",
                        "2503": "Kab. Natuna",
                        "2504": "Kab. Lingga",
                        "2505": "Kota Batam",
                        "2506": "Kota Tanjung Pinang",
                        "2507": "Kab. Bintan",
                        "2508": "Kab. Kepulauan Anambas",
                        "3101": "Kab. Kepulauan Seribu",
                        "3102": "Kota Jakarta Pusat",
                        "3103": "Kota Jakarta Utara",
                        "3104": "Kota Jakarta Barat",
                        "3105": "Kota Jakarta Selatan",
                        "3106": "Kota Jakarta Timur",
                        "3201": "Kab. Bogor",
                        "3202": "Kab. Sukabumi",
                        "3203": "Kab. Cianjur",
                        "3204": "Kab. Bandung",
                        "3205": "Kab. Sumedang",
                        "3206": "Kab. Garut",
                        "3207": "Kab. Tasikmalaya",
                        "3208": "Kab. Ciamis",
                        "3209": "Kab. Kuningan",
                        "3210": "Kab. Majalengka",
                        "3211": "Kab. Cirebon",
                        "3212": "Kab. Indramayu",
                        "3213": "Kab. Subang",
                        "3214": "Kab. Purwakarta",
                        "3215": "Kab. Karawang",
                        "3216": "Kab. Bekasi",
                        "3217": "Kota Bandung",
                        "3218": "Kota Bogor",
                        "3219": "Kota Sukabumi",
                        "3220": "Kota Cirebon",
                        "3221": "Kota Bekasi",
                        "3222": "Kota Depok",
                        "3223": "Kota Cimahi",
                        "3224": "Kota Tasikmalaya",
                        "3225": "Kota Banjar",
                        "3226": "Kab. Bandung Barat",
                        "3231": "Kab. Pangandaran",
                        "3301": "Kab. Cilacap",
                        "3302": "Kab. Banyumas",
                        "3303": "Kab. Purbalingga",
                        "3304": "Kab. Banjarnegara",
                        "3305": "Kab. Kebumen",
                        "3306": "Kab. Purworejo",
                        "3307": "Kab. Wonosobo",
                        "3308": "Kab. Magelang",
                        "3309": "Kab. Boyolali",
                        "3310": "Kab. Klaten",
                        "3311": "Kab. Sukoharjo",
                        "3312": "Kab. Wonogiri",
                        "3313": "Kab. Karanganyar",
                        "3314": "Kab. Sragen",
                        "3315": "Kab. Grobogan",
                        "3316": "Kab. Blora",
                        "3317": "Kab. Rembang",
                        "3318": "Kab. Pati",
                        "3319": "Kab. Kudus",
                        "3320": "Kab. Jepara",
                        "3321": "Kab. Demak",
                        "3322": "Kab. Semarang",
                        "3323": "Kab. Temanggung",
                        "3324": "Kab. Kendal",
                        "3325": "Kab. Batang",
                        "3326": "Kab. Pekalongan",
                        "3327": "Kab. Pemalang",
                        "3328": "Kab. Tegal",
                        "3329": "Kab. Brebes",
                        "3330": "Kota Magelang",
                        "3331": "Kota Surakarta",
                        "3332": "Kota Salatiga",
                        "3333": "Kota Semarang",
                        "3334": "Kota Pekalongan",
                        "3335": "Kota Tegal",
                        "3401": "Kab. Bantul",
                        "3402": "Kab. Sleman",
                        "3403": "Kab. Gunung Kidul",
                        "3404": "Kab. Kulonprogo",
                        "3405": "Kota Yogyakarta",
                        "3501": "Kab. Gresik",
                        "3502": "Kab. Sidoarjo",
                        "3503": "Kab. Mojokerto",
                        "3504": "Kab. Jombang",
                        "3505": "Kab. Bojonegoro",
                        "3506": "Kab. Tuban",
                        "3507": "Kab. Lamongan",
                        "3508": "Kab. Madiun",
                        "3509": "Kab. Ngawi",
                        "3510": "Kab. Magetan",
                        "3511": "Kab. Ponorogo",
                        "3512": "Kab. Pacitan",
                        "3513": "Kab. Kediri",
                        "3514": "Kab. Nganjuk",
                        "3515": "Kab. Blitar",
                        "3516": "Kab. Tulungagung",
                        "3517": "Kab. Trenggalek",
                        "3518": "Kab. Malang",
                        "3519": "Kab. Pasuruan",
                        "3520": "Kab. Probolinggo",
                        "3521": "Kab. Lumajang",
                        "3522": "Kab. Bondowoso",
                        "3523": "Kab. Situbondo",
                        "3524": "Kab. Jember",
                        "3525": "Kab. Banyuwangi",
                        "3526": "Kab. Pamekasan",
                        "3527": "Kab. Sampang",
                        "3528": "Kab. Sumenep",
                        "3529": "Kab. Bangkalan",
                        "3530": "Kota Surabaya",
                        "3531": "Kota Malang",
                        "3532": "Kota Madiun",
                        "3533": "Kota Kediri",
                        "3534": "Kota Mojokerto",
                        "3535": "Kota Blitar",
                        "3536": "Kota Pasuruan",
                        "3537": "Kota Probolinggo",
                        "3538": "Kota Batu",
                        "3601": "Kab. Pandeglang",
                        "3602": "Kab. Lebak",
                        "3603": "Kab. Tangerang",
                        "3604": "Kab. Serang",
                        "3605": "Kota Cilegon",
                        "3606": "Kota Tangerang",
                        "3607": "Kota Tangerang Selatan",
                        "3608": "Kota Serang",
                        "4101": "Kab. Sambas",
                        "4102": "Kab. Mempawah",
                        "4103": "Kab. Sanggau",
                        "4104": "Kab. Sintang",
                        "4105": "Kab. Kapuas Hulu",
                        "4106": "Kab. Ketapang",
                        "4107": "Kab. Bengkayang",
                        "4108": "Kab. Landak",
                        "4109": "Kab. Melawi",
                        "4110": "Kota Pontianak",
                        "4111": "Kota Singkawang",
                        "4112": "Kab. Sekadau",
                        "4113": "Kab. Kayong Utara",
                        "4114": "Kab. Kubu Raya",
                        "4201": "Kab. Kapuas",
                        "4202": "Kab. Barito Selatan",
                        "4203": "Kab. Barito Utara",
                        "4204": "Kab. Kotawaringin Timur",
                        "4205": "Kab. Kotawaringin Barat",
                        "4206": "Kab. Pulang Pisau",
                        "4207": "Kab. Gunung Mas",
                        "4208": "Kab. Barito Timur",
                        "4209": "Kab. Sukamara",
                        "4210": "Kab. Katingan",
                        "4211": "Kab. Lamandau",
                        "4212": "Kab. Seruyan",
                        "4213": "Kab. Murung Raya",
                        "4214": "Kota Palangkaraya",
                        "4301": "Kab. Banjar",
                        "4302": "Kab. Tanah Laut",
                        "4303": "Kab. Barito Kuala",
                        "4304": "Kab. Tapin",
                        "4305": "Kab. Hulu Sungai Selatan",
                        "4306": "Kab. Hulu Sungai Tengah",
                        "4307": "Kab. Hulu Sungai Utara",
                        "4308": "Kab. Tabalong",
                        "4309": "Kab. Kotabaru",
                        "4310": "Kab. Tanah Bumbu",
                        "4311": "Kab. Balangan",
                        "4312": "Kota Banjarmasin",
                        "4313": "Kota Banjarbaru",
                        "4401": "Kab. Paser",
                        "4402": "Kab. Kutai Kartanegara",
                        "4403": "Kab. Berau",
                        "4404": "Kab. Bulungan",
                        "4405": "Kab. Malinau",
                        "4406": "Kab. Nunukan",
                        "4407": "Kab. Kutai Barat",
                        "4408": "Kab. Kutai Timur",
                        "4409": "Kab. Penajam Paser Utara",
                        "4410": "Kota Samarinda",
                        "4411": "Kota Balikpapan",
                        "4412": "Kota Tarakan",
                        "4413": "Kota Bontang",
                        "4414": "Kab. Tana Tidung",
                        "4415": "Kab. Mahakam Ulu",
                        "4501": "Kota Tarakan",
                        "4502": "Kab. Bulungan",
                        "4503": "Kab. Malinau",
                        "4504": "Kab. Nunukan",
                        "4505": "Kab. Tana Tidung",
                        "4506": "Kab. Penajam Paser Utara",
                        "5101": "Kab. Bolaang Mongondow",
                        "5102": "Kab. Minahasa",
                        "5103": "Kab. Kepulauan Sangihe",
                        "5104": "Kab. Kepulauan Talaud",
                        "5105": "Kab. Minahasa Selatan",
                        "5106": "Kab. Minahasa Utara",
                        "5107": "Kota Manado",
                        "5108": "Kota Bitung",
                        "5109": "Kota Tomohon",
                        "5110": "Kab. Bolaang Mongondow Utara",
                        "5111": "Kab. Kepulauan Siau Tagulandang Biaro",
                        "5112": "Kab. Minahasa Tenggara",
                        "5113": "Kota Kotamobagu",
                        "5114": "Kab. Bolaang Mongondow Timur",
                        "5115": "Kab. Bolaang Mongondow Selatan",
                        "5201": "Kab. Banggai Kepulauan",
                        "5202": "Kab. Donggala",
                        "5203": "Kab. Poso",
                        "5204": "Kab. Banggai",
                        "5205": "Kab. Buol",
                        "5206": "Kab. Toli Toli",
                        "5207": "Kab. Morowali",
                        "5208": "Kab. Parigi Moutong",
                        "5209": "Kab. Tojo Una-Una",
                        "5210": "Kota Palu",
                        "5211": "Kab. Sigi",
                        "5212": "Kab. Banggai Laut",
                        "5213": "Kab. Morowali Utara",
                        "5301": "Kab. Maros",
                        "5302": "Kab. Pangkajene Kepulauan",
                        "5303": "Kab. Gowa",
                        "5304": "Kab. Takalar",
                        "5305": "Kab. Jeneponto",
                        "5306": "Kab. Barru",
                        "5307": "Kab. Bone",
                        "5308": "Kab. Wajo",
                        "5309": "Kab. Soppeng",
                        "5310": "Kab. Bantaeng",
                        "5311": "Kab. Bulukumba",
                        "5312": "Kab. Sinjai",
                        "5313": "Kab. Kepulauan Selayar",
                        "5314": "Kab. Pinrang",
                        "5315": "Kab. Sidenreng Rappang",
                        "5316": "Kab. Enrekang",
                        "5317": "Kab. Luwu",
                        "5318": "Kab. Tana Toraja",
                        "5319": "Kab. Luwu Utara",
                        "5320": "Kab. Luwu Timur",
                        "5321": "Kota Makassar",
                        "5322": "Kota Pare Pare",
                        "5323": "Kota Palopo",
                        "5324": "Kab. Toraja Utara",
                        "5401": "Kab. Konawe",
                        "5402": "Kab. Muna",
                        "5403": "Kab. Buton",
                        "5404": "Kab. Kolaka",
                        "5405": "Kab. Konawe Selatan",
                        "5406": "Kab. Kolaka Utara",
                        "5407": "Kab. Wakatobi",
                        "5408": "Kab. Bombana",
                        "5409": "Kota Kendari",
                        "5410": "Kota Bau-Bau",
                        "5411": "Kab. Buton Utara",
                        "5412": "Kab. Konawe Utara",
                        "5413": "Kab. Kolaka Timur",
                        "5414": "Kab. Konawe Kepulauan",
                        "5415": "Kab. Buton Selatan",
                        "5416": "Kab. Buton Tengah",
                        "5417": "Kab. Muna Barat",
                        "5501": "Kab. Boalemo",
                        "5502": "Kab. Gorontalo",
                        "5503": "Kab. Pohuwato",
                        "5504": "Kab. Bonebolango",
                        "5505": "Kota Gorontalo",
                        "5506": "Kab. Gorontalo Utara",
                        "5601": "Kab. Mamuju",
                        "5602": "Kab. Pasangkayu",
                        "5603": "Kab. Polewali Mandar",
                        "5604": "Kab. Mamasa",
                        "5605": "Kab. Majene",
                        "5606": "Kab. Mamuju Tengah",
                        "6101": "Kab. Buleleng",
                        "6102": "Kab. Jembrana",
                        "6103": "Kab. Tabanan",
                        "6104": "Kab. Badung",
                        "6105": "Kab. Gianyar",
                        "6106": "Kab. Klungkung",
                        "6107": "Kab. Bangli",
                        "6108": "Kab. Karang Asem",
                        "6109": "Kota Denpasar",
                        "6201": "Kab. Lombok Barat",
                        "6202": "Kab. Lombok Tengah",
                        "6203": "Kab. Lombok Timur",
                        "6204": "Kab. Sumbawa",
                        "6205": "Kab. Dompu",
                        "6206": "Kab. Bima",
                        "6207": "Kab. Sumbawa Barat",
                        "6208": "Kota Mataram",
                        "6209": "Kota Bima",
                        "6210": "Kab. Lombok Utara",
                        "6301": "Kab. Kupang",
                        "6302": "Kab. Timor Tengah Selatan",
                        "6303": "Kab. Timor Tengah Utara",
                        "6304": "Kab. Belu",
                        "6305": "Kab. Alor",
                        "6306": "Kab. Flores Timur",
                        "6307": "Kab. Sikka",
                        "6308": "Kab. Ende",
                        "6309": "Kab. Ngada",
                        "6310": "Kab. Manggarai",
                        "6311": "Kab. Sumba Timur",
                        "6312": "Kab. Sumba Barat",
                        "6313": "Kab. Lembata",
                        "6314": "Kab. Rote Ndao",
                        "6315": "Kab. Manggarai Barat",
                        "6316": "Kota Kupang",
                        "6317": "Kab. Nagekeo",
                        "6318": "Kab. Sumba Barat Daya",
                        "6319": "Kab. Sumba Tengah",
                        "6320": "Kab. Manggarai Timur",
                        "6321": "Kab. Sabu Raijua",
                        "6322": "Kab. Malaka",
                        "7101": "Kab. Maluku Tengah",
                        "7102": "Kab. Maluku Tenggara",
                        "7103": "Kab. Buru",
                        "7104": "Kab. Kepulauan Tanimbar",
                        "7105": "Kab. Seram Bagian Barat",
                        "7106": "Kab. Seram Bagian Timur",
                        "7107": "Kab. Kepulauan Aru",
                        "7108": "Kota Ambon",
                        "7109": "Kota Tual",
                        "7110": "Kab. Buru Selatan",
                        "7111": "Kab. Maluku Barat Daya",
                        "7201": "Kab. Halmahera Barat",
                        "7202": "Kab. Halmahera Tengah",
                        "7203": "Kab. Halmahera Utara",
                        "7204": "Kab. Halmahera Selatan",
                        "7205": "Kab. Kepulauan Sula",
                        "7206": "Kab. Halmahera Timur",
                        "7207": "Kota Ternate",
                        "7208": "Kota Tidore Kepulauan",
                        "7209": "Kab. Pulau Morotai",
                        "7210": "Kab. Pulau Taliabu",
                        "7301": "Kab. Jayapura",
                        "7302": "Kab. Biak Numfor",
                        "7303": "Kab. Kepulauan Yapen",
                        "7304": "Kab. Merauke",
                        "7305": "Kab. Jayawijaya",
                        "7306": "Kab. Paniai",
                        "7307": "Kab. Nabire",
                        "7308": "Kab. Puncak Jaya",
                        "7309": "Kab. Mimika",
                        "7310": "Kab. Keerom",
                        "7311": "Kab. Sarmi",
                        "7312": "Kab. Asmat",
                        "7313": "Kab. Mappi",
                        "7314": "Kab. Boven Digoel",
                        "7315": "Kab. Yahukimo",
                        "7316": "Kab. Pegunungan Bintang",
                        "7317": "Kab. Supiori",
                        "7318": "Kab. Waropen",
                        "7319": "Kab. Tolikara",
                        "7320": "Kota Jayapura",
                        "7321": "Kab. Mamberamo Raya",
                        "7322": "Kab. Dogiyai",
                        "7323": "Kab. Lanny Jaya",
                        "7324": "Kab. Mamberamo Tengah",
                        "7325": "Kab. Nduga",
                        "7326": "Kab. Puncak",
                        "7327": "Kab. Yalimo",
                        "7328": "Kab. Intan Jaya",
                        "7329": "Kab. Deiyai",
                        "7401": "Kab. Fak-Fak",
                        "7402": "Kab. Sorong",
                        "7403": "Kab. Manokwari",
                        "7404": "Kab. Kaimana",
                        "7405": "Kab. Sorong Selatan",
                        "7406": "Kab. Raja Ampat",
                        "7407": "Kab. Teluk Bintuni",
                        "7408": "Kab. Teluk Wondama",
                        "7409": "Kota Sorong",
                        "7410": "Kab. Tambrauw",
                        "7411": "Kab. Maybrat",
                        "7412": "Kab. Manokwari Selatan",
                        "7413": "Kab. Pegunungan Arfak",
                        "9101": "Egypt",
                        "9102": "Japan",
                        "9103": "Kuala Lumpur",
                        "9104": "Myanmar",
                        "9105": "Netherlands",
                        "9106": "Davao",
                        "9107": "Russia",
                        "9108": "Jeddah",
                        "9109": "Singapore",
                        "9110": "Syria",
                        "9111": "Thailand",
                        "9112": "Serbia",
                        "9113": "Taiwan",
                        "9114": "Johor",
                        "9115": "Kinabalu",
                        "9116": "Mekkah",
                        "9117": "Riyadh",
                        "9999": "-"
                    },
                    "m_kualifikasi": {
                        "1": "SD",
                        "2": "SMP",
                        "3": "SMA",
                        "4": "SMK",
                        "5": "D1",
                        "6": "D2",
                        "7": "D3",
                        "8": "D4",
                        "9": "S1",
                        "10": "S2",
                        "11": "S3",
                        "12": "Paket C",
                        "13": "Paket B",
                        "14": "Paket A",
                        "15": "Putus SD",
                        "16": "TK",
                        "17": "PAUD",
                        "18": "Tidak Sekolah"
                    },
                    "m_golongan": {
                        "1": "Ia - Juru Muda",
                        "2": "Ib - Juru Muda Tingkat 1",
                        "3": "Ic - Juru",
                        "4": "Id - Juru Tingkat I",
                        "5": "IIa - Pengatur Muda",
                        "6": "IIb - Pengatur Muda Tingat I",
                        "7": "IIc - Pengatur",
                        "8": "IId - Pengatur Tingkat I",
                        "9": "IIIa - Penata Muda",
                        "10": "IIIb - Penata Muda Tingkat I",
                        "11": "IIIc - Penata",
                        "12": "IIId - Penata Tingkat I",
                        "13": "IVa - Pembina",
                        "14": "IVb - Pembina Tingkat I",
                        "15": "IVc - Pembina Utama Muda",
                        "16": "IVd - Pembina Utama Madya",
                        "17": "IVe - Pembina Utama",
                        "99": "Tidak Ada",
                        "-2": "CPNS",
                        "-1": "PNS"
                    }
                }
            }
        })
    }
    test('test next button', async () => {
        const wrapper = wrapperFactory()
        console.log(wrapper.html())
    })

})
