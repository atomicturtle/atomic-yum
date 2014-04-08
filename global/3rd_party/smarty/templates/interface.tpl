<html>
 <head>
  <title>Atomic Web Yum</title>
  {literal}
  <script type="text/javascript">
   <!--
    function Checkall(form)
     {
      for (var i=0; i < form.elements.length; i++)
       {
        var e = form.elements[i];
        if ((e.name != 'allbox') && (e.type=='checkbox'))
          e.checked = form.allbox.checked;
       }
     }
   -->
  </script>
  {/literal}
<!-- Temporarily added until code is added to use selected skin -->
  <link rel="stylesheet" type="text/css" href="/skins/winxp.new.compact/css/general.css">
  <link rel="stylesheet" type="text/css" href="/skins/winxp.new.compact/css/main/custom.css">
  <link rel="stylesheet" type="text/css" href="/skins/winxp.new.compact/css/main/layout.css">
  <link rel="stylesheet" type="text/css" href="/skins/winxp.new.compact/css/main/desktop.css">
  <!--[if IE]><link rel="stylesheet" type="text/css" href="/skins/winxp.new.compact/css/ie.css"><![endif]-->
  <link rel="stylesheet" type="text/nonsense" href="/skins/winxp.new.compact/css/misc.css">
<!--  <link rel="stylesheet" type="text/css" href="css/atomic.css" /> -->
 </head>
 <body class="visibilityAdminMode MainFrameBody" id="mainCP">
  <a href="#" name="top" id="top"></a>
  <table width="100%" cellspacing="0" cellpadding="0" border="0" id="pageLayout"><tr><td id="screenWH">
   <div class="pathbar">&nbsp;</div>
   <div class="screenTitle">
    <table width="100%" cellspacing="0">
     <tr>
      <td>Atomic Yum Updater</td>
      <td class="uplevel"></td>
     </tr>
    </table>
   </div>
   <div class="screenSubTitle"></div>
   <div id="screenTabs">
    <div id="tabs">
<!-- Removed from links: onClick=";lon();" -->

     <ul>
      <li class="first" {if $query=="install"}id="current"{/if}><a href="/yum/global/post.php?action=repo&query=repolist"><span>Available</span></a></li>
      <li {if $query=="update"}id="current"{/if}><a href="/yum/global/post.php?action=list&query=updates"><span>Updates</span></a></li>
      <li {if $query=="remove" || $yum_info}id="current"{/if} class="last"><a href="/yum/global/post.php?action=list&query=installed"><span>Installed</span></a></li>
     </ul>
    </div>
   </div>
   <div class="screenBody" id="">
    <form method="POST" action="/yum/global/post.php?action={$query}">
     <div class="listArea">
      <fieldset>
       <table><tbody><tr><td>
        <table cellspacing="0" width="600px" class="list">
         <tbody>
{if $yum_info}
          <tr>
           <th>&nbsp;</th>
           <th>&nbsp;</th>
          </tr>
{section name=repo loop=$yum_output}
          <tr class="{cycle values="oddrowbg,evenrowbg}">
           <td>{$yum_output[repo][0]}</td>
           <td>{$yum_output[repo][1]}</td>
          </tr>
{/section}
{elseif $yum_repo}
          <tr>
           <th>Repo ID</th>
           <th>Name</b></th>
           <th>Status</b></th>
          </tr>
{section name=repo loop=$yum_output}
          <tr class="{cycle values="oddrowbg,evenrowbg}">
           <td><a href=/yum/global/post.php?action=list&query=available&repo={$yum_output[repo].0}>{$yum_output[repo].0}</a></td>
           <td>{$yum_output[repo].1}</td>
           <td>{$yum_output[repo].2}</td>
          </tr>
{/section}

{elseif $yum_single}
          <tr>
           <th>Output</th>
          </tr>
{section name=repo loop=$yum_output}
          <tr class="{cycle values="oddrowbg,evenrowbg}">
           <td>{$yum_output[repo]}</td>
          </tr>
{/section}



{else}
          <tr>
           <th><input type="checkbox" name="allbox" onClick="Checkall(this.form);" ></th>
           <th>Name</th>
           <th>Version</th>
           <th>Repo</th>
          </tr>
{section name=repo loop=$yum_output}
          <tr class="{cycle values="oddrowbg,evenrowbg}">
           <td><input type="checkbox" name="package[]" value={$yum_output[repo].0}></td>
           <td><a href=/yum/global/post.php?action=info&name={$yum_output[repo].0}>{$yum_output[repo].0}</a></td>
           <td>{$yum_output[repo].1}</td>
           <td>{$yum_output[repo].2}</td>
          </tr>
{/section}
          <tr>
           <td colspan="4"><input type="Submit" name="submit" value="Submit"></td>
          </tr>
{/if}

         </tbody>
        </table>
       </td></tr></tbody></table>
      </fieldset>
     </div>
    </form>
   </div>
  </td></tr></table>
 </body>
</html>


