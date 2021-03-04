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

require_once _PS_MODULE_DIR_.'socialmediasidebar/classes/SocialMediaSidebarModel.php';

class SocialmediaSidebar extends Module
{
    private $lockTab = 'enable';

    public function __construct()
    {
        $this->name = 'socialmediasidebar';
        $this->tab = 'front_office_features';
        $this->version = '2.0.0';
        $this->author = 'PrestaCraft';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6.0.3', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->postProcess();

        $this->displayName = $this->l('Social media sidebar');
        $this->description = $this->l('Display fixed sidebar with social media buttons.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall? You will lose your all custom social 
        medias and settings.');
    }


    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        $this->loadDefaultConfiguration();

        // social_media_sidebar table ; hooks ; configuration
        if (!parent::install() ||
            !$this->installDb() ||
            !$this->registerHook('footer') ||
            !$this->registerHook('header')
        ) {
            return false;
        }

        return true;
    }

    public function installDb()
    {
        if (!Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'social_media_sidebar`')) {
            return false;
        }

        if (!Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'social_media_sidebar` (
        `id_social_media_sidebar` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
        `social_class` VARCHAR(30),
        `background` VARCHAR(15),
        `bg_default` VARCHAR(15),
        `icon_color` VARCHAR(15),
        `url` VARCHAR(300),
        `enabled` INT,
        `nr` INT,
        hide_mobile TINYINT,
        id_shop INT,
        PRIMARY KEY (`id_social_media_sidebar`)
        ) AUTO_INCREMENT = 1 ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8')) {
            return false;
        }

        return true;
    }

    private function loadDefaultConfiguration()
    {
        $config = array(
            'PC_SOCIAL_MONOCOLORED' => '0',
            'PC_SOCIAL_MONOCOLORED_1' => '#FF2310',
            'PC_SOCIAL_MONOCOLORED_2' => '#F1F1F1',
            'PC_SOCIAL_NEW_WINDOW' => '1',
            'PC_SOCIAL_INVERSE' => '0',
            'PC_MOBILE_BREAKPOINT' => '768',
            'PC_HIDE_MOBILE' => '0',
            'PC_MOBILE_BOTTOM' => '1',
            'PC_SIDEBAR_POSITION' => 'left',
            'PC_TOP_PADDING_PERCENT' => '20',
            'PC_ICON_BOX_SIZE' => '50',
            'PC_ENABLE_ICON_SLIDING' => '1',
            'PC_ICON_SIZE' => '30',
            'PC_ENABLE_CACHE' => '0',
            'PC_WIDER' => '10',
        );

        foreach ($config as $key => $value) {
            if (!Configuration::hasKey($key)) {
                Configuration::updateValue($key, $value);
            }
        }
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
        if (Shop::getContext() != Shop::CONTEXT_SHOP) {
            return $this->context->smarty->fetch($this->local_path.'views/templates/admin/single_shop_message.tpl');
        }

        $data = Tools::file_get_contents(
            'http://prestacraft.com/version_checker.php?module='.$this->name.'&version='.$this->version.''
        );

        $this->context->smarty->assign('ajax_url', Context::getContext()->link->getModuleLink(
            'socialmediasidebar', 'ajax')
        );
        $this->context->smarty->assign('id_employee', (int)Context::getContext()->employee->id);
        $this->context->smarty->assign('icons', $this->getIcons());
        $this->context->smarty->assign('db_icons', SocialMediaSidebarModel::getAll((int)Shop::getContextShopID()));
        $this->context->smarty->assign('all_icons', $this->getIcons(true));
        $this->context->smarty->assign('icons_brand', $this->getIcons(false, true));
        $this->context->smarty->assign('VERSION_CHECKER', $data);
        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('module_url', 'index.php?controller=AdminModules&configure='.$this->name.
            '&module_name='.$this->name.'&token='.Tools::getValue('token'));
        $this->context->smarty->assign('lock_tab', $this->lockTab);
        $this->context->smarty->assign('ICON_SETTINGS', $this->renderIconSettings());
        $this->context->smarty->assign('CUSTOMIZE_STYLE', $this->renderCustomizeStyle());
        $this->context->smarty->assign('CUSTOMIZE_STYLE_MISC', $this->renderCustomizeStyleMisc());

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output;
    }

    public function updateValue($icons, $move = false)
    {
        if (Shop::getContext() == Shop::CONTEXT_SHOP) {
            $iconsPassed = array();
            $icons = preg_replace('/[ \t]+/', ' ', preg_replace('/\s*$^\s*/m', "\n", $icons));
            $arrayOfIcons = explode(",", $icons);

            foreach (array_filter($arrayOfIcons) as $icon) {
                if (trim($icon) != "") {
                    $iconsPassed[] = $icon;
                }
            }

            if ($move) {
                $i = 0;
                foreach ($iconsPassed as $id) {
                    $obj = new SocialMediaSidebarModel($id);
                    $obj->nr = $i++;
                    $obj->save();
                }
            } else {
                $nr = SocialMediaSidebarModel::getMax((int)Shop::getContextShopID());
                $newIcon = end($iconsPassed);

                $obj = new SocialMediaSidebarModel();
                $obj->social_class = $newIcon;
                $obj->enabled = 1;
                $obj->hide_mobile = 0;
                $obj->icon_color = '#000000';
                $obj->background = '#FFFFFF';
                $obj->id_shop = (int)Shop::getContextShopID();
                $obj->nr = $nr+1;
                $obj->save();

                die($obj->id);
            }
        }
    }

    public function removeIcon($id)
    {
        $obj = new SocialMediaSidebarModel($id);
        $obj->delete();
        $obj->reposition((int)Shop::getContextShopID());
    }

    private function getIcons($all = false, $brand = false)
    {
        $file = fopen(_PS_MODULE_DIR_.'socialmediasidebar/icons.txt', "r");
        $icons = array();
        $iconsToReturn = array();

        while (!feof($file)) {
            $icons[] = fgets($file);
        }

        if ($brand) {
            foreach ($icons as $icon) {
                if ($this->startsWith($icon, "[fab]")) {
                    $iconsToReturn[] = str_replace("[fab]", "", $icon);
                }
            }
        } else {
            foreach ($icons as $icon) {
                if (!$this->startsWith($icon, "[fab]")) {
                    $iconsToReturn[] = $icon;
                }
            }
        }

        fclose($file);

        if ($all) {
            return $icons;
        }

        return $iconsToReturn;
    }

    /*
     * Helper function to get all available icons class from FontAwesome CSS.
     * Loaded only once to fill in icons.txt file.
     */
    private function loadIconsToTxt()
    {
        $file = fopen(_PS_MODULE_DIR_.'socialmediasidebar/views/css/font-awesome.css', "r");
        $icons = array();
        $iconsFiletered = array();

        while (!feof($file)) {
            $icons[] = fgets($file);
        }

        fclose($file);

        $line = 0;

        foreach ($icons as $icon) {
            $line++;

            if ($line > 194 && $line < 4400) {
                if ($this->startsWith($icon, ".fa-")) {
                    if (trim($icon) != "") {
                        $iconsFiletered[] = ltrim(str_replace(":before", "", str_replace(" {", "", $icon)), ".");
                    }
                }
            }
        }

        $file = fopen(_PS_MODULE_DIR_.'socialmediasidebar/icons.txt', "w");

        foreach($iconsFiletered as $value){
            fwrite($file, $value);
        }

        fclose($file);
    }

    private function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public function postProcess()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (Tools::isSubmit('saveIconSettings')) {
                if (isset($_POST['icon'])) {
                    foreach ($_POST['icon'] as $id => $values) {
                        $obj = new SocialMediaSidebarModel($id);

                        if (Validate::isLoadedObject($obj)) {
                            foreach ($values as $key => $data) {
                               if (property_exists($obj, $key)) {
                                   $obj->$key = $data;
                               }
                            }

                            if (!isset($values['enabled'])) {
                                $obj->enabled = 0;
                            }

                            if (!isset($values['hide_mobile'])) {
                                $obj->hide_mobile = 0;
                            }
                        }

                        $obj->save();
                    }
                }

                $this->lockTab = 'iconsettings';
            }

            if (Tools::isSubmit('saveStyle')) {
                foreach ($this->getFormFieldsValues('saveStyle') as $k => $v) {
                    Configuration::updateValue($k, Tools::getValue($k));
                }
                $this->lockTab = 'settings';
            }

            if (Tools::isSubmit('saveStyleMisc')) {
                foreach ($this->getFormFieldsValues('saveStyleMisc') as $k => $v) {
                    Configuration::updateValue($k, Tools::getValue($k));
                }
                $this->lockTab = 'settings';
            }
        }
    }

    public function renderIconSettings()
    {
        $this->context->smarty->assign('defined_icons', SocialMediaSidebarModel::getAll((int)Shop::getContextShopID()));

        return $this->context->smarty->fetch($this->local_path.'views/templates/admin/icon_settings.tpl');
    }

    public function renderCustomizeStyle()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array('title' => $this->l('Color settings')),
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
                ),

            ),

        );

        return $this->formGenerator($fields_form, 'saveStyle');
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
                        'label' => $this->l('Enable cache in module'),
                        'name' => 'PC_ENABLE_CACHE',
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
                        'type' => 'text',
                        'label' => $this->l('Mobile devices width breakpoint'),
                        'name' => 'PC_MOBILE_BREAKPOINT',
                        'suffix' => 'px',
                        'col' => 3
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
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Sidebar position'),
                        'name' => 'PC_SIDEBAR_POSITION',
                        'values' => array(
                            array(
                                'id' => 'left',
                                'value' => 'left',
                                'label' => $this->l('Left')
                            ),
                            array(
                                'id' => 'right',
                                'value' => 'right',
                                'label' => $this->l('Right')
                            )
                        )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Padding from top'),
                        'name' => 'PC_TOP_PADDING_PERCENT',
                        'suffix' => '%',
                        'col' => 3
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Icon square box size'),
                        'name' => 'PC_ICON_BOX_SIZE',
                        'suffix' => 'px',
                        'col' => 3
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Icon font size'),
                        'name' => 'PC_ICON_SIZE',
                        'suffix' => 'px',
                        'col' => 3
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Wider icon on hover?'),
                        'desc' => $this->l('Currently selected icon will be wider than the rest on mouse-over'),
                        'name' => 'PC_ENABLE_ICON_SLIDING',
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
                        'label' => $this->l('How many pixels wider on hover?'),
                        'desc' => $this->l('If you want to enable sliding - you can define how wider current icon box should be'),
                        'name' => 'PC_WIDER',
                        'suffix' => 'px',
                        'col' => 3
                    ),
                ),
            ),

        );

        return $this->formGenerator($fields_form, 'saveStyleMisc');
    }

    public function formGenerator($formFields, $submitAction)
    {
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
        $helper->submit_action = $submitAction;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&pos=2&configure='.
            $this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&page=settings';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getFormFieldsValues($submitAction),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($formFields));
    }

    public function getFormFieldsValues($submitAction)
    {
        $fields = array();

        switch ($submitAction) {
            case 'saveStyle':
                $fields['PC_SOCIAL_MONOCOLORED_1'] = Configuration::get('PC_SOCIAL_MONOCOLORED_1');
                $fields['PC_SOCIAL_MONOCOLORED_2'] = Configuration::get('PC_SOCIAL_MONOCOLORED_2');
                $fields['PC_SOCIAL_MONOCOLORED'] = Configuration::get('PC_SOCIAL_MONOCOLORED');
                $fields['PC_SOCIAL_BORDER_PX'] = Configuration::get('PC_SOCIAL_BORDER_PX');
                $fields['PC_SOCIAL_INVERSE'] = Configuration::get('PC_SOCIAL_INVERSE');
                break;
            case 'saveStyleMisc':
                $fields['PC_SOCIAL_NEW_WINDOW'] = Configuration::get('PC_SOCIAL_NEW_WINDOW');
                $fields['PC_HIDE_MOBILE'] = Configuration::get('PC_HIDE_MOBILE');
                $fields['PC_MOBILE_BREAKPOINT'] = Configuration::get('PC_MOBILE_BREAKPOINT');
                $fields['PC_MOBILE_BOTTOM'] = Configuration::get('PC_MOBILE_BOTTOM');
                $fields['PC_SIDEBAR_POSITION'] = Configuration::get('PC_SIDEBAR_POSITION');
                $fields['PC_TOP_PADDING_PERCENT'] = Configuration::get('PC_TOP_PADDING_PERCENT');
                $fields['PC_ICON_BOX_SIZE'] = (int)Configuration::get('PC_ICON_BOX_SIZE');
                $fields['PC_ICON_SIZE'] = (int)Configuration::get('PC_ICON_SIZE');
                $fields['PC_ENABLE_ICON_SLIDING'] = Configuration::get('PC_ENABLE_ICON_SLIDING');
                $fields['PC_ENABLE_CACHE'] = Configuration::get('PC_ENABLE_CACHE');
                $fields['PC_WIDER'] = Configuration::get('PC_WIDER');
                break;
        }

        return $fields;
    }

    public function hookHeader()
    {
        if ((bool)Configuration::get('PC_ENABLE_ICON_SLIDING')) {
            Media::addJsDef(array('pc_icon_box_size' => (int)Configuration::get('PC_ICON_BOX_SIZE')));
            Media::addJsDef(array('pc_total_wider' => (int)Configuration::get('PC_ICON_BOX_SIZE')+(int)Configuration::get('PC_WIDER')));
            Media::addJsDef(array('pc_wider' => (int)Configuration::get('PC_WIDER')));

            if (!Configuration::hasKey('PC_SIDEBAR_POSITION') || Configuration::get('PC_SIDEBAR_POSITION') == 'left') {
                Media::addJsDef(array('pc_sidebar_position' => 'left'));
            } else {
                Media::addJsDef(array('pc_sidebar_position' => 'right'));
            }

            $this->context->controller->addJS($this->_path.'views/js/pc_sidebar.js', 'all');
        }

        $this->context->controller->addCSS($this->_path.'views/css/font-awesome.css', 'all');
    }

    public function hookDisplayFooter($params)
    {
        $cacheEnabled = (bool)Configuration::get('PC_ENABLE_CACHE');

        if ($cacheEnabled) {
            if (!$this->isCached('socialmediasidebar.tpl', $this->getCacheId($this->name))) {
                $this->context->smarty->assign(self::getSmartyVariables());
            }

            return $this->display(__FILE__, 'socialmediasidebar.tpl', $this->getCacheId());
        } else {
            $this->context->smarty->assign(self::getSmartyVariables());
            return $this->display(__FILE__, 'socialmediasidebar.tpl');
        }
    }

    public static function getSmartyVariables()
    {
        $icons = SocialMediaSidebarModel::getAll((int)Shop::getContextShopID(), true);

        $hideMobileSingle = false;
        $hideMobileSingleCount = 0;

        foreach ($icons as $icon) {
            if ((bool)$icon['hide_mobile']) {
                $hideMobileSingle = true;
                $hideMobileSingleCount++;
            }
        }

        $assign = array();
        $assign['pc_icons'] = $icons;
        $assign['pc_hide_mobile_single'] = $hideMobileSingle;
        $assign['pc_min'] = (int)Configuration::get('PC_MOBILE_BREAKPOINT')+1;
        $assign['hide_mobile'] = (bool)Configuration::get('PC_HIDE_MOBILE');
        $assign['pc_mobile_breakpoint'] = (int)Configuration::get('PC_MOBILE_BREAKPOINT');
        $assign['pc_mobile_bottom'] = (bool)Configuration::get('PC_MOBILE_BOTTOM');
        $assign['pc_social_inverse'] = (bool)Configuration::get('PC_SOCIAL_INVERSE');
        $assign['pc_social_monocolored'] = (bool)Configuration::get('PC_SOCIAL_MONOCOLORED');
        $assign['pc_social_monocolored_1'] = Configuration::get('PC_SOCIAL_MONOCOLORED_1');
        $assign['pc_social_monocolored_2'] = Configuration::get('PC_SOCIAL_MONOCOLORED_2');
        $assign['pc_social_new_window'] = (bool)Configuration::get('PC_SOCIAL_NEW_WINDOW');
        $assign['pc_top_padding'] = (int)Configuration::get('PC_TOP_PADDING_PERCENT');
        $assign['pc_sidebar_position'] = Configuration::get('PC_SIDEBAR_POSITION');
        $assign['pc_icon_size'] = (int)Configuration::get('PC_ICON_SIZE');
        $assign['pc_icon_box_size'] = (int)Configuration::get('PC_ICON_BOX_SIZE');
        $assign['calc_padding'] = ((int)Configuration::get('PC_ICON_BOX_SIZE')-(int)Configuration::get('PC_ICON_SIZE'))/2;

        return $assign;
    }
}
