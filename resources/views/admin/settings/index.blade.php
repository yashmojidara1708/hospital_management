@extends('admin.layouts.index')

@section('admin-title', 'Settings')
@section('page-title', 'Settings')

@section('admin-content')
    <form id="settings-form" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="hospital_name">Hospital Name</label>
            <input type="text" name="hospital_name" class="form-control" value="{{ $settings['hospital_name'] ?? '' }}"
                required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" class="form-control" rows="3">{{ $settings['address'] ?? '' }}</textarea>
        </div>

        <div class="form-group">
            <label for="country">Country</label>
            <select name="country" class="form-control" id="country">
                <option value="">Select Country</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->name }}"
                        {{ old('country', $settings['country'] ?? '') == $country->name ? 'selected' : '' }}>
                        {{ $country->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="state">State</label>
            <input type="text" name="state" class="form-control" value="{{ $settings['state'] ?? '' }}">
        </div>

        <div class="form-group">
            <label for="city">City</label>
            <input type="text" name="city" class="form-control" value="{{ $settings['city'] ?? '' }}">
        </div>

        <div class="form-group">
            <label for="zipcode">Zip Code</label>
            <input type="text" name="zipcode" class="form-control" value="{{ $settings['zipcode'] ?? '' }}">
        </div>

        {{-- <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" class="form-control" value="{{ $settings['phone_number'] ?? '' }}">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $settings['email'] ?? '' }}">
        </div> --}}
        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" class="form-control" value="{{ $settings['phone_number'] ?? '' }}">
            <small class="form-text text-muted">Use commas (,) to separate multiple phone numbers.</small>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" name="email" class="form-control" value="{{ $settings['email'] ?? '' }}">
            <small class="form-text text-muted">Use commas (,) to separate multiple email addresses.</small>
        </div>

        <div class="form-group">
            <label for="company_logo">Company Logo</label>
            <input type="file" name="company_logo" class="form-control">
            @if (!empty($settings['company_logo']))
                <img id="company_logo_preview" src="{{ asset('uploads/' . $settings['company_logo']) }}" alt="Company Logo" width="100">
            @endif
        </div>
        
        <div class="form-group">
            <label for="favicon">Favicon</label>
            <input type="file" name="favicon" class="form-control">
            @if (!empty($settings['favicon']))
                <img id="favicon_preview" src="{{ asset('uploads/' . $settings['favicon']) }}" alt="Favicon" width="50">
            @endif
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
    </form>
@endsection

@section('admin-js')
    <script>
        $(document).ready(function() {
            $.validator.addMethod("multipleEmails", function(value, element) {
                // Split the value by commas and validate each email
                var emails = value.split(',');
                for (var i = 0; i < emails.length; i++) {
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emails[i].trim())) {
                        return false;
                    }
                }
                return true;
            }, "Please enter valid email addresses separated by commas.");

            $.validator.addMethod("multiplePhoneNumbers", function(value, element) {
                // Split the value by commas and validate each phone number
                var phoneNumbers = value.split(',');
                for (var i = 0; i < phoneNumbers.length; i++) {
                    if (!/^\d{10}$/.test(phoneNumbers[i].trim())) {
                        return false;
                    }
                }
                return true;
            }, "Please enter valid phone numbers separated by commas.");

            $("form[id='settings-form']").validate({
                rules: {
                    // Other rules...
                    phone_number: { required: true, multiplePhoneNumbers: true },
                    email: { required: true, multipleEmails: true },
                },
                messages: {
                    // Other messages...
                    phone_number: {
                        required: "Please enter the phone number.",
                        multiplePhoneNumbers: "Please enter valid phone numbers separated by commas."
                    },
                    email: {
                        required: "Please enter the email address.",
                        multipleEmails: "Please enter valid email addresses separated by commas."
                    }
                },
                submitHandler: function(form) {
                    var formData = new FormData($("#settings-form")[0]);
                    $('#loader-container').show();

                    $.ajax({
                        url: "/admin/settings/update",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function(response) {
                            $('#loader-container').hide();
                            if (response.status == 1) {
                                toastr.success(response.message);
                                $('#settings-form')[0].reset();
                                location.reload();
                            } else {
                                toastr.error("Failed to update settings.");
                            }
                        },
                        error: function(xhr) {
                            $('#loader-container').hide();
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
