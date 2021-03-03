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

<link rel="stylesheet" type="text/css" href="../modules/socialmediasidebar/views/css/back.css">
<link rel="stylesheet" type="text/css" href="../modules/socialmediasidebar/views/css/font-awesome.css">
<script src="../modules/socialmediasidebar/views/js/jqui.js"></script>
<script src="../modules/socialmediasidebar/views/js/jqColorPicker.min.js"></script>

{literal}
<script>

    $( document ).ready(function() {
        $(".color").colorPicker({renderCallback: function($elm, toggled) {
        $elm.val('#' + this.color.colors.HEX);
        var id = $elm.attr('id');

        if(id == "bgc") {
        if ($elm.text) {
        $(".icon-preview").css("background-color", $elm.text);
        }
    }

    if(id == "ic") {
        if ($elm.text) {
            $(".icon-preview").css("color", $elm.text);
        }
    }
    }});

    $(".cp-alpha").hide();

    $(".bgc").keyup(function() {
        $(".icon-preview").css("background-color", $(this).val());
    });

    $("#codename").keyup(function() {
        $("#prev-icon").removeClass();
        $("#prev-icon").addClass("icon-" + $(this).val());
    });

    $(".searchbox-socialmediasidebar").keyup(function() {
        if ($(this).val().length > 0) {
            $(this).closest(".tab-pane").find(".box-for-icons").hide();
            $(this).closest(".tab-pane").find('[class*=' + $(this).val() + ']').closest("div").show();
        } else {
            $(this).closest(".tab-pane").find(".box-for-icons").show();
        }
    });

    $(".icons-part .box-for-icons").click(function() {
        $("#no-icon-selected").hide();
        var boxForIcons = $(this).clone().removeAttr("style").addClass('draggable');
        $('.selection-box').append(boxForIcons);
        var arr = [];
        $(".selection-box").find("i").each(function(index) {
            arr.push($(this).attr("class"));
        });
        saveIcons(arr, "add");
    });

    var outside = false;

    $( ".selection-box" ).sortable({
        placeholder: "ui-sortable-placeholder",
        axis: "y",
        start: function(e, ui){
            ui.placeholder.height(ui.item.height());
        },
        over: function (event, ui) {
            outside = false;
        },
        out: function (event, ui) {
            outside = true;
        },
        beforeStop: function (event, ui) {
            var currentId = ui.item.attr("database-id");
            var arr = [];

            $(".selection-box .box-for-icons").each(function(index) {
                arr.push($(this).attr("database-id"));
            });

            if (outside) {
                ui.item.remove();
                removeIcon(currentId);

                const index = arr.indexOf(currentId);
                if (index > -1) {
                    arr.splice(index, 1);
                }
            } else {
                saveIcons(arr, "move");
            }
        }
    });

    function saveIcons(arr, action)
    {
        var text = '';
        for (var i = 0; i < arr.length; i++) {
            text += arr[i] + ',';
        }

        $.ajax({
            url: '{/literal}{$ajax_url}{literal}',
            type: 'post',
            data: {
                action     : action,
                ajax       : true,
                id_employee: {/literal}{$id_employee}{literal},
                module     : 'socialmediasidebar',
                fc         : 'module',
                controller : 'ajax',
                token: token,
                icons:  text.trim().replace(/(\r\n|\n|\r)/gm, "")
            },
            success: function (data) {
                if (action == "add") {
                    $(".selection-box .box-for-icons").last().attr("database-id", data);
                } else {

                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });
    }

    function removeIcon(id)
    {
        $.ajax({
            url: '{/literal}{$ajax_url}{literal}',
            type: 'post',
            data: {
                action:    "remove",
                ajax       : true,
                id_employee: {/literal}{$id_employee}{literal},
                module     : 'socialmediasidebar',
                fc         : 'module',
                controller : 'ajax',
                token: token,
                icons:  id
            },
            success: function (data) {
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });
    }
    });
</script>{/literal}

{if isset($errors)}
    <div class="pc-alert pc-errors">
        {$errors}
        <br />
        <small>Click to dismiss</small>
    </div>
{/if}

{if isset($success)}
    <div class="pc-alert pc-success">
        {$success}
        <br />
        <small>Click to dismiss</small>
    </div>
{/if}

{if isset($multistore)}
    <div class="pc-alert pc-informations">
        <small>You are running multistore. Please note that all settings will be saved
            to currently selected shop.</small>
    </div>
{/if}

<div role="tabpanel" class="prestacraft">
    <!-- Nav tabs -->
    <div class="col-lg-3 col-md-4 col-xs-12 prestacraft-left">
        <div class="menu-container">
            <div class="logo-container">PRESTACRAFT</div>
            <ul class="nav nav-tabs">
                <li role="presentation" {if !isset($smarty.get.page) || (isset($smarty.get.page) && $smarty.get.page == "enable")}class="active"{/if}>
                    <a href="{$module_url}">
                        <i class="icon-pencil"></i>&nbsp;&nbsp;&nbsp;{l s='Choose your icons' mod='socialmediasidebar'}</a>
                </li>
                <li role="presentation" {if isset($smarty.get.page) && $smarty.get.page == 'iconsettings'}class="active"{/if}>
                    <a href="{$module_url}&page=iconsettings">
                        <i class="icon-cog"></i>&nbsp;&nbsp;&nbsp;{l s='Icons settings' mod='socialmediasidebar'}</a>
                </li>
                <li role="presentation" {if isset($smarty.get.page) && $smarty.get.page == 'settings'}class="active"{/if}>
                    <a href="{$module_url}&page=settings">
                        <i class="icon-cogs"></i>&nbsp;&nbsp;&nbsp;{l s='General settings' mod='socialmediasidebar'}
                    </a>
                </li>
            </ul>
        </div>

        <div class="pc-info">
            <div class="pc-checker">
                {$VERSION_CHECKER}
            </div>
            <br/>
            <div>
                <a href="http://prestacraft.com/social-media-sidebar" target="_blank"
                   class="bottom-button btn-official-module">{l s='Official module website' mod='socialmediasidebar'}</a>

                <a href="https://github.com/PrestaCraft/socialmediasidebar/issues" target="_blank"
                   class="bottom-button btn-issue">{l s='Report an issue' mod='socialmediasidebar'}</a>
            </div>

            {include file='./extras/prestacraft.tpl'}
            <br />
            {include file='./extras/paypal.tpl'}
            <br /><br />
        </div>
    </div>

    <div class="col-md-8 col-lg-9 col-xs-12">
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane {if !isset($smarty.get.page) || (isset($smarty.get.page) && $smarty.get.page == "enable")}active{/if}" id="enable">
                <p style="text-align: center;">{l s='Click on buton below to select icons and their order.' mod='socialmediasidebar'}</p>
                <div class="text-center">
                    <a class="btn btn-primary btn-lg" href="#" data-toggle="modal"
                       data-target="#icons">{l s='Icon selection' mod='socialmediasidebar'}</a>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane {if isset($smarty.get.page) && $smarty.get.page == 'iconsettings'}active{/if}" id="iconsettings">
                <div class="row">
                    <div class="isettings-container">
                        {$ICON_SETTINGS}
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane {if isset($smarty.get.page) && $smarty.get.page == 'settings'}active{/if}" id="settings">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">{$CUSTOMIZE_STYLE}</div>
                    <div class="col-xs-12 col-sm-6">{$CUSTOMIZE_STYLE_MISC}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--- Inverse info modal --->
<div class="modal fade" id="modalInverse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{l s='About inverse hover colors' mod='socialmediasidebar'}</h4>
            </div>
            <div class="modal-body">{l s='Enable this feature if You want to make switch Your background color with icon color on button hover.' mod='socialmediasidebar'}
                <br><br>
                {l s='Example of enabling inverse hover colors' mod='socialmediasidebar'}
                <br><br>
                <table>
                    <tr>
                        <td style="border:1px solid black;"><strong>&nbsp;&nbsp;{l s='Normal mode' mod='socialmediasidebar'}&nbsp;&nbsp;</strong></td>
                        <td style="border:1px solid black;"><strong>&nbsp;&nbsp;{l s='Normal mode hover' mod='socialmediasidebar'}&nbsp;&nbsp;</strong></td>
                        <td style="border:1px solid black;"><strong>&nbsp;&nbsp;{l s='Monocolored mode' mod='socialmediasidebar'}&nbsp;&nbsp;</strong></td>
                        <td style="border:1px solid black;"><strong>&nbsp; {l s='Monocolored mode hover' mod='socialmediasidebar'}&nbsp;&nbsp;</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid black;">
                            <img src="{$module_dir}/views/img/normal.png"></td>
                        <td style="border:1px solid black;">
                            <img src="{$module_dir}/views/img/normal_hover.png"></td>
                        <td style="border:1px solid black;">
                            <img src="{$module_dir}/views/img/monocolored.png"></td>
                        <td style="border:1px solid black;">
                            <img src="{$module_dir}/views/img/monocolored_hover.png">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='socialmediasidebar'}</button>
            </div>
        </div>
    </div>
</div>

<!--- Monocolored info modal --->
<div class="modal fade" id="modalMonocolored" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{l s='About monocolored mode' mod='socialmediasidebar'}</h4>
            </div>
            <div class="modal-body">
                {l s='Monocolored mode is just a combination of 2 colors (icon and background) which will be
                used for all Your social media buttons in sidebar.' mod='socialmediasidebar'}
                <br><br>
                {l s='For example, the following settings...' mod='socialmediasidebar'}
                <br><br>
                <img src="{$module_dir}/views/img/1.png" style="border:1px solid black;">
                <br><br>
                {l s='...will generate a sidebar which looks like this' mod='socialmediasidebar'}
                <br><br>
                <img src="{$module_dir}/views/img/2.png" style="border:1px solid black;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='socialmediasidebar'}</button>
            </div>
        </div>
    </div>
</div>

<!--- Icon selection modal --->
<div class="modal fade" id="icons" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog icons-modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: white;font-size: 30px;opacity:0.9;">
                    <span aria-hidden="true" style="color: white;opacity:0.9;">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{l s='Icon selection' mod='socialmediasidebar'}</h4>
            </div>
            <div class="modal-body">
                <div class="preview-part">
                    <div style="text-align: center;">
                        <div class="selection-text">
                            <p>{l s='Your selected icons.' mod='socialmediasidebar'} </p>
                            <p>{l s='Drag and drop to change order.' mod='socialmediasidebar'}</p>
                            <p>{l s='Move icon outside blue frames to remove it.' mod='socialmediasidebar'}</p>
                            <p>{l s='Save is done automatically.' mod='socialmediasidebar'}</p>
                        </div>
                        <span class="selection-box-frame text-center">{l s='Your sidebar preview' mod='socialmediasidebar'}
                        </span>
                    </div>
                    <div class="selection-box">
                        <p id="no-icon-selected" style="margin: 0;{if $db_icons|@count > 0}display:none;{/if}">
                            {l s='Your icon list is empty. Select icon(s) from the box located below.' mod='socialmediasidebar'}
                        </p>
                        {foreach from=$db_icons item=it}
                            <div class="box-for-icons draggable" database-id="{$it.id_social_media_sidebar}">
                                <i class="{$it.social_class}"></i>
                            </div>
                        {/foreach}
                    </div>
                </div>

                <div class="icons-part">
                    <div class="selection-text">
                        <p>Here are all available icons. Click one or more to add it to your selection box.</p>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#home">{l s='All icons' mod='socialmediasidebar'}
                                ({l s='brands' mod='socialmediasidebar'} + {l s='miscellaneous' mod='socialmediasidebar'})</a>
                        </li>
                        <li><a data-toggle="tab" href="#menu1">{l s='Brands' mod='socialmediasidebar'}</a></li>
                        <li><a data-toggle="tab" href="#menu2">{l s='Miscellaneous' mod='socialmediasidebar'}</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <input type="text" class="searchbox-socialmediasidebar" placeholder="Type FontAwesome icon name to search...">
                            {foreach from=$all_icons item=icon}
                                <div class="box-for-icons">
                                    {if $icon|strpos:'[fab]'===0}
                                        <i class="fab {$icon|replace:"[fab]":""}"></i>
                                    {else}
                                        <i class="fa {$icon}"></i>
                                    {/if}
                                </div>
                            {/foreach}
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <input type="text" class="searchbox-socialmediasidebar" placeholder="Type FontAwesome icon name to search...">
                            {foreach from=$icons_brand item=icon}
                                <div class="box-for-icons">
                                    <i class="fab {$icon}"></i>
                                </div>
                            {/foreach}
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <input type="text" class="searchbox-socialmediasidebar" placeholder="Type FontAwesome icon name to search...">
                            {foreach from=$icons item=icon}
                                <div class="box-for-icons">
                                    <i class="fa {$icon}"></i>
                                </div>
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>