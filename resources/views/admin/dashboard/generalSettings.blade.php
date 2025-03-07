@extends('admin.layouts.index')
@section('admin-title', 'General')
@section('center-title', 'Settings')
@section('center-title-route', route('admin.generalSettings'))
@section('page-title', 'General Settings')
@section('admin-content')
<div class="row">
    <div class="col-12">
        <!-- General -->
        
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">General</h4>
                </div>
                <div class="card-body">
                    <form name="generalsettingsForm" id="generalsettingsForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Website Name</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <div class="form-group">
                            <label>Website Logo</label>
                            <input type="file" class="form-control" name="logo" id="logo">
                            <small class="text-secondary">Recommended image size is <b>150px x 150px</b></small>
                        </div>
                        <div class="form-group mb-0">
                            <label>Favicon</label>
                            <input type="file" class="form-control" name="favicon" id="favicon">
                            <small class="text-secondary">Recommended image size is <b>16px x 16px</b> or <b>32px x 32px</b></small><br>
                            <small class="text-secondary">Accepted formats : only png and ico</small>
                        </div>
                        <button class="btn btn-primary" type="submit" id="generalsettings">Save Changes</button>
                       
                    </form>
                </div>
            </div>
        
        <!-- /General -->
            
    </div>
</div>
@endsection
@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/Generalsettings.js') }}"></script>
@endsection
