@extends('admin.layouts.master')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome-font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="{{ asset('admin/dist/css/calendar.css') }}">
@section('title')
    {{ __('Daily Mass Timings') }}
@endsection

@section('content')
    <div id="modal-view-event-view" class="modal modal-top fade calendar-modal" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="view-event" method="post" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4>View Daily Mass Timings</h4>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>Mass Timing Date <span class="text-danger">*</span></label>
                                <input type='text' class="datetimepicker form-control" name="startdate"
                                    autocomplete="off" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="edit" style="border-radius: 30px"><i class="fa fa-edit"></i></button>
                        <button type="submit" class="btn btn-primary" id="save" style="display: none;">Save</button>
                        <button type="submit" class="btn btn-primary" id="delete" onclick="deleteEvent(this)"><i class="fa fa-trash"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-view-event-add" class="modal modal-top fade calendar-modal" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="add-event" method="post" autocomplete="off" action="{{ route('admin.events.store') }}">
                    @csrf
                    <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4>Add Daily Mass Timings</h4>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>Mass Timing Date <span class="text-danger">*</span></label>
                                <div class="input-group date" id="datetimepicker1" data-target-input="nearest" style="margin: 0 auto;">
                                    <input type="text" class="datetimepicker form-control" name="startdate" data-target="#datetimepicker1" data-toggle="datetimepicker" autocomplete="off" />
                                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" id="recurring" name="recurring">
                                    <label class="form-check-label" for="recurring">Is Recurring</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" id="firstfriday" name="firstfriday">
                                    <label class="form-check-label" for="firstfriday">Special Mass</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="border-radius: 30px">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h3 class="m-0 text-dark">{{ __('Daily Mass Timings') }}</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Daily Mass Timings') }}</li>
                        </ol>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        onclick="$('#year-dropdown').toggle()">
                        Select Year
                    </button>
                    <div class="dropdown-menu" id="year-dropdown" style="overflow-y: scroll; height: 250px; -ms-overflow-style: none;">
                        @foreach (range(2000, date('Y') + 30) as $year)
                            <a class="dropdown-item" href="#" data-year="{{ $year }}">{{ $year }}</a>
                        @endforeach
                    </div>
                    <style>
                        #year-dropdown::-webkit-scrollbar {
                            width: 8px;
                        }
                        #year-dropdown::-webkit-scrollbar-track {
                            background: #f1f1f1; 
                        }
                        #year-dropdown::-webkit-scrollbar-thumb {
                            background: #888; 
                        }
                        #year-dropdown::-webkit-scrollbar-thumb:hover {
                            background: #555; 
                        }
                    </style>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('.datetimepicker').datepicker({
            timepicker: true,
            language: 'en',
            dateFormat: 'mm/dd/yyyy',
            timeFormat: 'hh:ii aa'
        });

        $('#year-dropdown .dropdown-item').click(function(e) {
            e.preventDefault();
            let year = $(this).data('year');
            $('#calendar').fullCalendar('gotoDate', year + '-01-01');
            $('#year-dropdown').toggle();
        });


        $('#calendar').fullCalendar({
            themeSystem: 'bootstrap4',
            businessHours: false,
            defaultView: 'month',
            editable: true,
            eventLimit: 2,
            eventLimitClick: "popover",
            selectable: true,
            header: {
                left: 'title',
                center: 'month,agendaWeek,agendaDay,listWeek',
                right: 'today prev,next'
            },
            eventRender: function(event, element) {
                element.find(".fc-title").html(moment(event.start).format('h:mm A'));
            },
            events: function(start, end, timezone, callback) {
                $.ajax({
                    url: `${window.location.origin}/api/get/masstime`,
                    dataType: 'json',
                    success: function(response) {
                        var events = [];
                        if (response.success && response.data) {
                            var currentDate = new Date();
                            response.data.forEach(function(eventData) {
                                let eventStartDate = new Date(eventData.startdate);
                                if (eventStartDate >= currentDate) {
                                    events.push({
                                        id: eventData.id,
                                        start: eventData.startdate,
                                        title: moment(eventData.startdate).format('h:mm A') + (eventData.first_friday ? " - Special Mass" : ""),
                                        recurring: eventData.recurring,
                                        firstFriday: eventData.first_friday,
                                        color: eventData.first_friday ? 'coral' : ''
                                    });
                                }
                                if (eventStartDate.toDateString() === currentDate.toDateString()) {
                                    events.push({
                                        id: eventData.id,
                                        start: eventData.startdate,
                                        title: moment(eventData.startdate).format('h:mm A') + (eventData.first_friday ? " - Special Mass" : ""),
                                        recurring: eventData.recurring,
                                        firstFriday: eventData.first_friday,
                                        color: eventData.first_friday ? 'coral' : ''
                                    });
                                }
                            });
                        }
                        callback(events);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching events:", error);
                    }
                });
            },
            eventRender: function(event, element) {
                if (event.icon) {
                    element.find(".fc-title").prepend("<i class='fa fa-" + event.icon + "'></i>");
                }
            },
            dayClick: function(date) {
                $('#modal-view-event-add').modal();
            },
            eventClick: function(event) {
                $('#modal-view-event-view').modal();
                var titleParts = event.title.split(' - ');
                $('#view-event input[name="startdate"]').val(moment(event.start).format('MM/DD/YYYY hh:mm A'));
                $('#delete').attr('data-id', event.id);
            }
        });

        $('#add-event').submit(function(event) {
            event.preventDefault();

            var startdate = $('#add-event input[name="startdate"]').val();

            if (!startdate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill in all required fields.',
                });
                return;
            }

            var formData = $(this).serialize();

            Swal.fire({
                title: 'Saving...',
                text: 'Please wait while we save your Mass Time.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Mass Time added successfully.',
                        showConfirmButton: true
                    }).then(function() {
                        $('#modal-view-event-add').modal('hide');
                        $('#calendar').fullCalendar('refetchEvents');
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again later.',
                    });
                }
            });
        });

        $('#view-event').submit(function(event) {
            event.preventDefault();

            var id = $('#delete').attr('data-id');
            var startdate = $('#view-event input[name="startdate"]').val();

            if (!startdate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill in all required fields.',
                });
                return;
            }

            var formData = $(this).serialize();

            Swal.fire({
                title: 'Updating...',
                text: 'Please wait while we update your Mass Time.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '/admin/events/' + id,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Mass Times updated successfully.',
                        showConfirmButton: true
                    }).then(function() {
                        $('#modal-view-event-view').modal('hide');
                        $('#calendar').fullCalendar('refetchEvents');
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again later.',
                    });
                }
            });
        });

        $(document).on('click', '#edit', function() {
            $(this).hide();
            $('#delete').hide();
            $('.modal-title').text('Edit Daily Mass Timings');
            $('input, textarea, #recurring').prop('disabled', false);
            $('#save').show();
            $('#close').removeClass('btn-danger').addClass('btn-secondary').text('Cancel');
        });

        function deleteEvent(params) {
            var token = "{{ csrf_token() }}";
            var id = $(params).attr('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to delete this Mass Timing?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        dataType: "json",
                        type: "DELETE",
                        url: window.location.origin + "/admin/events/" + id,
                        data: {_token: token},
                        success: function(response) {
                            if (response.status === "success") {
                                Swal.fire(
                                    'Deleted!',
                                    'Mass Time has been deleted.',
                                    'success'
                                ).then(function() {
                                    $('#calendar').fullCalendar('refetchEvents');
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Mass Time deletion failed: ' + response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error!',
                                'AJAX request failed: ' + error,
                                'error'
                            );
                        }
                    });
                }
            });
        }

        $(document).on('click', '#delete', function(event) {
            event.preventDefault();
            deleteEvent(this);
        });
    });

</script>

@endsection
