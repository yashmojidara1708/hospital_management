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
                    <option value="{{ $country->name }}" {{ ($settings['country'] ?? '') == $country->name ? 'selected' : '' }}>
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
            @if(!empty($settings['company_logo']))
                <img src="{{ asset('uploads/'.$settings['company_logo']) }}" alt="Company Logo" width="100">
            @endif
        </div>

        <div class="form-group">
            <label for="favicon">Favicon</label>
            <input type="file" name="favicon" class="form-control">
            @if(!empty($settings['favicon']))
                <img src="{{ asset('uploads/'.$settings['favicon']) }}" alt="Favicon" width="50">
            @endif
        </div>
        
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
    </form>
@endsection

@section('admin-js')
<script>
    $(document).ready(function () {
        $("#settings-form").on("submit", function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "/admin/settings/update",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        text: "Settings updated successfully!",
                        confirmButtonColor: "#3085d6"
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Failed to update settings. Please try again.",
                        confirmButtonColor: "#d33"
                    });
                }
            });
        });
    });
</script>
@endsection
