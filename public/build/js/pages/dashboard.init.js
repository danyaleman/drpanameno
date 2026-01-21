

/*
Product Name: Doctorly - Hospital & Clinic Management Laravel System
Author: Themesbrand
Version: 1.0.0
Website: https://themesbrand.com/
Contact: support@themesbrand.com
File: Dashboard Init Js File
*/

// Mixed Chart

if (document.querySelector("#monthly_users")) {
    $(document).ready(function () {

        var total_patient = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        var total_revenue = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        $.ajax({

            type: 'GET',
            url: 'getMonthlyUsersRevenue',
            dataType: 'json',
            success: function (data) {
                var i;
                for (i = 0; i < 12; i++) {

                    if (data.total_patient[i] !== undefined) {
                        // data.total_patient;

                        total_patient.splice(data.total_patient[i].Month - 1, 1, data.total_patient[i].total_patient);

                    }

                    if (data.total_revenue[i] !== undefined) {

                        total_revenue.splice(data.total_revenue[i].Month - 1, 1, data.total_revenue[i].total_revenue);

                    }

                }

                var options = {
                    series: [{
                        name: 'Patients',
                        type: 'column',
                        data: total_patient
                    }, {
                        name: 'Revenue',
                        type: 'line',
                        data: total_revenue
                    }],
                    chart: {
                        height: 350,
                        type: 'line',
                        toolbar: {
                            show: false
                        }
                    },
                    stroke: {
                        width: [0, 4]
                    },
                    //labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    xaxis: {
                        //type: 'datetime'
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                    },
                    yaxis: [{
                        title: {
                            text: 'No. of Patients',
                        },

                    }, {
                        opposite: true,
                        title: {
                            text: '$ (thousands)'
                        }
                    }]
                };

                var monthly_user_chart = new ApexCharts(document.querySelector("#monthly_users"), options);
                monthly_user_chart.render();

            },
            error: function (data) {
                console.log('oops! Something Went Wrong!!!');
            }

        });

    });

}

if (document.querySelector("#monthly_invoices")) {
    $(document).ready(function () {

        var paid_invoice = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        var unpaid_invoice = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        $.ajax({

            type: 'GET',
            url: 'getMonthlyInvoice',
            dataType: 'json',
            success: function (invoiceData) {
                var i;
                for (i = 0; i < 12; i++) {
                    if (invoiceData.paid_invoice[i] !== undefined) {
                        // invoiceData.paid_invoice;
                        paid_invoice.splice(invoiceData.paid_invoice[i].Month - 1, 1, invoiceData.paid_invoice[i].paid_invoice);

                    }

                    if (invoiceData.unpaid_invoice[i] !== undefined) {

                        unpaid_invoice.splice(invoiceData.unpaid_invoice[i].Month - 1, 1, invoiceData.unpaid_invoice[i].unpaid_invoice);

                    }

                }


                var options = {
                    series: [{
                        name: 'Paid Invoice',
                        type: 'column',
                        data: paid_invoice
                    }, {
                        name: 'Unpaid Invoice',
                        type: 'line',
                        data: unpaid_invoice
                    }],
                    chart: {
                        height: 350,
                        type: 'line',
                        toolbar: {
                            show: false
                        }
                    },
                    stroke: {
                        width: [0, 4]
                    },
                    //labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    xaxis: {
                        //type: 'datetime'
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                    },
                    yaxis: [{
                        title: {
                            text: 'No. of Invoices',
                        },

                    }]
                };

                var monthly_user_chart = new ApexCharts(document.querySelector("#monthly_invoices"), options);
                monthly_user_chart.render();

            },
            error: function (data) {
                console.log('oops! Something Went Wrong!!!');
            }

        });

    });

}
// Column Chart

if (document.querySelector("#monthly_appointment")) {

    $(document).ready(function () {

        var total_appointment = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        $.ajax({

            type: 'GET',
            url: 'getMonthlyAppointments',
            dataType: 'json',
            success: function (data) {
                var i;
                for (i = 0; i < 12; i++) {
                    if (data[i] !== undefined) {
                        total_appointment.splice(data[i].Month - 1, 1, data[i].total_appointment);
                    }
                }

                var options = {
                    chart: {
                        height: 350,
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '45%',
                            endingShape: 'rounded'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    series: [{
                        name: 'No. of Appointments',
                        data: total_appointment
                    }],
                    colors: ['#34c38f'],
                    xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                    },
                    yaxis: {
                        title: {
                            text: 'No. of Appointments'
                        }
                    },
                    grid: {
                        borderColor: '#f1f1f1'
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function formatter(val) {
                                return val;
                            }
                        }
                    }
                };

                var monthly_appointment_chart = new ApexCharts(document.querySelector("#monthly_appointment"), options);
                monthly_appointment_chart.render();

            },
            error: function (data) {
                console.log('oops! Something Went Wrong!!!');
            }

        });

    });

}

// Radial chart
if (document.getElementById("radialBar-chart")) {
    $.ajax({

        type: 'GET',
        url: 'getMonthlyEarning',
        dataType: 'json',
        success: function (data) {
            var options = {
                chart: {
                    height: 200,
                    type: 'radialBar',
                    offsetY: -10
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        dataLabels: {
                            name: {
                                fontSize: '13px',
                                color: undefined,
                                offsetY: 60
                            },
                            value: {
                                offsetY: 22,
                                fontSize: '16px',
                                color: undefined,
                                formatter: function formatter(val) {
                                    return val + "%";
                                }
                            }
                        }
                    }
                },
                colors: ['#556ee6'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 65, 91]
                    }
                },
                stroke: {
                    dashArray: 4
                },
                series: [data['diff']],
                labels: ['Monthly Analytics']
            };
            var chart = new ApexCharts(document.querySelector("#radialBar-chart"), options);
            chart.render();

        },
        error: function (data) {
            console.log('oops! Something Went Wrong!!!');
        }

    });
}
$('.per-page-items').click(function (e) {
    var token = $("input[name='_token']").val();
    var page = $(this).data("page");
    $('.per-page-items').each(function () {
        $(this).removeClass('btn-primary').addClass('btn-info');
    });
    $(this).removeClass('btn-info').addClass('btn-primary');

    $.ajax({
        url: "per-page-item",
        type: 'POST',
        data: {
            "page": page,
            "_token": token,
        },
        success: function (response) {
            toastr.success(response.Message);
        },
        error: function (response) {
            toastr.error(response.responseJSON.message);
        }
    });

});
