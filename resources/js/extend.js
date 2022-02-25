import Vue from "vue";

VueAdmin.booting((Vue, router, store) => {
    Vue.component("GoodsSku", require('./components/GoodsSku.vue').default);
});
