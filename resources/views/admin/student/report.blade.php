@extends('layouts.admin')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Student</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Student</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Student Report</h3>
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addStudentModal">
                            <i class="fas fa-plus"></i> Add Student
                        </button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="report" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile No</th>
                                    <th>Date of Birth</th>
                                    <th>Gender</th>
                                    <th>City</th>
                                    <th>Hobbies</th>
                                    <th>Profile Image</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>

{{-- Add Student Model --}}
<div class="modal fade" id="addStudentModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="addStudentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Student</h4>
                <button type="button" class="close modelCloseBtn" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="add_student_form" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="Name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="Email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email Address">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="Mobile no" class="form-label">Mobile no <span class="text-danger">*</span></label>
                            <input type="number" name="mobile" class="form-control" id="mobile_no" placeholder="Enter Mobile no">
                        </div>
                        <div class="col-md-4 mt-3 form-group">
                            <label for="Password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                        </div>
                        <div class="col-md-4 mt-3 form-group">
                            <label for="Confirm Password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Enter Confirm Password">
                        </div>
                        <div class="col-md-4 mt-3 form-group">
                            <label for="Date of birth" class="form-label">Date of birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" placeholder="Enter Date of birth">
                        </div>
                        <div class="col-md-4 mt-3 form-group">
                            <label for="Gender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="" selected>Select</option>
                                <option value="male">male</option>
                                <option value="female">female</option>
                                <option value="other">other</option>
                            </select>
                        </div>
                        <div class="col-md-4 mt-3 form-group">
                            <label for="City" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" id="city" class="form-control" placeholder="Enter City">
                        </div>
                        <div class="col-md-4 mt-3 form-group">
                            <label for="Hobbies" class="form-label">Hobbies</label>
                            <input type="text" name="hobbies" id="hobbies" class="form-control" placeholder="Enter Hobbies">
                        </div>
                        <div class="col-md-4 mt-3 form-group">
                            <label for="Profile Image" class="form-label">Profile Image <span class="text-danger">*jpg, jpeg, png, webp</span></label>
                            <input type="file" name="profile_image" id="profile_image" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="addStudentBtn" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

{{-- Edit Student Model --}}
<div class="modal fade" id="editStudentModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="editStudentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Student</h4>
                <button type="button" class="close modelEditCloseBtn" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="edit_student_form" enctype="multipart/form-data">
                <div class="modal-body" id="selected_append_student">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" id="UpdateStudentBtn" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection
@section('page_script')
@include('admin.student.script.main_script')
@endsection