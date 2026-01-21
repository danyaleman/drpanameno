
/*
Product Name: Doctorly - Hospital & Clinic Management Laravel System
Author: Themesbrand
Version: 1.0.0
Website: https://themesbrand.com/
Contact: support@themesbrand.com
File: Form Advanced Js File
*/
!function ($) {
  "use strict";

  var AdvancedForm = function AdvancedForm() {};

  AdvancedForm.prototype.init = function () {
    // Select2
    $(".select2").select2({
      width: '100%'
    });

    if(document.querySelector('#appointmenttime')){

      $('#appointmenttime').timepicker({
        template: 'modal'
      });

    }

  }, //init
  $.AdvancedForm = new AdvancedForm(), $.AdvancedForm.Constructor = AdvancedForm;
}(window.jQuery), //initializing
function ($) {
  "use strict";

  $.AdvancedForm.init();
}(window.jQuery);
