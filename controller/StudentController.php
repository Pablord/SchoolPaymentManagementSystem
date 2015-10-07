<?php

class StudentController
{
    public static function addStudentView()
    {

        $view = new AddStudentView();
        $view->show();
    }

    public static function addStudentAction()
    {
        $studentRepository = new StudentModel();
        $studentID = $studentRepository->createStudent($_POST);

        $student["id"] = $studentID;
        $student["firstName"] = $_POST["firstName"];
        $student["lastName"] = $_POST["lastName"];

       	if($_POST['guardianType'] == 'createGuardian')
       	{
    		GuardianController::addGuardianView($student);
       	}
       	else
       	{
            GuardianController::listGuardiansView(0,$student);
       	}
    }

    public static function listStudentsView()
    {
        $view = new ListStudentsView();
        $studentData = (new StudentModel())->getStudents();
        $students = array();

        foreach ($studentData as $student)
        {
            $student["guardiansAmount"] = (new GuardianOfStudentModel())->getGuardiansOfStudentAmount($student["id"]);
            $students[$student["id"]] = $student;
        }

        $view ->show($students);

    }
    public static function listStudentsWithNameView($studentName)
    {
        $view = new ListStudentsView();
        $studentData = (new StudentModel())->getStudentsByName($studentName);
        $view ->show($studentData);
    }
    public static function updateStudentView($studentID)
    {
        $view = new UpdateStudentView();
        $studentData = (new StudentModel())->getStudent($studentID);
        $view->show($studentData, $studentID);
    }

    public static function updateStudentAction ($studentID)
    {
        $studentRepository = new StudentModel();
        $studentRepository->updateStudent($_POST, $studentID);
        header("Location: /ListStudents");
    }

    public static function listStudentsWithPayedEnrolmentView($startingIndex = 0)
    {

        $studentRepository = new StudentModel();
        $students = $studentRepository->getStudentsWithPayedEnrolment($startingIndex);

        (new StudentsWithPayedEnrolmentView())->show($students);
    }

    public static function listAdmittedStudentsView($startingIndex = 0)
    {

        $StudentModel = new StudentModel();
        $students = $StudentModel->getAdmittedStudents($startingIndex);

        (new ListAdmittedStudentsView())->show($students);
    }

    public  static function deleteStudentAction($studentID)
    {

        (new StudentModel())->deleteStudent($studentID);
        header("Location: /ListStudents");
    }
}