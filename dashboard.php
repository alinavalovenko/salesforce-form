<?php $form_content = file_get_contents( SSF_TEMP . 'form.txt' ); ?>
    <form action="tools.php?page=simple-salesforce-form&action=ssf-form" method="post" id="ssf-form"
          enctype="multipart/form-data">
        <h1>Simple Salesforce Form</h1>
        <p><i>Upload the *.txt file with a form markup</i></p>
        <input type="file" name="ssf-content" id="ssf-content">
        <input type="submit" name='save-ssf-form' id='save-ssf-form' value="Save">
    </form>
<?php if ( ! empty( $form_content ) ): ?>
    <div class="current-state">
        <h2>You have uploaded this form: </h2>
		<?php echo $form_content; ?>
    </div>
<?php endif; ?>