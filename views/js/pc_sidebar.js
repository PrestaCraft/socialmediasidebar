/*
 * This software is provided "as is" without warranty of any kind.
 *
 * Made by PrestaCraft
 *
 * Visit my website (http://prestacraft.com) for future updates, new articles and other awesome modules.
 *
 * @author     PrestaCraft
 * @copyright  2015-2017 PrestaCraft
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

$(function() {
    $( ".pc-social-icon" ).mouseenter(function() {
        $(this).animate({ width: "62"}, "fast");
    });

    $( ".pc-social-icon" ).mouseleave(function() {
        $(this).animate({ width: "50"}, "fast");
    });
});