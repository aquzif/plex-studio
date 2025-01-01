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



document.addEventListener('refreshShows',(e) => {
    window.refreshTooltips();
});

document.addEventListener('refreshTooltips',(e) => {
    setTimeout(() => window.refreshTooltips(),500);
});

$(document).ready(function() {

    // Ensure Livewire updates re-instantiate tooltips
    // if (typeof window.Livewire !== 'undefined') {
    //     window.Livewire.hook('message.processed', (message, component) => {
    //         // $('[data-toggle="tooltip"]').tooltip('dispose').tooltip();
    //         window.refreshTooltips();
    //     });
    // // }



});

document.addEventListener('alpine:init', () => {
    Alpine.store('loader', {
        isLoading: false,

        toggle() {
            this.on = ! this.on
        },
        show() {
            this.isLoading = true;
        },
        hide() {
            this.isLoading = false;
        }
    });
})

window.asyncEventWithLoader = function(event, params = {}) {
    Alpine.store('loader').show();
    Livewire.dispatch(event,params);
}

window.Navigator = {
    redirectToUp: () => {
        let url = window.location.pathname;
        if (url.endsWith('/')) {
            url = url.slice(0, -1);
        }
        if (url.startsWith('/')) {
            url = url.slice(1);
        }
        let urlParts = url.split('/');

        if(urlParts.length === 2
            && (urlParts[0] === 'series' || urlParts[0] === 'movie')
            && !isNaN(urlParts[1])
        ){
            window.location.href = '/';
        }

        if(urlParts.length === 4
            && urlParts[0] === 'series'
            && urlParts[2] === 'episodes'
            && !isNaN(urlParts[1])
            && !isNaN(urlParts[3])
        ){
            urlParts.pop();
            urlParts.pop();
            window.location.href = '/'+urlParts.join('/');
        }



    }
}

window.refreshTooltips= function () {
    return;
    $('.tooltip').each(function () {
        console.log(this);

        //remove tooltipster
        try{
            $(this).tooltipster('destroy');
        }catch (e) {}

        $(this).tooltipster({
            delay: 0,
            side: 'bottom',
            content: $(this).data('tooltip-content'),
            contentCloning: true,
            trigger: 'custom',
            triggerOpen: {
                mouseenter: true
            },
            triggerClose: {
                click: true,
                mouseleave: false
            }
        });
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
