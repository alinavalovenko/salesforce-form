<?php $form_content = file_get_contents(SSF_TEMP . 'form.txt');?>
<form action="tools.php?page=simple-salesforce-form&action=ssf-form" method="post" id="ssf-form">
    <h1>Simple Salesforce Form</h1>
    <p><i>Paste you form here</i></p>

    <textarea name="ssf-content" id="ssf-content" cols="200" rows="30"><?php echo $form_content; ?></textarea><br/>
    <input type="submit" name='save-ssf-form' id='save-ssf-form' value="Save">
</form>