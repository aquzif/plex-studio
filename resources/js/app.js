import './bootstrap';

import './../../vendor/power-components/livewire-powergrid/dist/powergrid'
import './../../vendor/power-components/livewire-powergrid/dist/tailwind.css'
import 'flatpickr/dist/flatpickr.css';
import flatpickr from "flatpickr";
//const Polish = require("flatpickr/dist/l10n/pl").default.pl;
import Polish from "flatpickr/dist/l10n/pl";
import 'jquery/dist/jquery.min.js';
import 'tooltipster/dist/js/tooltipster.bundle.min.js';
import 'tooltipster/dist/css/tooltipster.bundle.min.css';

window.refreshTooltips= function () {
    $('.tooltip').each(function () {
        if(!$(this).hasClass('tooltipstered')) {
            $(this).tooltipster({
                delay: 0,
                side: 'bottom'
            });
        }
    });
}

window.flatpickr = flatpickr;
window.Toaster = {
    sendSuccess: function (
        options
    ) {
        let id = Math.floor(Math.random() * 1000000);
        let opts = Object.assign({
            id,
            title: 'Sukces!',
            message: '',
        },options)


        Livewire.dispatch('toaster-send-success',opts);
        setTimeout(() => {
            Livewire.dispatch('toaster-hide-toast',{id});
        },5000);
    },
    sendError: function (options) {
        let id = Math.floor(Math.random() * 1000000);
        let opts = Object.assign({
            id,
            title: 'Sukces!',
            message: '',
        },options)

        Livewire.dispatch('toaster-send-error',opts);
        setTimeout(() => {
            Livewire.dispatch('toaster-hide-toast',{id});
        },5000);

    },
    sendWarning: function (options) {
        let id = Math.floor(Math.random() * 1000000);
        let opts = Object.assign({
            id,
            title: 'Sukces!',
            message: '',
        },options)

        Livewire.dispatch('toaster-send-warning',opts);
        setTimeout(() => {
            Livewire.dispatch('toaster-hide-toast',{id});
        },5000);

    },
}

window.Modals = {
    show: function (name) {
        Livewire.dispatch('openModal',{name});
    },
    hide: function (modal) {
        Livewire.dispatch('closeModal',{name});
    }
}

window.DateUtils = {
    formatDate: function (date) {
        let d = new Date(date);
        return d.getFullYear()+'-'
            +((d.getMonth()+1) < 10 ? '0':'')+(d.getMonth()+1)+'-'
            +((d.getDate()) < 10 ? '0':'')+d.getDate();
    }
}

document.addEventListener('toast-success',(e) => {
    Toaster.sendSuccess(e.detail[0]);
})

document.addEventListener('toast-error',(e) => {
    Toaster.sendError(e.detail[0]);
});

document.addEventListener('toast-warning',(e) => {
    Toaster.sendWarning(e.detail[0]);
});
