@extends('layouts.app')

@section('content')

    <div class="create">
        <div class="content container">
            <div class="card-header"><h1 style="color:white;">Personal Details</h1></div>
            <h5>NOTE: no more than one scholar per registration. </h5>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{ Form::open(['action' => 'ProfileController@store', 'files' => true]) }}
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
                                    {{ Form::text('name', Auth::user()->name, ['class' => 'form-control', 'placeholder' => 'NAME & SURNAME']) }}
                                    @if ($errors->has('name'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('name') }}</strong></span>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('contact_no', 'Tel No') }}
                                    {{ Form::text('contact_no', '', ['class' => 'form-control', 'placeholder' => 'TEL NO']) }}
                                    @if ($errors->has('contact_no'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('contact_no') }}</strong></span>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('id_no', 'ID / passport number') }}
                                    {{-- (<small>Enter ID/passport number below and upload proof of ID/passport.</small>) --}}
                                    {{ Form::text('id_no', '', ['class' => 'form-control', 'placeholder' => 'ID NO']) }}
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
                                @if ($errors->has('id_file'))
                                    <span role="alert"
                                          class="invalid-feedback d-block"><strong>{{ $errors->first('id_file') }}</strong></span>
                                @endif
                                (<small>Max file size: 5mb</small>)
                            </div>

                            <h5><strong>The Job Shadow-ers Profile Photo</strong></h5>

                            <div class="form-group ">
                                {{ Form::file('profile_photo', ['class' => 'form-control-file']) }}
                                @if ($errors->has('profile_photo'))
                                    <span role="alert"
                                          class="invalid-feedback d-block"><strong>{{ $errors->first('profile_photo') }}</strong></span>
                                @endif
                                (<small>Max file size: 5mb</small>)
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    {{ Form::label('school_of_attendance', 'Current School') }}
                                    {{ Form::text('school_of_attendance', '', ['class' => 'form-control', 'style' => 'margin-bottom: 5px;', 'placeholder' => "CURRENT SCHOOL"]) }}
                                    @if ($errors->has('school_of_attendance'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('school_of_attendance') }}</strong></span>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::radio('school_type', 'public', true) }}&nbsp;Public School&nbsp;&nbsp;
                                    {{ Form::radio('school_type', 'private', false) }}&nbsp;Private School
                                    {{ Form::radio('school_type', 'home', false) }}&nbsp;Home School
                                </div>
                            </div>

                            <div class="form-row col-md-12">
                                <div class="form-group col-md-6">
                                    <p class="my-0"><strong>Career Interests</strong></p>
                                    {{ Form::label('career_interests', 'Career interests') }}
                                    {{ Form::text('career_interests', '', ['class' => 'form-control', 'rows' => 2]) }}
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
                                    {{ Form::text('dietary_requirements', '', ['class' => 'form-control', 'rows' => 2]) }}
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
                                    {{ Form::text('guardian_name', '', ['class' => 'form-control', 'placeholder'=> 'PARENT / GUARDIAN NAME']) }}
                                    @if ($errors->has('guardian_name'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('guardian_name') }}</strong></span>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('guardian_contact_no', 'Parent / Guardian contact no') }}
                                    {{ Form::text('guardian_contact_no', '', ['class' => 'form-control', 'placeholder'=> 'PARENT / GUARDIAN CONTACT NO']) }}
                                    @if ($errors->has('guardian_contact_no'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('guardian_contact_no') }}</strong></span>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('guardian_email', 'Parent / Guardian email address') }}
                                    {{ Form::text('guardian_email', '', ['class' => 'form-control', 'placeholder'=> 'PARENT / GUARDIAN EMAIL ADDRESS']) }}
                                    @if ($errors->has('guardian_email'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('guardian_email') }}</strong></span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    {{ Form::label('guardian_id_no', 'Parent / Guardian ID/passport') }}
                                    {{ Form::text('guardian_id_no', '', ['class' => 'form-control', 'placeholder'=> 'PARENT / GUARDIAN ID NO']) }}
                                    @if ($errors->has('guardian_id_no'))
                                        <span role="alert"
                                              class="invalid-feedback d-block"><strong>{{ $errors->first('guardian_id_no') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body form-group form-check">

                        <div class="card-body form-group form-check">
                            <div class="container">
                                <h1 class="tcs">T &amp; C's</h1>
                                <a class="tcs-view" href="#" data-toggle="modal" data-target="#tcModal">View here</a>
                            </div>
                            {{ Form::checkbox('tc_accepted', 1, false, ['class' => 'form-check-input']) }}
                            <label for="tc_accepted" class="form-check-label">I agree with the <a href="#" data-toggle="modal" data-target="#tcModal">Terms &amp; Conditions</a>
                            </label>
                            @if ($errors->has('tc_accepted'))
                                <span role="alert"
                                      class="invalid-feedback d-block"><strong>{{ $errors->first('tc_accepted') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
                <br/>
                <div class="container form-container">
                    {{ Form::submit('Complete &amp; Go to Step 2', ['class' => 'btn btn-primary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>


    <!-- Ts & Cs Modal -->
    <div class="modal fade" id="tcModal" tabindex="-1" role="dialog" aria-labelledby="tcModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="background-color:#e5c611;color:white;">
                <div class="modal-header">
                    <h5 class="modal-title" id="tcModalLabel">Terms &amp; Conditions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="tccontainer">
                        <div id="tcs">
                            <div></div>
                            <p>By accessing, browsing or using www.jobshadow.co.za.co.za (“the Website”), you are
                                agreeing to the terms and conditions that appear below (“the Website Terms”) and our
                                Privacy Policy.</p>
                            <ol>
                                <li><strong>Website Terms</strong>
                                    <ol>
                                        <li>You are reading a legal document, which is an agreement between –
                                            <ol>
                                                <li>yourself, the browser or user of the Website or Services described
                                                    herein, referred to as “you”, “yourself” or “your”; and
                                                </li>
                                                <li>the owners of the Website, referred to as “Jobshadow”, being a
                                                    company registered in South Africa situated at 4<sup>th</sup> on
                                                    Anslow Office Park, Anslow Lane, Bryanston 2021.
                                                </li>
                                            </ol>
                                        </li>
                                        <li>By using this Website, you agree that all the terms of this Agreement are
                                            reasonable. If you don’t think that they are reasonable, you must not use
                                            this Website.
                                        </li>
                                        <li>You must be 18 years of age or older to use this Website.</li>
                                        <li>Jobshadow, may, from time to time, in its sole discretion, amend the Website
                                            Terms. Any changes to the Website Terms will be effective immediately upon
                                            the posting of the revised Website Terms on the Website. It is your
                                            responsibility to revisit the Website Terms every time you access the
                                            Website.
                                        </li>
                                        <li>The Website Terms and any further terms contained within the Website shall
                                            in all respects be governed by the laws of the Republic of South Africa.
                                        </li>
                                        <li>South African courts shall have non-exclusive jurisdiction over any claim
                                            arising from, or related to the access, browsing or usage of the Website,
                                            but Jobshadow retains the right to bring proceedings against you for breach
                                            of the Website Terms in your country of residence or any other relevant
                                            country.
                                        </li>
                                    </ol>
                                </li>
                                <li><strong>Website Use</strong>
                                    <ol>
                                        <li>All information of the Website is subject to change without notice.</li>
                                        <li>The Website is provided “as is”, and Jobshadow does not provide any warranty
                                            or guarantee as to the accuracy, timelines, performance, completeness, or
                                            suitability of the information found on the Website.
                                        </li>
                                        <li>You acknowledge that the information on the Website might contain
                                            inaccuracies or errors and Jobshadow expressly excludes its liability for
                                            any such inaccuracies or errors to the fullest extent permitted by
                                            applicable law.
                                        </li>
                                        <li>Your reliance or use of any information contained on the Website is entirely
                                            at your own risk.
                                        </li>
                                        <li>The Website may contain links to other websites. These links are provided
                                            for your convenience only and do not signify Jobshadow’s endorsement of such
                                            linked websites. Jobshadow bears no responsibility for the content and the
                                            privacy practices of such websites.
                                        </li>
                                        <li>Jobshadow reserves the right to prevent you from using the Website for any
                                            reason at any time.
                                        </li>
                                        <li>If you create an account on the Website, you are responsible for maintaining
                                            the security of your account, and you are fully responsible for all actions
                                            taken in connection with it.
                                        </li>
                                        <li>You indemnify Jobshadow against all claims, actions, suits, liabilities,
                                            costs and expenses incurred as a result of using this Website.
                                        </li>
                                        <li>While Jobshadow will implement precautionary measures to ensure that the
                                            Jobshadow website is free from viruses, Jobshadow cannot and does not
                                            guarantee or warrant that files available for downloading through the site
                                            or delivered via electronic mail through the site will be free of infection
                                            or viruses, worms, Trojan horses or other code that manifest contaminating
                                            or destructive properties.
                                        </li>
                                        <li>You agree, at all times, to deal with any information provided by Jobshadow
                                            in a manner which abides by all applicable laws of South Africa.
                                        </li>
                                        <li>You agree to keep Jobshadow and its associates fully indemnified against any
                                            actual or contingent liabilities incurred in relation to any actions or
                                            claims brought by any person against Jobshadow as a result of an actual or
                                            alleged breach by you of any law, or such other actions or claims brought in
                                            relation to the provision of services by Jobshadow to you.
                                        </li>
                                        <li>You warrant and guarantee that any content you upload to the Website will be
                                            free from viruses and will not contain any illegal content and its
                                            publication will not violate any applicable laws and will not infringe any
                                            third party rights.
                                        </li>
                                        <li>You agree not to use the Website to market any services to Jobshadow.</li>
                                    </ol>
                                </li>
                                <li><strong>SERVICES</strong>
                                    <ol>
                                        <li>Connecting Scholars to host companies through the use of the Website, and
                                            thereby providing Scholars the opportunity to learn about a particular
                                            industry or occupation and to gain valuable work skills through job
                                            shadowing.
                                        </li>
                                    </ol>
                                </li>
                                <li><strong>SIGN-IN AND PAYMENT</strong>
                                    <ol>
                                        <li>You may access and use the Website without providing any personal
                                            information.
                                        </li>
                                        <li>However, should you wish to utilise the services and the entire Website, you
                                            will need to:
                                            <ol>
                                                <li>register by supplying Jobshadow with your name, surname, identity /
                                                    passport number, email address, mobile telephone number and a unique
                                                    password;
                                                </li>
                                                <li>accept the further terms and conditions applicable to the Privacy
                                                    Policy; and
                                                </li>
                                                <li>pay the Placement Fee displayed when prompted in the placement
                                                    process. (<strong>“Registration / Register”</strong>).
                                                </li>
                                            </ol>
                                        </li>
                                        <li>Once you have Registered, Jobshadow will:
                                            <ol>
                                                <li>send a One-Time-Password (OTP) to the mobile phone number supplied
                                                    by you during the Registration process; and
                                                </li>
                                                <li>send an email to your chosen email address. You will have to verify
                                                    your details by clicking on the link provided in the email.
                                                </li>
                                            </ol>
                                        </li>
                                        <li>Jobshadow reserves the right to reject any Registration for any reason.</li>
                                        <li>You must provide Jobshadow with a valid email address that you access
                                            regularly. Jobshadow may require you to re-validate your account if
                                            Jobshadow believes you are using an invalid email address.
                                        </li>
                                        <li>You must keep the password confidential, and you herewith agree that any
                                            person to whom your password is disclosed is authorised to act as your agent
                                            for the purpose of using the Website. You must immediately notify us if any
                                            unauthorised third party becomes aware of that password, if there is any
                                            unauthorised use of your email address, or any breach of security known to
                                            you.
                                        </li>
                                        <li>You can update your Registration details on the Website.</li>
                                        <li>If you experience any problems with Signing In or updating your details,
                                            kindly email info@jabshadow.co.za.
                                        </li>
                                        <li>All payments are done online through PayFast – we do not keep any personal
                                            information in this regard. We accept: VISA, MASTERCARD and DEBIT CARDS– a
                                            full list of supplier logos can be found in the website footer on the home
                                            page. Payment can be made by [ EFT, debit card, instant bank transfer credit
                                            card, where payment is made by credit card, we may require additional
                                            information in order to authorise and/or verify the validity of payment. You
                                            warrant that you are fully authorised to use the credit card supplied for
                                            purposes of paying the Costs. You also warrant that your credit card has
                                            sufficient available funds to cover all the costs incurred as a result of
                                            the services used on the Website]
                                        </li>
                                    </ol>
                                </li>
                                <li><strong>INTELLECTUAL PROPERTY</strong>
                                    <ol>
                                        <li>We own or are licensed to use all intellectual property rights in all
                                            materials, text, drawings and data (collectively “the Materials”) made
                                            available via the Website.
                                        </li>
                                        <li>Any unauthorised reproduction, distribution, derivative creation, sale,
                                            broadcast or other circulation or exploitation of the whole or any part of
                                            the Materials is an infringement of our rights.
                                        </li>
                                        <li>All Jobshadow’s intellectual property and associated rights thereto
                                            (inclusive of, but not limited to trademark and copyright) remain the
                                            intellectual property and rights of Jobshadow and are not transferred to you
                                            by virtue this Agreement.
                                        </li>
                                    </ol>
                                </li>
                                <li><strong>&nbsp;</strong><strong>PRIVACY</strong>
                                    <ol>
                                        <li>Jobshadow is committed to protecting your privacy and will only use the
                                            information that it collects about you lawfully in accordance with the
                                            Electronic Communications and Transactions Act 25 of 2002, the Consumer
                                            Protection Act 68 of 2008, the Protection of Personal Information Act 4 of
                                            2013 (“POPI Act”) and any other applicable data protection laws enacted in
                                            South Africa from time to time.
                                        </li>
                                        <li>Transmitting data over the internet cannot be warranted to be 100% secure.
                                            Whilst we take all reasonable precautions to safeguard your information, we
                                            may be unable to prevent unauthorised access to, or unintentional disclosure
                                            of such information by third parties and we are not responsible for any such
                                            access or disclosure. You acknowledge this risk when accessing, browsing or
                                            using the Website.
                                        </li>
                                        <li>It is your responsibility to treat any ID, password or username or other
                                            security device provided for the use of the services with due diligence and
                                            due care and to take all necessary steps to ensure that they are kept
                                            confidential, secure, are used properly and are not disclosed to
                                            unauthorized persons.
                                        </li>
                                        <li>You must immediately inform Jobshadow of any authorized use of your
                                            password.
                                        </li>
                                    </ol>
                                </li>
                                <li><strong>NOTICES</strong>
                                    <ol>
                                        <li>Jobshadow may need to communicate with you for various reasons, and
                                            accordingly we may notify you by sending a message to you at your contact
                                            information provided (e.g., email, mobile number, physical address). You
                                            agree to keep your contact information up to date.
                                        </li>
                                    </ol>
                                </li>
                                <li><strong>LIMITATION OF LIABILITY</strong>
                                    <ol>
                                        <li>Jobshadow shall not be liable for any direct, indirect, incidental,&nbsp;special
                                            or consequential loss or damages which might arise from your&nbsp;use of, or
                                            reliance upon, the Website or the content contained in the&nbsp;Website; or
                                            your inability to use the Website, and/or unlawful activity&nbsp;on the
                                            Website and/or any linked third party Website.
                                        </li>
                                        <li>You hereby indemnify Jobshadow against any loss, claim or damage which&nbsp;may
                                            be suffered by yourself or any third party arising in any way from
                                            <ol>
                                                <li>your use of this Website and/or;</li>
                                                <li>your use of any linked third party Website and /or;</li>
                                                <li>the services prescribed in this Website.</li>
                                            </ol>
                                        </li>
                                    </ol>
                                </li>
                                <li><strong>GENERAL TERMS</strong>
                                    <ol>
                                        <li>Jobshadow may assume that all electronic communications which reasonably
                                            appear to originate from you are in fact from the User and the form in which
                                            Jobshadow receives the communication is the same as when it was first sent.
                                        </li>
                                        <li>Jobshadow may send alerts, notifications and other communications to you by
                                            way of SMS, email or other electronic delivery mechanisms and you consent to
                                            receive communications from Jobshadow in any such manner.
                                        </li>
                                        <li>Jobshadow may send electronic alerts to the cellular number or email address
                                            which you provided to Jobshadow.
                                        </li>
                                        <li>This Agreement constitutes the entire agreement between the parties and no
                                            warranties, representations, or other terms and conditions of whatsoever
                                            nature not expressly recorded herein will be of any force or effect.
                                        </li>
                                        <li>The parties hereto agree that no extension of time, acceptance of late
                                            performance, or any other indulgence will constitute a waiver of any party’s
                                            rights in terms of this Agreement or in law, and will be at all times
                                            entirely without prejudice to such rights.
                                        </li>
                                        <li>Save as expressly provided for herein, none of the parties will be entitled
                                            to cede or assign this Agreement or any of their rights and obligations
                                            herein except with the written consent of the other parties, which consent
                                            will not be unreasonably withheld.
                                        </li>
                                        <li>In the event that any of the provisions of this Agreement are found to be
                                            invalid, unlawful, or unenforceable such terms will be severed from the
                                            remaining terms which will continue to be valid and enforceable.
                                        </li>
                                        <li>If you are in breach of this Agreement, Jobshadow is entitled to, amongst
                                            other rights, without notice to you, immediately interrupt the operation of
                                            its Services, suspend your access to the Website, and/or bringing court
                                            proceedings against you.
                                        </li>
                                        <li>A certificate signed by any one of our directors of Jobshadow will, unless
                                            the contrary is proven, be sufficient evidence of –
                                            <ol>
                                                <li>the date of publication and the content of the Terms and any amended
                                                    Website Terms;
                                                </li>
                                                <li>the date of publication and the content of earlier versions of the
                                                    Website Terms; and
                                                </li>
                                                <li>the date and content of any communication and notifications sent in
                                                    terms of the Website Terms.
                                                </li>
                                            </ol>
                                        </li>
                                    </ol>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" style="background-color: #7dc600;border-color:#7dc600;"
                            class="btn btn-secondary" data-dismiss="modal">Close
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection