
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
    function matchCustom(params, data) {
      if ($.trim(params.term) === '') { return data; }
      if (typeof data.text === 'undefined') { return null; }
      
      var terms = $.trim(params.term).toLowerCase().split(/\s+/);
      var text = data.text.toLowerCase();
      var matches = true;
      
      for (var i = 0; i < terms.length; i++) {
        if (text.indexOf(terms[i]) === -1) {
          matches = false;
          break;
        }
      }
      return matches ? data : null;
    }

    $(".select2").select2({
      width: '100%',
      matcher: matchCustom
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
