/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');
window.datepicker = require('bootstrap-datepicker');
// window.dt = require('datatables.net')();

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app'
// });

(function ($) {
    // Table Search
    $.fn.tableSearch = function (options) {
        if (!$(this).is('table')) {
            return;
        }
        var tableObj = $(this),
            searchText = (options.searchText) ? options.searchText : 'Search: ',
            searchPlaceHolder = (options.searchPlaceHolder) ? options.searchPlaceHolder : '',
            divObj = $('<div style="padding:0 20px;max-width:25%;">' + searchText + '</div><br /><br />'),
            inputObj = $('<input class="form-control search_input" type="text" placeholder="' + searchPlaceHolder + '" />'),
            caseSensitive = (options.caseSensitive === true) ? true : false,
            searchFieldVal = '',
            pattern = '';
        inputObj.off('keyup').on('keyup', function () {
            searchFieldVal = $(this).val();
            pattern = (caseSensitive) ? RegExp(searchFieldVal) : RegExp(searchFieldVal, 'i');
            tableObj.find('tbody tr').hide().each(function () {
                var currentRow = $(this);
                currentRow.find('td').each(function () {
                    if (pattern.test($(this).html())) {
                        currentRow.show();
                        return false;
                    }
                });

                // currentRow.find('td.dates_hidden').each(function () {
                //     let dateformat = /^(0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
                //     let searchedDate = null;
                //
                //     if (searchFieldVal.match(dateformat)) {
                //         let dates = $(this).html().split(", ");
                //
                //         for (let date of dates) {
                //             if (pattern.test(date)) {
                //                 searchedDate = date;
                //             }
                //         }
                //
                //         if (searchedDate) {
                //             currentRow.find('td.dates').html(searchedDate)
                //         }
                //
                //     } else {
                //         currentRow.find('td.dates').html($(this).html())
                //     }
                // });
            });
        });
        tableObj.before(divObj.append(inputObj));
        return tableObj;
    }

    $(document).ready(function () {
        // Data Table
        // if ($('#jobs-table').length) {
        //     $('#jobs-table').DataTable();
        // }

        if ($('#applications-table').length) {
            $('#applications-table').tableSearch({
                searchText: 'Search Table',
                searchPlaceHolder: 'Input Value'
            });
        }

        if ($('#jobs-table').length) {
            $('#jobs-table').tableSearch({
                searchText: 'Search Table',
                searchPlaceHolder: 'Input Value'
            });
        }

        var _href = $("#export_jobs").attr("href");

        $('.search_input').on('keyup', function () {
            var search_input = $('.search_input').val();
            if ($('#jobs-table').length) {
                let dateformat = /^(0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
                var searchDateStart = $('#job_date_start').val();
                var searchDateEnd = $('#job_date_end').val();

                let params = '?search=' + search_input;

                if (searchDateStart.match(dateformat) && searchDateEnd.match(dateformat)) {
                    params += '&start-date=' + searchDateStart + '&end-date=' + searchDateEnd;
                } else if (searchDateStart.match(dateformat)) {
                    params += '&start-date=' + searchDateStart;
                } else if (searchDateEnd.match(dateformat)) {
                    params += '&end-date=' + searchDateEnd;
                }

                $('#export_jobs').attr("href", _href + params);
            }
            if ($('#applications-table').length) {
                $('input#export_search').val(search_input);
            }
        });

        $('#job_date_start, #job_date_end').on('change', function () {
            var search_input = $('.search_input').val();

            let dateformat = /^(0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/;
            var searchDateStart = $('#job_date_start').val();
            var searchDateEnd = $('#job_date_end').val();
            let jobsTable = $('#jobs-table');

            if (jobsTable.length) {
                let params = '?search=' + search_input;

                if (searchDateStart.match(dateformat) && searchDateEnd.match(dateformat)) {
                    params += '&start-date=' + searchDateStart + '&end-date=' + searchDateEnd;
                } else if (searchDateStart.match(dateformat)) {
                    params += '&start-date=' + searchDateStart;
                } else if (searchDateEnd.match(dateformat)) {
                    params += '&end-date=' + searchDateEnd;
                }

                $('#export_jobs').attr("href", _href + params);
            }


            console.log(searchDateStart);

                var tableObj = $('#jobs-table');

                tableObj.find('tbody tr').hide().each(function () {
                    var currentRow = $(this);

                    currentRow.find('td.dates_hidden').each(function () {

                        let searchedDates = [];

                        if (searchDateStart.match(dateformat) || searchDateEnd.match(dateformat)) {
                            let dates = $(this).html().split(", ");

                            for (let date of dates) {
                                if (searchDateStart.match(dateformat) && searchDateEnd.match(dateformat)) {

                                    if (new Date(date) >= new Date(searchDateStart) && new Date(date) <= new Date(searchDateEnd)) {
                                        searchedDates.push(date);
                                    }
                                } else if (searchDateStart.match(dateformat)) {
                                    if (new Date(date) >= new Date(searchDateStart)) {
                                        searchedDates.push(date);
                                    }
                                } else {
                                    if (new Date(date) <= new Date(searchDateEnd)) {
                                        searchedDates.push(date);
                                    }
                                }
                            }

                            currentRow.find('td.dates').html(searchedDates.join(', '));

                            if (searchedDates.length > 0) {
                                currentRow.show();
                            }

                        } else {
                            currentRow.find('td.dates').html($(this).html());
                            currentRow.show();
                        }
                    });
                });


        });

        // Bootstrap Date Picker
        if ($('#period-calendar-1').length || $('#period-calendar-2').length || $('#period-calendar-3').length || $('#period-calendar-4').length || $('#period-calendar-5').length) {
            $('#period-calendar-1, #period-calendar-2, #period-calendar-3, #period-calendar-4, #period-calendar-5').datepicker({
                multidate: true
            });
        }

        // Voucher Date Picker
        if ($('.voucher-date').length) {
            $('.voucher-date').datepicker();
        }

        // Applications Export Date Pickers
        if ($('.applications-date-ranges').length) {
            $('.applications-date-ranges').datepicker();
        }

        if ($('#applicant_calendar').length) {
            $.ajax({
                    url: "/application/step/5/getAvailableDates",
                })
                .done(function (data) {
                    var availableDates = data;

                    if (availableDates.error) {
                        $('#calendar-instructions').text(availableDates.error.message);
                        return;
                    }

                    $('#calendar-instructions').text('Please scroll through calendar and select from the dates highlighted in red');

                    $('#applicant_calendar').datepicker({
                        beforeShowDay: function (date) {
                            var formattedDate = moment(date).format('MM/DD/YYYY');

                            // Check if the date is available and make it clickable
                            return availableDates.indexOf(formattedDate) > -1
                        },
                    }).on('changeDate', function (e) {
                        var selectedFormattedDate = moment(e.date).format('MM/DD/YYYY');
                        $('#applicant_date').val(selectedFormattedDate);
                    });
                });
        }

        // Application Modal
        $('#applicationModal').on('show.bs.modal', function (e) {
            var button = $(e.relatedTarget);
            var userId = button.data('user-id');
            var applicationId = button.data('application-id');

            $.ajax({
                    url: `/profile/${userId}/applications/${applicationId}`,
                })
                .done(function (application) {
                    $('#applicationModal .spinner-border').hide();

                    // Set title
                    $('.modal-title').html(`Application ${application.id} - <span style="font-weight:bold;font-size:20px;">${(application.job) ? application.job.company : ''}</span>`);

                    var applicationBody = `<div class="row">
                    <div class="col-md-6">
                        <h3><strong>Sector:</strong> ${application.sectorName}</h3>
                    </div>
                    <div class="col-md-6">
                        <h3><strong>Career:</strong> ${application.careerName}</h3>
                    </div>

                    <div class="col-md-6">
                        <h3><strong>Address:</strong> ${(application.job) ? application.job.address : ''}</h3>
                    </div>
                    <div class="col-md-6">
                        <h3><strong>Dates:</strong> ${application.dates}</h3>
                        <p>The duration of this job shadow is ${(application.job) ? application.job.days_per_job_shadow: ''} day/s.</p>
                    </div>
                    <div class="col-md-6">
                        <h3><strong>Time:</strong> ${(application.job) ? application.job.arrival_time : ''} - ${(application.job) ? application.job.collection_time : ''}</h3>
                    </div>

                    <div class="col-md-6">
                        <h3><strong>Contact Person:</strong> ${(application.job) ? application.job.job_mentor.name : ''}</h3>
                    </div>
                    <div class="col-md-6">
                        <h3><strong>Contact Number:</strong> ${(application.job) ? application.job.job_mentor.telephone : ''}</h3>
                    </div>
                </div>`;

                    $('.modal-body-content').html(applicationBody);

                    // Show delete button
                    $('#delete-application-form').css('display', 'block');
                    // Update delete button form action
                    $('#delete-application-form').attr('action', '/application/delete/' + application.id);

                });
        });

        $('#applicationModal').on('hidden.bs.modal', function (e) {
            $('#applicationModal .spinner-border').show();
            $('.modal-title').empty();
            $('.modal-body-content').empty();
        });

        // Show career description
        $('#career').change(function () {
            var this_career = $(this).val();
            $('#career-desc').text('');

            $.ajax({
                    method: 'GET',
                    url: '/application/step/2/getDescription',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        career: this_career
                    }
                })
                .done(function (res) {
                    $('#career-desc').text(res.description);
                });
        });

        // Check if the selected career already has a booking for this user
        $('#career').change(function () {
            $('#career-continue').attr('disabled', true);
            $('#careerLoading').css('display', 'inline-block');
            $('#duplicate-career-app').text('');

            var thisSector = $('#business-hidden').val();
            var thisCareer = $(this).val();
            $.ajax({
                    method: 'POST',
                    url: '/application/duplicate-application',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        sector: thisSector,
                        career: thisCareer
                    }
                })
                .done(function (res) {
                    if (res.duplicate) {
                        $('#career-continue').attr('disabled', true);
                        $('#duplicate-career-app').text('You already have an existing application for this sector and career. Please select another career.');
                    } else {
                        $('#career-continue').attr('disabled', false);
                    }

                    $('#careerLoading').css('display', 'none');
                });
        });

        // Apply Promo Code
        $('#applyPromoCode').click(function () {
            // Disable 'apply code' button and display loading spinner
            $(this).attr('disabled', true);
            $('#promoCodeLoading').css('display', 'inline-block');

            var code = $('#promoCode').val();

            $.ajax({
                    url: "/voucher/search?promo_code=" + code,
                })
                .done(function (res) {
                    if (res.error) {
                        $('#applyPromoCode').removeAttr('disabled');
                        $('#promoCodeLoading').css('display', 'none');
                        $('#promoCodeMessage').text(res.message);

                        return;
                    }
                    // on success remove the input field & display success message
                    $('#promoCodeInput').slideUp();
                    $('#promoCodeMessage').text(res.message);

                    // apply new amount on screen
                    $('.amount').text(res.amount);

                    // replace old Payfast form with new one
                    $('#payfast-pay-form').remove();
                    $('#payfast-form-wrapper').append(res.payfast_form);

                });

        });

        // Disable #submit-indemnity button after first click
        $('#upload-indemnity-form').submit(function () {
            $(this).find('input[type="submit"]').prop("disabled", true);
        });

        // Show the indemnity reminder modal
        if ($('#indemnityReminderModal').length) {
            $('#indemnityReminderModal').modal('show');
        }

        // Navbar Toggle
        $('.navbar-toggler').click(function () {
            $('#side-nav').toggleClass('d-none');
        });
    });
})(jQuery);
