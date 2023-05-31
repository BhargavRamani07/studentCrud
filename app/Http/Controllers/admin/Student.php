<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Student as ModelsStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Student extends Controller
{
    public function report()
    {
        return view('admin.student.report');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'name' => 'required',
                'email' => 'required|email|unique:students,email',
                'mobile_no' => 'required|numeric|digits:10|unique:students,mobile',
                'password' => 'required|min:5',
                'date_of_birth' => 'required',
                'gender' => 'required',
                'city' => 'required',
                'hobbies' => 'nullable',
                'profile_image' => 'required|file|mimes:png,jpg,jpeg,webp',
            ]);
            if ($validator->fails()) {
                $res = array(
                    'status' => false,
                    'message' => $validator->errors()->first()
                );
            } else {
                unset($data['_token']);

                // if (!empty($request->post("profile_image"))) {
                if ($request->hasFile('profile_image')) {

                    $p_image = $request->file('profile_image');

                    $original_name = $p_image->extension();
                    $file_name = rand(5997507956097, 5674433321234) . '.' . $original_name;
                    $p_image->move(public_path('upload/student_image'), $file_name);
                }
                // }

                $insertArray = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'mobile' => $data['mobile_no'],
                    'password' => Hash::make($data['password']),
                    'date_of_birth' => $data['date_of_birth'],
                    'gender' => $data['gender'],
                    'city' => $data['city'],
                    'hobbies' => $data['hobbies'],
                    'profile_image' => $file_name,
                    'user_id' => Auth::user()->id,
                ];

                $student = ModelsStudent::create($insertArray);

                if ($student) {
                    $res = array(
                        'status' => true,
                        "message" => "Student Added successfully",
                    );
                } else {
                    $res = array(
                        'status' => false,
                        "message" => "Student Adding failed",
                    );
                }
            }
        } catch (Exception $e) {
            $res = array(
                'status' => false,
                "message" => $e->getMessage(),
            );
        }

        echo json_encode($res);
    }


    public function getSingleStudent(Request $request)
    {
        try {
            $student_id = Crypt::decrypt($request->post('sid'));

            $student = ModelsStudent::where("user_id", "=", Auth::user()->id)->where("id", "=", $student_id)->first();

            if ($student) {
                return view('admin.student.edit_modal', compact('student'));
            } else {
                $res = array(
                    'status' => false,
                    "message" => 'Student Not Found',
                );
            }
        } catch (Exception $e) {
            $res = array(
                'status' => false,
                "message" => $e->getMessage(),
            );
        }

        echo json_encode($res);
    }

    public function updateStudent(Request $request)
    {
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'name' => 'required',
                'email' => ['required', 'email', Rule::unique('students')->ignore($data['sid'])],
                'mobile' => ['required', 'numeric', 'digits:10', Rule::unique('students')->ignore($data['sid'])],
                'password' => 'nullable|min:5',
                'date_of_birth' => 'required',
                'gender' => 'required',
                'city' => 'required',
                'hobbies' => 'nullable',
            ]);
            if ($validator->fails()) {
                $res = array(
                    'status' => false,
                    'message' => $validator->errors()->first()
                );
            } else {
                unset($data['_token']);

                $student = ModelsStudent::where('id', '=', $data['sid'])->first();

                if ($request->hasFile('profile_image')) {

                    $p_image = $request->file('profile_image');

                    $original_name = $p_image->extension();
                    $file_name = rand(5997507956097, 5674433321234) . '.' . $original_name;
                    $p_image->move(public_path('upload/student_image'), $file_name);

                    if (File::exists("upload/student_image/" . $student->profile_image)) {
                        File::delete("upload/student_image/" . $student->profile_image);
                    }
                }

                $updateArray = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'mobile' => $data['mobile'],
                    'password' => Hash::make($data['password']),
                    'date_of_birth' => $data['date_of_birth'],
                    'gender' => $data['gender'],
                    'city' => $data['city'],
                    'hobbies' => $data['hobbies'],
                    'profile_image' => $file_name ?? $student->profile_image,
                    'user_id' => Auth::user()->id,
                ];

                $editStudent = ModelsStudent::where("id", "=", $student->id)->update($updateArray);

                if ($editStudent != 0) {
                    $res = array(
                        'status' => true,
                        "message" => "Student Updated successfully",
                    );
                } else {
                    $res = array(
                        'status' => false,
                        "message" => "Student Updating failed",
                    );
                }
            }
        } catch (Exception $e) {
            $res = array(
                'status' => false,
                "message" => $e->getMessage(),
            );
        }

        echo json_encode($res);
    }

    public function deleteStudent(Request $request)
    {
        try {
            $data = $request->all();
            $student = ModelsStudent::findOrFail($data['sid']);
            if ($student->profile_image) {
                $path = 'upload/student_image/' . $student->profile_image;
                if (File::exists($path)); {
                    File::delete($path);
                }
            }

            $student->delete();
            $res = [
                "status" => true,
                "message" => "Student Deleted successfully",
            ];
        } catch (Exception $e) {
            $res = [
                "status" => false,
                "message" => $e->getMessage(),
            ];
        }

        echo json_encode($res);
    }

    public function getStudents(Request $request)
    {
        $response = array();

        $draw = $request->post('draw');
        $start = $request->post('start');
        $rowperpage = $request->post('length'); // Rows display per page
        $columnIndex = $request->post('order')[0]['column']; // Column index
        $columnName = $request->post('columns')[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->post('order')[0]['dir']; // asc or desc
        $searchValue = $request->post('search')['value']; // Search value

        $searchQuery = $searchValue;

        $totalRecords = ModelsStudent::where("user_id", '=', Auth::user()->id)->orderBy('id', 'DESC')->count();

        $totalRecordwithFilter = ModelsStudent::orderBy('id', 'DESC')
            ->where(function ($query) use ($searchQuery, $searchValue) {
                if ($searchQuery != '') {
                    $query->orwhere('name', 'Like', "%$searchValue%");
                    $query->orwhere('email', 'Like', "%$searchValue%");
                    $query->orwhere('mobile', 'Like', "%$searchValue%");
                }
            })
            ->where("user_id", '=', Auth::user()->id)
            ->orderBy('id', 'DESC')->count();

        $records = ModelsStudent::orderBy('id', 'DESC')
            ->where(function ($query) use ($searchQuery, $searchValue) {
                if ($searchQuery != '') {
                    $query->orwhere('name', 'Like', "%$searchValue%");
                    $query->orwhere('email', 'Like', "%$searchValue%");
                    $query->orwhere('mobile', 'Like', "%$searchValue%");
                }
            })
            ->where("user_id", '=', Auth::user()->id)
            ->orderBy('id', 'DESC')
            ->offset($start)->limit($rowperpage);
        $records = $records->get();

        $data = array();
        $srno = $start + 1;

        foreach ($records as $record) {

            $sid = Crypt::encrypt($record->id);

            $editAction = '<button type="button" data-sid="' . $sid . '" class="ml-1 editStudentModalBtn btn btn-sm btn-primary btn-circle">
                                <i class="fas fa-edit"></i>
                            </button>';


            $deleteAction = '<button data-sid="' . $record->id . '" class="ml-1 deleteStudentBtn btn btn-sm btn-danger btn-circle">
                                <i class="fas fa-trash"></i>
                              </button>';

            if ($record->profile_image != null) {
                $image = '<img src="' . url('/') . '/upload/student_image/' . $record->profile_image . '" width="50px" height="50px">';
            } else {
                $image = '';
            }
            $data[] = array(
                "id" => $srno++,
                "action" => $editAction . $deleteAction,
                "name" => $record->name,
                "email" => $record->email,
                "mobile_no" => $record->mobile,
                "date_of_birth" => $record->date_of_birth,
                "gender" => $record->gender,
                "city" => $record->city,
                "hobbies" => $record->hobbies,
                "image" => $image,
            );
        }

        // Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }
}
