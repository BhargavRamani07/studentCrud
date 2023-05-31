<script>
    $(document).ready(function() {

        var SITEURL = '{{ URL::to("/") }}';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var studentReport = $("#report").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            'processing': true,
            'serverSide': true,
            'pagination': true,
            'serverMethod': 'post',
            'ajax': {
                'url': SITEURL + "/admin/getStudents"
            },
            "deferRender": true,
            'columns': [{
                    data: 'id'
                },
                {
                    data: 'action'
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'mobile_no'
                },
                {
                    data: 'date_of_birth'
                },
                {
                    data: 'gender'
                },
                {
                    data: 'city'
                },
                {
                    data: 'hobbies'
                },
                {
                    data: 'image'
                }
            ],
        });

        // Remove All input and dropdown value from the model when model is closed
        $(document).on("click", ".modelCloseBtn", function() {
            $("#name").val(null);
            $("#email").val(null);
            $("#mobile_no").val(null);
            $("#date_of_birth").val(null);
            $("#password").val(null);
            $("#city").val(null);
            $("#gender").val(null);
            $("#hobbies").val(null);
            $('#profile_image').val(null);

            $('#addStudentModal').modal('hide');
            $("input").removeClass('is-invalid');
            $("select").removeClass('is-invalid');
        });

        $(document).on("click", ".modelEditCloseBtn", function() {
            $("#es_name").val(null);
            $("#es_email").val(null);
            $("#es_mobile_no").val(null);
            $("#es_date_of_birth").val(null);
            $("#es_password").val(null);
            $("#es_city").val(null);
            $("#es_gender").val(null);
            $("#es_hobbies").val(null);
            $('#es_profile_image').val(null);

            $('#editStudentModal').modal('hide');
            $("input").removeClass('is-invalid');
            $("select").removeClass('is-invalid');
        });

        // Add Student Form Validation And Insert Student
        $('#add_student_form').validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                },
                mobile: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                },
                password: {
                    required: true,
                    minlength: 5
                },
                confirm_password: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password"
                },
                date_of_birth: {
                    required: true
                },
                gender: {
                    required: true
                },
                city: {
                    required: true
                },
                profile_image: {
                    required: true,
                    extension: "jpg|jpeg|png|webp"
                }
            },
            messages: {
                email: {
                    required: "Please enter a email address",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                confirm_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long",
                    equalTo: "confirm password is does not match"
                },
                profile_image: {
                    required: "Please upload file.",
                    extension: "Please upload file in these format only (jpg, jpeg, png, webp)."
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },

            submitHandler: function(f, event) {
                event.preventDefault();

                var formData = new FormData();

                formData.append('name', $("#name").val());
                formData.append('email', $("#email").val());
                formData.append('mobile_no', $("#mobile_no").val());
                formData.append('date_of_birth', $("#date_of_birth").val());
                formData.append('password', $("#password").val());
                formData.append('city', $("#city").val());
                formData.append('gender', $("#gender").val());
                formData.append('hobbies', $("#hobbies").val());
                formData.append('profile_image', $('#profile_image')[0].files[0]);

                $.ajax({
                    url: SITEURL + "/admin/addStudent",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#addStudentBtn").prop("disabled", true);
                    },
                    success: function(response) {
                        response = JSON.parse(response);
                        try {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    text: response.message
                                });
                                $('#addStudentModal').modal('hide')
                                studentReport.clear().draw();

                                $("#name").val(null);
                                $("#email").val(null);
                                $("#mobile_no").val(null);
                                $("#date_of_birth").val(null);
                                $("#password").val(null);
                                $("#city").val(null);
                                $("#gender").val(null);
                                $("#hobbies").val(null);
                                $('#profile_image').val(null);

                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message
                                })
                            }
                        } catch (e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: error.message
                            })
                        }
                    },
                    error: function(error) {
                        var errors = error.responseJSON;
                        var errorsHtml = '';
                        $.each(errors, function(key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong',
                            text: errorsHtml
                        })
                    },
                    complete: function() {
                        $("#addStudentBtn").prop("disabled", false);
                    },
                });
            }
        });

        // Edit click Show the modal
        $(document).on("click", ".editStudentModalBtn", function() {
            var sid = $(this).attr('data-sid');
            $("#selected_append_student").html(null);

            $.ajax({
                type: "post",
                url: SITEURL + "/admin/getStudent",
                data: {
                    sid: sid
                },
                success: function(response) {
                    try {
                        if (response.status != false) {
                            $('#editStudentModal').modal('show');
                            $("#selected_append_student").html(response)
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message
                            })
                        }
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.message
                        })
                    }
                },
                error: function(error) {
                    var errors = error.responseJSON;
                    var errorsHtml = '';
                    $.each(errors, function(key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong',
                        text: errorsHtml
                    })
                },
            });
        });

        $('#edit_student_form').validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                },
                mobile: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                },
                password: {
                    minlength: 5
                },
                confirm_password: {
                    minlength: 5,
                    equalTo: "#password"
                },
                date_of_birth: {
                    required: true
                },
                gender: {
                    required: true
                },
                city: {
                    required: true
                },
                profile_image: {
                    extension: "jpg|jpeg|png|webp"
                }
            },
            messages: {
                email: {
                    required: "Please enter a email address",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                confirm_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long",
                    equalTo: "confirm password is does not match"
                },
                profile_image: {
                    extension: "Please upload file in these format only (jpg, jpeg, png, webp)."
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },

            submitHandler: function(f, event) {
                event.preventDefault();

                var formData = new FormData();

                formData.append('sid', $("#sid").val());
                formData.append('name', $("#es_name").val());
                formData.append('email', $("#es_email").val());
                formData.append('mobile', $("#es_mobile_no").val());
                formData.append('date_of_birth', $("#es_date_of_birth").val());
                formData.append('password', $("#es_password").val());
                formData.append('city', $("#es_city").val());
                formData.append('gender', $("#es_gender").val());
                formData.append('hobbies', $("#es_hobbies").val());
                formData.append('profile_image', $('#es_profile_image')[0].files[0]);

                $.ajax({
                    url: SITEURL + "/admin/updateStudent",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#UpdateStudentBtn").prop("disabled", true);
                    },
                    success: function(response) {
                        response = JSON.parse(response);
                        try {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    text: response.message
                                });
                                $('#editStudentModal').modal('hide')
                                studentReport.clear().draw();

                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message
                                })
                            }
                        } catch (e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: error.message
                            })
                        }
                    },
                    error: function(error) {
                        var errors = error.responseJSON;
                        var errorsHtml = '';
                        $.each(errors, function(key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong',
                            text: errorsHtml
                        })
                    },
                    complete: function() {
                        $("#UpdateStudentBtn").prop("disabled", false);
                    },
                });
            }
        });

        $(document).on("click", ".deleteStudentBtn", function() {
            if( !confirm('Are you sure you want to delete this student?')) {
                return false;
            }
            var sid = $(this).attr('data-sid');

            $.ajax({
                type: "post",
                url: SITEURL + "/admin/deleteStudent",
                data: {
                    sid: sid
                },
                success: function(response) {
                    try {
                        if (response.status != false) {
                            studentReport.clear().draw();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message
                            })
                        }
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.message
                        })
                    }
                },
                error: function(error) {
                    var errors = error.responseJSON;
                    var errorsHtml = '';
                    $.each(errors, function(key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong',
                        text: errorsHtml
                    })
                },
            });
        });


    });
</script>