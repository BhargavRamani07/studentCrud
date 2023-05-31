<input type="hidden" id="sid" value="{{ $student->id }}">
<div class="row">
    <div class="col-md-4 form-group">
        <label for="Name" class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" id="es_name" placeholder="Enter Name" value="{{ $student->name }}">
    </div>
    <div class="col-md-4 form-group">
        <label for="Email" class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control" id="es_email" placeholder="Enter Email Address" value="{{ $student->email }}">
    </div>
    <div class="col-md-4 form-group">
        <label for="Mobile no" class="form-label">Mobile no <span class="text-danger">*</span></label>
        <input type="number" name="mobile" class="form-control" id="es_mobile_no" placeholder="Enter Mobile no" value="{{ $student->mobile }}">
    </div>
    <div class="col-md-4 mt-3 form-group">
        <label for="Password" class="form-label">Password <span class="text-danger">*</span></label>
        <input type="password" name="password" id="es_password" class="form-control" placeholder="Enter Password">
    </div>
    <div class="col-md-4 mt-3 form-group">
        <label for="Confirm Password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
        <input type="password" name="confirm_password" class="form-control" placeholder="Enter Confirm Password">
    </div>
    <div class="col-md-4 mt-3 form-group">
        <label for="Date of birth" class="form-label">Date of birth <span class="text-danger">*</span></label>
        <input type="date" name="date_of_birth" id="es_date_of_birth" class="form-control" placeholder="Enter Date of birth" value="{{ $student->date_of_birth }}">
    </div>
    <div class="col-md-4 mt-3 form-group">
        <label for="Gender" class="form-label">Gender <span class="text-danger">*</span></label>
        <select name="gender" id="es_gender" class="form-control">
            <option value="" selected>Select</option>
            <option value="male" {{ $student->gender == "male" ? "selected" : ''}}>male</option>
            <option value="female" {{ $student->gender == "female" ? "selected" : ''}}>female</option>
            <option value="other" {{ $student->gender == "other" ? "selected" : ''}}>other</option>
        </select>
    </div>
    <div class="col-md-4 mt-3 form-group">
        <label for="City" class="form-label">City <span class="text-danger">*</span></label>
        <input type="text" name="city" id="es_city" class="form-control" placeholder="Enter City" value="{{ $student->city }}">
    </div>
    <div class="col-md-4 mt-3 form-group">
        <label for="Hobbies" class="form-label">Hobbies</label>
        <input type="text" name="hobbies" id="es_hobbies" class="form-control" placeholder="Enter Hobbies" value="{{ $student->hobbies }}">
    </div>
    <div class="col-md-4 mt-3 form-group">
        <label for="Profile Image" class="form-label">Profile Image <span class="text-danger">*jpg, jpeg, png, webp</span></label>
        <input type="file" name="profile_image" id="es_profile_image" class="form-control">
    </div>
</div>