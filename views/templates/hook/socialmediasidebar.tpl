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

{if $version != "1.6"}
    <script type="text/javascript" src="{$jq}"></script>
{/if}

<div class="pc-social-sidebar">
    {foreach from=$socialmedias item=social}
        <a href="{$social.url}"
           {if Configuration::get('PC_SOCIAL_NEW_WINDOW') == 1}target="_blank"{/if}>
            <div class="pc-social-icon iconid{$social.id}"
                 style="{if Configuration::get('PC_SOCIAL_MONOCOLORED') == 1}
                 background-color:{Configuration::get('PC_SOCIAL_MONOCOLORED_2')};
                 color:{Configuration::get('PC_SOCIAL_MONOCOLORED_1')};
                 {else}background-color:{$social.background};
                 color:{$social.icon_color};{/if}">
                <i class="{$social.social_class}"></i>
            </div>
        </a>
    {/foreach}
</div>
