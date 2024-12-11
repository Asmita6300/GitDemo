<?php


require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Users/User.php');
require_once('include/utils.php');
//require_once('include/database/PearDatabase.php');
require_once('modules/Users/UserInfoUtil.php');
global $current_user;
global $theme;
global $default_language;

global $app_strings;
global $mod_strings;
//global $adb;
$focus = new User();

$admin_array = array(119826,125453,8790,82613,125457,125459,125460,7358,125465,125463,125454,125461,125462,125464,125458,125455,125456,125953,127405);//sudhir
$rolequery="select id,hierarchy from users inner join user2role on users.id=user2role.userid inner join role on user2role.roleid=role.roleid where id=".$current_user->id." ";

$rquery_result=$adb->query($rolequery);


if(!empty($_REQUEST['record'])) {
        $focus->retrieve($_REQUEST['record']);
}
else
{
        header("Location: index.php?module=Users&action=ListView");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	$focus->id = "";
}

if(isset($_REQUEST['reset_preferences']))
{
	print_r($current_user->user_preferences);
	$current_user->resetPreferences();
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$role = fetchUserRole($focus->id);
$rolename =  getRoleName($role);
//the user might belong to multiple groups
if($focus->id != 1)
{
 $group = fetchUserGroups($focus->id);
}
$log->info("User detail view");

$xtpl=new XTemplate ('modules/Users/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("USER_NAME", $focus->user_name);
$xtpl->assign("FIRST_NAME", $focus->first_name);
$xtpl->assign("LAST_NAME", $focus->last_name);
$xtpl->assign("STATUS", $focus->status);
$xtpl->assign("YAHOO_ID", $focus->yahoo_id);
$xtpl->assign("DATE_FORMAT", $focus->date_format);
$xtpl->assign("TITLE", $focus->title);
if (isset($focus->yahoo_id) && $focus->yahoo_id !== "") $xtpl->assign("YAHOO_MESSENGER", "<a href='http://edit.yahoo.com/config/send_webmesg?.target=".$focus->yahoo_id."'><img border=0 src='http://opi.yahoo.com/online?u=".$focus->yahoo_id."'&m=g&t=2'></a>");
if ((is_admin($current_user) || $_REQUEST['record'] == $current_user->id)
		&& isset($default_user_name)
		&& $default_user_name == $focus->user_name
		&& isset($lock_default_user_name)
		&& $lock_default_user_name == true) {

$buttons = "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=EditView&return_module=Users&return_action=DetailView&return_id.value=".$focus->id."'>".$app_strings['LBL_EDIT_BUTTON_LABEL']."</a></td></tr>\n";
	
		//$buttons = "<tr><td><input title='".$app_strings['LBL_EDIT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_EDIT_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.return_id.value='$focus->id'; this.form.action.value='EditView'\" type='submit' name='Edit' value='  ".$app_strings['LBL_EDIT_BUTTON_LABEL']."  '></td></tr>\n";
}
elseif (is_admin($current_user) || $_REQUEST['record'] == $current_user->id) {
if(is_admin($current_user))
{
	$buttons = "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=EditView&return_module=Users&return_action=DetailView&return_id.value=".$focus->id."'>".$app_strings['LBL_EDIT_BUTTON_LABEL']."</a></td></tr>\n";
}

$teamcheck=vacantusers_inhierarchy($current_user->id,$default);
// start 0124140
$current_date = date('Y-m-d');
//$current_date = "2022-09-14";
$exception_start_date = "2022-09-12";
$exception_end_date = "2022-09-16";
if(!($current_date >= $exception_start_date && $current_date <= $exception_end_date))
{
	if((fetchUserProfileId($current_user->id) == 6) || (fetchUserProfileId($current_user->id) == 7) || (fetchUserProfileId($current_user->id) == 8)){

	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Contacts&action=UpdateAreas'>Update Doctor Areas</a></li></td></tr>\n";

	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Contacts&action=UpdateChemistAreas'>Update Chemist Areas</a></li></td></tr>\n";
	}
}
// end 0124140
if(fetchUserProfileId($current_user->id) == 1)//Ticket id:0083616
{
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Contacts&action=UpdateAreas'>Update Doctor Areas</a></li></td></tr>\n";
}
if($current_user->id == 12114 || $current_user->id == 12536){
$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=updation_list'>Data Update</a></li></td></tr>\n";
 }
 
 
 $idf = array(10169, 80963);
 
if(fetchUserProfileId($current_user->id) == 5 ||  (fetchUserProfileId($current_user->id) == 6) || (fetchUserProfileId($current_user->id) == 7) || (fetchUserProfileId($current_user->id) == 8) || in_array($current_user->id,$idf))
{
	//$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=ListTowns'>Town Master</a></li></td></tr>\n";
	
$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=ListBricks'>Patch Master</a></li></td></tr>\n";
}
 
 
 
if (fetchUserProfileId($current_user->id) == 6 || (fetchUserProfileId($current_user->id) == 7) || (fetchUserProfileId($current_user->id) == 8) )
{
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=HigherListTowns'>Town Adoption</a></li></td></tr>\n";
} 

 if(fetchUserProfileId($current_user->id)!= 21 && fetchUserProfileId($current_user->id)!= 22)
 {
//commented sudhir 2017-01-02 for blocking leave filing $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Leave&action=index'>Leave</a></li></td></tr>\n";
 }
 
//$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Expense&action=index'>Expense</a></li></td></tr>\n"; 
if(fetchUserProfileId($current_user->id)!= 21 && fetchUserProfileId($current_user->id)!= 22)
{
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Reports&action=index'>Reports</a></li></td></tr>\n";
}
	if($rolename!="HO")
	{
$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='#' onclick='return window.open(\"index.php?module=Users&action=ChangePassword&name=password&form=DetailView\",\"test\",\"width=320,height=230,resizable=1,scrollbars=1\");' >Change Password</a></li></td></tr>\n";
		
		
		//$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='#' onclick='return window.open(\"index.php?module=Users&action=ChangeSecurityQuestion\",\"test\",\"width=425,height=300,resizable=no,scrollbars=no,status=no\");' >Security Question</a></li></td></tr>\n";
	
	}	
$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&return_module=Users&action=ShowHistory&return_action=ShowHistory&return_id=$focus->id'>Login History</a></li></td></tr>\n";

	if($rolename=="HO"||$rolename=="SALESADMIN")
	{
		$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Inventory&action=index'>Issue Inventory</a></li></td></tr>\n";
	}
	
	
	
}

if (is_admin($current_user)) 
{
	
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&return_module=Users&action=EditView&return_action=DetailView&.return_id.value='".$_REQUEST['record']."'>".$app_strings['LBL_DUPLICATE_BUTTON_LABEL']."</a></li></td></tr>\n";
	
	//done so that only the admin user can see the customize tab button
	if($_REQUEST['record'] == $current_user->id)
	{	
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&return_module=Users&action=TabCustomise&return_action=TabCustomise'>".$app_strings['LBL_TABCUSTOMISE_BUTTON_LABEL']."</a></li></td></tr>\n";
	}
	if($_REQUEST['record'] != $current_user->id)
	{
	$buttons .= "<tr><td><input title='".$app_strings['LBL_DELETE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_DELETE_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.return_id.value='$focus->id'; this.form.action.value='UserDeleteStep1'\" type='submit' name='Delete' value='  ".$app_strings['LBL_DELETE_BUTTON_LABEL']."  '></td></tr>\n";
	}

        
	if($_SESSION['authenticated_user_roleid'] == 'administrator')
	{
	 $buttons .= "<tr><td><input title='".$app_strings['LBL_LISTROLES_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_LISTROLES_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='TabCustomise'; this.form.action.value='listroles'; this.form.record.value= '". $current_user->id ."'\" type='submit' name='ListRoles' value=' ".$app_strings['LBL_LISTROLES_BUTTON_LABEL']." '></td></tr>\n";
	}
}
// BD module master and budget creation 2019 for marketing managers only
/* if(fetchUserProfileId($current_user->id)== 21 || $current_user->id == 12114) 
{
	$buttons .="<tr><td><li><a href='index.php?module=Settings&action=Groups'>Create Groups(New)</a></li></td></tr>";

    $buttons .="<tr><td><li><a href='index.php?module=Settings&action=Activites'>Create Activity(New)</a></li></td></tr>";

	$buttons .="<tr><td><li><a href='index.php?module=Settings&action=BudgetCreation'>Create Budget(New)</a></li></td></tr>";
} */
//End


//$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&return_module=Users&action=UserFinanceDetails&return_action=DetailView&record=$current_user->id '>My Finance</a></li></td><tr>\n";
/*
if($adb->query_result($rquery_result,0,'hierarchy')!=6)

	{
		$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=NewUser&action=index'>TDS Entry</a></li></td></tr>\n";
	}
*/	


// Modification by Venom
if (fetchUserProfileId($current_user->id) != 5 && fetchUserProfileId($current_user->id)!= 21 && fetchUserProfileId($current_user->id)!= 22)
{
$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=OrgSMS&action=index'>Organogram</a></li></td></tr>\n";
}
if (fetchUserProfileId($current_user->id) == 5 || fetchUserProfileId($current_user->id)== 21 || fetchUserProfileId($current_user->id)== 22) {
    
} else {
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=OrgSMS&action=statusview'>Status Change</a></li></td></tr>\n";
	
}

	if(fetchUserProfileId($current_user->id) >= 7 && fetchUserProfileId($current_user->id)!= 21 && fetchUserProfileId($current_user->id)!= 22)
	{
		$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=LoginHistory&action=ActiveInactiveUser'>Active Inactive User</a></li></td></tr>\n";
	}
/* added sudhir for tkt 0346841 */
if(fetchUserProfileId($current_user->id)== 21 || $current_user->id == 80950)//mdfd by sudhir for tkt 0349013
	{
 $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=addpreapprovedactivity'>Pre-approved activity Upload</a></li></td></tr>\n";
		
	
	}
	/* end added sudhir for tkt 0346841 */

if($current_user->title!="SALESADMIN" && fetchUserProfileId($current_user->id)!= 21 && fetchUserProfileId($current_user->id)!= 22)
{
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=UserWiseListTemplateSearchForm'>Holiday List</a></li></td></tr>\n";
}

if($current_user->id == 10138 || $current_user->id == 7538 || $current_user->id == 7359)
{
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=TourPlans&action=masterSearch'>SRC Master Search</a></li></td></tr>\n";
}

if($current_user->title=="TD" || $current_user->title=="SALESADMIN")
{
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Contacts&action=trainingEditView'>Training Details</a></li></td></tr>\n";
}



if($current_user->title=="SALESADMIN")
{
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=Editgreetings'>Edit Greetings</a></li></td></tr>\n";
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Message&action=index'>Scroll Message</a></li></td></tr>\n";
	//$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=ListDepartment'>Department</a></li></td></tr>\n";
}


// if(fetchUserProfileId($current_user->id)==8 || $current_user->title=='SALESADMIN')
// {
	// $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=exception_scorecard'>Operational Score Card</a></li></td></tr>\n";
// }


if($current_user->title=="SALESADMIN")
{
$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=ListDepartment'>Department</a></li></td></tr>\n";
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=ListQueryType'>Query Type</a></li></td></tr>\n";
	
	

}





$buttons .= "<!--tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=hotel'>Hotel Booking</a></li></td></tr-->\n";

$buttons .= "<!--tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=edit_hotel_booking'>Edit Hotel booking </a></li></td></tr-->\n";

$buttons .= "<!--tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=vehicle'>Vehicle Booking</a></li></td></tr-->\n";

$buttons .= "<!--tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=edit_vehicle_booking'>Edit vehicle booking </a></li></td></tr-->\n";

$buttons .= "<!--tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=purchase_book'>Purchase Book</a></li></td></tr-->\n";


$buttons .= "<!--tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=edit_purchase_book'>Edit Purchase Book</a></li></td></tr-->\n";

$buttons .= "<!--tr><td bgcolor='#F5F5F5' valign='center'><li> <a href='index.php?module=Users&action=purchase_equipment'>Purchase Equipment</a></li></td></tr-->\n";

$buttons .= "<!--tr><td bgcolor='#F5F5F5' valign='center'><li> <a href='index.php?module=Users&action=edit_purchase_equipment'>Edit Purchase Equipment</a></li></td></tr-->\n";

$buttons .= "<!--tr><td bgcolor='#F5F5F5' valign='center'><li> <a href='index.php?module=Users&action=upward_downward'>Upward Downward</a></li></td></tr-->\n";

$buttons .= "<!--tr><td bgcolor='#F5F5F5' valign='center'><li> <a href='index.php?module=Users&action=edit_upward_downward'>Edit Upward Downward</a></li></td></tr-->\n";

if(fetchUserProfileId($current_user->id) !=5 && fetchUserProfileId($current_user->id) !=6 && fetchUserProfileId($current_user->id) !=7 && fetchUserProfileId($current_user->id) !=8 && fetchUserProfileId($current_user->id)!= 21 && fetchUserProfileId($current_user->id)!= 22)
{
	// $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=TownListView'>Town Master</a></li></td></tr>\n";
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=TourPlans&action=MastersfcEditView'>SRC Update</a></li></td></tr>\n";
}
/**added by ankita on 22-03-2016**/
/* if(fetchUserProfileId($current_user->id) == 21 || fetchUserProfileId($current_user->id)== 22)
{
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?action=CampaignActivitylistview1&module=Dcrs'>Campaign Master</a></li></td></tr>\n";
	
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=uploadimage'>Upload Image</a></li></td></tr>\n";
	
	
} */


//$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=NewTownRequest'>New Town Request</a></li></td></tr>\n";


if(fetchUserProfileId($current_user->id)== 21 || $current_user->id == 80950)//0088400
	{
 $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Dcrs&action=MylistCamplistview'>Camp Creation</a></li></td></tr>\n";
		
	
	}


if($current_user->id== 123802)// added for tkt 0028224 09.07.2020
{
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=uploadimage'>Upload Image</a></li></td></tr>\n";
}

if($current_user->ho_user==1 && fetchUserProfileId($current_user->id)!= 21 && fetchUserProfileId($current_user->id)!= 22)
{
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=NewTownApproval'>New Town Approval</a></li></td></tr>\n";
}

//for tck 0407373: DCR Unlocking option is not present: enable for profile 9.
if (((fetchUserProfileId($current_user->id) == 7) || (fetchUserProfileId($current_user->id) == 19) ||(fetchUserProfileId($current_user->id) == 23) || (fetchUserProfileId($current_user->id) == 8) || (fetchUserProfileId($current_user->id) == 1) || $current_user->ho_user=='1' || $current_user->title=='NSM' || (fetchUserProfileId($current_user->id) == 9) || $current_user->id == 7352 || $current_user->id == 7355 || $current_user->id == 120164) && (fetchUserProfileId($current_user->id)!= 21) && fetchUserProfileId($current_user->id)!= 22 || $current_user->title=='AGM') {
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=unlockusers'>DCR Unlocking System</a></li></td></tr>\n";
}
if ((fetchUserProfileId($current_user->id) == 1 || $current_user->id == 7358 || $current_user->id == 7359 || $current_user->id == 8790 || $current_user->id == 8795 || $current_user->id == 8942 || $current_user->id == 10138 || $current_user->id == 12533 || $current_user->id == 12534 || $current_user->id == 12535 || $current_user->id == 12536 || $current_user->id == 119826 || $current_user->id == 119827 || $current_user->id == 82613 || in_array($current_user->id,$admin_array))  && fetchUserProfileId($current_user->id)!= 21 && fetchUserProfileId($current_user->id)!= 22) {
	//modified sudhir added in_array($current_user->id,$admin_array)
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=stationchange'>Station Type Change</a></li></td></tr>\n";
} 

/* commented by suchitra told by girish on 20-11-13
if ((fetchUserProfileId($current_user->id)==1) || (fetchUserProfileId($current_user->id)==18) || (fetchUserProfileId($current_user->id)==24) || (fetchUserProfileId($current_user->id)==21)) {
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=vacantuser'>Mark vacant</a></li></td></tr>\n";
}  */

if ($current_user->id == 7538) {
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=vacantuser'>Mark vacant</a></li></td></tr>\n";
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Contacts&action=DoctorDetails'>Doctor Details</a></li></td></tr>\n";
} 

$iid=array(82346,10136,82347,82345,12535,82348);
$paiid=array(126171);
$dcrdel=array(130563,10134,82674,82663,120973,131121,121407,125451,123801,7355,125476,123797);

if (fetchUserProfileId($current_user->id) == 1 || fetchUserProfileId($current_user->id) == 7 || fetchUserProfileId($current_user->id) == 8)  {
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=secondarysalesedit'>Secondary Sales Edit(Previous one month))</a></li></td></tr>\n";
}

if (fetchUserProfileId($current_user->id) == 7 || fetchUserProfileId($current_user->id) == 8 || (in_array($current_user->id,$dcrdel)))  {
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Settings&action=dailyexpensedeletion'>DCR, Daily Expense Deletion</a></li></td></tr>\n";
}


if ((fetchUserProfileId($current_user->id)==1) || (in_array($current_user->id,$iid)) || (in_array($current_user->id,$paiid))) {
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=esv'>ESV Update</a></li></td></tr>\n";
} 

if ((fetchUserProfileId($current_user->id)==1) || (in_array($current_user->id,$iid))) {
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=svm_valid'>Multiple Town Working</a></li></td></tr>\n";
} 
//Commented For Ticket : 0394582 if ((fetchUserProfileId($current_user->id)==1) || (in_array($current_user->id,$iid))) {
	if ((fetchUserProfileId($current_user->id)==1) || (in_array($current_user->id,$iid)) || (fetchUserProfileId($current_user->id)==8) || (fetchUserProfileId($current_user->id)==9) || (fetchUserProfileId($current_user->id)==10)) {  /*Added For Ticket : 0394582*/
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=change_of_event_date'>Changing Event Date</a></li></td></tr>\n";
} 

 if(($current_user->id==7538) || ($current_user->id==10138)) {
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=club_town'>Clubbing of town</a></li></td></tr>\n";
} 
 if(($current_user->id==7538) || ($current_user->id==12536)) {
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=upd_item'>Add/Update Item</a></li></td></tr>\n";
	 $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=activateBDreq'>Activate Business Development Request</a></li></td></tr>\n";
}
 if(($current_user->id==12536)) {
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=businessdevlopupld'>BD Upload</a></li></td></tr>\n";
}
if($current_user->id==7538 || $current_user->id==12114 || $current_user->id==10138 || $current_user->id==120974) {
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Settings&action=UploadDataListView'>Meeting Activity User Wise Upload</a></li></td></tr>\n";
	
	
}
//if ( $_REQUEST["payslip"]==1 && (fetchUserProfileId($current_user->id) == 5 || fetchUserProfileId($current_user->id) == 6 || fetchUserProfileId($current_user->id) == 7) )
if ( $_REQUEST["payslip"]==1)
{  



     $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=payslip'>Payslip</a></li></td></tr>\n";
 

}




if (fetchUserProfileId($current_user->id) == 18 || fetchUserProfileId($current_user->id) == 22 || $current_user->id == 7538 || $current_user->id == 12114 || $current_user->id == 8786)  {
    $buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=createnew_grp'>Create Group   (Circulars/Imails)</a></li></td></tr>\n";
}	
	// BD module master and budget creation 2019 for marketing managers only
	if($current_user->id == 7538 || $current_user->id == 12114 || $current_user->id == 8786 || $current_user->title == 'Admin'){
	$buttons .="<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Settings&action=Groups'>Create Groups(New)</a></li></td></tr>";
	$buttons .="<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Settings&action=Activites'>Create Activity(New)</a></li></td></tr>";	
	$buttons .="<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Settings&action=BudgetCreation'>Create Budget(New)</a></li></td></tr>";}
	

	//rahul 2021-02-23 providing Activity Master access to users
	if($current_user->id == 12536 /*|| $current_user->id == 12114*/)
	{
		$buttons .="<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Settings&action=Activites'>Create Activity(New)</a></li></td></tr>";
	}
	//rahul


	if($current_user->id == 12114){
		$buttons .="<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?action=VC_search_listing_admin&module=Dashboard'>Form Builder</a></li></td></tr>";
	}
	
	//END
 // End of modification by VenoM

	$buttons .="<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Settings&action=vendorRegistration'>Vendor Registration</a></li></td></tr>";


	if(fetchUserProfileId($current_user->id) >= 6 || $current_user->id==12536 || $current_user->id==130370 ) {
		$buttons .="<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Settings&action=doctorRegistration'>Doctor Registration</a></li></td></tr>";
}



	if($current_user->id==12536 || $current_user->id==130370)
	{
		$buttons .="<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Settings&action=AdminVendorApproval'>Vendor Approval</a></li></td></tr>";
		$buttons .="<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Settings&action=AdminDoctorApproval'>Doctor Approval</a></li></td></tr>";
		$buttons .="<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Settings&action=TDS'>TDS Master</a></li></td></tr>";
		$buttons .="<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Settings&action=GST'>GST Master</a></li></td></tr>";
	}
	//add by rahul sakhalkar on 17/10/22 Ticket id :0129576
	if($current_user->id==8786 || fetchUserProfileId($current_user->id) >= 7 /*|| fetchUserProfileId($current_user->id) == 8 || fetchUserProfileId($current_user->id) == 9*/)
	{
		$buttons .="<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Settings&action=AdminDoctorApproval'>Doctor Approval</a></li></td></tr>";
	}
	//add by rahul sakhalkar on 17/10/22 Ticket id :0129576
	
//vishnu20180306
if(fetchUserProfileId($current_user->id) == 21)
{
	$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Dcrs&action=MylistActivitylistview1'>My Activity List Master</a></li></td></tr>\n";
}

/* added sudhir for ticket 0313221 */
if($current_user->id == 125452)
{

$buttons = "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&return_module=Users&action=ShowHistory&return_action=ShowHistory&return_id=$focus->id'>Login History</a></li></td></tr>\n";
$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Reports&action=index'>Reports</a></li></td></tr>\n";
$buttons .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='#' onclick='return window.open(\"index.php?module=Users&action=ChangePassword&name=password&form=DetailView\",\"test\",\"width=320,height=230,resizable=1,scrollbars=1\");' >Change Password</a></li></td></tr>\n";
}

/* end added sudhir */

/*added aby rahul */
$profile_id=fetchUserProfileId($current_user->id);
$sql_permission_check="SELECT COUNT(id) AS pcount FROM link_availability_master WHERE deleted=0 AND module='Users' AND action='townRestriction' AND profileid='".$profile_id."'";
$sql_permission_res=$adb->query($sql_permission_check) or die("Permission check failed: ".mysql_error());
$pcount=$adb->query_result($sql_permission_res,0,"pcount");
if($pcount>0){
	$buttons .="<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=townRestriction'>Controls for Towns/Activity</a></li></td></tr>";
}
/* end adding by rahul */

//doc profile
$buttons .="<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=DoctorProfile'>One Page Doctor Profile</a></li></td></tr>";


if (isset($buttons)) $xtpl->assign("BUTTONS", $buttons);


$profileid=fetchUserProfileId($current_user->id);
if($profileid==5 || $profileid==6 || $profileid==7 || $profileid==8)
	{
		$content_bt = "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Dcrs&action=ListingContentSurfer_multi'>Content Surfer</a></li></td></tr>\n";
		$content_bt .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Dcrs&action=ListDoctorQuery'>Doctor Query</a></li></td></tr>\n";
		
	}
if($profileid==1 || $profileid==21)
			{
				$content_bt .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Settings&action=ContentListing'>Content Upload</a></li></td></tr>\n";
			}

//town activity restriction
$checkprofile=array(6,7,8,9,19,20,23,18);			
if(in_array($profileid,$checkprofile))
{
	$content_bt .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=townRestrictionApprovalListview'>Towns Control Workflow Approval</a></li></td></tr>\n";
}

//town activity restriction
$checkprofile=array(1,5,6,7,8,9);			
if(in_array($profileid,$checkprofile))
{
	$content_bt .= "<tr><td bgcolor='#F5F5F5' valign='center'><li><a href='index.php?module=Users&action=townControlException'>Exception For Town Control Restriction</a></li></td></tr>\n";

}

if (isset($content_bt)) $xtpl->assign("CONTENT_BUTTON", $content_bt);




$xtpl->parse("main");
$xtpl->out("main");

if ((is_admin($current_user) || $_REQUEST['record'] == $current_user->id) && $focus->is_admin == 'on') {
	$xtpl->assign("IS_ADMIN", "checked");
	$xtpl->parse("user_settings");
	$xtpl->out("user_settings");
}

$xtpl->assign("DESCRIPTION", nl2br($focus->description));
if(is_admin($current_user))
{
	$xtpl->assign("ROLEASSIGNED","<a href=index.php?module=Users&action=RoleDetailView&roleid=".$role .">" .$rolename ."</a>");
}


if(is_admin($current_user))
{
	$xtpl->assign("GROUPASSIGNED","<a href='index.php?module=Users&action=UserInfoUtil&groupname=".$group."'>".$group."</a>");
}

else
{
	$xtpl->assign("GROUPASSIGNED",$group);
}

$sql="select * from division where divisionid='".$focus->division."'";
$ressql=$adb->query($sql);
$result=$adb->query_result($ressql,0,"desc");

$sqlstate="select * from state where stateid='".$focus->address_state."'";
$ressqlstate=$adb->query($sqlstate);
$resultsqlstate=$adb->query_result($ressqlstate,0,"statename");

$sqlcity="select * from city where cityid='".$focus->address_city."'";
$ressqlcity=$adb->query($sqlcity);
$resultsqlcity=$adb->query_result($ressqlcity,0,"cityname");

$sqlstatepermanent="select * from state where stateid='".$focus->permanent_state."'";
$ressqlstatepermanent=$adb->query($sqlstatepermanent);
$resultsqlstatepermanent=$adb->query_result($ressqlstatepermanent,0,"statename");

$sqlcitypermanent="select * from city where cityid='".$focus->permanent_city."'";
$ressqlcitypermanent=$adb->query($sqlcitypermanent);
$resultsqlcitypermanent=$adb->query_result($ressqlcitypermanent,0,"cityname");

$sqlpassportmaster="select * from passportmaster where user_id='".$focus->id."'";
$respassportmaster=$adb->query($sqlpassportmaster);
$passport_no=$adb->query_result($respassportmaster,0,"passport_no");
$passport_name=$adb->query_result($respassportmaster,0,"passport_name");
$dateofissue=$adb->query_result($respassportmaster,0,"dateofissue");
$dateofexpiry=$adb->query_result($respassportmaster,0,"dateofexpiry");

$xtpl->assign("TITLE", $focus->title);
$xtpl->assign("DEPARTMENT", $focus->department);
$xtpl->assign("DIVISION", $result);
$xtpl->assign("CREATION_DATE", $focus->creation_date);
$xtpl->assign("REPORTS_TO_ID", $focus->reports_to_id);
$xtpl->assign("REPORTS_TO_NAME", $focus->reports_to_name);
$xtpl->assign("PHONE_HOME", $focus->phone_home);
$xtpl->assign("PHONE_MOBILE", $focus->phone_mobile);
$xtpl->assign("PHONE_WORK", $focus->phone_work);
$xtpl->assign("PHONE_OTHER", $focus->phone_other);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
$xtpl->assign("ADDRESS_STREET", $focus->address_street);
$xtpl->assign("ADDRESS_CITY", $resultsqlcity);
$xtpl->assign("ADDRESS_STATE", $resultsqlstate);
$xtpl->assign("ADDRESS_POSTALCODE", $focus->address_postalcode);
$xtpl->assign("ADDRESS_COUNTRY", $focus->address_country);



$xtpl->assign("ADDRESS_STREET_PERMANENT", $focus->permanent_street);
$xtpl->assign("ADDRESS_CITY_PERMANENT", $resultsqlcitypermanent);
$xtpl->assign("ADDRESS_STATE_PERMANENT", $resultsqlstatepermanent);
$xtpl->assign("ADDRESS_POSTALCODE_PERMANENT", $focus->permanent_pincode);
$xtpl->assign("ADDRESS_COUNTRY_PERMANENT", $focus->permanent_country);
$xtpl->assign("SIGNATURE", nl2br($focus->signature));

if($dateofissue=="" || $dateofissue=="0000-00-00")
$dateofissue = "";
else
$dateofissue=date("d-M-Y",strtotime($dateofissue));

if($dateofexpiry=="" || $dateofexpiry=="0000-00-00")
$dateofexpiry = "";
else
$dateofexpiry=date("d-M-Y",strtotime($dateofexpiry));

$xtpl->assign("PASSPORT_NUM", $passport_no);
$xtpl->assign("PASSPORT_NAME", $passport_name);
$xtpl->assign("PASSPORT_ISSUE_DATE", $dateofissue);
$xtpl->assign("PASSPORT_EXPIRY_DATE", $dateofexpiry);

$xtpl->parse("user_info");
$xtpl->out("user_info");


echo "</td></tr>\n";


?>
