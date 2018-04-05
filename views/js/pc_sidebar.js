/*
 * This software is provided "as is" without warranty of any kind.
 *
 * Made by PrestaCraft
 *
 * Visit my website (http://prestacraft.com) for future updates, new articles and other awesome modules.
 *
 * @author     PrestaCraft
 * @copyright  PrestaCraft
 */

$(function() {

    $( ".pc-social-icon" ).mouseenter(function() {
        if($(this).closest(".pc-social-sidebar").position().top < 200) {
            $(this).animate({ width: "62"}, "fast");
        }
    });

    $( ".pc-social-icon" ).mouseleave(function() {
        if($(this).closest(".pc-social-sidebar").position().top < 200) {
            $(this).animate({ width: "50"}, "fast");
        }
    });
});