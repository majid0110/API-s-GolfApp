<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class New_API extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    
public function insertData() {
    $request_data = json_decode(file_get_contents('php://input'), true);

    $email = $request_data['email'];

    // Check if the email already exists in the database
    $this->load->model('New_Model');
    $existingUser = $this->New_Model->getUserByEmail($email);

    if ($existingUser) {
        $response = array(
            'pagination' => null,
            'status' => 0,
            'msg' => 'Email already exists',
            'data' => null
        );
    } else {
        $role = $request_data['role'];
        $password = $request_data['password'];
        $profile = $request_data['profile'];

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $inserted = $this->New_Model->insertUserData(
            $role, $email, $hashedPassword, $profile
        );

        if ($inserted) {
            $response = array(
                'pagination' => null,
                'status' => 1,
                'msg' => 'User added successfully',
                'data' => null
            );
        } else {
            $response = array(
                'pagination' => null,
                'status' => 0,
                'msg' => 'Data insertion failed',
                'data' => null
            );
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}


    public function login() {
        // Get JSON input data from the request
        $request_data = json_decode(file_get_contents('php://input'), true);
    
        $email = $request_data['email'];
        $password = $request_data['password'];
    
        // Your database query to check the user's credentials
        $this->load->model('New_Model');
        $userDetails = $this->New_Model->getUserByEmail($email);
    
        if ($userDetails !== null) {
            // Verify the password
            if (password_verify($password, $userDetails['Password'])) {
                // Password is correct
    
                // Create the desired response structure
                $response = array(
                    'pagination' => null,
                    'status' => 1,
                    'msg' => null,
                    'data' => array(
                        'userId' => (int) $userDetails['UserId'],
                        'role' => (int) $userDetails['Role'],
                        'email' => $userDetails['Email'],
                        'profile' => array(
                            'firstName' => $userDetails['Profile_FirstName'],
                            'lastName' => $userDetails['Profile_LastName'],
                            'phone' => $userDetails['Profile_Phone'],
                            'gender' => ($userDetails['Profile_Gender'] == 1), // Convert to boolean
                            'age' => (int) $userDetails['Profile_Age'],
                            'hcp' => (int) $userDetails['Profile_Hcp'],
                            'healthLimitation' => $userDetails['Profile_HealthLimitation'],
                            'distanceUnit' => (int) $userDetails['Profile_DistanceUnit'],
                            'speedUnit' => (int) $userDetails['Profile_SpeedUnit'],
                        ),
                        'proViewHcp' => (int) $userDetails['ProViewHcp'],
                        'proViewLevel' => (int) $userDetails['ProViewLevel'],
                        'token' => $userDetails['VerificationToken'], // Or any appropriate token field
                    ),
                );
            } else {
                // Invalid password
                $response = array(
                    'pagination' => null,
                    'status' => 0,
                    'msg' => 'Invalid password',
                    'data' => null
                );
            }
        } else {
            // User not found
            $response = array(
                'pagination' => null,
                'status' => 0,
                'msg' => 'Invalid email or password',
                'data' => null
            );
        }
    
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    
    
    public function ironScore() {
        $this->load->model('New_Model');
        
        // Get the JSON request data
        $requestData = json_decode(file_get_contents('php://input'), true);

        if (!isset($requestData['studentId']) || !isset($requestData['dateTime'])) {
            $response = array(
                'status' => 0,
                'msg' => 'Invalid request data. Both studentId and dateTime are required.',
                'data' => null
            );
    
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return;
        }
    
        $studentId = $requestData['studentId'];
        $dateTime = $requestData['dateTime'];
        $result = $this->New_Model->fetchIronScore($studentId, $dateTime);

        $response = array(
            'pagination' => null,
            'status' => 1,
            'msg' => null,
            'data' => array()
        );
    
        foreach ($result as $item) {
            $formattedItem = array(
                'ground' => $item['Ground'],
                'club' => $item['Club'],
                'AvgDistance' => $item['AngDistance'],
                'clubHeadSpeed' => $item['ClubHeadSpeed'],
                'spinRate' => $item['SpinRate'],
                'apex' => $item['Apex'],
                'ballsAmount' => $item['BallsAmount'],
                'rating' => $item['Rating']
            );
    
            $response['data'][] = $formattedItem;
        }
    
        // Return the response as JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    //------------------------------No change below---------------
    
    // public function addScore()
    // {
    //     $input_json = file_get_contents('php://input');
    //     $input_data = json_decode($input_json, true); 
    //     if (isset($input_data['clubRecords']) && is_array($input_data['clubRecords'])) {
    //         $overallStatus = 1; // Initialize the overall status to 1 (success)
    //         $overallMsg = "Club score successfully updated"; 
    //         $responses = array();
    //         $clubRecords = $input_data['clubRecords'];
    //         $this->load->model('New_Model');
    //         foreach ($clubRecords as $score_entry) {
    //             if (isset($score_entry['dateTime']) && isset($score_entry['isWithPro']) && isset($score_entry['ground']) && isset($score_entry['angDistance'])
    //                 && isset($score_entry['clubHeadSpeed']) && isset($score_entry['spinRate']) && isset($score_entry['apex'])
    //                 && isset($score_entry['ballsAmount']) && isset($score_entry['rating']) && isset($score_entry['studentId'])) {

    //                 $dateTime = $score_entry['dateTime'];
    //                 $isWithPro = $score_entry['isWithPro'];
    //                 $ground = $score_entry['ground'];
    //                 $angDistance = $score_entry['angDistance'];
    //                 $clubHeadSpeed = $score_entry['clubHeadSpeed'];
    //                 $spinRate = $score_entry['spinRate'];
    //                 $apex = $score_entry['apex'];
    //                 $ballsAmount = $score_entry['ballsAmount'];
    //                 $rating = $score_entry['rating'];
    //                 $studentId = $score_entry['studentId'];

    //                 $result = $this->New_Model->addScore($dateTime, $isWithPro, $ground, $angDistance, $clubHeadSpeed, $spinRate, $apex, $ballsAmount, $rating, $studentId);
    //                 if ($result['status'] === 0) {
    //                     $overallStatus = 0;
    //                     $overallMsg = "One or more club scores failed to update";
    //                 }

    //                 $responses[] = $result;
    //             } else {
                   
    //                 $responses[] = array(
    //                     'status' => 0,
    //                     'msg' => 'Invalid JSON data for score entry',
    //                     'data' => null
    //                 );
    //             }
    //         }

    //         $overallResponse = array(
    //             'pagination' => null,
    //             'status' => $overallStatus,
    //             'msg' => $overallMsg,
    //             'data' => null
    //         );

            
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode($overallResponse));
    //     } else {
    //         $response = array(
    //             'status' => 0,
    //             'msg' => 'Invalid JSON array data. Make sure to include the "clubRecords" key.',
    //             'data' => null
    //         );

    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode($response));
    //     }
    // }
//----------------------------NO change Above--------------

public function addScore()
{
    $input_json = file_get_contents('php://input');
    $input_data = json_decode($input_json, true);
    $allowedColumns = ['dateTime', 'isWithPro', 'ground', 'avgDistance', 'clubHeadSpeed', 'spinRate', 'apex', 'ballsAmount', 'rating', 'studentId'];

    if (isset($input_data['clubRecords']) && is_array($input_data['clubRecords'])) {
        $overallStatus = 1;
        $overallMsg = "Club score successfully updated";
        $responses = array();
        $clubRecords = $input_data['clubRecords'];
        $this->load->model('New_Model');

        foreach ($clubRecords as $score_entry) {
            $filteredData = array_intersect_key($score_entry, array_flip($allowedColumns));
            $extraFields = array_diff_key($score_entry, array_flip($allowedColumns));

            if (count($filteredData) === count($allowedColumns)) {
                $result = $this->New_Model->addScore($filteredData);
                if ($result['status'] === 0) {
                    $overallStatus = 0;
                    $overallMsg = "One or more club scores failed to update";
                }
                $responses[] = $result;
            } else {
                $responses[] = array(
                    'status' => 0,
                    'msg' => 'Invalid JSON data for score entry',
                    'data' => null,
                    'extraFields' => $extraFields
                );
            }
        }

        $overallResponse = array(
            'pagination' => null,
            'status' => $overallStatus,
            'msg' => $overallMsg,
            'data' => null
        );

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($overallResponse));
    } else {
        $response = array(
            'status' => 0,
            'msg' => 'Invalid JSON array data. Make sure to include the "clubRecords" key',
            'data' => null
        );

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
public function profile() {
    $request_data = json_decode(file_get_contents('php://input'), true);

    $email = $request_data['email'];
    $password = $request_data['password'];
    $this->load->model('New_Model');
    $userDetails = $this->New_Model->getUserByEmail($email);

    if ($userDetails !== null) {
        if (password_verify($password, $userDetails['Password'])) {
            $profileData = array(
                'firstName' => $userDetails['Profile_FirstName'],
                'lastName' => $userDetails['Profile_LastName'],
                'phone' => $userDetails['Profile_Phone'],
                'gender' => ($userDetails['Profile_Gender'] == 1), // Convert to boolean
                'age' => (int) $userDetails['Profile_Age'],
                'hcp' => (int) $userDetails['Profile_Hcp'],
                'healthLimitation' => $userDetails['Profile_HealthLimitation'],
                'distanceUnit' => (int) $userDetails['Profile_DistanceUnit'],
                'speedUnit' => (int) $userDetails['Profile_SpeedUnit'],
            );

            $response = array(
                'pagination' => null,
                'status' => 1,
                'msg' => null,
                'data' => $profileData
            );
        } else {
            $response = array(
                'pagination' => null,
                'status' => -1,
                'msg' => "old password didn't match",
                'data' => null
            );
        }
    } else {
        $response = array(
            'pagination' => null,
            'status' => 0,
            'msg' => 'Invalid email or password',
            'data' => null
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

public function updateProfile() {
    $request_data = json_decode(file_get_contents('php://input'), true);
    $userId = $request_data['UserId'];
    $newPassword = $request_data['newPassword'];
    $oldPassword = $request_data['oldPassword'];
    $this->load->model('New_Model');
    $userData = $this->New_Model->getUserById($userId);

    if ($userData) {
        if (password_verify($oldPassword, $userData['Password'])) {
            $data = array(
                'Profile_FirstName' => $request_data['firstName'],
                'Profile_LastName' => $request_data['lastName'],
                'Profile_Phone' => $request_data['phone'],
                'Profile_Gender' => $request_data['gender'],
                'Profile_Age' => $request_data['age'],
                'Profile_Hcp' => $request_data['hcp'],
                'Profile_HealthLimitation' => $request_data['healthLimitation'],
                'Profile_DistanceUnit' => $request_data['distanceUnit'],
                'Profile_SpeedUnit' => $request_data['speedUnit'],
            );
            if (!empty($newPassword)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $data['Password'] = $hashedPassword;
            }
            $this->New_Model->updateProfileData($userData['Email'], $hashedPassword, $request_data['age'], $request_data['distanceUnit'], $request_data['firstName'], $request_data['gender'], $request_data['hcp'], $request_data['healthLimitation'], $request_data['lastName'], $request_data['phone'], $request_data['speedUnit']);
            $response = array(
                'pagination' => null,
                'status' => 1,
                'msg' => 'Profile successfully updated',
                'data' => null
            );
        } else {
            $response = array(
                'pagination' => null,
                'status' => 0,
                'msg' => 'Old password doesnot Match',
                'data' => null
            );
        }
    } else {
        $response = array(
            'pagination' => null,
            'status' => 0,
            'msg' => 'User not found',
            'data' => null
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}


    public function handleRequest($action) {
        switch ($action) {
            case 'insertData':
                $response = $this->insertData();
                break;
            case 'login':
                $response = $this->login();
                break;
            case 'addScore':
                $response = $this->addScore();
                break;
            case 'profile':
                $response = $this->profile();
                break;
            case 'updateProfile':
                $response = $this->updateProfile();
                break;
            case 'ironScore':
                $response = $this->ironScore();
                break;
            case 'testScore':
                $response = $this->testScore();
                break;
            default:
                $response = array(
                    'pagination' => null,
                    'status' => 0,
                    'msg' => 'Invalid action',
                    'data' => null
                );
                break;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
