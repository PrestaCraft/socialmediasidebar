{*
 * This software is provided "as is" without warranty of any kind.
 *
 * Made by PrestaCraft
 *
 * Visit my website (http://prestacraft.com) for future updates, new articles and other awesome modules.
 *
 * @author     PrestaCraft
 * @copyright  PrestaCraft
*}

<style>
{foreach from=$defined_icons item=icon}
    .isettings-container .iconid{$icon.id_social_media_sidebar} {
    {if $icon.background|count_characters > 0}background:{$icon.background};{/if}
    {if $icon.icon_color|count_characters > 0}color:{$icon.icon_color};{/if}
    }
.isettings-container .box-for-icons:hover i {
    color: {$icon.icon_color};
}

{/foreach}

.isettings-container .box-for-icons:hover {
    background: #31A1C5;
    border: 1px solid #31A1C5;
}

</style>
<p>
    {l s='Here you can set values for icons.' mod='socialmediasidebar'}
</p>
<form action="{$module_url}&page=iconsettings" method="POST">
    <input type="hidden" name="page" value="iconsettings">
{foreach from=$defined_icons item=icon}
<div class="panel">
    <table class="itable">
        <tr class="head">
            <td>Icon</td>
            <td>Enabled?</td>
            <td>Hide on mobile?</td>
            <td>Background color</td>
            <td>Icon color</td>
            <td>URL</td>
        </tr>
        <tr>
            <td>
                <div class="box-for-icons iconid{$icon.id_social_media_sidebar}">
                    <i class="{$icon.social_class}"></i></div>
            </td>
            <td class="row-checkbox">
                <input type="checkbox" name="icon[{$icon.id_social_media_sidebar}][enabled]"
                       value="1" {if $icon.enabled == 1}checked{/if} />
            </td>
            <td class="row-checkbox">
                    <input type="checkbox" name="icon[{$icon.id_social_media_sidebar}][hide_mobile]"
                           value="1" {if $icon.hide_mobile == 1}checked{/if} />
            </td>
            <td class="row-color">
                <input type="color"
                       data-hex="true"
                        class="color mColorPickerInput"
                       name="icon[{$icon.id_social_media_sidebar}][background]"
                       value="{$icon.background}" />
            </td>
            <td class="row-color">
                <input type="color"
                       data-hex="true"
                       class="color mColorPickerInput"
                       name="icon[{$icon.id_social_media_sidebar}][icon_color]"
                       value="{$icon.icon_color}" />
            </td>
            <td class="row-input"><input type="text" class="form-control" name="icon[{$icon.id_social_media_sidebar}][url]" value="{$icon.url}"></td>
        </tr>
    </table>
</div>
{/foreach}
<div class="text-center">
<button type="submit" name="saveIconSettings" class="btn btn-primary btn-lg" style="margin-top: 20px;">{l s='Save' mod='socialmediasidebar'}</button>
</div>
</form>