jQuery(document).ready(function ($) {
    var ssForm = $('#av-ssf-form');
    var countriesList = $('#country_code');
    var statesList = $('#state_code_parent');
    var statesListLabel =  statesList.prev('label');
    statesListLabel.after("<select id='state_code' name='state_code'></select>");
    var newStatesList = $('#state_code');

    countriesList.change(function () {
        newStatesList.hide();
        statesListLabel.hide();
        // clean newStatesList
        newStatesList[0].options.length = 0;
        newStatesList.append('<option value="">--None--</option>');
        let selectedCountry = this.value;
        for (let i = 1; i < statesList[0].options.length; i++) {
            if (selectedCountry === statesList[0].options[i].className) {
                //console.log(statesList[0].options[i]);
                newStatesList.append(statesList[0].options[i]);
            }
        }
        if(1 !== newStatesList[0].options.length) {
            statesListLabel.show();
            newStatesList.show();
        }
    });
    jQuery.validator.addMethod("phoneUS", function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length < 20 && phone_number.match(/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im);
    }, "Please specify a valid phone number");

    ssForm.validate({
        rules: {
            first_name: "required",
            last_name: "required",
            company: "required",
            country_code: "required",
            '00Nf200000DJ9Ik': "required",
            state_code: "required",
            email:{
                required: true,
                email:true
            },
            phone: {
                required: true,
                phoneUS: true
            }
        },
        messages: {
            first_name: "Please enter your first name",
            last_name: "Please enter your surname name",
            company: "Please enter your company name",
            country_code: "Please select your country",
            '00Nf200000DJ9Ik': "Please select what are you interested in?",
        }
    });

});