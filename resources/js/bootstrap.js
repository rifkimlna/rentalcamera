import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import $ from 'jquery';
window.$ = window.jQuery = $;

import toastr from 'toastr';
window.toastr = toastr;
toastr.options.positionClass = 'toast-top-right';
toastr.options.timeOut = 3000;

import Swal from 'sweetalert2';
window.Swal = Swal;

import './echo';

document.addEventListener('DOMContentLoaded', function () {
    const userId = document.querySelector('meta[name="user-id"]')?.content;
    if (!window.Echo || !userId) return;

    window.Echo.private('notifications.' + userId)
        .listen('notification.received', function (e) {
            const type = e.type || 'info';
            const title = e.title || 'Notifikasi';
            const message = e.message || '';

            const iconMap = {
                transaction: 'info',
                payment: 'success',
                shipping: 'info',
                system: 'warning',
                promotion: 'success',
            };

            toastr[iconMap[type] || 'info'](message, title);

            Swal.fire({
                icon: iconMap[type] || 'info',
                title: title,
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
            });
        });
});
