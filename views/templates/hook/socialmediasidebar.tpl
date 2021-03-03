{*
 *
 * This software is provided "as is" without warranty of any kind.
 *
 * Made by PrestaCraft
 *
 * Visit my website (http://prestacraft.com) for future updates, new articles and other awesome modules.
 *
 * @author     PrestaCraft
 * @copyright  PrestaCraft
 *
*}

<style>
.pc-social-icon {
    height: {$pc_icon_box_size}px;
    font-size: {$pc_icon_size}px;
    text-align: center;
    padding-top: {$calc_padding}px;
}

@media(min-width:{$pc_min}px) {
    .pc-social-sidebar {
        position: fixed;
        {if $pc_sidebar_position == 'left'}left:0;{else}right:0;{/if}
        top: {$pc_top_padding}%;
        z-index: 99999;
        width: {$pc_icon_box_size}px;
    }
}

@media(max-width:{$pc_mobile_breakpoint}px) {
    {foreach from=$pc_icons item=icon}
    {if $icon.hide_mobile == 1}
    .iconid{$icon.id_social_media_sidebar} { display: none !important; }
    {/if}
    {/foreach}
}

{if $hide_mobile}
    @media(max-width:{$pc_mobile_breakpoint}px) { .pc-social-sidebar { display: none; } }
{else}
    {if $pc_mobile_bottom}
    @media(max-width:{$pc_mobile_breakpoint}px) {
        .pc-social-icon {
            height: {$pc_icon_box_size}px;
            font-size: {$pc_icon_size}px;
            text-align: center;
            padding-top: 12px;
            display: inline-block;
            width: {$pc_icon_box_size}px !important;
        }

        .pc-social-sidebar {
            position: fixed;
            {if $pc_sidebar_position == 'left'}left:0;{else}right:0;{/if}
            text-align: center;
            z-index: 99999;
            width: 100%;
            bottom: 0;
        }
    }
    {else}
    @media(max-width:{$pc_mobile_breakpoint}px) {
        .pc-social-sidebar {
            position: fixed;
            {if $pc_sidebar_position == 'left'}left:0;{else}right:0;{/if}
            top:{$pc_top_padding}%;
            z-index: 99999;
            width: {$pc_icon_box_size}px;
        }
    {/if}
{/if}

{if $pc_social_inverse}
    {if $pc_social_monocolored}
        .pc-social-icon:hover {
            background-color:{$pc_social_monocolored_1} !important;
            color:{$pc_social_monocolored_2} !important;
        }
    {else}
        {foreach from=$pc_icons item=icon}
        .iconid{$icon.id_social_media_sidebar}:hover {
            background-color:{$icon.icon_color} !important;
            color:{$icon.background} !important;
        }
        {/foreach}
    {/if}
{/if}
</style>

<div class="pc-social-sidebar">
    {foreach from=$pc_icons item=icon}
        <a href="{$icon.url}"
           {if $pc_social_new_window}target="_blank"{/if}>
            <div class="pc-social-icon iconid{$icon.id_social_media_sidebar}"
                 style="{if $pc_social_monocolored}
                 background-color:{$pc_social_monocolored_2};
                 color:{$pc_social_monocolored_1};
                 {else}background-color:{$icon.background};
                 color:{$icon.icon_color};{/if}">
                <i class="{$icon.social_class}"></i>
            </div>
        </a>
    {/foreach}
</div>
