/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
//import './bootstrap';
const $ =  require('jquery');
const jquery = require('jquery');
const jQuery =  require('jquery');
require('jquery');
require('select2');
require('bootstrap');

$(document).ready(function() {
    $('#add_game_to_list_OwnGames').select2();
    
    $('#add_game_to_list_wish').select2();
});

$(document).change(function() {
    console.log($('#add_game_to_list_OwnGames').val());
    /* $wish = $('#add_game_to_list_wish').val(); */
});
