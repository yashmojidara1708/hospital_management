@extends('admin.layouts.index')

@section('admin-title', 'Settings')
@section('page-title', 'Settings')

@section('admin-content')
    <form id="settings-form" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="hospital_name">Hospital Name</label>
            <input type="text" name="hospital_name" class="form-control" value="{{ $settings['hospital_name'] ?? '' }}" required>
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
                    <option value="{{ $country->name }}" {{ old('country', $settings['country'] ?? '') == $country->name ? 'selected' : '' }}>
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
            <input type="file" name="company_logo" class="form-control" id="company_logo">
            @if (!empty($settings['company_logo']))
                <img id="company_logo_preview" src="{{ asset('uploads/' . $settings['company_logo']) }}" alt="Company Logo" width="100">
            @else
                <img id="company_logo_preview" src="" alt="Company Logo Preview" width="100" style="display: none;">
            @endif
        </div>
        
        <div class="form-group">
            <label for="favicon">Favicon</label>
            <input type="file" name="favicon" class="form-control" id="favicon">
            @if (!empty($settings['favicon']))
                <img id="favicon_preview" src="{{ asset('uploads/' . $settings['favicon']) }}" alt="Favicon" width="50">
            @else
                <img id="favicon_preview" src="" alt="Favicon Preview" width="50" style="display: none;">
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
            // Function to preview image
            function previewImage(input, previewId) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(previewId).attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Handle company logo file input change
            $('#company_logo').on('change', function() {
                previewImage(this, '#company_logo_preview');
            });

            // Handle favicon file input change
            $('#favicon').on('change', function() {
                previewImage(this, '#favicon_preview');
            });

            // Validation and AJAX submission
            $.validator.addMethod("multipleEmails", function(value, element) {
                var emails = value.split(',');
                for (var i = 0; i < emails.length; i++) {
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emails[i].trim())) {
                        return false;
                    }
                }
                return true;
            }, "Please enter valid email addresses separated by commas.");

            $.validator.addMethod("multiplePhoneNumbers", function(value, element) {
                var phoneNumbers = value.split(',');
                for (var i = 0; i < phoneNumbers.length; i++) {
                    if (!/^\d{10}$/.test(phoneNumbers[i].trim())) {
                        return false;
                    }
                }
                return true;
            }, "Please enter valid phone numbers separated by commas.");

            $.validator.addMethod("fileSize", function(value, element, param) {
                if (element.files.length === 0) {
                    return true; // Skip validation if no file is selected
                }
                return element.files[0].size <= param;
            }, "File size must be less than {0} bytes.");

            $("form[id='settings-form']").validate({
                rules: {
                    hospital_name: { required: true },
                    address: { required: true, maxlength: 50 },
                    country: { required: true },
                    state: { required: true },
                    city: { required: true },
                    zipcode: { required: true, digits: true, minlength: 6, maxlength: 6 },
                    phone_number: { required: true, multiplePhoneNumbers: true },
                    email: { required: true, multipleEmails: true },
                    company_logo: { 
                        required: function() {
                            return $("img#company_logo_preview").length === 0;
                        },
                        extension: "jpg|jpeg|png|gif",
                        fileSize: 5 * 1024 * 1024 // 5MB
                    },
                    favicon: { 
                        required: function() {
                            return $("img#favicon_preview").length === 0;
                        },
                        extension: "jpg|jpeg|png|gif",
                        fileSize: 5 * 1024 * 1024 // 5MB
                    }
                },
                messages: {
                    hospital_name: "Please enter the hospital name.",
                    address: "Maximum 50 characters allowed.",
                    country: "Please select a country.",
                    state: "Please enter the state.",
                    city: "Please enter the city.",
                    zipcode: {
                        required: "Please enter the ZIP code.",
                        digits: "ZIP code must be numbers only.",
                        minlength: "ZIP code must be exactly 6 digits.",
                        maxlength: "ZIP code must be exactly 6 digits."
                    },
                    phone_number: {
                        required: "Please enter the phone number.",
                        multiplePhoneNumbers: "Please enter valid phone numbers separated by commas."
                    },
                    email: {
                        required: "Please enter the email address.",
                        multipleEmails: "Please enter valid email addresses separated by commas."
                    },
                    company_logo: {
                        required: "Please upload a company logo.",
                        extension: "Only JPG, JPEG, PNG, or GIF files are allowed.",
                        fileSize: "File size must be less than 5MB."
                    },
                    favicon: {
                        required: "Please upload a favicon.",
                        extension: "Only JPG, JPEG, PNG, or GIF files are allowed.",
                        fileSize: "File size must be less than 5MB."
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
                                location.reload(); // Reload the page to reflect changes
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