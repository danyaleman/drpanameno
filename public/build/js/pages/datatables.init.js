
/*
Product Name: Doctorly - Hospital & Clinic Management Laravel System
Author: Themesbrand
Version: 1.0.0
Website: https://themesbrand.com/
Contact: support@themesbrand.com
File: Datatables Js File
*/
$(document).ready(function () {
  $('.datatable').DataTable(); //Buttons examples

  var table = $('#datatable-buttons').DataTable({
    lengthChange: false,
    buttons: ['copy', 'excel', 'pdf', 'colvis']
  });
  table.buttons().container().appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
});
