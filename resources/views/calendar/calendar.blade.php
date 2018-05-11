@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('bc/fullcalendar/dist/fullcalendar.min.css')}}">

@endsection

@section('title') Full Calendar @endsection

@section('content')

    <div class="container">
        <div class="row">

            <div id="calendar">
            </div>
            //src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"
            <script src="{{ asset('bc/jquery/dist/jquery.min.js') }}"></script>

            <!-- fullcalendar -->
            <script src="{{ asset('bc/moment/moment.js')}}"></script>
            <script src="{{ asset('bc/fullcalendar/dist/fullcalendar.min.js') }}"></script>

            <script>
                $(              function () {

                    /* initialize the external events
                     -----------------------------------------------------------------*/
                    function init_events(ele) {
                        ele.each(function () {

                            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                            // it doesn't need to have a start or end
                            var eventObject = {
                                title: $.trim($(this).text()) // use the element's text as the event title
                            }

                            // store the Event Object in the DOM element so we can get to it later
                            $(this).data('eventObject', eventObject)

                            // make the event draggable using jQuery UI
                            $(this).draggable({
                                zIndex        : 1070,
                                revert        : false, // will cause the event to go back to its
                                revertDuration: 0  //  original position after the drag
                            })

                        })
                    }

                    init_events($('#external-events div.external-event'))

                    /* initialize the calendar
                     -----------------------------------------------------------------*/
                    //Date for the calendar events (dummy data)

                    var date = new Date()
                    var d    = date.getDate(),
                        m    = date.getMonth(),
                        y    = date.getFullYear()

                    $('#calendar').fullCalendar({
                        header    : {
                            left  : 'prev,next today',
                            center: 'title',
                            right : 'month,agendaWeek,agendaDay'
                        },
                        buttonText: {
                            today: 'today',
                            month: 'month',
                            week : 'week',
                            day  : 'day'
                        },

                        events    : [

                                @foreach($leaves as $leave)

                            {
                                title : "{{$leave->user->name}} Time-Off :: {{$leave->no_of_day}} days",
                                start : "{{$leave->from}}",
                                end : "{{\Carbon\Carbon::parse($leave->to)->addDay(1)}}",
                                backgroundColor : "orange",
                                borderColor : "black"
                            },
                                @endforeach
                                @foreach($users as $user)
                            {
                                title : '{{$user->name}} Birthday Time Up',
                                start : '{{$user->birthday}}',
                                backgroundColor: '#FF5733',
                                borderColor : 'black'
                            },
                            {
                                title : "{{$user->name}} Anniversary Time Up",
                                start : "{{$user->join_date}}",
                                backgroundColor: "Picker",
                                borderColor : "black",
                            },
                            @endforeach



                        ],





                        editable  : false,
                        droppable : true, // this allows things to be dropped onto the calendar !!!
                        drop      : function (date, allDay) { // this function is called when something is dropped

                            // retrieve the dropped element's stored Event Object
                            var originalEventObject = $(this).data('eventObject')

                            // we need to copy it, so that multiple events don't have a reference to the same object
                            var copiedEventObject = $.extend({}, originalEventObject)

                            // assign it the date that was reported
                            copiedEventObject.start           = date
                            copiedEventObject.allDay          = allDay
                            copiedEventObject.backgroundColor = $(this).css('background-color')
                            copiedEventObject.borderColor     = $(this).css('border-color')

                            // render the event on the calendar
                            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

                            // is the "remove after drop" checkbox checked?
                            if ($('#drop-remove').is(':checked')) {
                                // if so, remove the element from the "Draggable Events" list
                                $(this).remove()
                            }

                        }
                    })

                    /* ADDING EVENTS */
                    var currColor = '#3c8dbc' //Red by default
                    //Color chooser button
                    var colorChooser = $('#color-chooser-btn')
                    $('#color-chooser > li > a').click(function (e) {
                        e.preventDefault()
                        //Save color
                        currColor = $(this).css('color')
                        //Add color effect to button
                        $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
                    })
                    $('#add-new-event').click(function (e) {
                        e.preventDefault()
                        //Get value and make sure it is not null
                        var val = $('#new-event').val()
                        if (val.length == 0) {
                            return
                        }

                        //Create events
                        var event = $('<div />')
                        event.css({
                            'background-color': currColor,
                            'border-color'    : currColor,
                            'color'           : '#fff'
                        }).addClass('external-event')
                        event.html(val)
                        $('#external-events').prepend(event)

                        //Add draggable funtionality
                        init_events(event)

                        //Remove event from text input
                        $('#new-event').val('')
                    });


                    //console.log(moment("1995-12-25"));
                    //Colorpicker
                    // $('.my-colorpicker1').colorpicker()
                    //color picker with addon
                    // $('.my-colorpicker2').colorpicker()

                });

            </script>

        </div>
    </div>

@endsection



