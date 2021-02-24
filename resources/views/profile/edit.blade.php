@extends('layouts.app')

@section('content')

    <div class="create">
        <div class="content container">
            <div class="card-header"><h1 style="color:white;">Personal Details</h1></div>
            <h5>NOTE: no more than one scholar per registration. </h5>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{ Form::open(['method' => 'PUT', 'action' => ['ProfileController@update', $user->id], 'files' => true]) }}
                <div class="card">
                    <div class="card-body" style="background-color:#6fc7c0;color:white;">
                        <div class="container form-container">
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('errors'))
                                <div class="alert alert-danger" role="alert">
                                    <p>You have a few errors in your profile. Please correct before moving onto to step 2 of your job shadow application.</p>
                                </div>
                            @endif
                            <h5><strong>The Job Shadow-ers Details</strong></h5>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    {{ Form::label('name', 'Name & Surname') }}
                                    {{ Form::text('name', $user->name, ['class' => 'form-control', 'placeholder' => 'NAME & SURNAME']) }}
                                    @if ($errors->has('name'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('name') }}</strong></span>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('contact_no', 'Tel No') }}
                                    {{ Form::text('contact_no', $user->profile->contact_no, ['class' => 'form-control', 'placeholder' => 'TEL NO']) }}
                                    @if ($errors->has('contact_no'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('contact_no') }}</strong></span>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('id_no', 'ID / passport number') }}
                                    {{-- (<small>Enter ID/passport number below and upload proof of ID/passport.</small>) --}}
                                    {{ Form::text('id_no', $user->profile->id_no, ['class' => 'form-control', 'placeholder' => 'ID NO']) }}
                                    @if ($errors->has('id_no'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('id_no') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <hr>

                            <h5><strong>The Job Shadow-ers Scan of ID</strong></h5>

                            <div class="form-group ">
                                {{ Form::file('id_file', ['class' => 'form-control-file']) }}
                                @if($user->profile->id_path)
                                        <p>{{ $user->profile->id_path }}</p>
                                    @endif
                                @if ($errors->has('id_file'))
                                    <span role="alert"
                                          class="invalid-feedback d-block"><strong>{{ $errors->first('id_file') }}</strong></span>
                                @endif
                                (<small>Max file size: 5mb</small>)
                            </div>

                            <h5><strong>The Job Shadow-ers Profile Photo</strong></h5>

                            <div class="form-group ">
                                {{ Form::file('profile_photo', ['class' => 'form-control-file']) }}
                                @if($user->profile->id_path)
                                        <p>{{ $user->profile->profile_photo }}</p>
                                    @endif
                                @if ($errors->has('profile_photo'))
                                    <span role="alert"
                                          class="invalid-feedback d-block"><strong>{{ $errors->first('profile_photo') }}</strong></span>
                                @endif
                                (<small>Max file size: 5mb</small>)
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    {{ Form::label('school_of_attendance', 'Current School') }}
                                    {{ Form::text('school_of_attendance', $user->profile->school_of_attendance, ['class' => 'form-control', 'style' => 'margin-bottom: 5px;', 'placeholder' => "CURRENT SCHOOL"]) }}
                                    @if ($errors->has('school_of_attendance'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('school_of_attendance') }}</strong></span>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::radio('school_type', 'public', ($user->profile->school_type == 'public') ? true : false) }}&nbsp;Public School&nbsp;&nbsp;
                                    {{ Form::radio('school_type', 'private', ($user->profile->school_type == 'private') ? true : false) }}&nbsp;Private School
                                    {{ Form::radio('school_type', 'home', ($user->profile->school_type == 'home') ? true : false) }}&nbsp;Home School
                                </div>
                            </div>

                            <div class="form-row col-md-12">
                                <div class="form-group col-md-6">
                                    <p class="my-0"><strong>Career Interests</strong></p>
                                    {{ Form::label('career_interests', 'Career interests') }}
                                    {{ Form::text('career_interests', $user->profile->career_interests, ['class' => 'form-control', 'rows' => 2]) }}
                                    @if ($errors->has('career_interests'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('career_interests') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <div class="form-group col-md-6">
                                    <p class="my-0"><strong>Dietary requirements/allergies</strong></p>
                                    {{ Form::label('dietary_requirements', 'Dietary requirements/allergies') }}
                                    {{ Form::text('dietary_requirements', $user->profile->dietary_requirements, ['class' => 'form-control', 'rows' => 2]) }}
                                    @if ($errors->has('dietary_requirements'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('dietary_requirements') }}</strong></span>
                                    @endif
                                </div>
                            </div>

                            <hr>
                            <h5><strong>Parent / Guardian Information</strong></h5>
                            <p>If you are under the age of 18, please fill in your parent/ guardian information below</p>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    {{ Form::label('guardian_name', 'Parent / Guardian name') }}
                                    {{ Form::text('guardian_name', $user->profile->guardian_name, ['class' => 'form-control', 'placeholder'=> 'PARENT / GUARDIAN NAME']) }}
                                    @if ($errors->has('guardian_name'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('guardian_name') }}</strong></span>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('guardian_contact_no', 'Parent / Guardian contact no') }}
                                    {{ Form::text('guardian_contact_no', $user->profile->guardian_contact_no, ['class' => 'form-control', 'placeholder'=> 'PARENT / GUARDIAN CONTACT NO']) }}
                                    @if ($errors->has('guardian_contact_no'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('guardian_contact_no') }}</strong></span>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('guardian_email', 'Parent / Guardian email address') }}
                                    {{ Form::text('guardian_email', $user->profile->guardian_email, ['class' => 'form-control', 'placeholder'=> 'PARENT / GUARDIAN EMAIL ADDRESS']) }}
                                    @if ($errors->has('guardian_email'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('guardian_email') }}</strong></span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    {{ Form::label('guardian_id_no', 'Parent / Guardian ID/passport') }}
                                    {{ Form::text('guardian_id_no', $user->profile->guardian_id_no, ['class' => 'form-control', 'placeholder'=> 'PARENT / GUARDIAN ID NO']) }}
                                    @if ($errors->has('guardian_id_no'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('guardian_id_no') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="container form-container">
                    {{ Form::submit('Update Profile', ['class' => 'btn btn-primary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>


@endsection