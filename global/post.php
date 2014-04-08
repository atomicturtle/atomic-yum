<?php
include_once(PRODUCT_ROOT."/admin/plib/modules/pm.php");
if (!$session->chkLevel(IS_ADMIN)) {
        pm_alert(pm_lmsg('__perm_denied').'You must be admin to use this page');
        go_to_uplevel();
}

/** include the yum class **/
include_once('yum_class.inc.php');

$yum = new Yum();

/** set selected regex to default **/
$select_regex = NULL;

switch ($_GET["action"])
{
	case "list":
		$cmd = "/usr/bin/sudo /usr/bin/yum list " . escapeshellcmd($_GET["query"]);
		if ($_GET["query"] == "updates") {
			$post_action = "update";
		} elseif ($_GET["query"] == "available") {
			$post_action = "install";
		} elseif ($_GET["query"] == "installed") {
			$post_action = "remove";
		}

		if ($_GET["repo"]) {
			$select_regex=$_GET["repo"];
		} 
		break;

	case "info":
		$cmd = "/usr/bin/sudo /usr/bin/yum info " . escapeshellcmd($_GET["name"]);
		$_SESSION["info"] = true;
		break;

	case "update":
		$cmd = $yum->build_pkg_list("update", $_POST["package"]);
		$select_regex = "update";
		$_SESSION["single"] = true;
		break;

	case "remove":
		$cmd = $yum->build_pkg_list("remove", $_POST["package"]);
		//$yum->execute($cmd);
		//$cmd = "/usr/bin/sudo /usr/bin/yum list installed";
		$select_regex = "update";
		$_SESSION["single"] = true;
		$post_action = "remove";
		break;

	case "repo":
		$cmd = "/usr/bin/sudo /usr/bin/yum " . escapeshellcmd($_GET["query"]) . " |sort";
		$_SESSION["repo"] = true;
		$select_regex = "repo";
		$post_action = "install";
		break;

	case "install":
		$cmd = $yum->build_pkg_list("install", $_POST["package"]);
		$select_regex = "update";
		$_SESSION["single"] = true;
		break;
}

/** execute the yum command **/
$yum_output = $yum->execute($cmd, $select_regex);

/** store the yumdata for use on another page **/
$_SESSION["yumdata"] = $yum_output;

/** redirect to the main use page **/
header("location: /yum/main.php?action=".$post_action);
?>
