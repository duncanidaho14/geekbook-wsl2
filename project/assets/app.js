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
import projetAbout from './images/projet.jpg';
import './images/css-3.svg';
import './images/dev-diplome.jpg';
import './images/docker-svgrepo-com.svg';
import './images/ftl express.png';
import './images/uml-logo.png';
import './images/wordpress-logo.png';
import './images/Git_icon.svg.png';
import './images/diplome.svg';
import second from './images/iad-site.png';
import symfonyLogo from './images/symfony.svg';
import chartjsSymfony from './images/symfony-chartjs.png';
import javascriptLogo from './images/logo-javascript.svg';
import kalliste from './images/saskalliste.png';
import cartGKBook from './images/panier geekbook screenshot.png';


initTE({ Ripple, Animate });



Turbo.setProgressBarDelay(1);


document.addEventListener('DOMContentLoaded', async () => {
    let searchParams = new URLSearchParams(window.location.search);
    if (searchParams.has('session_id')) {
        const session_id = searchParams.get('session_id');
        document.getElementById('session-id').setAttribute('value', session_id);
    }

    // const url = JSON.parse(document.getElementById("mercure-url").textContent);
    // const eventSource = new EventSource(url)
});




