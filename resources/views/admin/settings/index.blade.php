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

        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" class="form-control" value="{{ $settings['phone_number'] ?? '' }}">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $settings['email'] ?? '' }}">
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
            $.validator.addMethod("fileSize", function(value, element, param) {
                if (element.files.length === 0) {
                    return true;
                }
                return element.files[0].size <= param;
            }, "File size must be between 1MB and 5MB.");

            $.validator.addMethod("customEmail", function(value, element) {
                return this.optional(element) || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            }, "Please enter a valid email address.");

            $("form[id='settings-form']").validate({
                rules: {
                    hospital_name: { required: true },
                    address: { required: true, maxlength: 50 },
                    country: { required: true },
                    state: { required: true },
                    city: { required: true },
                    zipcode: { required: true, digits: true, minlength: 6, maxlength: 6 },
                    phone_number: { required: true, minlength: 10, maxlength: 10 },
                    email: { required: true, customEmail: true },
                    company_logo: { 
                        required: function() {
                            return $("img#company_logo_preview").length === 0; // Only required if no preview image
                        },
                        extension: "jpg|jpeg|png|gif",
                        fileSize: 5 * 1024 * 1024 
                    },
                    favicon: { 
                        required: function() {
                            return $("img#favicon_preview").length === 0; // Only required if no preview image
                        },
                        extension: "jpg|jpeg|png|gif",
                        fileSize: 5 * 1024 * 1024 
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
                        digits: "Phone number must be numbers only."
                    },
                    email: {
                        required: "Please enter the email address.",
                        customEmail: "Please enter a valid email address."
                    },
                    company_logo: {
                        required: "Please upload a company logo.",
                        extension: "Only JPG, JPEG, PNG, or GIF files are allowed.",
                        fileSize: "File size must be between 1MB and 5MB."
                    },
                    favicon: {
                        required: "Please upload a favicon.",
                        extension: "Only JPG, JPEG, PNG, or GIF files are allowed.",
                        fileSize: "File size must be between 1MB and 5MB."
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
