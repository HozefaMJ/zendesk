<?php
error_reporting(0);

///  DON'T CHANGE ticket_form_id, custom_fields IDS. YOU CAN CHANGE OTHER THINGS AS REQUIREMENTS. ////

if( isset($_POST['form_type']) && $_POST['form_type'] == 'bulk-supplies')
{
	$url = 'https://smbsupport.zendesk.com/api/v2/requests.json';
	$header[] = "Content-type: application/json";
	
	$name = addslashes($_POST['sname']);
	$email = addslashes($_POST['semail']);
	$phone = addslashes($_POST['scontact']);
	$btype = addslashes($_POST['buyer_type']);
	$desc = addslashes($_POST['sdesc']);
	
	$data = '
	{
		"request": 
		{
			"ticket_form_id": 360005245954,
			"tags":["Bulk-Supplies-Inquiry"],
			"via": {"channel": "web"},
			"subject": "Bulk Supply inquiry - smartmedicalbuyer.com", 
			"requester": {"name": "'.$name.'", "email": "'.$email.'"}, 
			"comment": {"body": "'.$desc.'" },
			"custom_fields": [{"id": 360042417893, "value": "'.$name.'"},
			  {"id": 360044284893, "value": "'.$phone.'"},
			  {"id": 360051656173, "value": "'.$btype.'"}]
		}
	}';

	$header[] = "Content-type: application/json";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); 
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	
	$data = curl_exec($ch);
	
	if (curl_errno($ch)) {
		$error_msg = curl_error($ch);
	}
	curl_close($ch);
	
	if (isset($error_msg)) {
		echo "<a href='".$_SERVER['REQUEST_URI']."'>Please try again.</a>";
	}
	else
	{
		echo "We received your request. A member of our support staff will respond as soon as possible";
	}
}
else
{
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<style>
.error { color:red; }
small { color:#999; }
</style>
<div class="bulk-supplies-section">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h4 style="padding:20px 0px"> &nbsp; Send us your requirements  -  Smart Medical Buyer </h4>
        <form method="post" id="bulk_supplies_form" accept-charset="UTF-8" class="form-horizontal form-row-seperated contact-form" onSubmit="return validations();">
          <div class="error" style="padding:0px 0px 10px 15px;"></div>
          <input type="hidden" name="form_type" value="bulk-supplies" />
          <div class="form-group">
            <label class="control-label col-md-2"></label>
            <div class="col-md-5">
              <input name="sname" type="text" maxlength="100" placeholder="Full Name" value="" class="tReq tName form-control" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2"></label>
            <div class="col-md-5">
              <input name="semail" type="text" maxlength="400" placeholder="Email address" value="" class="tReq tEmail form-control" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-10"></label>
            <div class="col-md-5">
              <input name="scontact" type="text" maxlength="50" placeholder="Contact No " class="tReq tNum form-control" >
              <div><small>(eg.+91-98xxx98xxx)</small></div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2"></label>
            <div class="col-md-5">
              <select name="buyer_type" class="form-control tType tReq">
                <option value="">Select your segment</option>
                <option value="doctor/medical_professional">Doctor / Medical Professional</option>
                <option value="hospital/nursing_homes">Hospital / Nursing Homes</option>
                <option value="dental_clinic">Dental Clinic</option>
                <option value="veterinary_clinic/hospital">Veterinary Clinic / Hospital</option>
                <option value="reseller/medical_distributor">Reseller / Medical Distributor</option>
                <option value="individual_buyer">Individual Buyer</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2"></label>
            <div class="col-md-7"> 
              
              <!--<input required="required" name="sdesc" type="text" maxlength="5000" class="tReq form-control" >-->
              
              <textarea name="sdesc" rows="3" class="form-control tMsg tReq" placeholder="Write a message..."></textarea>
              <div><small>Please share products and quantities required for a quick quote with lowest prices.</small></div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2"></label>
            <div class="col-md-5">
              <button type="submit" class="btn btn-primary">Send Request</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script> 
<script>
function validations()
{
	var isValid = 1;

	$('.error').html('');
	var vName = $('.tName').val();
	if ( $.trim(vName)!="" )
	{
		isValid = 1;
	}
	else
	{
		isValid = 0;
		$('.error').html("Please provide name");
		$('.tName').focus();
		return false;
	}
	
	var vEmail = $('.tEmail').val();
	var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	if (filter.test(vEmail))
		isValid = 1;
	else{
		isValid = 0;
		$('.error').html("Please provide valid email address");
		$('.tEmail').focus();
		return false;
	}
	
	var vPhone = $('.tNum').val();
	var phoneno = /^(\+91[\-\s]?)?[0]?(91)?[6789]\d{9}$/;
	if( vPhone.match(phoneno) )
	{
		isValid = 1;
	}
	else
	{
		isValid = 0;
		$('.error').html("Please provide valid contact number");
		$('.tNum').focus();
		return false;
	}
	
	var vType = $('.tType').val();
	if ( $.trim(vType)!="" )
	{
		isValid = 1;
	}
	else
	{
		isValid = 0;
		$('.error').html("Please choose from buyer type");
		$('.tType').focus();
		return false;
	}
	
	var vMsg = $('.tMsg').val();
	if ( $.trim(vMsg)!="" )
	{
		isValid = 1;
	}
	else
	{
		isValid = 0;
		$('.error').html("Please provide your requirements");
		$('.tMsg').focus();
		return false;
	}
	
	if (isValid == 0) return false;  
	else return;
}

</script> 
<?php
}
?>
