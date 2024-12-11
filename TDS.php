<?php
require_once('XTemplate/xtpl.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/ComboUtil.php');
require_once('include/utils.php');
require_once('include/uifromdbutil.php');

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$xtpl=new XTemplate("modules/Settings/TDS.html");
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("Theme",$theme_path);

$query="SELECT * FROM bd_tds_master where deleted=0";
$res=$adb->query($query);
$i=1;
if($adb->num_rows($res))
{ 
    foreach($res as $rs)
    {

        $service_id=$rs['service_id'];
        $service_query="SELECT * FROM services where id='$service_id' and deleted=0";
        $service_query_res=$adb->query($service_query);
        // print_r($service_query_res);
        // $service_name=$adb->query_result($service_query_res,0,'serviceName');
        foreach($service_query_res as $sqr)
        {
            $service_name=$sqr['serviceName'];
        }

        $ac_code=$rs['account_code'];

        $account_query="SELECT * FROM localactivity_expensewiseaccount WHERE id=$ac_code and deleted=0";
       
        $account_res=$adb->query($account_query);
        if($adb->num_rows($account_res))
        {
            foreach($account_res as $ar)
            {
                $acc_code=$ar['account_code'];
            }
        }
        else
        {
            $acc_code="";
        }
    

               
        $rows.="<tr id=".$rs['idbd_tds_master'].">
        <td style='height:50px;'>".$i."</td>
        <td style='height:50px;'>".$rs['tds_code']."</td>
        <td style='height:50px;'>".$service_name."</td>
        <td style='height:50px;'>".$acc_code."</td>
        <td style='height:50px;'>".$rs['fourth_word_pan']."</td>
        <td style='height:50px;'>".$rs['tds_rate']."</td>        
        <td style='height:50px;'>".$rs['from_date']."</td>
        <td style='height:50px;'>".$rs['to_date']."</td>
        <td style='height:50px;'>
        <a onclick='edittds(".$rs['idbd_tds_master'].")' >Edit</a>&nbsp;|&nbsp;
        <a onclick='deltds(".$rs['idbd_tds_master'].")' >Del</a>
        <input type='hidden' name='tdsid' id='tdsid' value='".$rs['idbd_tds_master']."'>
        </td>
    </tr>";
    $i++;
    }
    }else 
    {
   /* $rows="<tr><td colspan='9' style='text-align:center;'>No TDS found</td></tr>"; */
}

$xtpl->assign("pages",$adb->num_rows($res));
$xtpl->assign("total",$adb->num_rows($res));
$xtpl->assign("TDS_LIST",$rows);

$acc_query="SELECT * FROM localactivity_expensewiseaccount WHERE deleted=0";
$acc_res=$adb->query($acc_query);
if($adb->num_rows($acc_res))
{
    foreach($acc_res as $ar)
    {
        $acc_options .="<option value='".$ar['id']."'>".$ar['account_code']."</option>";
    }
}
else
{
    $acc_options .="<option value='0'>No Account code available</option>";
}
$xtpl->assign("ACCOUNT_CODE",$acc_options);

$services_query="SELECT * FROM services where deleted=0";
$services_query_res=$adb->query($services_query);
if($adb->num_rows($services_query_res))
{
    foreach($services_query_res as $sqr)
    {
        $services_options .="<option value='".$sqr['id']."'>".$sqr['serviceName']."</option>";
    }
}
else
{
    $services_options .="<option value='0'>No Services available</option>";
}
$xtpl->assign("SERVICEOPTIONS",$services_options);

$taxtype_options = "<option value='S'>S</option>
<option value='R'>R</option>";

$taxcriteria_options = "<option value='I'>I</option>
<option value='C'>C</option>
<option value='B'>B</option>";

$xtpl->assign("TDS_TAX_TYPE_OPT",$taxtype_options);
$xtpl->assign("TDS_TAX_CRITERIA_OPT",$taxcriteria_options);

$xtpl->parse("main");
$xtpl->out("main");

?>