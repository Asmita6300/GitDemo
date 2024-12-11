<?php 
require_once('XTemplate/xtpl.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/ComboUtil.php');
require_once('include/utils.php');
require_once('include/uifromdbutil.php');

$limit=10;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$xtpl=new XTemplate("modules/Settings/GST.html");
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("Theme",$theme_path);

$query="SELECT * FROM services where deleted=0 order by serviceName";
$res=$adb->query($query);
if($adb->num_rows($res))
{
    foreach($res as $r)
    {
        $services_options .="<option value='".$r['id']."'>".$r['serviceName']."</option>";
    }
}
else
{
    $services_options .="<option value='0'>NO Services Available</option>";
}

$xtpl->assign("SERVICES_OPTION",$services_options);

$opt_yesno = "<option value='Y'>Yes</option>
<option value='N'>No</option>";
$xtpl->assign("OPT_YN",$opt_yesno);

$igst_arr=array(5,6,8,12,18,24,28);
foreach($igst_arr as $igst) {
    $igst_opt.="<option value='".$igst."'>".$igst."</option>";

}
$xtpl->assign("OPT_IGST",$igst_opt);

$gst_query="SELECT * from `bd_gst_master` WHERE `deleted`=0";
$gst_res=$adb->query($gst_query);
if($adb->num_rows($gst_res))
{
    $i=1;
    foreach($gst_res as $rs)
    {
        $service_query="SELECT `serviceName` as servicename FROM services where deleted=0 and id=".$rs['service_id'];

        $service_query_res=$adb->query($service_query);
        $service_name = $adb->query_result($service_query_res, 0, 'servicename');
                
        $gst_listing .="
            <tr id=".$rs['idbd_gst_master'].">
            <td style='height:50px;text-align:center;'>".$i."</td>
            <td style='height:50px;text-align:center;'>".$service_name."</td>
            <td style='height:50px;text-align:center;'>".$rs['hsn_code']."</td>
            <td style='height:50px;text-align:center;'>".$rs['sgst']."</td>
            <td style='height:50px;text-align:center;'>".$rs['cgst']."</td>
            <td style='height:50px;text-align:center;'>".$rs['igst']."</td>
            <td style='height:50px;text-align:center;'>".$rs['from_date']."</td>
            <td style='height:50px;text-align:center;'>".$rs['to_date']."</td>
            <td style='height:50px;text-align:center;'>
                <a onclick='editgst(".$rs['idbd_gst_master'].")' >Edit</a>
                &nbsp;|&nbsp;
                    <a onclick='delgst(".$rs['idbd_gst_master'].")' >Del</a>
                    <input type='hidden' name='gst_id' id='gst_id' value='".$rs['idbd_gst_master']."'>
                </td></tr>";
                 $i++;       
        }
}
else
{
    /* $gst_listing="<tr>
            <td colspan='9' style='text-align:center;'>
                No Records Found
            </td>
        </tr>"; */
}

$xtpl->assign("GST_LIST",$gst_listing);


$xtpl->parse("main");
$xtpl->out("main");

?>