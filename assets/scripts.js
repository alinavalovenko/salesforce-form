jQuery(document).ready(function ($) {
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

});