<?php

namespace App\Repository;

use App\Exports\StudentsExport;
use App\interfaces\StudentRepositoryInterface;
use App\Models\Blood;
use App\Models\Classroom;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Image;
use App\Models\My_Parent;
use App\Models\Nationalitie;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class StudentRepository implements StudentRepositoryInterface
{

    protected bool $image_exist;

    public function Get_Students()
    {
        $students = Student::all();
        return view('pages.Students.index', compact('students'));
    }

    public function Create_Student()
    {
        $data['my_classes'] = Grade::all();
        $data['parents'] = My_Parent::all();
        $data['Genders'] = Gender::all();
        $data['nationals'] = Nationalitie::all();
        $data['bloods'] = Blood::all();

        return view('pages.Students.add', $data);
    }

    public function Edit_Student($id)
    {
        $data['Grades'] = Grade::all();
        $data['parents'] = My_Parent::all();
        $data['Genders'] = Gender::all();
        $data['nationals'] = Nationalitie::all();
        $data['bloods'] = Blood::all();
        $data['Students'] = Student::findorFail($id);
        return view('pages.Students.edit', $data);
    }

    public function Store_Student($request)
    {
        $student = new Student();
        $student->name = ['ar' => $request->name_ar, 'en' => $request->name_en];
        $student->email = $request->email;
        $student->password = Hash::make($request->password);
        $student->gender_id = $request->gender_id;
        $student->nationalitie_id = $request->nationalitie_id;
        $student->blood_id = $request->blood_id;
        $student->Date_Birth = $request->Date_Birth;
        $student->Grade_id = $request->Grade_id;
        $student->Classroom_id = $request->Classroom_id;
        $student->section_id = $request->section_id;
        $student->parent_id = $request->parent_id;
        $student->academic_year = $request->academic_year;
        $student->save();

        $this->extracted($request, $student);

    }

    public function Update_Student($request)
    {
        $student = Student::findorFail($request->id);
        $student->name = ['ar' => $request->name_ar, 'en' => $request->name_en];
        $student->email = $request->email;
        $student->password = Hash::make($request->password);
        $student->gender_id = $request->gender_id;
        $student->nationalitie_id = $request->nationalitie_id;
        $student->blood_id = $request->blood_id;
        $student->Date_Birth = $request->Date_Birth;
        $student->Grade_id = $request->Grade_id;
        $student->Classroom_id = $request->Classroom_id;
        $student->section_id = $request->section_id;
        $student->parent_id = $request->parent_id;
        $student->academic_year = $request->academic_year;
        $student->save();

    }

    public function add_attachment($request)
    {
        $student = Student::find($request->student_id);

        $this->extracted($request, $student);
    }

    public function Delete_Student($request)
    {
        Student::findorFail($request->id)->delete();
    }

    public function Get_classrooms($id)
    {
        $list_classes = Classroom::where('Grade_id', $id)->pluck("Name_class", 'id');
        return $list_classes;

    }

    public function Get_sections($id)
    {
        $list_sections = Section::where('Classroom_id', $id)->pluck("Name_section", 'id');
        return $list_sections;

    }

    public function Show_Student($id)
    {
        $Student = Student::findorFail($id);
        return view('pages.Students.show', compact('Student'));
    }

    public function export_students()
    {
        return Excel::download(new StudentsExport(), 'students.xlsx');
    }


    public function extracted($request, $student)
    {
        $error = false;
        if ($request->hasfile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $name = $photo->getClientOriginalName();
                $image = $student->images()->where('filename', $name)->exists();
                if ($image) {
                    $error = true;
                } else {
                    $photo->storeAs('attachments/students/' . $student->name, $name, 'upload_attachments');
                    $images = new Image();
                    $images->filename = $name;
                    $images->imageable_id = $student->id;
                    $images->imageable_type = 'App\Models\Student';
                    $images->save();
                }
            }
            if ($error) {
                return redirect()->back()->withErrors(['error' => 'One or more files already exist']);
            }
        } else {
            return redirect()->back()->withErrors(['error' => 'No files uploaded']);
        }
        toastr()->success(trans('messages.success'));
        return redirect()->back();
    }


    public function download_attachment($studentsname, $filename)
    {
        return response()->download(public_path('attachments\students\\' . $studentsname . '\\' . $filename));
    }

    public function delete_attachment($request)
    {
        Storage::disk('upload_attachments')->delete('attachments/students/' . $request->student_name . '/' . $request->filename);
        Image::where('id', $request->id)->where('filename', $request->filename)->delete();
        toastr()->error(trans('messages.Delete'));
        return redirect()->route('students.show', $request->student_id);

    }

}
