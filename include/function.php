<?php

    require '../config/config.php';

    if (!function_exists('error422')) {
        function error422($message)
        {
            $data = ['status' => 422,
                'message' => $message . '',
            ];
            header("HTTP/1.0 422 Unprocessable Entity");
            echo json_encode($data);
            exit();
        }
    }


    //create user
    function storeUser($userInput){

        global $conn;

        $user_id = mysqli_real_escape_string($conn, $userInput['user_id']);
        $name = mysqli_real_escape_string($conn, $userInput['name']);
        $email = mysqli_real_escape_string($conn, $userInput['email']);
        $password = mysqli_real_escape_string($conn, $userInput['password']);
        $user_role = mysqli_real_escape_string($conn, $userInput['user_role']);
        $year_level = mysqli_real_escape_string($conn, $userInput['year_level']);
        $course_id = mysqli_real_escape_string($conn, $userInput['course_id']);
        $school_id = mysqli_real_escape_string($conn, $userInput['school_id']);

        if(empty(trim($user_id))) {
            return error422('Enter your student ID');
        }
        elseif(empty(trim($name))) {
            return error422('Enter your name');
        }
        elseif(empty(trim($email))){
            return error422('Enter your email');
        }
        elseif(empty(trim($password))){
            return error422('Enter a password');
        }
        elseif(empty(trim($user_role))){
            return error422('Enter your role');
        }
        elseif(empty(trim($year_level))){
            return error422('Enter your year');
        }
        elseif(empty(trim($course_id))){
            return error422('Enter your course');
        }
        elseif(empty(trim($school_id))){
            return error422('Enter your school');
        }
        else
        {
            $query = "INSERT INTO users (user_id, name, email, password, user_role, year_level, course_id, school_id) VALUES ('$user_id','$name', '$email', '$password', '$user_role', '$year_level', '$course_id', '$school_id')";
            $result = mysqli_query($conn, $query);

            if($result){
                $data = ['status' => 200,
                'message' => 'User Created',
            ];
                header("HTTP/1.0 200 User Created");
                return json_encode($data);
            }else{
                $data = ['status' => 500,
                'message' => 'Internal Server Error',
            ];
                header("HTTP/1.0 500 Internal Server Error");
                return json_encode($data);
            }
        }
    }

    //get list of users

    function getUserList(){

        global $conn;

        $query = "SELECT * FROM users";
        $query_run = mysqli_query($conn, $query);

        if($query_run){

            if(mysqli_num_rows($query_run) > 0){

                $response = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
                $data = ['status' => 200,
                         'message' => 'User List Fetched Successfully',
                         'data' => $response
            ];
                header("HTTP/1.0 200 User List Fetched Successfully");
                return json_encode($data);

            }
            else{
                $data = ['status' => 504,
                'message' => 'No User Found',
            ];
                header("HTTP/1.0 404 No User Found");
                return json_encode($data);
            }
        }
        else{
            $data = ['status' => 500,
            'message' => 'Internal Server Error',
        ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }

    }
    
    //get specific user
    function getUser($userParams){
        global $conn;

        if(($userParams['name'] == null)) {
            return error422('Enter a name');
        }

        $userName = mysqli_real_escape_string($conn, $userParams['name']);

        $query = "SELECT * FROM users where name='$userName' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if($result){

            if(mysqli_num_rows($result) == 1){

                $res = mysqli_fetch_assoc($result);
                
                $data = ['status' => 200,
                'message' => 'User found',
                'data' => $res
            ];
                header("HTTP/1.0 404 User Found");
                return json_encode($data);

            }else{
                $data = ['status' => 404,
                'message' => 'No user found',
            ];
                header("HTTP/1.0 404 User Not Found");
                return json_encode($data);
            }

        }else{
            $data = ['status' => 500,
            'message' => 'Internal Server Error',
        ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }

    }

    //update a user
    function updateUser($userInput, $userParams){
        
        global $conn;

        if(!isset($userParams['user_id'])){
            return error422('User Not Found');
        }elseif($userParams['user_id'] == null){
            return error422('Enter user ID');
        }

        $id = mysqli_real_escape_string($conn, $userParams['user_id']);

        $name = mysqli_real_escape_string($conn, $userInput['name']);
        $email = mysqli_real_escape_string($conn, $userInput['email']);
        $year_level = mysqli_real_escape_string($conn, $userInput['year_level']);
        $course_id = mysqli_real_escape_string($conn, $userInput['course_id']);


        $userExistsQuery = "SELECT COUNT(*) AS count FROM users WHERE user_id = '$id'";
        $userExistsResult = mysqli_query($conn, $userExistsQuery);
        $userExistsRow = mysqli_fetch_assoc($userExistsResult);
        $userExists = $userExistsRow['count'];
        if($userExists == 0) {
            return error422('User ID not found in the database');
        }
        

        if(empty(trim($name))) {
            return error422('Enter your name');
        }
        elseif(empty(trim($email))){
            return error422('Enter your email');
        }
        elseif(empty(trim($year_level))){
            return error422('Enter your year');
        }
        elseif(empty(trim($course_id))){
            return error422('Enter your course');
        }else{
            $query = "UPDATE users SET name='$name', email='$email', year_level='$year_level', course_id='$course_id' WHERE user_id = '$id' LIMIT 1";
            $result = mysqli_query($conn, $query);

            if($result){

                $data = [
                    'status' => 200,
                    'message' => 'User Updated Successfully',
                
                ];
                header("HTTPS/1.0 200 Success");
                return json_encode($data);
            }else{
                $data = [
                    'status' => 500,
                    'message' => 'Internal Server Error',
                ];
                header("HTTP/1.0 500 Internal Server ERror");
                return json_encode($data);
            }
        }
    }

    //remove user

    function removeUser($userParams){
        
        global $conn;

        if(!isset($userParams['user_id'])){
            return error422('User does not exist');
        }elseif($userParams['user_id'] == null){
            return error422('Enter user ID');
        }

        $id = mysqli_real_escape_string($conn, $userParams['user_id']);
        $query = "DELETE FROM users WHERE user_id='$id' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if($result){
            $data = [
                'status' => 200,
                'message' => 'User deleted',
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($data);

        }else{
            $data = [
                'status' => 404,
                'message' => 'User not found.',
            ];
            header("HTTP/1.0 400 Not Found");
            return json_encode($data);
        }

    }
