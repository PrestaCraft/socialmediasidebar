<?php
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

if (!defined('_PS_VERSION_')) {
    exit;
}

class SocialmediaSidebar extends Module
{

    public function __construct()
    {
        $this->name = 'socialmediasidebar';
        $this->tab = 'front_office_features';
        $this->version = '1.3.0';
        $this->author = 'PrestaCraft';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Social media sidebar');
        $this->description = $this->l('Display fixed sidebar with social media buttons.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }


    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        // social_media_sidebar table ; hooks ; configuration
        if (!parent::install() ||
            !$this->installDb() ||
            !$this->registerHook('footer') ||
            !$this->registerHook('header') ||
            !Configuration::updateValue('PC_SOCIAL_MONOCOLORED', '0') ||
            !Configuration::updateValue('PC_SOCIAL_MONOCOLORED_1', '#FF2310') ||
            !Configuration::updateValue('PC_SOCIAL_MONOCOLORED_2', '#F1F1F1') ||
            !Configuration::updateValue('PC_SOCIAL_INVERSE', '0') ||
            !Configuration::updateValue('PC_SOCIAL_NEW_WINDOW', '1') ||
            !Configuration::updateValue('PC_HIDE_MOBILE', '0') ||
            !Configuration::updateValue('PC_MOBILE_BREAKPOINT', '768') ||
            !Configuration::updateValue('PC_MOBILE_BOTTOM', '1')
        ) {
            return false;
        }

        return true;
    }


    public function installDb()
    {
        if (!Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'social_media_sidebar` (
            `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
            `social_name` VARCHAR(30),
            `social_class` VARCHAR(30),
            `field_name` VARCHAR(30),
            `background` VARCHAR(15),
            `bg_default` VARCHAR(15),
            `icon_color` VARCHAR(15),
            `url` VARCHAR(300),
            `enabled` INT,
            `nr` INT,
            PRIMARY KEY (`id`)
        ) AUTO_INCREMENT = 1 ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8')) {
            return false;
        }

        $count = Db::getInstance()->getValue('SELECT count(*) FROM `' . _DB_PREFIX_ . 'social_media_sidebar`');
        $result = Db::getInstance()->executeS('SHOW COLUMNS FROM `' . _DB_PREFIX_ . 'social_media_sidebar` LIKE "hide_mobile"');
        if (count($result) == 0) {
            Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'social_media_sidebar` 
            ADD COLUMN hide_mobile TINYINT default 0');
        }

        // Dummy check if table has already some rows
        if ($count < 15) {
            if (!Db::getInstance()->execute('TRUNCATE TABLE `' . _DB_PREFIX_ . 'social_media_sidebar`')) {
                return false;
            }

            // Fixtures
            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                    'social_name' => 'Facebook',
                    'social_class' => 'icon-facebook',
                    'field_name' => 'facebook',
                    'background' => '#3765A2',
                    'bg_default' => '#3765A2',
                    'icon_color' => '#FFFFFF',
                    'url' => '',
                    'enabled' => '1',
                    'nr' => '1'
                )
            );

            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                    'social_name' => 'Google+',
                    'social_class' => 'icon-google-plus',
                    'field_name' => 'google',
                    'background' => '#E14D29',
                    'bg_default' => '#E14D29',
                    'icon_color' => '#FFFFFF',
                    'url' => '',
                    'enabled' => '1',
                    'nr' => '2' )
            );

            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                    'social_name' => 'Twitter',
                    'social_class' => 'icon-twitter',
                    'field_name' => 'twitter',
                    'background' => '#01AAEB',
                    'bg_default' => '#01AAEB',
                    'icon_color' => '#FFFFFF',
                    'url' => '',
                    'enabled' => '1',
                    'nr' => '3'
                )
            );

            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                    'social_name' => 'YouTube',
                    'social_class' => 'icon-youtube',
                    'field_name' => 'youtube',
                    'background' => '#E51D1D',
                    'bg_default' => '#E51D1D',
                    'icon_color' => '#FFFFFF',
                    'url' => '',
                    'enabled' => '1',
                    'nr' => '4'
                )
            );

            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                'social_name' => 'Flickr',
                'social_class' => 'icon-flickr',
                'field_name' => 'flickr',
                'background' => '#EBEBEB',
                'bg_default' => '#EBEBEB',
                'icon_color' => '#000000',
                'url' => '',
                'enabled' => '0',
                'nr' => '5'
                )
            );

            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                'social_name' => 'Pinterest',
                'social_class' => 'icon-pinterest',
                'field_name' => 'pinterest',
                'background' => '#CA2025',
                'bg_default' => '#CA2025',
                'icon_color' => '#FFFFFF',
                'url' => '',
                'enabled' => '0',
                'nr' => '6'
                )
            );

            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                'social_name' => 'Tumblr',
                'social_class' => 'icon-tumblr',
                'field_name' => 'tumblr',
                'background' => '#4c7390',
                'bg_default' => '#4c7390',
                'icon_color' => '#FFFFFF',
                'url' => '',
                'enabled' => '0',
                'nr' => '7'
                )
            );

            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                'social_name' => 'Xing',
                'social_class' => 'icon-xing',
                'field_name' => 'xing',
                'background' => '#EFEEEE',
                'bg_default' => '#EFEEEE',
                'icon_color' => '#000000',
                'url' => '',
                'enabled' => '0',
                'nr' => '8'
                )
            );

            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                    'social_name' => 'Instagram',
                    'social_class' => 'icon-instagram',
                    'field_name' => 'instagram',
                    'background' => '#EB178F',
                    'bg_default' => '#EB178F',
                    'icon_color' => '#FFFFFF',
                    'url' => '',
                    'enabled' => '0',
                    'nr' => '9'
                )
            );

            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                    'social_name' => 'VK',
                    'social_class' => 'icon-vk',
                    'field_name' => 'xing',
                    'background' => '#5181B8',
                    'bg_default' => '#5181B8',
                    'icon_color' => '#FFFFFF',
                    'url' => '',
                    'enabled' => '0',
                    'nr' => '10'
                )
            );

            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                    'social_name' => 'WordPress',
                    'social_class' => 'icon-wordpress',
                    'field_name' => 'wordpress',
                    'background' => '#FFFFFF',
                    'bg_default' => '#FFFFFF',
                    'icon_color' => '#000000',
                    'url' => '',
                    'enabled' => '0',
                    'nr' => '11'
                )
            );

            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                    'social_name' => 'Spotify',
                    'social_class' => 'icon-spotify',
                    'field_name' => 'spotify',
                    'background' => '#000000',
                    'bg_default' => '#000000',
                    'icon_color' => '#23CF5F',
                    'url' => '',
                    'enabled' => '0',
                    'nr' => '12'
                )
            );

            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                    'social_name' => 'Soundcloud',
                    'social_class' => 'icon-soundcloud',
                    'field_name' => 'soundcloud',
                    'background' => '#FF5510',
                    'bg_default' => '#FF5510',
                    'icon_color' => '#FFFFFF',
                    'url' => '',
                    'enabled' => '0',
                    'nr' => '13'
                )
            );

            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                    'social_name' => 'Whatsapp',
                    'social_class' => 'icon-whatsapp',
                    'field_name' => 'whatsapp',
                    'background' => '#2BB240',
                    'bg_default' => '#2BB240',
                    'icon_color' => '#FFFFFF',
                    'url' => '',
                    'enabled' => '0',
                    'nr' => '14'
                )
            );
            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                    'social_name' => 'LinkedIn',
                    'social_class' => 'icon-linkedin',
                    'field_name' => 'linkedin',
                    'background' => '#0274B3',
                    'bg_default' => '#0274B3',
                    'icon_color' => '#FFFFFF',
                    'url' => '',
                    'enabled' => '0',
                    'nr' => '15'
                )
            );
        }
        return true;
    }


    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        return true;
    }


    public function getContent()
    {
        $script = '
        <style>
            .leftfix {
                margin-left:10px;
            }
        </style>

        <script src="../modules/socialmediasidebar/views/js/jqColorPicker.min.js"></script>

        <script type="text/javascript">
        $( document ).ready(function() {
            $(".color").colorPicker({renderCallback: function($elm, toggled) {
        $elm.val(\'#\' + this.color.colors.HEX);
        var id = $elm.attr(\'id\');

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
        });
        </script>';

        return $script.$this->postProcess().$this->displayTabs();
    }


    public function postProcess()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sql = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'social_media_sidebar`');
            $enable = 'enable__';
            $disable = 'disable__';
            $nr = 'nr__';
            $url = 'url__';
            $color = 'color__';
            $iconcolor = 'iconcolor__';
            $hidemobile = 'hide_mobile__';

            foreach ($sql as $field) {
                if (isset($_POST[$enable.$field['id']])) {
                    Db::getInstance()->update(
                        'social_media_sidebar',
                        array(
                            'enabled' => '1'),
                        'id='.$field['id'].''
                    );
                }

                if (isset($_POST[$hidemobile.$field['id']])) {
                    Db::getInstance()->update(
                        'social_media_sidebar',
                        array(
                            'hide_mobile' => '1'),
                        'id='.$field['id'].''
                    );
                }

                if (isset($_POST[$disable.$field['id']])) {
                    Db::getInstance()->update(
                        'social_media_sidebar',
                        array(
                            'enabled' => '0'),
                        'id='.$field['id'].''
                    );
                }

                if (isset($_POST[$nr.$field['id']])) {
                    Db::getInstance()->update(
                        'social_media_sidebar',
                        array(
                            'url' => Tools::getValue($url.$field['id']),
                            'background' => Tools::getValue($color.$field['id']),
                            'icon_color' => Tools::getValue($iconcolor.$field['id']),
                            'nr' => Tools::getValue($nr.$field['id'])),
                        'id='.$field['id'].''
                    );
                }
            }
        }

        if (Tools::isSubmit('saveStyle')) {
            Configuration::updateValue('PC_SOCIAL_MONOCOLORED', Tools::getValue('PC_SOCIAL_MONOCOLORED'));
            Configuration::updateValue('PC_SOCIAL_MONOCOLORED_1', Tools::getValue('PC_SOCIAL_MONOCOLORED_1'));
            Configuration::updateValue('PC_SOCIAL_MONOCOLORED_2', Tools::getValue('PC_SOCIAL_MONOCOLORED_2'));
            Configuration::updateValue('PC_SOCIAL_BORDER_PX', Tools::getValue('PC_SOCIAL_BORDER_PX'));
        }

        if (Tools::isSubmit('saveStyleMisc')) {
            Configuration::updateValue('PC_SOCIAL_NEW_WINDOW', Tools::getValue('PC_SOCIAL_NEW_WINDOW'));
            Configuration::updateValue('PC_SOCIAL_INVERSE', Tools::getValue('PC_SOCIAL_INVERSE'));
            Configuration::updateValue('PC_HIDE_MOBILE', Tools::getValue('PC_HIDE_MOBILE'));
            Configuration::updateValue('PC_MOBILE_BREAKPOINT', Tools::getValue('PC_MOBILE_BREAKPOINT'));
            Configuration::updateValue('PC_MOBILE_BOTTOM', Tools::getValue('PC_MOBILE_BOTTOM'));
        }

        if (Tools::getValue('addicon')) {
            $max = Db::getInstance()->getValue(
                'SELECT max(nr) 
                FROM '._DB_PREFIX_.'social_media_sidebar'
            );

            Db::getInstance()->insert(
                'social_media_sidebar',
                array(
                    'social_name' => Tools::getValue("icon_name"),
                    'social_class' => 'icon-'.Tools::getValue("icon_codename").'',
                    'field_name' => Tools::getValue("codename"),
                    'background' => Tools::getValue("icon_bgc"),
                    'bg_default' => Tools::getValue("icon_bgc"),
                    'icon_color' => Tools::getValue("icon_ic"),
                    'url' => '',
                    'enabled' => '0',
                    'nr' => (int)$max+1
                )
            );
        }
    }


    public function displayTabs()
    {
        $head = ' <div role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-tabs-sticky" role="tablist">
                <li role="presentation" class="active"><a href="#settings" aria-controls="home" role="tab" 
                data-toggle="tab"><i class="icon-power-off"></i>&nbsp;&nbsp;&nbsp;'.$this->l('Enable/Disable').'</a></li>
                <li role="presentation"><a href="#colors" aria-controls="colors" role="tab" data-toggle="tab">
                <i class="icon-cogs"></i>&nbsp;&nbsp;&nbsp;'.$this->l('Settings').'</a></li>
                <li role="presentation"><a href="#add" aria-controls="add" role="tab" data-toggle="tab">
                <i class="icon-plus"></i>&nbsp;&nbsp;&nbsp;'.$this->l('Add your icon').'</a></li>
                <li role="presentation"><a href="#version" aria-controls="version" role="tab" data-toggle="tab">
                <i class="icon-refresh"></i>&nbsp;&nbsp;&nbsp;'.$this->l('Version checker').'</a></li>
                <li role="presentation"><a href="#about" aria-controls="profile" role="tab" data-toggle="tab">
                <i class="icon-info-circle"></i>&nbsp;&nbsp;&nbsp;'.$this->l('About').'</a></li>
            </ul>

        <!-- Tab panes -->
        <div class="tab-content">
        <div role="tabpanel" class="tab-pane panel active" id="settings">
        <h2 style="margin-top:20px;margin-left:30px;">'.$this->l('Enabled icons').'</h2>
        <p style="margin-left:30px;">'.$this->l('Visible in Your shop').'</p>
        <form method="POST" action="index.php?controller=AdminModules&token='.Tools::getAdminTokenLite('AdminModules').
            '&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'">
        <table style="margin-top:20px;margin-left:30px;">
       <tr style="border: 1px solid #333;">
            <td colspan="2" style="text-align:left;">
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$this->l('Social media name').'</strong>
            </td>
            <td style="text-align:center;">
                <strong>'.$this->l('URL & colors').'</strong>
            </td>
            <td style="text-align:right;">
                <strong>'.$this->l('Display order').'</strong>
            </td>
            <td>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$this->l('Hide on mobile?').'</strong>&nbsp;
            </td>
            <td style="text-align:right;">
                <strong>'.$this->l('Disable?').'</strong>&nbsp;
            </td>
        </tr>';

        $table = '';

        $sql = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'social_media_sidebar` 
        WHERE enabled=1 ORDER BY nr ASC');

        foreach ($sql as $field) {
            $table .= '<tr style="border: 1px solid #333;">
            <td style="text-align:right;">
                <i class="'.$field["social_class"].'" style="font-size:40px;margin-left:20px;
                margin-top:5px;margin-right:10px;"></i>
            </td>

            <td>
                <h2>'.$field["social_name"].'</h2>
            </td>
            <td>
               <span style="margin-left:20px;margin-top:10px;">URL:</span> <input type="text" 
               style="margin-left:20px;width:350px;" name="url__'.$field["id"].'" 
               value="'.$field["url"].'" placeholder="URL"><br>
               <span style="margin-left:20px;">'.$this->l('Background color').':</span> <input type="text" 
               class="color" 
               name="color__'.$field["id"].'"
               style="margin-left:20px;margin-bottom:10px;width:130px;" value="'.$field["background"].'" 
               placeholder="Background color">
               <span style="margin-left:20px;">'.$this->l('Icon color').':</span> <input type="text" class="color" 
               name="iconcolor__'.$field["id"].'"
               style="margin-left:20px;margin-bottom:10px;width:130px;" value="'.$field["icon_color"].'"
                placeholder="Icon color">
            </td><td><select style="margin-left:10px;" name="nr__'.$field["id"].'">';


            $number = Db::getInstance()->getValue('SELECT count(*) FROM `' . _DB_PREFIX_ . 'social_media_sidebar`');

            for ($i=1; $i<=$number; $i++) {
                if ($field["nr"] == $i) {
                    $table .= '<option value="'.$i.'" selected>'.$i.'</option>';
                } else {
                    $table .= '<option value="'.$i.'">'.$i.'</option>';
                }
            }

            $checked = '';

            if ($field["hide_mobile"] == 1)
                $checked = 'checked';

            $table .= '</select></td> <td>
                <input style="margin-left:60px;" name="hide_mobile__'.$field["id"].'" type="checkbox" '.$checked.'>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td><td>
                <input style="margin-left:60px;" name="disable__'.$field["id"].'" type="checkbox">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>';
        }

        $disabledSQL = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'social_media_sidebar`
        WHERE enabled=0 ORDER BY social_name ASC');

        $footer = '</table>

<input style="margin-top:30px;margin-left:30px;margin-bottom:60px;" type="submit"  value="'.$this->l('Save').'" 
class="btn btn-default btn-lg">
</form>';

        if ($disabledSQL) {
            $footer .= '<form method="POST" action="index.php?controller=AdminModules&token='
                .Tools::getAdminTokenLite('AdminModules').'&configure='.$this->name.'&tab_module='.
                $this->tab.'&module_name='.$this->name.'">
        <hr style="margin-top:50px;"/>
        <h2 style="margin-top:50px;margin-left:30px;">'.$this->l('Available icons').'</h2>
        <p style="margin-left:30px;">'.$this->l('Select some of them if You wish to display them in the sidebar').'</p>
        <table style="margin-top:30px;margin-left:30px;">
        ';

            foreach ($disabledSQL as $field) {
                $footer .= '<tr><td><input type="checkbox" name="enable__'.$field["id"].'" value="'.$field["id"].'">
                &nbsp;&nbsp;</td>
            <td style="text-align:right;"><i class="'.$field["social_class"].'"></i></td> 
            <td>&nbsp;&nbsp;'.$field["social_name"].'</td></tr>';
            }

            $footer .= '
        </table>
        <input style="margin-top:30px;margin-left:30px;margin-bottom:60px;" type="submit" 
        value="'.$this->l('Enable').'" class="btn btn-default btn-lg">
        </form>';
        }
        $data = file_get_contents('http://prestacraft.com/free-modules/version_checker.php?module='.$this->name.'&version='.$this->version.'');
        $footer .= '
        </div>
        <div role="tabpanel" class="tab-pane panel" id="colors">
        '.$this->renderCustomizeStyle().'
        '.$this->renderCustomizeStyleMisc().'
        </div>
        <div role="tabpanel" class="tab-pane panel" id="add">
                <h3>'.$this->l('Add your icon').'</h3>
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <form method="POST" action="index.php?controller=AdminModules&configure=socialmediasidebar&token='.Tools::getValue("token").'">
                        <input type="hidden" name="addicon" value="1">
                              <table>
                                <tr>
                                    <td style="padding: 5px;">'.$this->l("FontAwesome icon codename (ex. envelope)").'
                                    </td>
                                    <td style="padding: 5px;"> <input id="codename" type="text" class="form-control" 
                                    name="icon_codename">
                                    </td>
                               </tr>
                                <tr>
                                   <td style="padding: 5px;">'.$this->l("Your new icon name (ex. Mail)").'
                                    </td>
                                    <td style="padding: 5px;"> <input type="text" class="form-control" name="icon_name">
                                    </td>
                               </tr>
                                <tr>
                                   <td style="padding: 5px;">'.$this->l("Background color").'
                                    </td>
                                    <td style="padding: 5px;"> <input id="bgc" type="text" class="color form-control bgc" 
                                    name="icon_bgc">
                                    </td>
                                   </tr>
                                    <tr>
                                   <td style="padding: 5px;">'.$this->l("Icon color").'
                                    </td>
                                    <td style="padding: 5px;"> <input name="icon_ic" type="text" id="ic"
                                     class="color form-control ic">
                                    </td>
                                       </tr>
                             
                                </table>
                                <button type="submit" class="btn btn-primary btn-lg" 
                                style="margin-top: 15px;margin-bottom:30px;">
                                '.$this->l("Add").'</button>
                         </form>
                    </div>
                    <div class="col-xs-12 col-md-6">
                            <div class="panel" >
                            <h3>'.$this->l("Preview").'</h3>
                            <p>'.$this->l("After you fill all the data, you should see your icon preview. 
                            If you can not see anything 
                            make sure that icon codename is right!").'</p>
                            
                            <div class="icon-preview" style=\'font-family:"Font Awesome";width:50px;height:50px;
                            background:#000000; text-align: center;padding-top: 10px;color:#ffffff;\'>
                            <i id="prev-icon" class="icon-envelope"></i>
                            </div>
                            </div>
                    </div>
                </div>
                <style>
                .icon-preview [class^="icon-"] {
                font-size: 30px !important;
                }
                </style>
               
        </div>
        <div role="tabpanel" class="tab-pane panel" id="version">
                <h3>'.$this->l('Version checker').'</h3>
                <p><strong>'.$this->l('Your version:').'</strong> '.$this->version.'</p>
        '.$data.'
        </div>
        <div role="tabpanel" class="tab-pane panel" id="about">
   '.$this->l('Icons are part of Font Awesome Icons').' - <a href="https://fontawesome.com/" 
   target="_blank">https://fontawesome.com/</a>
        <br /><br />
        '.$this->l('Have a look at my blog with tutorials and modules for PrestaShop').' -
        <a href="http://prestacraft.com" target="_blank">http://prestacraft.com</a>. '.$this->l('Thanks').'.
        <br /><br />'.$this->l('Made with').' <i class="icon-heart"></i> '.$this->l('by').' 
        <a href="http://prestacraft.com" target="_blank">PrestaCraft</a>.
<br /><br /><br />
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="2NL2KJBLW86SQ">
<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit"
 alt="PayPal – The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" width="1" height="1">
</form>
        </div>
        </div>
        </div>
<div class="modal fade" id="modalMonocolored" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">'.$this->l('About monocolored mode').'</h4>
      </div>
      <div class="modal-body">
        '.$this->l('Monocolored mode is just a combination of 2 colors (icon and background) which will be 
        used for all Your social media buttons in sidebar.').'
        <br><br>
        '.$this->l('For example, the following settings...').'
        <br><br>
        <img src="../modules/socialmediasidebar/views/img/1.png" style="border:1px solid black;">
        <br><br>
        '.$this->l('...will generate a sidebar which looks like this').':
         <br><br>
         <img src="../modules/socialmediasidebar/views/img/2.png" style="border:1px solid black;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">'.$this->l('Close').'</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalInverse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span 
        aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">'.$this->l('About inverse hover colors').'</h4>
      </div>
      <div class="modal-body">
        '.$this->l('Enable this feature if You want to make switch Your background color with icon color 
        on button hover.').'
        <br><br>
        '.$this->l('Example of enabling inverse hover colors').':
         <br><br>
         <table>
            <tr>
                <td style="border:1px solid black;"><strong>&nbsp;&nbsp;Normal mode&nbsp;&nbsp;</strong></td>
                <td style="border:1px solid black;"><strong>&nbsp;&nbsp;Normal mode hover&nbsp;&nbsp;</strong></td>
                <td style="border:1px solid black;"><strong>&nbsp;&nbsp;Monocolored mode&nbsp;&nbsp;</strong></td>
                <td style="border:1px solid black;"><strong>&nbsp;&nbsp;Monocolored mode hover&nbsp;&nbsp;</strong>
                </td>
            </tr>
            <tr>
                <td style="border:1px solid black;">
                <img src="../modules/socialmediasidebar/views/img/normal.png"></td>
                <td style="border:1px solid black;">
                <img src="../modules/socialmediasidebar/views/img/normal_hover.png"></td>
                <td style="border:1px solid black;">
                <img src="../modules/socialmediasidebar/views/img/monocolored.png"></td>
                <td style="border:1px solid black;">
                <img src="../modules/socialmediasidebar/views/img/monocolored_hover.png">
                </td>
            </tr>
         </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">'.$this->l('Close').'</button>
      </div>
    </div>
  </div>
</div>
        ';

        return $head.$table.$footer;
    }


    public function renderCustomizeStyle()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array('title' => $this->l('Monocolored mode')),
                'submit' => array(
                    'title' => $this->l('Save')
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable monocolored mode?').'<br><a href="#" data-toggle="modal"
                         data-target="#modalMonocolored">[ '. $this->l('What is this?') . ' ]</a>',
                        'name' => 'PC_SOCIAL_MONOCOLORED',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('First color (main)'),
                        'name' => 'PC_SOCIAL_MONOCOLORED_1',
                        'class' => 'leftfix fixed-width-sm'
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Second color (background)'),
                        'name' => 'PC_SOCIAL_MONOCOLORED_2',
                        'class' => 'leftfix fixed-width-sm'
                    ),
                ),

            ),

        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang =
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
                Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'saveStyle';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&pos=2&configure='.
            $this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getStyleFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }


    public function getStyleFieldsValues()
    {
        $fields = array();

        $fields['PC_SOCIAL_MONOCOLORED_1'] = Configuration::get('PC_SOCIAL_MONOCOLORED_1');
        $fields['PC_SOCIAL_MONOCOLORED_2'] = Configuration::get('PC_SOCIAL_MONOCOLORED_2');
        $fields['PC_SOCIAL_MONOCOLORED'] = Configuration::get('PC_SOCIAL_MONOCOLORED');
        $fields['PC_SOCIAL_BORDER_PX'] = Configuration::get('PC_SOCIAL_BORDER_PX');

        return $fields;
    }


    public function renderCustomizeStyleMisc()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array('title' => $this->l('Miscellaneous')),
                'submit' => array(
                    'title' => $this->l('Save')
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Open URLs in new window?'),
                        'name' => 'PC_SOCIAL_NEW_WINDOW',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),

                    array(
                        'type' => 'switch',
                        'label' => $this->l('Inverse hover colors?').'<br><a href="#" data-toggle="modal" 
                        data-target="#modalInverse">[ '. $this->l('What is this?') . ' ]</a>',
                        'name' => 'PC_SOCIAL_INVERSE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),

                    array(
                        'type' => 'text',
                        'label' => $this->l('Mobile devices width breakpoint'),
                        'name' => 'PC_MOBILE_BREAKPOINT',
                        'suffix' => 'px',
                        'col' => 2
                    ),

                    array(
                        'type' => 'switch',
                        'label' => $this->l('Hide icons on mobile devices?'),
                        'name' => 'PC_HIDE_MOBILE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),

                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show bottom icon bar on mobile devices?'),
                        'name' => 'PC_MOBILE_BOTTOM',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),


                ),
            ),

        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang =
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
                Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'saveStyleMisc';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&pos=2&configure='.
            $this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getStyleMiscFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }


    public function getStyleMiscFieldsValues()
    {
        $fields = array();

        $fields['PC_SOCIAL_NEW_WINDOW'] = Configuration::get('PC_SOCIAL_NEW_WINDOW');
        $fields['PC_SOCIAL_INVERSE'] = Configuration::get('PC_SOCIAL_INVERSE');
        $fields['PC_HIDE_MOBILE'] = Configuration::get('PC_HIDE_MOBILE');
        $fields['PC_MOBILE_BREAKPOINT'] = Configuration::get('PC_MOBILE_BREAKPOINT');
        $fields['PC_MOBILE_BOTTOM'] = Configuration::get('PC_MOBILE_BOTTOM');

        return $fields;
    }


    public function hookDisplayHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/pc_sidebar.js', 'all');
        $this->context->controller->addCSS($this->_path.'views/css/pc_sidebar.css', 'all');
        $this->context->controller->addCSS($this->_path.'views/css/font-awesome.css', 'all');
    }


    public function hookDisplayFooter($params)
    {
        $style = '<style>';

        $hideMobile = Db::getInstance()->executeS('SELECT id FROM '._DB_PREFIX_.'social_media_sidebar 
        WHERE hide_mobile=1');


        if (count($hideMobile) > 0) {
            $style .= '@media(max-width:'.Configuration::get('PC_MOBILE_BREAKPOINT').'px) {';
                foreach ($hideMobile as $item) {
                    $style .= '.iconid' . $item["id"] . ' { display: none !important; } ';
                }
            $style .= '}';
        }

        $min = (int)Configuration::get('PC_MOBILE_BREAKPOINT')+1;

        $style .= '
				@media(min-width:'.$min.'px) { 
					.pc-social-sidebar {
						position:fixed;
						left:0;
						top:20%;
						z-index:9999;
						width:50px;
					}
				}';

        if (Configuration::get('PC_HIDE_MOBILE') == 1) {
            $style .= '@media(max-width:'.Configuration::get('PC_MOBILE_BREAKPOINT').'px) { .pc-social-sidebar { display: none; } }';
        } else {
            if (Configuration::get('PC_MOBILE_BOTTOM') == 1) {
                $nbrMedia = Db::getInstance()->getValue('SELECT COUNT(*) FROM '._DB_PREFIX_.'social_media_sidebar 
                WHERE enabled=1');
                $nbrMedia = $nbrMedia-count($hideMobile);
                $widthElement = 100/$nbrMedia-1.5;

                $style .= ' @media(max-width:'.Configuration::get('PC_MOBILE_BREAKPOINT').'px) { 
                 .pc-social-icon {
                    height:50px;
                    font-size:24px;
                    text-align:center;
                    padding-top:12px;
                    display: inline-block;
                    width: '.$widthElement.'% !important;
                }
                
                .pc-social-sidebar {
                    position:fixed;
                    left:0;
                    text-align: center;
                    z-index:9999;
                    width:100%;
                    bottom: 0;
                }
                 }';
            } else {
                $style .= ' @media(max-width:'.Configuration::get('PC_MOBILE_BREAKPOINT').'px) { 
                 .pc-social-sidebar {
                     position:fixed;
                    left:0;
                    top:20%;
                    z-index:9999;
                    width:50px;
                }';
            }
        }

        if (Configuration::get('PC_SOCIAL_INVERSE') == 1) {
            if (Configuration::get('PC_SOCIAL_MONOCOLORED') == 1) {

                $style .= '
                .pc-social-icon:hover {
                background-color:'.Configuration::get('PC_SOCIAL_MONOCOLORED_1').' !important;
                color:'.Configuration::get('PC_SOCIAL_MONOCOLORED_2').' !important;
                }
             ';
            } else {
                $sql = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'social_media_sidebar` 
                WHERE enabled=1 ORDER BY nr ASC');

                $style .= '<style>
.pc-social-icon {
transition-duration:0.6s;
}';
                foreach ($sql as $social) {
                    $style .= '
.iconid'.$social["id"].':hover {
background-color:'.$social["icon_color"].' !important;
color:'.$social["background"].' !important;
                    }';
                }

            }
        }

        $style .= '</style>';

        $sql = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'social_media_sidebar`
         WHERE enabled=1 ORDER BY nr ASC');
        $assign = array(
            'socialmedias' => $sql
        );

        $this->context->smarty->assign($assign);

        return $style.$this->display(__FILE__, 'socialmediasidebar.tpl');
    }
}
