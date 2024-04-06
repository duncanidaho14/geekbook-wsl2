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

initTE({ Ripple, Animate });



Turbo.setProgressBarDelay(1);

function onClick(e) {
    e.preventDefault();
    grecaptcha.enterprise.ready(async () => {
      const token = await grecaptcha.enterprise.execute('6Lf4KLIpAAAAANk9vPixJ8IfOJcN40WN2fqW01_Z', {action: 'LOGIN'});
    });
}