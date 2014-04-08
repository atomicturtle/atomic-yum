<?php
include_once(PRODUCT_ROOT."/admin/plib/modules/pm.php");
if (!$session->chkLevel(IS_ADMIN)) {
        pm_alert(pm_lmsg('__perm_denied').'You must be admin to use this page');
        go_to_uplevel();
}

class Yum
{
	public function execute($cmd, $pattern)
	{
		/** yum query to get update list **/
		exec($cmd, $yum_result, $err);

		if ($err)
		{
			echo "Error in exec command! <br>".$cmd."<br>";
			if (!is_array($yum_result)) {
				echo $yum_result;
			} else {
				foreach ($yum_result as $key => $value) { 
					echo $value."<br>"; 
				}
			}
			exit();
		}	

		/** initialize the count variable **/
		$count = 0;

		foreach($yum_result as $key => $value) {

		    if ($pattern == NULL) {
			/** regex to match un-needed stuff **/
			$regex_pattern = "/^Loading|^Installed|^Updated|^Setting|^Reading|^Available|^repo|^=|enabled$|^$|packages.*excluded/";

  		        /** checks to make sure the un-needed stuff is not matched **/
		        if (!preg_match($regex_pattern, $value)) {
		    	  $yum_output[$count++] = $_GET["action"] != "info" ? preg_split("/\s+/", $value) : $value;
                        }



		    } else if ($pattern == "update") {
			$yum_output[$count++] = $value;
			
		    } else if ($pattern == "repo") {
			$regex_pattern = "/(\S+)\s+([a-zA-Z].*)\s+(enabled$)/";
		        if (preg_match($regex_pattern, $value, $info)) {
                          $yum_output[$count++] = array($info[1], $info[2], $info[3]);
                        }

		    } else  {
			$regex_pattern = "/(\S+)\s+(\S+)\s+($pattern)/";
		        if (preg_match($regex_pattern, $value, $info)) {
                          $yum_output[$count++] = array($info[1], $info[2], $info[3]);
 			}
		    }

		}


		// Scott, forgive me for I am shit with using preg style operators =)
		// Yeah this is a great start, we need to get better parsing for the Description field.
		// -Scott
		if ($_GET["action"] == "info") {
			foreach ($yum_output as $key => $value) {

				if (substr_count($value,"Description") > 0) {
					$yum_output[$key] = array("Description", $yum_output[$key+1]);
					$killthis = $key+1;

				// Why doesn't yum put a colon after description? 
				} elseif (substr_count($value,":") > 0) {
					$yum_output[$key] = explode(":",$value);
				}

				if (isset($killthis)) {
					$yum_output[$killthis] = array();
				}
			}
		}


// ------------------------------------------------------------------
	

		
		return $yum_output;
	}
	
	public function build_pkg_list($type, $pkgs)
	{
		/** make sure the pkglist variable is empty **/
		$pkglist = "";
	
		/** build the package list **/
		foreach ($pkgs as $key => $value)
		{
			if (!empty($pkglist))
			{
				$pkglist .= " ".$value;
			} else 
			{
				$pkglist = $value;
			}
		}
	
		$cmd = "/usr/bin/sudo /usr/bin/yum -y "; 
		
		if ($type == "remove") {
			$cmd .= "remove "; 
		} else if ($type == "install") {
			$cmd .= "install "; 
		} else {
			$cmd .= "update ";
		} 
		
		$cmd .= escapeshellcmd($pkglist);
		
		return $cmd;
	}
}
?>
