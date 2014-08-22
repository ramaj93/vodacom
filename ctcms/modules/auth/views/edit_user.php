<?php //To do enable ordering of objects  ?>
<script type="text/javascript">
    $(function() {
        $(".user-role .btn").click(function() {
            // whenever a button is clicked, set the hidden helper
            $("#role").val($(this).val());
        });
    });
</script>
<!-- Errors -->
<?php
if ($message) {
    echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
}
?>
<h1>Edit User</h1>
<p><?php echo $this->lang->line("update_user_info"); ?></p>
<?php
$first_name = array(
    'name' => 'first_name',
    'id' => 'first_name',
    'placeholder' => "First Name",
    'value' => $user->first_name,
    'class' => 'span4'
);
$last_name = array(
    'name' => 'last_name',
    'id' => 'last_name',
    'value' => $user->last_name,
    'class' => 'span4'
);
$company = array(
    'name' => 'company',
    'id' => 'company',
    'value' => $user->company,
    'class' => 'span4',
);
$phone = array(
    'name' => 'phone',
    'id' => 'phone',
    'value' => $user->phone,
    'class' => 'span4',
);
$email = array(
    'name' => 'email',
    'id' => 'email',
    'value' => $user->email,
    'class' => 'span4',
);
?>
<?php
$attrib = array('class' => 'form-horizontal');
echo form_open("module=auth&view=edit_user&id=" . $id, $attrib);
?>
<div class="control-group">
    <label class="control-label" for="first_name"><?php echo $this->lang->line("first_name"); ?></label>
    <div class="controls"> <?php echo form_input($first_name); ?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="last_name"><?php echo $this->lang->line("last_name"); ?></label>
    <div class="controls"> <?php echo form_input($last_name); ?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="phone"><?php echo $this->lang->line("phone"); ?></label>
    <div class="controls"> <?php echo form_input($phone); ?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="email"><?php echo $this->lang->line("email_address"); ?></label>
    <div class="controls"> <?php echo form_input($email); ?> </div>
</div>

<div class="control-group">
    <label class="control-label" for="phone"><?php echo $this->lang->line("user_role"); ?></label>
    <div class="controls">
        <div class="btn-group user-role" data-toggle="buttons-radio">
            <button type="button" value="1" class="btn <?php
            if ($group->group_id == '1') {
                echo "active";
            } if (isset($_POST['submit']) && ($_POST['role'] == '1')) {
                echo "active";
            }
            ?>"><?php echo $this->lang->line("admin"); ?>
            </button>
            <button type="button" value="3" class="btn <?php
            if ($group->group_id == '3') {
                echo "active";
            } if (isset($_POST['submit']) && ($_POST['role'] == '3')) {
                echo "active";
            }
            ?>"><?php echo $this->lang->line("configuration"); ?>
            </button>
            <button type="button" value="4" class="btn <?php
            if ($group->group_id == '4') {
                echo "active";
            } if (isset($_POST['submit']) && ($_POST['role'] == '4')) {
                echo "active";
            }
            ?>"><?php echo $this->lang->line("project"); ?>
            </button>
            <button type="button" value="2" class="btn <?php
            if ($group->group_id == '2') {
                echo "active";
            } if (isset($_POST['submit']) && ($_POST['role'] == '2')) {
                echo "active";
            }
            ?>"><?php echo $this->lang->line("user"); ?>
            </button>
        </div>
        <input type="hidden" name="role" id="role" value="<?php echo $group->group_id; ?>">
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="department"><?php echo $this->lang->line("department"); ?></label>
    <div class="controls">  <?php
        $dpt = array();
        foreach ($depts as $dept) {
            $dpt[$dept->id] = $dept->name;
        }
        echo form_dropdown('department', $dpt, (isset($_POST['department']) ? $_POST['department'] : $deptid), 'id="department" class="span4" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("customer") . '" required="required" data-error="' . $this->lang->line("customer") . ' ' . $this->lang->line("is_required") . '"');
        ?> </div>
</div>
<ul class="dropdown-menu">
    <?php
    foreach ($warehouses as $warehouse) {
        echo "<li><a href='index.php?module=products&view=warehouse&warehouse_id=" . $warehouse->id . "'>" . $warehouse->name . "</a></li>";
    }
    ?>
</ul>
<div class="control-group">
    <label class="control-label" for="password"><?php echo $this->lang->line("pw"); ?></label>
    <div class="controls"> <?php echo form_input($password, '', 'class="password span4" id="password"'); ?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="confirm_pw"><?php echo $this->lang->line("confirm_pw"); ?></label>
    <div class="controls"> <?php echo form_input($password_confirm, '', 'class="password span4" id="confirm_pw"'); ?> </div>
</div>
<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("update_user"), 'class="btn btn-primary"'); ?> </div>
</div>
<ul class="dropdown-menu">
    <?php
    foreach ($warehouses as $warehouse) {
        echo "<li><a href='index.php?module=products&view=warehouse&warehouse_id=" . $warehouse->id . "'>" . $warehouse->name . "</a></li>";
    }
    ?>
</ul>
<?php echo form_close(); ?>

