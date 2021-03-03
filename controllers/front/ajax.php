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

class SocialmediasidebarAjaxModuleFrontController extends ModuleFrontController
{
    public $php_self = 'ajax';

    public function initContent()
    {
        $icons = pSQL(Tools::getValue("icons"));
        $action = pSQL(Tools::getValue("action"));
        $employee = (int)Tools::getValue("id_employee");
        $tokenGenerated = pSQL(Tools::getAdminToken('AdminModules'.(int) Tab::getIdFromClassName('AdminModules').$employee));
        $tokenPassed = pSQL(Tools::getValue('token'));

        if ($tokenGenerated == $tokenPassed) {
            $mod = Module::getInstanceByName('socialmediasidebar');

            switch ($action) {
                case "add":
                    $idAdded = (int)$mod->updateValue($icons);
                    die($idAdded);
                    break;
                case "move":
                    $mod->updateValue($icons, true);
                    die("ok");
                    break;
                case "remove":
                    $mod->removeIcon($icons);
                    die("ok");
                    break;
            }
        } else {
            die("invalid_token");
        }
    }
}