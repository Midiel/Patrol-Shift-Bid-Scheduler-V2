@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Create New Schedule</div>

                <div class="card-body">
                    <form action={{ route('admin.bidding-schedule.store') }} method="POST">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Schedule Nme</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>

                        <div class="form-group row">

                            <label for="start_date" class="col-md-4 col-form-label text-md-right">Schedule Start Date</label>

                            <div class="col-md-6">
                                <input id="start_date" type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="" required>

                                @error('start_date')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="end_date" class="col-md-4 col-form-label text-md-right">Schedule End Date</label>

                            <div class="col-md-6">
                                <input id="end_date" type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="" required>

                                @error('end_date')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="response_time" class="col-md-4 col-form-label text-md-right">Response Time</label>

                            <div class="col-md-6">
                                <input id="response_time" type="text" class="form-control @error('response_time') is-invalid @enderror"  name="response_time" required>

                                @error('response_time')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>

                        <!--Shift Table-->
                        <div class="form-group row">

                            <div data-repeater-item class="table-wrapper-scroll-y my-custom-scrollbar">

                                <table class="table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Shift Name</th>
                                            <th scope="col">Start Time</th>
                                            <th scope="col">End Time</th>
                                            <th scope="col">Early Start Time</th>
                                            <th scope="col">Early End Time</th>
                                            <th scope="col">Early Sports</th>
                                            <th scope="col">Minimum Staffing</th>
                                            <th scope="col">Select</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($shifts as $index => $shift)
                                        <tr>
                                            <td>{{ $shift->name }}</td>
                                            <td>{{ $shift->start_time }}</td>
                                            <td>{{ $shift->end_time }}</td>
                                            <td>{{ !empty($shift->earlyShift) ?  $shift->earlyShift->early_start_time:'' }}</td>
                                            <td>{{ !empty($shift->earlyShift) ? $shift->earlyShift->early_end_time:'' }}</td>
                                            <td>{{ !empty($shift->earlyShift) ? $shift->earlyShift->num_early_spot:'' }}</td>
                                            <td>{{ $shift->minimun_staff }}</td>
                                            <td><input id="shift_{{ $index }}" type="checkbox" class="form-control shift-queue-array" name="shift_{{ $index }}" ></td>
                                            <input id="shift_hidden_{{ $index }}" type="hidden"  name="shiftQueue[]" value="{{ $shift->id}}:" >
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="container">
                                    <div class="row mt-3 mb-3 d-flex justify-content-end">
                                        <div class="col-md-4 d-flex justify-content-end">
                                            <a href="{{ route('admin.shift.createFromSchedule') }}"><button type="button" class="btn btn-success">Create New Shift</button></a>
                                        </div>
                                    </div>
                                </div>

                                @error('shiftQueue')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <!--End Shift Table-->

                        <h4 style="margin-top: 10%;">All Users, ordered by date in position.</h4>

                        <div class="form-group row">


                            <div class="table-wrapper-scroll-y my-custom-scrollbar" >

                                <table class="table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Line</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Specialty</th>
                                            <th scope="col">Date in Position</th>
                                            <th scope="col">Bidding Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $index => $user)
                                            <tr id = $index>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ implode(', ', $user->specialties()->get()->pluck('name')->toArray()) }}</td>
                                                <td>{{ $user->date_in_position }}</td>
                                                <td><input id="queue_position_{{ $index + 1 }}" type="number" class="form-control officer-queue-array" name="queue_position_{{ $index + 1 }}" value="{{ $index + 1 }}"></td>
                                                <input id="value_{{ $index + 1 }}" type="hidden" class="" name="officerQueue[]" value="{{ $user->id}}:">
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @error('officerQueue')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6" style="margin-left: 5%;">
                                <input id="seve_as_template" type="checkbox" class="form-check-input" name="seve_as_template" >
                                <label for="save_as_template" class="l text-md-right">Save as a Template</label>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-2 offset-md-4">
                                <button type="submit" class="btn btn-primary" id="send-btn">
                                    {{ __('Create') }}
                                </button>
                            </div>
                            <div class="col-md-2 offset-md-1">

                                <a  class="btn btn-secondary" href="{{ route('admin.bidding-schedule.index') }}">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
