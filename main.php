<?php
include_once(PRODUCT_ROOT."/admin/plib/modules/pm.php");
if (!$session->chkLevel(IS_ADMIN)) {
        pm_alert(pm_lmsg('__perm_denied').'You must be admin to use this page');
        go_to_uplevel();
}

/** include the config **/
include_once('./global/config.inc.php');

/** include smarty templates **/
include_once(SMARTY_DIR.'Smarty.class.php');

/** insticate the smarty class **/
$smarty_tmp = new Smarty();

/** starts smarty template caching if debug mode is not set **/
if (DEBUG == true)
{
	$smarty_tmp->caching = 0;
	$smarty_tmp->clear_all_cache();
	$smarty_tmp->force_compile;
	$smarty_tmp->cache_dir = SMARTY_TEMPLATES_C_DIR;
	$smarty_tmp->compile_dir = SMARTY_TEMPLATES_C_DIR;
	$smarty_tmp->debugging = 1;
} else 
{
	$smarty_tmp->caching = TEMPLATE_CACHE;
	$smarty_tmp->cache_dir = SMARTY_TEMPLATES_C_DIR;
	$smarty_tmp->compile_dir = SMARTY_TEMPLATES_C_DIR;
	$smarty_tmp->debugging = 0;
}

/** assign the yum_output data **/
$smarty_tmp->assign("yum_output", $_SESSION["yumdata"]);

/** tell the template to use the info section not the list **/
if (isset($_SESSION["info"]))
{
	$smarty_tmp->assign("yum_info", true);
	unset($_SESSION["info"]);
} else 
{
	$smarty_tmp->assign("yum_info", false);
}

if (isset($_SESSION["repo"])) {
	$smarty_tmp->assign("yum_repo", true);
	unset($_SESSION["repo"]);
} else
{
	$smarty_tmp->assign("yum_repo", false);
}

// This is so we can have a single column display for output from install/remove/update
if (isset($_SESSION["single"]))
{
	$smarty_tmp->assign("yum_single", true);
	unset($_SESSION["single"]);
} else
{
	$smarty_tmp->assign("yum_single", false);
}


/** assign the next action type for the submit **/
if ($_GET["action"])
{
	$smarty_tmp->assign("query", $_GET["action"]);
}

/** define the template file **/
$template_file = SMARTY_TEMPLATES_DIR."interface.tpl";

/** set the smarty tempalte config dir **/
$smarty_tmp->config_dir = SMARTY_TEMPLATES_DIR."configs/";

/** sets the smarty template dir **/
$smarty_tmp->template_dir = SMARTY_TEMPLATES_DIR;

$smarty_tmp->display($template_file);
?>
