/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/template_bootstrap.scss';
import './styles/site.scss';
import './toast';

require('bootstrap');

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop >= 40 || document.documentElement.scrollTop >= 40) {
    document.getElementById("navbar-top").style.padding = "0px 0px";
    document.getElementById("navbar-top").classList.remove("mt-1");
    document.getElementById("navbar-top").style.borderBottomLeftRadius = "10px";
    document.getElementById("navbar-top").style.borderBottomRightRadius = "10px";
    document.getElementById("logo").style.scale = 0.9;
  } else {
    document.getElementById("navbar-top").style.padding = "10px 10px";
    document.getElementById("navbar-top").classList.add("mt-1");
    document.getElementById("navbar-top").style.borderBottomLeftRadius = "0px";
    document.getElementById("navbar-top").style.borderBottomRightRadius = "0px";
    document.getElementById("logo").style.scale = 1;
  }
}

