<?php
include_once(PRODUCT_ROOT."/admin/plib/modules/pm.php");
if (!$session->chkLevel(IS_ADMIN)) {
        pm_alert(pm_lmsg('__perm_denied').'You must be admin to use this page');
        go_to_uplevel();
}


define("SMARTY_DIR", "/usr/local/psa/admin/htdocs/yum/global/3rd_party/smarty/libs/");
define("SMARTY_TEMPLATES_DIR", "/usr/local/psa/admin/htdocs/yum/global/3rd_party/smarty/templates/");
define("SMARTY_TEMPLATES_C_DIR", "/usr/local/psa/admin/htdocs/yum/global/3rd_party/smarty/templates_c/");
define("TEMPLATE_CACHE", false);
define("DEBUG", false);
?>
