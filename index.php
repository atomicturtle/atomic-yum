<?php
include(PRODUCT_ROOT."/admin/plib/modules/pm.php");
if (!$session->chkLevel(IS_ADMIN)) {
        pm_alert(pm_lmsg('__perm_denied').'You must be admin to use this page');
        go_to_uplevel();
}

header("Location: /yum/global/post.php?action=list&query=updates");

?>
