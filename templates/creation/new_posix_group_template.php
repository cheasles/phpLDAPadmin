<?php
// $Header: /cvsroot/phpldapadmin/phpldapadmin/templates/creation/new_posix_group_template.php,v 1.10 2005/03/05 06:27:07 wurley Exp $

// Common to all templates
$container = $_POST['container'];
$server_id = $_POST['server_id'];

// Change this to suit your needs
$default_number_of_users = 10;

$step = 1;
if( isset($_POST['step']) )
    $step = $_POST['step'];

check_server_id( $server_id ) or pla_error( "Bad server_id: " . htmlspecialchars( $server_id ) );
have_auth_info( $server_id ) or pla_error( "Not enough information to login to server. Please check your configuration." );

?>

<center><h2>New Posix Group</h2></center>

<?php if( $step == 1 ) { ?>

<form action="creation_template.php" method="post" name="posix_group_form">
<input type="hidden" name="step" value="2" />
<input type="hidden" name="server_id" value="<?php echo $server_id; ?>" />
<input type="hidden" name="template" value="<?php echo htmlspecialchars( $_POST['template'] ); ?>" />

<center>
<table class="confirm">
<tr>
	<td></td>
	<td class="heading">Posix Group Name:</td>
	<td><input type="text" name="posix_group_name" value="" /> <small>(example: MyGroup, do not include "cn=")</small></td>
</tr>
<tr>
	<td></td>
	<td class="heading"><acronym title="Group Identification">GID</acronym> Number:</td>
	<td><input type="text" name="gid_number" value="" /> <small>(example: 2000)</small></td>
</tr>
<tr>
	<td></td>
	<td class="heading">Container <acronym title="Distinguished Name">DN</acronym>:</td>
	<td><input type="text" name="container" size="40" value="<?php echo htmlspecialchars( $container ); ?>" />
		<?php draw_chooser_link( 'posix_group_form.container' ); ?>
	</td>
</tr>
<tr>
	<td></td>
	<td class="heading" style="vertical-align:top">Users:</td>
    <td>
<?php for( $i=0; $i<=$default_number_of_users; $i++ ) { ?>
	<input type="text" name="member_uids[<?php echo $i; ?>]" id="member_uids_<?php echo $i; ?>" value="" /><?php draw_chooser_link( "posix_group_form.member_uids_$i", false ); ?><br />
<?php } ?>
	</td>
</tr>
<tr>
	<td colspan="3"><center><br /><input type="submit" value="Proceed &gt;&gt;" /></center></td>
</tr>
</table>
</center>
</form>

<?php } elseif( $step == 2 ) {

	$group_name = trim( $_POST['posix_group_name'] );
    $group_name or pla_error( "You left the group name blank. Please go back and give the group a name." );
	$container = trim( $_POST['container'] );
    $container or pla_error( "You left the container DN blank. Please go back and give the group a container DN." );
	$gid_number = trim( $_POST['gid_number'] );
    $gid_number or pla_error( "You left the group GID Number blank. Please go back and give the group a GID Number." );
	$uids = $_POST['member_uids'];
	$member_uids = array();
	foreach( $uids as $uid )
		if( '' != trim( $uid ) && ! in_array( $uid, $member_uids ) )
			$member_uids[] = $uid;
	
	dn_exists( $ldapserver, $container ) or
		pla_error( "The container you specified (" . htmlspecialchars( $container ) . ") does not exist. " .
	       		       "Please go back and try again." );

	?>
	<form action="create.php" method="post">
	<input type="hidden" name="server_id" value="<?php echo $server_id; ?>" />
	<input type="hidden" name="new_dn" value="<?php echo htmlspecialchars( 'cn='.$group_name.','.$container ); ?>" />

	<!-- ObjectClasses  -->
	<?php $object_classes = rawurlencode( serialize( array( 'top', 'posixGroup' ) ) ); ?>

	<input type="hidden" name="object_classes" value="<?php echo $object_classes; ?>" />
		
	<!-- The array of attributes/values -->
	<input type="hidden" name="attrs[]" value="cn" />
		<input type="hidden" name="vals[]" value="<?php echo htmlspecialchars($group_name);?>" />
	<input type="hidden" name="attrs[]" value="gidNumber" />
		<input type="hidden" name="vals[]" value="<?php echo htmlspecialchars($gid_number);?>" />
	<?php foreach( $member_uids as $uid ) { ?>
	<input type="hidden" name="attrs[]" value="memberUid" />
		<input type="hidden" name="vals[]" value="<?php echo htmlspecialchars($uid);?>" />
	<?php } ?>

	<center>
	Really create this new Posix Group entry?<br />
	<br />

	<table class="confirm">
	<tr class="even"><td>Name</td><td><b><?php echo htmlspecialchars($group_name); ?></b></td></tr>
	<tr class="odd"><td>Container</td><td><b><?php echo htmlspecialchars( $container ); ?></b></td></tr>
	<tr class="even"><td>gidNumber</td><td><b><?php echo htmlspecialchars( $gid_number ); ?></b></td></tr>
	<tr class="odd"><td>Member UIDs</td><td><b>
	<?php foreach( $member_uids as $i => $uid ) 
		echo htmlspecialchars($uid) . "<br />"; ?>
		</b></td></tr>
	</table>
	<br /><input type="submit" value="Create Group" />
	</center>
	</form>

<?php } ?>
