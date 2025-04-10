import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    connect() {
        this.element.textContent = 'Hello Stimulus! Edit me in assets/controllers/hello_controller.js';
        document.addEventListener('keydown', this.handleEscape.bind(this));
        useDispatch(this);
    }
    
    disconnect() {
        document.removeEventListener('keydown', this.handleEscape.bind(this));
    }

    // handleEscape(event) {
    //     if (event.key === 'Escape') {
    //         return this.close();
    //     }
    //     return;
    // }

    // handleSearch(event) {
    //     if (event.key === 'ctrl+ g') {
    //         return this.open();
    //     }
    //     return;
    // }

    static targets = ['modal'];

    openModal() {
        this.modalTarget.classList.remove('hidden');
    }

    closeModal() {
        this.modalTarget.classList.add('hidden');
    }
}
