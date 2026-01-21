
/*
Product Name: Doctorly - Hospital & Clinic Management Laravel System
Author: Themesbrand
Version: 1.0.0
Website: https://themesbrand.com/
Contact: support@themesbrand.com
File: Form repeater Js File
*/
$(document).ready(function () {
  'use strict';

  $('.repeater').repeater({
    initEmpty: false,
    defaultValues: {
      'text-input': 'foo'
    },
    show: function show() {
      console.log('Item Created');
      $(this).slideDown();
    },
    hide: function hide(deleteElement) {
        $(this).slideUp(deleteElement);
    },
    ready: function ready(setIndexes) {},
    isFirstItemUndeletable: true
  });

});
