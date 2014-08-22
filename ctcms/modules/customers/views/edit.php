<?php $name = array(
              'name'        => 'name',
              'id'          => 'name',
              'value'       => $customer->name,
              'class'       => 'span4',
			  'required'	=> 'required',
			  'data-error'	=> $this->lang->line("name").' '.$this->lang->line("is_required")
            );
			$email = array(
              'name'        => 'email',
              'id'          => 'email',
              'value'       => $customer->email,
              'class'       => 'span4',
			  'required'	=> 'required',
			  'data-error'	=> $this->lang->line("email_address").' '.$this->lang->line("is_required")
            );
			$company = array(
              'name'     => 'company',
              'id'          => 'company',
              'value'       => $customer->company,
              'class'       => 'span4 tip',
			  'title'		=> $this->lang->line("bypass") ,
			  'required'	=> 'required',
			  'data-error'	=> $this->lang->line("company").' '.$this->lang->line("is_required")
            );
			$address = array(
              'name'        => 'address',
              'id'          => 'address',
              'value'       => $customer->address,
              'class'       => 'span4',
			  'required'	=> 'required',
			  'data-error'	=> $this->lang->line("address").' '.$this->lang->line("is_required")
            );
			$city = array(
              'name'        => 'city',
              'id'          => 'city',
              'value'       => $customer->city,
              'class'       => 'span4',
			  'required'	=> 'required',
			  'data-error'	=> $this->lang->line("city").' '.$this->lang->line("is_required")
            );
			$state = array(
              'name'     => 'state',
              'id'          => 'state',
              'value'       => $customer->state,
              'class'       => 'span4',
			  'required'	=> 'required',
			  'data-error'	=> $this->lang->line("state").' '.$this->lang->line("is_required")
            );
			$postal_code = array(
              'name'        => 'postal_code',
              'id'          => 'postal_code',
              'value'       => $customer->postal_code,
              'class'       => 'span4',
			  'required'	=> 'required',
			  'data-error'	=> $this->lang->line("postal_code").' '.$this->lang->line("is_required")
            );
			$country = array(
              'name'        => 'country',
              'id'          => 'country',
              'value'       => $customer->country,
              'class'       => 'span4',
			  'required'	=> 'required',
			  'data-error'	=> $this->lang->line("country").' '.$this->lang->line("is_required")
            );
			$phone = array(
              'name'        => 'phone',
              'id'          => 'phone',
              'value'       => $customer->phone,
              'class'       => 'span4',
			  'required'	=> 'required',
			  'data-error'	=> $this->lang->line("phone").' '.$this->lang->line("is_required")
            );
			
		?>
<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
$(function() {
	$('form').form();
});
</script>
        
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>


	<h3 class="title"><?php echo $page_title; ?></h3>
	<p><?php echo $this->lang->line("enter_info"); ?></p>

   	<?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=customers&view=edit&id=".$id, $attrib);?>

<div class="control-group">
  <label class="control-label" for="serial"><?php echo $this->lang->line("serial_no"); ?></label>
  <div class="controls"> <?php echo form_input('serial',(isset($_POST['serial']) ? $_POST['serial'] : $customer->name));?>
  </div>
</div> 
<div class="control-group">
  <label class="control-label" for="company"><?php echo $this->lang->line("company"); ?></label>
  <div class="controls"> <?php echo form_input($company,(isset($_POST['company']) ? $_POST['company'] : $customer->company));?>
  </div>
</div> 
<div class="control-group">
  <label class="control-label" for="address"><?php echo $this->lang->line("address"); ?></label>
  <div class="controls"> <?php echo form_input($address,(isset($_POST['address']) ? $_POST['address'] : $customer->address));?>
  </div>
</div>  
<div class="control-group">
  <label class="control-label" for="city"><?php echo $this->lang->line("city"); ?></label>
  <div class="controls"> <?php echo form_input($city,(isset($_POST['city']) ? $_POST['city'] : $customer->city));?>
  </div>
</div> 
<div class="control-group">
  <label class="control-label" for="postal_code"><?php echo $this->lang->line("postal_code"); ?></label>
  <div class="controls"> <?php echo form_input($postal_code,(isset($_POST['man_ip']) ? $_POST['man_ip'] : $customer->postal_code));?>
  </div>
</div> 
<div class="control-group">
  <label class="control-label" for="country"><?php echo $this->lang->line("country"); ?></label>
  <div class="controls"> <?php echo form_input($country,(isset($_POST['man_ip']) ? $_POST['man_ip'] : $customer->country));?>
  </div>
</div> 
<div class="control-group">
    <label class="control-label"><?php echo $this->lang->line("current_contacts"); ?></label>
    <div class="span9">
        <div>
            <label class="control-label" for="name1"><?php echo $this->lang->line("name"); ?></label>
            <div class="controls"> <?php echo form_input('name1',(isset($_POST['name1']) ? $_POST['name1'] : $contacts[0]->name), 'class="span4" id="name1"' . '" required="required" data-error="' . $this->lang->line("name") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div> 
        </div>
        <br>
        <div>
            <label class="control-label" for="email1"><?php echo $this->lang->line("email"); ?></label>
            <div class="controls"> <?php echo form_input('email1', (isset($_POST['email1']) ? $_POST['email1'] : $contacts[0]->email), 'class="span4" id="email1"' . ' required="required" data-error="' . $this->lang->line("email") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div>
        </div>
        <br>
        <div>
            <label class="control-label" for="phone1"><?php echo $this->lang->line("phone"); ?></label>
            <div class="controls"> <?php echo form_input('phone1',(isset($_POST['phone1']) ? $_POST['phone1'] : $contacts[0]->phone), 'class="span4" id="phone1"' . ' required="required" data-error="' . $this->lang->line("phone") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div> 
        </div>
        <br>
        <div>
            <label class="control-label" for="location1"><?php echo $this->lang->line("location"); ?></label>
            <div class="controls"> <?php echo form_input('location1', (isset($_POST['location1']) ? $_POST['location1'] : $contacts[0]->location), 'class="span4" id="location1"' . '" required="required" data-error="' . $this->lang->line("location") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div>
        </div>
        <br>
        <div>
            <label class="control-label" for="name2"><?php echo $this->lang->line("name"); ?></label>
            <div class="controls"> <?php echo form_input('name2', (isset($_POST['name2']) ? $_POST['name2'] : $contacts[1]->name), 'class="span4" id="name2"' . '" required="required" data-error="' . $this->lang->line("name") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div> 
        </div>
        <br>
        <div>
            <label class="control-label" for="email2"><?php echo $this->lang->line("email"); ?></label>
            <div class="controls"> <?php echo form_input('email2', (isset($_POST['email2']) ? $_POST['email2'] : $contacts[1]->email), 'class="span4" id="email2"' . ' required="required" data-error="' . $this->lang->line("email") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div>
        </div>
        <br>
        <div>
            <label class="control-label" for="phone2"><?php echo $this->lang->line("phone"); ?></label>
            <div class="controls"> <?php echo form_input('phone2', (isset($_POST['phone2']) ? $_POST['phone2'] : $contacts[1]->phone), 'class="span4" id="phone2"' . ' required="required" data-error="' . $this->lang->line("phone") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div> 
        </div>
        <br>
        <div>
            <label class="control-label" for="location2"><?php echo $this->lang->line("location"); ?></label>
            <div class="controls"> <?php echo form_input('location2', (isset($_POST['location2']) ? $_POST['location2'] : $contacts[1]->location), 'class="span4" id="location2"' . '" required="required" data-error="' . $this->lang->line("location") . ' ' . $this->lang->line("is_required") . '"'); ?>
            </div>
        </div>
    </div> 
</div> 

<div class="control-group">
  <div class="controls"> <?php echo form_submit('submit', $this->lang->line("update_customer"), 'class="btn btn-primary"');?> </div>
</div>
<?php echo form_close();?> 
 