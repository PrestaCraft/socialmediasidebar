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
        if (typeof pc_total_wider !== 'undefined' && typeof pc_icon_box_size !== 'undefined') {
            if($(this).closest(".pc-social-sidebar").position().top < 200) {
                if (pc_sidebar_position == 'left') {
                    $(this).animate({ width: pc_total_wider}, "fast");
                } else {
                    $(this).css('width', pc_total_wider);
                    $(this).animate({ marginLeft: '-' + pc_wider + 'px'}, "fast");
                }
            }
        }
    });

    $( ".pc-social-icon" ).mouseleave(function() {
        if (typeof pc_total_wider !== 'undefined' && typeof pc_icon_box_size !== 'undefined') {
            if ($(this).closest(".pc-social-sidebar").position().top < 200) {
                if (pc_sidebar_position == 'left') {
                    $(this).animate({width: pc_icon_box_size}, "fast");
                } else {
                    $(this).animate({marginLeft: '0'}, "fast");
                }
            }
        }
    });
});