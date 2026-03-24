@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || My Attendance')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <style>
        #calendar {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
        }

        .fc-daygrid-event {
            text-align: center;
            font-weight: 600;
            border-radius: 6px;
            padding: 2px 0;
        }

        .fc-daygrid-day-number {
            font-weight: 600;
            color: #333;
        }

        .fc .fc-daygrid-body-natural .fc-daygrid-day-events {
            width: 30px;
        }

        .fc-daygrid-day.fc-day-today {
            background-color: #a2d2f5 !important; 
        }
    </style>

    <div class="content">
        <div class="card shadow-sm border-0">

            {{-- HEADER --}}
            <div class="card-header bg-white border-0">
                <h4 class="mb-3 fw-bold">📅 Attendance Calendar</h4>

                {{-- FILTER FORM --}}
                <form method="GET" class="row g-2">

                    <div class="col-md-4">
                        <select name="month" class="form-select">
                            <option value="">Select Month</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select name="year" class="form-select">
                            <option value="">Select Year</option>
                            @for ($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        <button class="btn btn-primary w-100">Filter</button>
                        <a href="{{ route('employee.my.attendance') }}" class="btn btn-outline-secondary w-100">
                            Reset
                        </a>
                    </div>

                </form>
            </div>

            {{-- BODY --}}
            <div class="card-body">

                {{-- CALENDAR --}}
                <div id="calendar"></div>

                <div class="legend-box mt-4 d-flex flex-wrap gap-3 align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success px-2 py-2">P</span>
                        <span class="fw-semibold">Present</span>
                    </div>        
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-danger px-2 py-2">A</span>
                        <span class="fw-semibold">Absent</span>
                    </div>                
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning text-dark px-2 py-2">AP</span>
                        <span class="fw-semibold">Approval Pending</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-info px-2 py-2">L</span>
                        <span class="fw-semibold">Leave</span>
                    </div>                
                </div>
            </div>

        </div>
    </div>

    {{-- CALENDAR SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            let calendarEl = document.getElementById('calendar');

            let calendar = new FullCalendar.Calendar(calendarEl, {

                initialView: 'dayGridMonth',

                initialDate: "{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}-01",

                events: @json($events),

                height: "auto",

                eventDisplay: 'block',

                // ✅ HIDE NEXT / PREV BUTTONS
                headerToolbar: {
                    left: '',
                    center: 'title',
                    right: ''
                }

            });

            calendar.render();
        });
    </script>

@endsection
