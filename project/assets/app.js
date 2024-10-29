/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
// start the Stimulus application
import './bootstrap';
// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import {
    Animate,
    Ripple,
    initTE,
} from "tw-elements";
import 'flowbite';
import './images/hero.png';
import logoPath from './images/logo.svg';

initTE({ Ripple, Animate });



Turbo.setProgressBarDelay(1);


document.addEventListener('DOMContentLoaded', async () => {
    let searchParams = new URLSearchParams(window.location.search);
    if (searchParams.has('session_id')) {
        const session_id = searchParams.get('session_id');
        document.getElementById('session-id').setAttribute('value', session_id);
    }

    const url = JSON.parse(document.getElementById("mercure-url").textContent);
    const eventSource = new EventSource(url)
});




