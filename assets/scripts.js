jQuery(document).ready(function ($) {
    var countriesList = $('#country_code');
    var statesList = $('#state_code');

    statesList.prev('label').hide();
    statesList.hide();

    countriesList.change(function () {
        var selectedCountry = this.value;
        $("#state_code > option").each(function() {
            if($(selectedCountry) !== this.className){
              //to do magic
            }
        });
        statesList.prev('label').show();
        statesList.show();
    });

});