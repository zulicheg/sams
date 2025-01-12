<?php
/*  
 * SAMS (Squid Account Management System)
 * Author: Dmitry Chemerik chemerik@mail.ru
 * (see the file 'main.php' for license details)
 */

class UpTime
{
  var $days="";
  var $hours="";
  var $minits="";
}

function GetUpTime()
{
  $value=`getuptime`;
  return($value);
}

/*
function GetHostName()
{
  if(!($value=getenv('SERVER_NAME')))
     {  $value="N.A."; }
  return($value);
}
*/

function GetIPAddr()
{
  if(!($value=getenv('SERVER_ADDR')))
     {  $value="N.A."; }
  return($value);
}

function MemoryUsage()
{
  $finp=`free`;
  $str=strtok($finp," ");
  for($i=0;$i<20;$i++)
     {
       $mem[$i]=strtok(" ");
     }
  print("<P><TABLE CLASS=samstable>");
  print("<TR >");
  print("<TH>");
  print("<TH><B>Total</B>");
  print("<TH><B>Used</B>");
  print("<TH><B>Free</B>\n");
  print("<TR >");
  print("<TD>Memory");
  print("<TD>$mem[5]");
  print("<TD>$mem[6]");
  print("<TD>$mem[7]\n");
  print("<TR >");
  print("<TD>Swap");
  print("<TD>$mem[14]");
  print("<TD>$mem[15]");
  print("<TD>$mem[16]\n");
  print("</TABLE>");
}

function FileSystemUsage()
{
//  $finp=`df`;
//  ��������, ����� ����� ���� �
  $finp=`df -P -h | grep -v devfs`;

  $len=strlen($finp);
  $str[0]=strtok($finp,"\n");
  $newlen=strlen($str[0])+1;
  $c=0;
  while($newlen<$len)
     {
       $c=$c+1;
       $str[$c]=strtok("\n");
       $newlen=$newlen+strlen($str[$c])+1;
     }
  print("<P><TABLE CLASS=samstable>");
  print("<TR>");
  print("<TH><B>Filesystem</B>");
  print("<TH><B>Size</B>");
  print("<TH><B>Used</B>");
  print("<TH><B>Available</B>");
  print("<TH><B>Use%</B>");
  //������� � � � �
  print("<TH><B>Mount</B>");

  for($i=1;$i<$c+1;$i++)
     {
       $fs[0]=strtok($str[$i]," ");
       for($j=1;$j<6;$j++)
          {
            $fs[$j]=strtok(" ");

          }
       print("<TR>");
       print("<TD>$fs[0]");
       print("<TD>$fs[1]");
       print("<TD>$fs[2]");
       print("<TD>$fs[3]");
       print("<TD>$fs[4]");
 // ������� � � � 
      print("<TD>$fs[5]");
   }
  print("</TABLE>");

}



function SysInfo()
{
   PageTop("stat_48.jpg","System Information");

   $hostname=GetHostName();
   $ipaddr=GetIPAddr();
   $uptime=GetUpTime();
   print("<TABLE WIDTH=90%  CLASS=samstable>");
   print("<TR>");
   print("<TD WIDTH=\"25%\"><B>Hostname</B>");
   print("<TD WIDTH=\"75%\">$hostname");
   print("<TR>");
   print("<TD WIDTH=\"25%\"><B>IP addr</B>");
   print("<TD WIDTH=\"75%\">$ipaddr");
   print("<TR>");
   print("<TD WIDTH=\"25%\"><B>Uptime</B>");
   print("<TD WIDTH=\"75%\">$uptime");
   print("</TABLE>");

   MemoryUsage();
   FileSystemUsage();
   
  $syea=strftime("%Y");
  $smon=strftime("%m");
  $eday=strftime("%d");

  $sdate="$syea-$smon-1";
  $edate="$syea-$smon-$eday";
  $stime="0:00:00";
  $etime="0:00:00";
  
   print("<P><TABLE CLASS=samstable>\n");
   print("<TH>\n");
   print("<TH width=\"33%\" >All traffic\n");
   print("<TH width=\"33%\" >From cache\n");
   print("<TH width=\"33%\" >Traffic\n");
   
  $result=mysql_query("SELECT sum(size),sum(hit) FROM ".$SAMSConf->LOGDB.".cachesum WHERE date>=\"$sdate\"&&date<=\"$edate\" ");
  $row=mysql_fetch_array($result);
   print("<TR>\n");
   print("<TD > This month\n");
   $aaa=FormattedString("$row[0]");
   RTableCell($aaa,33);
   $aaa=FormattedString("$row[1]");
   RTableCell($aaa,33);
   $aaa=$row[0]-$row[1];
   $aaa=FormattedString($row[0]-$row[1]);
   RTableCell($aaa,33);
   
  $result=mysql_query("SELECT sum(size),sum(hit) FROM ".$SAMSConf->LOGDB.".cachesum WHERE date=\"$edate\" ");
  $row=mysql_fetch_array($result);
   print("<TR>\n");
   print("<TD > This day\n");
   $aaa=FormattedString("$row[0]");
   RTableCell($aaa,33);
   $aaa=FormattedString("$row[1]");
   RTableCell($aaa,33);
   $aaa=$row[0]-$row[1];
   $aaa=FormattedString($row[0]-$row[1]);
   RTableCell($aaa,33);
   
   print("</TABLE>\n");
   
}

 
function CUserDoc()
{
  global $SAMSConf;
   
  $lang="./lang/lang.$SAMSConf->LANG";
  require($lang);

  PageTop("user.jpg","$admintray_UserDoc_1");

  print("<H2>$admintray_UserDoc_2</H2>");
  print("</CENTER>");
  print("<IMG SRC=\"$SAMSConf->ICONSET/lframe.jpg\" ALIGN=LEFT>");
  print("$admintray_UserDoc_3");
  print("$admintray_UserDoc_4");
}


function WebConfigTray()
{
  global $SAMSConf;
  $lang="./lang/lang.$SAMSConf->LANG";
  require($lang);

  print("<SCRIPT>\n");
  if($SAMSConf->access==2)
    {       print("parent.basefrm.location.href=\"main.php?show=exe&function=sysinfo&filename=configtray.php\";\n");    }
  else
    {       print("parent.basefrm.location.href=\"main.php?show=exe&function=cuserdoc&filename=configtray.php\";\n");    }
  print("</SCRIPT> \n");

  print("<TABLE WIDTH=\"100%\" BORDER=0>\n");
  print("<TR>\n");
  print("<TD VALIGN=\"TOP\" WIDTH=\"30%\"\">");
  
  print("<B>$webconfigbuttom_1_prop_webconfigbuttom_1_propadmintray_1</B>\n");

      ExecuteFunctions("./src", "webconfigbuttom","1");

  print("<TD>\n");
  print("</TABLE>\n");


}

?>
