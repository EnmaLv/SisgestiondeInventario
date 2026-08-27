import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse'

Alpine.plugin(Collapse)
window.Alpine = Alpine;

Alpine.start();

import './realtime';
import Swal from 'sweetalert2';
window.Swal = Swal;
import flatpickr from "flatpickr";
window.flatpickr = flatpickr;

// Configuración global de SweetAlert2
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

const modalInstances = new WeakMap();

window.TailwindModal = class {
    constructor(element) {
        this.element = element;
        modalInstances.set(element, this);
    }

    show() {
        this.element.classList.remove('hidden');
        this.element.classList.add('flex', 'show');
        this.element.setAttribute('aria-hidden', 'false');
    }

    hide() {
        this.element.classList.add('hidden');
        this.element.classList.remove('flex', 'show');
        this.element.setAttribute('aria-hidden', 'true');
    }

    static getInstance(element) {
        return modalInstances.get(element);
    }
};

document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-modal-toggle]');
    if (toggle) {
        const modal = document.querySelector(toggle.dataset.modalTarget);
        if (modal) {
            (TailwindModal.getInstance(modal) ?? new TailwindModal(modal)).show();
        }
    }

    const dismiss = event.target.closest('[data-modal-dismiss]');
    if (dismiss) {
        const modal = dismiss.closest('[role="dialog"], .fixed');
        const instance = modal && TailwindModal.getInstance(modal);
        if (instance) instance.hide();
    }
});