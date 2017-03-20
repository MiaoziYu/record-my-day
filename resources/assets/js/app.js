
/**
 * First we will load all of this project"s JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import router from "./router";
import store from "./store";
// import * as filters from "./filters"

// register global utility filters.
// Object.keys(filters).forEach(key => {
//     Vue.filter(key, filters[key])
// })

// create the app instance.
// here we inject the router and store to all child components,
// making them available everywhere as `this.$router` and `this.$store`.
const app = new window.Vue({
    router,
    store
}).$mount("#app");