<?php

function displayRuns($resultSet, $title = "")
{
    echo "<div class=\"runTitle\">$title</div>\n";
    echo "<table id=\"box-table-a\" class=\"tablesorter\" summary=\"Stats\"><thead><tr><th>Timestamp</th><th>Cpu</th><th>Wall Time</th><th>Peak Memory Usage</th><th>URL</th><th>Simplified URL</th></tr></thead>";
    echo "<tbody>\n";
    while ($row = XHProfRuns_Default::getNextAssoc($resultSet))
    {
        $c_url = urlencode($row['c_url']);
        $url = urlencode($row['url']);
        $html['url'] = htmlentities($row['url'], ENT_QUOTES, 'UTF-8');
        $html['c_url'] = htmlentities($row['c_url'], ENT_QUOTES, 'UTF-8');
        $date = strtotime($row['timestamp']);
        $date = date('M d H:i:s', $date);
        echo "\t<tr><td><a href=\"?run={$row['id']}\">$date</a><br /><span class=\"runid\">{$row['id']}</span></td><td>{$row['cpu']}</td><td>{$row['wt']}</td><td>{$row['pmu']}</td><td><a href=\"?geturl={$url}\">{$html['url']}</a></td><td><a href=\"?getcurl={$c_url}\">{$html['c_url']}</a></td></tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
    echo <<<SORTTABLE
<script type="text/javascript">
$(document).ready(function() 
    { 
        $("#box-table-a").tablesorter( {sortList: []} ); 
    } 
);
</script>
SORTTABLE;
}

function printSeconds($time)
{
    $suffix = "microsecond";

    if ($time > 1000)
    {
        $time = $time / 1000;
        $suffix = "ms";
        
    }
    
    if ($time > 1000)
    {
        $time = $time / 1000;
        $suffix = "s";
    }
    
    if ($time > 60 && $suffix == "s")
    {
        $time = $time / 60;
        $suffix = "minutes!";
    }
    return sprintf("%.4f {$suffix}", $time);
    
}
 


function showChart($rs, $flip = false)
{

        $dataPoints = "";
        $ids = array(); 
        $arCPU = array();
        $arWT = array();
        $arPEAK = array();
        $arIDS = array();
        $arDateIDs = array();
    
         while($row = XHProfRuns_Default::getNextAssoc($rs))
        {
            $date[] = "'" . date("Y-m-d", $row['timestamp']) . "'" ;  
           
            $arCPU[] = $row['cpu'];
            $arWT[] = $row['wt'];
            $arPEAK[] = $row['pmu'];  
            $arIDS[] = $row['id']; 
            
            $arDateIDs[] =  "'" . date("Y-m-d", $row['timestamp']) . " <br/> " . $row['id'] . "'"; 
        }

        $date = $flip ? array_reverse($date) : $date;
        $arCPU = $flip ? array_reverse($arCPU) : $arCPU;
        $arWT = $flip ? array_reverse($arWT) : $arWT;
        $arPEAK = $flip ? array_reverse($arPEAK) : $arPEAK;
        $arIDS = $flip ? array_reverse($arIDS) : $arIDS;
        $arDateIDs = $flip ? array_reverse($arDateIDs) : $arDateIDs;
        
       $dateJS = implode(", ", $date);
       $cpuJS = implode(", ", $arCPU);
       $wtJS = implode(", ", $arWT);
       $pmuJS = implode(", ", $arPEAK);
       $idsJS = implode(", ", $arIDS);
       $dateidsJS = implode(", ", $arDateIDs);
  
   
    ob_start();
      require ("../xhprof_lib/templates/chart.phtml");   
      $stuff = ob_get_contents();
    ob_end_clean();
   return array($stuff, "<div id=\"container\" style=\"width: 1000px; height: 500px; margin: 0 auto\"></div>");
}
 


function getFilter($filterName)
{
    if (isset($_GET[$filterName]))
    {
      if ($_GET[$filterName] == "None")
      {
        $serverFilter = null;
        setcookie($filterName, null, 0);
      }else
      {
        setcookie($filterName, $_GET[$filterName], (time() + 60 * 60));
        $serverFilter = $_GET[$filterName];
      }
    }elseif(isset($_COOKIE[$filterName]))
    {
        $serverFilter = $_COOKIE[$filterName];  
    }else
    {
      $serverFilter = null;
    }
    return $serverFilter;
}


function print_rc($expression, $return = null) {
    if ($return) {
        ob_start();
    }
    ?><pre><?php
        print_r($expression);
        ?></pre><script type="text/javascript">(function(e){function n(e){return e.replace(/(Array|Object)\n(\s*)\(/g,'<span class="debug-controls"><a class="toggle-display" href="#">$1</a> '+'<a class="toggle-children" href="#" title="toggle children">\\</a> '+'<a class="toggle-recursive" href="#" title="toggle recursive">*</a> '+'</span><span class="debug-data" style="display:none"> '+"\n$2(").replace(/\n(\s*?)\)\n/g,"\n$1)\n</span>")}function r(e,t){if("undefined"==typeof t){t=i(e,0)}e.style.display=t?"":"none";return t}function i(e,t){var n;if(!t){return e.style.display=="none"}else{for(var r=0;r<e.childNodes.length;++r){if("debug-data"==e.childNodes[r].className){n=i(e.childNodes[r],t-1);if(-1!=n){return n}}}}return-1}function s(e,t,n){if("undefined"==typeof n){n=i(e,t?2:1);if(-1==n){if(t){s(e,false);return}else{r(e);return}}}for(var o=0;o<e.childNodes.length;++o){if("debug-data"==e.childNodes[o].className){r(e.childNodes[o],n);if(t){s(e.childNodes[o],true,n)}}}if(n){r(e,n)}}function o(e){e.innerHTML=n(e.innerHTML);var t=e.getElementsByTagName("a");for(var i=0;i<t.length;++i){t[i].onclick=function(){var e=this.className,t=this.parentNode.nextSibling;if(e=="toggle-display"){r(t)}else if(e=="toggle-children"){s(t,false)}else if(e=="toggle-recursive"){s(t,true)}return false}}if("none"==e.style.display){e.style.display=""}}if("undefined"==typeof e){var t=document.getElementsByTagName("script");e=t[t.length-1].previousSibling}o(e)})()</script>
    <?php
    if ($return) {
        return ob_get_clean();
    }
    return true;
}