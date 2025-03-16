<?php
session_start();

// Student Class
class Student {
    public $name;
    public $age;
    public $class;

    public function __construct($name, $age, $class) {
        $this->name = $name;
        $this->age = $age;
        $this->class = $class;
    }
}

// Student Manager Class
class StudentManager {
    public static function getAllStudents() {
        return isset($_SESSION['students']) ? $_SESSION['students'] : [];
    }

    public static function addStudent($name, $age, $class) {
        $student = new Student($name, $age, $class);
        $_SESSION['students'][] = $student;
        return ["success" => true];
    }

    public static function deleteStudent($id) {
        if (isset($_SESSION['students'][$id])) {
            unset($_SESSION['students'][$id]);
            $_SESSION['students'] = array_values($_SESSION['students']); // Re-index array
            return ["success" => true];
        }
        return ["success" => false];
    }

    public static function updateStudent($id, $name, $age, $class) {
        if (isset($_SESSION['students'][$id])) {
            $_SESSION['students'][$id]->name = $name;
            $_SESSION['students'][$id]->age = $age;
            $_SESSION['students'][$id]->class = $class;
            return ["success" => true];
        }
        return ["success" => false];
    }
}

// Handle Requests
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit'])) {
        // Update student
        echo json_encode(StudentManager::updateStudent($_POST['edit'], $_POST['name'], $_POST['age'], $_POST['class']));
    } else {
        // Add new student
        echo json_encode(StudentManager::addStudent($_POST['name'], $_POST['age'], $_POST['class']));
    }
    exit();
}

if (isset($_GET['delete'])) {
    echo json_encode(StudentManager::deleteStudent($_GET['delete']));
    exit();

} elseif (isset($_GET['edit'])) {
    // Return the student data for editing
    echo json_encode(StudentManager::getAllStudents()[$_GET['edit']]);
    exit();
}

// Return all students as JSON for frontend
echo json_encode(StudentManager::getAllStudents());
?>
