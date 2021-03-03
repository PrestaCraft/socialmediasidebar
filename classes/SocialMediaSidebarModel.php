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

class SocialMediaSidebarModel extends ObjectModel
{
    public $social_class;
    public $background;
    public $bg_default;
    public $icon_color;
    public $url;
    public $enabled;
    public $nr;
    public $hide_mobile;
    public $id_shop;
    public $id;
    public $id_social_media_sidebar;

    public static $definition = array(
        'table' => 'social_media_sidebar',
        'primary' => 'id_social_media_sidebar',
        'fields' => array(
            'social_class' => array('type' => self::TYPE_STRING),
            'background' => array('type' => self::TYPE_STRING),
            'bg_default' => array('type' => self::TYPE_STRING),
            'icon_color' => array('type' => self::TYPE_STRING),
            'url' => array('type' => self::TYPE_STRING),
            'enabled' => array('type' => self::TYPE_INT),
            'nr' => array('type' => self::TYPE_INT),
            'hide_mobile' => array('type' => self::TYPE_INT),
            'id_shop' => array('type' => self::TYPE_INT),
        ),
    );



    public static function getInstanceByIconName($class)
    {
        $id = (int)Db::getInstance()->getValue('SELECT id_social_media_sidebar 
        FROM '._DB_PREFIX_.self::$definition['table'].' 
        WHERE social_class="'.$class.'"');

        if ($id > 0) {
            $obj = new self($id);

            if (Validate::isLoadedObject($obj)) {
                return $obj;
            }
        }

        return false;
    }

    public static function getMax($idShop)
    {
        $all = self::getAll($idShop);

        if (count($all) > 0) {
            $max = (int)Db::getInstance()->getValue('SELECT max(nr) FROM '._DB_PREFIX_.'social_media_sidebar 
            WHERE id_shop='.$idShop);

            return $max;
        } else {
            return -1;
        }
    }

    public function reposition($idShop)
    {
        $icons = self::getAll($idShop);
        $nr = 0;

        foreach ($icons as $icon) {
            $obj = new self($icon['id_social_media_sidebar']);
            $obj->nr = $nr++;
            $obj->save();
        }
    }

    public static function getAll($idShop, $enabledOnly = false)
    {
        $condition = ' WHERE id_shop='.$idShop;

        if ($enabledOnly) {
            $condition .= ' AND `enabled`=1';
        }

        $condition .= ' ORDER BY nr ASC';

        return Db::getInstance()->executeS(
            'SELECT * FROM '._DB_PREFIX_.self::$definition['table'].$condition
        );
    }

    public static function clearAll()
    {
        Db::getInstance()->execute('TRUNCATE TABLE '._DB_PREFIX_.self::$definition['table']);
    }
}