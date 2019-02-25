Nova.booting((Vue, router) => {
    Vue.component('form-dce-status-field', require('./components/FormField'));
    Vue.component('dce-status-item', require('./components/StatusItem'));
    Vue.component('dce-status-list', require('./components/StatusList'));
    Vue.component('dce-statuses-wrapper', require('./components/StatusesWrapper'));
    Vue.component('detail-dce-status-field', require('./components/StatusField'));
    Vue.component('index-dce-status-field', require('./components/IndexField'));
    Vue.component('detail-dce-statuses-field', require('./components/StatusesField'));
})
