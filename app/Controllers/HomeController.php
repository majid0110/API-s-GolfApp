<?php

namespace App\Controllers;
use App\Models\ApiModel;

use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Email\Email;

class HomeController extends BaseController
{
    protected $modelName = 'App\Models\ApiModel';
    protected $format = 'json';

    use ResponseTrait;
    // public function index(): string
    // {
    //     return view('welcome_message');
    // }

    public function login()
    {
        $request_data = $this->request->getJSON(true);
        // $knownPassword =  '12345678';
        $email = $request_data['email'];
        $password = trim($request_data['password']);
        $model = new ApiModel();
        $userDetails = $model->getUserByEmail($email);

        if ($userDetails !== null) {
            if (isset($userDetails['Password']) && password_verify($password, $userDetails['Password'])) {
                $response = [
                    'pagination' => null,
                    'status' => 1,
                    'msg' => null,
                    'data' => [
                        'userId' => (int) $userDetails['UserId'],
                        'role' => (int) $userDetails['Role'],
                        'email' => $userDetails['Email'],
                        'profile' => [
                            'firstName' => $userDetails['Profile_FirstName'],
                            'lastName' => $userDetails['Profile_LastName'],
                            'phone' => $userDetails['Profile_Phone'],
                            'gender' => ($userDetails['Profile_Gender'] == 1),
                            'age' => (int) $userDetails['Profile_Age'],
                            'hcp' => (int) $userDetails['Profile_Hcp'],
                            'healthLimitation' => $userDetails['Profile_HealthLimitation'],
                            'distanceUnit' => (int) $userDetails['Profile_DistanceUnit'],
                            'speedUnit' => (int) $userDetails['Profile_SpeedUnit'],
                        ],
                        'proViewHcp' => (int) $userDetails['ProViewHcp'],
                        'proViewLevel' => (int) $userDetails['ProViewLevel'],
                        'token' => $userDetails['VerificationToken'],
                    ],
                ];
            } else {
                // Invalid password response
                $response = [
                    'pagination' => null,
                    'status' => -1,
                    'msg' => 'Invalid password',
                    'data' => null
                ];
            }

        } else {
            // Invalid email response
            $response = [
                'pagination' => null,
                'status' => -1,
                'msg' => 'Invalid email or password',
                'data' => null
            ];
        }
        // header('Content-Type: application/json');
        // echo json_encode($response);
        return $this->respond($response);
    }
    // CI4 converted:
    public function getUsersByRole()
    {
        $role = $this->request->getVar('role');
        // $inputJSON = file_get_contents('php://input');
        // $data = json_decode($inputJSON, true);
        if ($role) {

            $model = new ApiModel();
            // Call the model function to get users by role
            $userData = $model->getUsersByRole($role);
            if ($userData) {
                $response = [
                    'pagination' => null,
                    'status' => 1,
                    'msg' => null,
                    'data' => $userData
                ];

                return $this->respond($response);
                // $this->output
                //     ->set_content_type('application/json')
                //     ->set_output(json_encode($response));
            } else {
                $response = [
                    'pagination' => null,
                    'status' => 0,
                    'msg' => 'No users found for the specified role',
                    'data' => null,
                ];
                return $this->respond($response, 404);
                // $this->output
                //     ->set_status_header(404) // You can adjust the status code as needed
                //     ->set_output(json_encode($response));
            }
        } else {
            $response = [
                'pagination' => null,
                'status' => 0,
                'msg' => 'Invalid request format',
                'data' => null,
            ];
            return $this->respond($response, 400);
            // $this->output
            //     ->set_status_header(400)
            //     ->set_output(json_encode($response));
        }
    }
    // CI 4 Converted:
    public function TournamentScore()
    {
        // $inputJSON = file_get_contents('php://input');
        $data = json_decode($this->request->getBody(), true);
        // $inputJSON = $this->request->getBody();
        // $data = json_decode($inputJSON, true);
        if (isset($data['date']) && isset($data['studentId'])) {
            $model = new ApiModel();
            $result = $model->getTournamentScore($data['date'], $data['studentId']);

            if ($result != null) {
                $formattedData = [];

                foreach ($result as $item) {
                    $formattedItem = [
                        'gameId' => $item['GameId'],
                        'dateTime' => $item['DateTime'],
                        'gameType' => $item['GameType'],
                        'warmupTime' => $item['WarmupTime'],
                        'driverPeaces' => $item['DriverPeaces'],
                        'ironPeaces' => $item['IronPeaces'],
                        'chipPeaces' => $item['ChipPeaces'],
                        'sandPeaces' => $item['SandPeaces'],
                        'puttPeaces' => $item['PuttPeaces'],
                        'golfCourse' => $item['GolfCourse'],
                        'exactHcp' => $item['ExactHcp'],
                        'playingHcp' => $item['PlayingHcp'],
                        'nervous' => $item['Nervous'],
                        'flightPartnersRating' => $item['FlightPartnersRating'],
                        'driversRating' => $item['DriversRating'],
                        'driversLeft' => $item['DriversLeft'],
                        'driversCenter' => $item['DriversCenter'],
                        'driversRight' => $item['DriversRight'],
                        'ironsRating' => $item['IronsRating'],
                        'ironsLeft' => $item['IronsLeft'],
                        'ironsCenter' => $item['IronsCenter'],
                        'ironsRight' => $item['IronsRight'],
                        'woodsRating' => $item['WoodsRating'],
                        'woodsLeft' => $item['WoodsLeft'],
                        'woodsCenter' => $item['WoodsCenter'],
                        'woodsRight' => $item['WoodsRight'],
                        'shortIronGameRating' => $item['ShortIronGameRating'],
                        'bunkerShortsRating' => $item['BunkerShortsRating'],
                        'puttingStrokes' => $item['PuttingStrokes'],
                        'greenSpeedRating' => $item['GreenSpeedRating'],
                        'strokes' => $item['Strokes'],
                        'newHcp' => $item['NewHcp'],
                        'walking' => $item['Walking'],
                        'distanceWalked' => $item['DistanceWalked'],
                        'gameDuration' => $item['GameDuration'],
                        'holes' => $item['Holes'],
                        'studentId' => $item['StudentId'],
                        'stablefordPoints' => $item['StablefordPoints']
                    ];
                    $formattedData[] = $formattedItem;

                    $response = [
                        'pagination' => null,
                        'status' => 1,
                        'msg' => null,
                        'data' => $formattedData,
                    ];
                    return $this->respond($response);
                    // $this->output
                    //     ->set_content_type('application/json')
                    //     ->set_output(json_encode($response));
                }

            } else {
                $response = [
                    'pagination' => null,
                    'status' => 0,
                    'msg' => 'No data found for the given date and student ID',
                    'data' => null,
                ];
                return $this->respond($response, 404);
                // $this->output
                //     ->set_status_header(404)
                //     ->set_output(json_encode($response));
            }
        } else {
            $response = [
                'pagination' => null,
                'status' => 0,
                'msg' => 'Invalid request format',
                'data' => null,
            ];
            return $this->respond($response, 400);
            // $this->output
            //     .set_status_header(400)
            //     ->set_output(json_encode($response));
        }
    }
    // CI4 Converted:
    public function playRoundScore()
    {
        // $inputJSON = file_get_contents('php://input');
        $inputJSON = $this->request->getBody();
        $data = json_decode($inputJSON, true);
        if (isset($data['date']) && isset($data['studentId'])) {
            $model = new ApiModel();
            $result = $model->getPlayRoundScore($data['date'], $data['studentId']);

            if ($result != null) {
                $formattedData = [];

                foreach ($result as $item) {
                    $formattedItem = [
                        'gameId' => $item['GameId'],
                        'dateTime' => $item['DateTime'],
                        'gameType' => $item['GameType'],
                        'warmupTime' => $item['WarmupTime'],
                        'driverPeaces' => $item['DriverPeaces'],
                        'ironPeaces' => $item['IronPeaces'],
                        'chipPeaces' => $item['ChipPeaces'],
                        'sandPeaces' => $item['SandPeaces'],
                        'puttPeaces' => $item['PuttPeaces'],
                        'golfCourse' => $item['GolfCourse'],
                        'exactHcp' => $item['ExactHcp'],
                        'playingHcp' => $item['PlayingHcp'],
                        'nervous' => $item['Nervous'],
                        'flightPartnersRating' => $item['FlightPartnersRating'],
                        'driversRating' => $item['DriversRating'],
                        'driversLeft' => $item['DriversLeft'],
                        'driversCenter' => $item['DriversCenter'],
                        'driversRight' => $item['DriversRight'],
                        'ironsRating' => $item['IronsRating'],
                        'ironsLeft' => $item['IronsLeft'],
                        'ironsCenter' => $item['IronsCenter'],
                        'ironsRight' => $item['IronsRight'],
                        'woodsRating' => $item['WoodsRating'],
                        'woodsLeft' => $item['WoodsLeft'],
                        'woodsCenter' => $item['WoodsCenter'],
                        'woodsRight' => $item['WoodsRight'],
                        'shortIronGameRating' => $item['ShortIronGameRating'],
                        'bunkerShortsRating' => $item['BunkerShortsRating'],
                        'puttingStrokes' => $item['PuttingStrokes'],
                        'greenSpeedRating' => $item['GreenSpeedRating'],
                        'strokes' => $item['Strokes'],
                        'newHcp' => $item['NewHcp'],
                        'walking' => $item['Walking'],
                        'distanceWalked' => $item['DistanceWalked'],
                        'gameDuration' => $item['GameDuration'],
                        'holes' => $item['Holes'],
                        'studentId' => $item['StudentId'],
                        'stablefordPoints' => $item['StablefordPoints']
                    ];
                    $formattedData[] = $formattedItem;

                    $response = [
                        'pagination' => null,
                        'status' => 1,
                        'msg' => null,
                        'data' => $formattedItem,
                    ];
                    return $this->respond($response);
                    // $this->output
                    //     ->set_content_type('application/json')
                    //     ->set_output(json_encode($response));
                }

            } else {
                $response = [
                    'pagination' => null,
                    'status' => 0,
                    'msg' => 'No data found for the given date and student ID',
                    'data' => null,
                ];
                return $this->respond($response, 404);
                // $this->output
                //     ->set_status_header(404)
                //     ->set_output(json_encode($response));
            }
        } else {
            $response = [
                'pagination' => null,
                'status' => 0,
                'msg' => 'Invalid request format',
                'data' => null,
            ];
            return $this->respond($response, 400);
            // $this->output
            //     .set_status_header(400)
            //     ->set_output(json_encode($response));
        }
    }

    // CI4 Converted:
    public function addGames()
    {
        // $inputJSON = file_get_contents('php://input');
        $inputJSON = $this->request->getBody();
        $data = json_decode($inputJSON, true);
        if (
            isset($data['dateTime']) &&
            isset($data['studentId'])
        ) {
            $model = new ApiModel();
            $response = [
                'status' => 1,
                'msg' => 'Game data added successfully',
                'data' => null,
            ];
            $dateTime = $data['dateTime'];
            $studentId = $data['studentId'];
            $result = $model->addGames($data, $dateTime, $studentId);
            if ($result) {

                return $this->respond($response);
                // $this->output
                //     ->set_content_type('application/json')
                //     ->set_output(json_encode($response));
            } else {
                $response = [
                    'status' => 0,
                    'msg' => 'Failed to add game data',
                    'data' => null,
                ];
                return $this->respond($response, 404);
                // $this->output
                //     ->set_status_header(400)
                //     ->set_output(json_encode($response));
            }
        } else {
            $response = [
                'status' => 0,
                'msg' => 'Invalid request format',
                'data' => null,
            ];
            $this->respond($response, 400);
            // $this->output
            //     ->set_status_header(400)
            //     ->set_output(json_encode($response));
        }
    }
    // CI4 Converted:
    public function sendMail($user_email, $new_password)
    {


        if (!empty($user_email) && !empty($new_password)) {

            // $this->load->library('email');
            $email = new Email();
            $to = $user_email; //$this->input->post('from');  // User email pass here
            $subject = 'Proview Golf Recovering Password.';
            $emailContent = "Hello $user_email, We have created your new password.\n Please use below password to login to your account, you can change this password after successful login.\nYour new password is: <strong>$new_password</strong>";
            $from = 'proviewgolf@regexbyte.com';

            $email->setFrom('proviewgolf@regexbyte.com', 'Proview Golf');
            $email->setTo($to);
            $email->setSubject($subject);
            $email->setMessage($emailContent);
            $email->setMailType('html'); //Set Email formate to html
            //  $this->email->from('proviewgolf@regexbyte.com', 'Proview Golf');
            // $this->email->to($to);
            // $this->email->subject($subject);
            // $this->email->message($emailContent);

            if ($email->send()) {
                return true;
            } else {
                return $this->fail($email->printDebugger());
                // show_error($this->email->print_debugger());
            }

        } else {
            return $this->fail('field must filled error');
        }

    }

    // CI4 converted:
    public function RecoverPassword()
    {
        // $jsonData = file_get_contents('php://input');
        $jsonData = $this->request->getJSON();
        if (empty($jsonData) || !isset($jsonData->email)) {
            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Invalid or empty JSON data',
                'data' => null
            ]);
        } else {
            $email = $jsonData->email;
            $model = new ApiModel();
            $user_exists = $model->getUserByEmail($email);

            if ($user_exists) {

                $random_password = $this->randomPassword(12);
                $sendMail = $this->sendMail($email, $random_password);
                if ($sendMail == true) {
                    $new_password = password_hash($random_password, PASSWORD_BCRYPT, ['cost' => 12]);
                    $model->updatePass($email, $new_password);
                    return $this->response->setJSON([
                        'pagination' => null,
                        'status' => 1,
                        'msg' => 'An email with a new password has been sent to the registered email address',
                        'data' => null
                    ]);


                    // 	  $this->output
                    // ->set_content_type('application/json')
                    // ->set_output(json_encode($success));

                }
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'msg' => 'This email is not registerd! Please correct email.',
                    'data' => null
                ]);

                // 	$success =  array();

                // 	  $this->output
                // ->set_content_type('application/json')
                // ->set_output(json_encode($success));

            }


            // $result = $this->New_Model->forgotPassword($email);
            // $response = $result;


        }

    }

    // CI4 converted:
    public function randomPassword($length)
    {
        $alphabet = 'abcde#fghijklmnopq#rstu$vwxyzAB%CDEFGHI&JKLMN%OPQRSTUVWXYZ12&34567890';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $n = random_int(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode('', $pass);
    }

    // CI4 Converted:
    public function insertData()
    {
        $request_data = json_decode(file_get_contents('php://input'), true);
        // $request_data = $this->request->getJSON(true);

        $email = $request_data['email'] ?? null;

        $model = new ApiModel();
        $existingUser = $model->getUserByEmail($email);

        if ($existingUser) {
            return $this->response->setJSON([
                'pagination' => null,
                'status' => 0,
                'msg' => 'Email already exists',
                'data' => null
            ]);
        } else {
            $role = $request_data['role'] ?? null;
            $password = $request_data['password'] ?? null;
            $profile = $request_data['profile'] ?? [];

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $inserted = $model->insertUserData(
                $role,
                $email,
                $hashedPassword,
                $profile
            );

            if ($inserted) {
                return $this->response->setJSON([
                    'pagination' => null,
                    'status' => 1,
                    'msg' => 'User added successfully',
                    'data' => null
                ]);
            } else {
                return $this->response->setJSON([
                    'pagination' => null,
                    'status' => 0,
                    'msg' => 'Data insertion failed',
                    'data' => null
                ]);
            }
        }
    }


    // CI4 Converted:
    public function ironScore()
    {
        // $this->load->model('New_Model');
        $model = new ApiModel();

        // $requestData = json_decode(file_get_contents('php://input'), true);
        $requestData = $this->request->getJSON(true);

        if (!isset($requestData['studentId']) || !isset($requestData['date'])) {
            $this->response->setJSON([
                'status' => 0,
                'msg' => 'Invalid request data. Both studentId and dateTime are required.',
                'data' => null
            ]);

            // $this->output->set_content_type('application/json')->set_output(json_encode($response));
            // return;
        }

        $studentId = $requestData['studentId'];
        $dateTime = $requestData['date'];
        $result = $model->fetchIronScore($studentId, $dateTime);

        $response = [
            'pagination' => null,
            'status' => 1,
            'msg' => null,
            'data' => array()
        ];
        if ($result != null) {

            foreach ($result as $item) {
                $formattedItem = [
                    'ground' => $item['Ground'],
                    'club' => $item['Club'],
                    'avgDistance' => $item['AngDistance'],
                    'clubHeadSpeed' => $item['ClubHeadSpeed'],
                    'spinRate' => $item['SpinRate'],
                    'apex' => $item['Apex'],
                    'ballsAmount' => $item['BallsAmount'],
                    'rating' => $item['Rating']
                ];

                $response['data'][] = $formattedItem;
            }
        }
        return $this->response->setJSON($response);
        // $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }




    // public function addScore()
// {
//     $input_json = file_get_contents('php://input');
//     $input_data = json_decode($input_json, true);
//     $allowedColumns = ['dateTime', 'isWithPro', 'ground', 'avgDistance', 'clubHeadSpeed', 'spinRate', 'apex', 'ballsAmount', 'rating', 'studentId'];

    //     if (isset($input_data['clubRecords']) && is_array($input_data['clubRecords'])) {
//         $overallStatus = 1;
//         $overallMsg = "Club score successfully updated";
//         $responses = array();
//         $clubRecords = $input_data['clubRecords'];
//         $this->load->model('New_Model');

    //         foreach ($clubRecords as $score_entry) {
//             $filteredData = array_intersect_key($score_entry, array_flip($allowedColumns));
//             $extraFields = array_diff_key($score_entry, array_flip($allowedColumns));

    //             if (count($filteredData) === count($allowedColumns)) {
//                 $result = $this->New_Model->addScore($filteredData);
//                 if ($result['status'] === 0) {
//                     $overallStatus = 0;
//                     $overallMsg = "One or more club scores failed to update";
//                 }
//                 $responses[] = $result;
//             } else {
//                 $responses[] = array(
//                     'status' => 0,
//                     'msg' => 'Invalid JSON data for score entry',
//                     'data' => null,
//                     'extraFields' => $extraFields
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
//             'msg' => 'Invalid JSON array data. Make sure to include the "clubRecords" key',
//             'data' => null
//         );

    //         $this->output
//             ->set_content_type('application/json')
//             ->set_output(json_encode($response));
//     }
// }


    public function addScore()
    {

        //    $inputJSON = file_get_contents('php://input');

        // $data = json_decode($inputJSON, true);
        $data = $this->request->getJSON(true);
        $isWithPro = $data['isWithPro'] ?? null;
        // Check if the request contains clubRecords
        if (isset($data['clubRecords']) && is_array($data['clubRecords'])) {
            $model = new ApiModel();
            foreach ($data['clubRecords'] as $record) {
                $model->addScore2($record, $isWithPro);
            }

            $response = [
                'status' => 1,
                'msg' => 'Club score successfully updated',
                'data' => null
            ];
            return $this->response->setJSON($response);
            // $this->output
            //     ->set_content_type('application/json')
            //     ->set_output(json_encode($response));
        } else {

            $response = [
                'status' => 0,
                'msg' => 'Invalid request format',
                'data' => null
            ];
            return $this->response->setStatusCode(400)->setJSON($response);

            // $this->output
            //     ->set_status_header(400)
            //     ->set_output(json_encode($response));
        }
    }

    // CI4 Converted:
    public function addEquipments()
    {
        // $inputJSON = file_get_contents('php://input');
        $inputJSON = $this->request->getJSON(true);

        // $data = json_decode($inputJSON, true);

        $model = new ApiModel();

        $inserted = $model->addEquipment(
            $inputJSON
        );

        if ($inserted) {
            $response = [
                'pagination' => null,
                'status' => 1,
                'msg' => 'Equipment added successfully',
                'data' => null
            ];
        } else {
            $response = [
                'pagination' => null,
                'status' => 0,
                'msg' => 'Data insertion failed',
                'data' => null
            ];
        }

        //  header('Content-Type: application/json');
        //  echo json_encode($response);
        return $this->response->setJSON($response);

    }

    // CI4 Converted:
    public function Equipments()
    {
        $type = $this->request->getGet('type');
        $studentId = $this->request->getGet('Studentid');
        if ($type && $studentId) {

            $model = new ApiModel();
            $result = $model->fetchEquipments($type, $studentId);

            $response = [
                'pagination' => null,
                'status' => 1,
                'msg' => null,
                'data' => array()
            ];

            if ($result != null) {

                foreach ($result as $item) {
                    $formattedItem = [
                        'equipmentId' => $item['Equipmentid'],
                        'type' => $item['Type'],
                        'club' => $item['Club'],
                        'brand' => $item['Brand'],
                        'shaft' => $item['Shaft'],
                        'model' => $item['Model'],
                        'clubLoft' => $item['ClubLoft'],
                        'grip' => $item['Grip'],
                        'size' => $item['Size'],
                        'pairs' => $item['Pairs'],
                        'pieces' => $item['Pieces'],
                        'studentId' => $item['Studentid'],
                        'student' => null
                    ];

                    $response['data'][] = $formattedItem;
                }

            }
            // $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return $this->response->setJSON($response);

        } else {
            // Handle the case where 'id' parameter is missing or invalid
            // $this->output->set_output(json_encode(['error' => 'Invalid or missing parameter']));
            return $this->response->setJSON(['error' => 'Invalid or missing parameter ']);
        }
    }

    // CI4 Converted:
    public function deleteEquipment()
    {
        // Check if the request method is DELETE
        if ($this->request->getMethod() === 'DELETE') {

            $id = $this->request->getVar('id');

            if ($id) {

                $model = new ApiModel();
                $result = $model->deleteEquipments($id);

                if ($result) {

                    $response = [
                        'pagination' => null,
                        'status' => -1,
                        'msg' => 'Equipment deleted'
                    ];
                }
                return $this->response->setJSON($response);
                // $this->output->set_output(json_encode($response));

            } else {

                // $this->output->set_output(json_encode(['error' => 'Invalid or missing ID parameter']));
                return $this->response->setJSON(['error' => 'Invalid or missing ID parameter']);
            }
        } else {
            return $this->response->setJSON(['error' => 'Invalid request method']);
            // $this->output->set_output(json_encode(['error' => 'Invalid request method']));
        }
    }

    // CI4 Converted:
    public function addSkills()
    {
        $request_data = $this->request->getJSON(true);

        $model = new ApiModel();

        $response = $model->addSkills($request_data);

        return $this->response->setJSON($response);
        //    $this->output
        // ->set_content_type('application/json')
        // ->set_output(json_encode($response));



    }

    // CI4 Converted:
    public function getSkills()
    {

        $model = new ApiModel();

        $requestData = $this->request->getJSON(true);
        if (!isset($requestData['studentId']) || !isset($requestData['date'])) {
            $response = [
                'status' => 0,
                'msg' => 'Invalid request data. Both studentId and dateTime are required.',
                'data' => null
            ];

            return $this->response->setJSON($response, 400);
        }

        $studentId = $requestData['studentId'];
        $dateTime = $requestData['date'];
        $result = $model->fetchSkills($studentId, $dateTime);


        if ($result !== null) {


            $formattedItem = [
                'skillId' => $result['SkillId'],
                'dateTime' => $result['DateTime'],
                'stretching' => $result['Stretching'],
                'fitnessSessionLowerBody' => $result['FitnessSessionLowerBody'],
                'fitnessSessionUpperBody' => $result['FitnessSessionUpperBody'],
                'fitnessSessionCore' => $result['FitnessSessionCore'],
                'mentalTraining' => $result['MentalTraining'],
                'alignmentDrill' => $result['AlignmentDrill'],
                'greenReading' => $result['GreenReading'],
                'courseManagement' => $result['CourseManagement'],
                'rulesQuiz' => $result['RulesQuiz'],
                'videoSwingAnalysis' => $result['VideoSwingAnalysis'],
                '_18HolesWalk' => $result['_18HolesWalk'],
                '_9HolesWalk' => $result['_9HolesWalk'],
                '_18HolesPlayedWithGolfCar' => $result['_18HolesPlayedWithGolfCar'],
                'studentId' => $result['StudentId'],
                'student' => null
            ];


            $response = [
                'pagination' => null,
                'status' => 1,
                'msg' => null,
                'data' => $formattedItem
            ];

            // $this->output->set_content_type('application/json')->set_output(json_encode($response));
        } else {
            $formattedItem = [
                'skillId' => 0,
                'dateTime' => 0,
                'stretching' => 0,
                'fitnessSessionLowerBody' => 0,
                'fitnessSessionUpperBody' => 0,
                'fitnessSessionCore' => 0,
                'mentalTraining' => 0,
                'alignmentDrill' => 0,
                'greenReading' => 0,
                'courseManagement' => 0,
                'rulesQuiz' => 0,
                'videoSwingAnalysis' => 0,
                '_18HolesWalk' => 0,
                '_9HolesWalk' => 0,
                '_18HolesPlayedWithGolfCar' => 0,
                'studentId' => 0,
                'student' => null
            ];

            $response = [
                'pagination' => null,
                'status' => 1,
                'msg' => null,
                'data' => $formattedItem
            ];
        }
        return $this->response->setJSON($response);



    }

    // CI4 Converted:
    public function profile()
    {
        $request_data = $this->request->getJSON(true);

        $email = $request_data['email'] ?? null;
        // $password = $request_data['password'];
        $model = new ApiModel();
        $userDetails = $model->getUserByEmail($email);

        if ($userDetails !== null) {

            $profileData = [
                'firstName' => $userDetails['Profile_FirstName'],
                'lastName' => $userDetails['Profile_LastName'],
                'phone' => $userDetails['Profile_Phone'],
                'gender' => ($userDetails['Profile_Gender'] == 1), // Convert to boolean
                'age' => (int) $userDetails['Profile_Age'],
                'hcp' => (int) $userDetails['Profile_Hcp'],
                'healthLimitation' => $userDetails['Profile_HealthLimitation'],
                'distanceUnit' => (int) $userDetails['Profile_DistanceUnit'],
                'speedUnit' => (int) $userDetails['Profile_SpeedUnit'],
            ];

            $response = [
                'pagination' => null,
                'status' => 1,
                'msg' => null,
                'data' => $profileData
            ];

        } else {
            $response = [
                'pagination' => null,
                'status' => 1,
                'msg' => 'User Not found',
                'data' => null
            ];
        }
        return $this->response->setJSON($response);
    }

    // CI4 Converted:
    public function updateProfile()
    {
        $request_data = $this->request->getJSON(true);
        $userId = $request_data['UserId'];
        $newPassword = $request_data['newPassword'] ?? null;
        $oldPassword = $request_data['oldPassword'] ?? null;
        $isChangePassword = $request_data['changePassword'] ?? false;
        $model = new ApiModel();
        $userData = $model->getUserById($userId);

        $data = [
            'Profile_FirstName' => $request_data['firstName'],
            'Profile_LastName' => $request_data['lastName'],
            'Profile_Phone' => $request_data['phone'],
            'Profile_Gender' => $request_data['gender'],
            'Profile_Age' => $request_data['age'],
            'Profile_Hcp' => $request_data['hcp'],
            'Profile_HealthLimitation' => $request_data['healthLimitation'],
            'Profile_DistanceUnit' => $request_data['distanceUnit'],
            'Profile_SpeedUnit' => $request_data['speedUnit'],
        ];

        // Prepare data to be updated
        $firstName = $request_data['firstName'];
        $lastName = $request_data['lastName'];
        $phone = $request_data['phone'];
        $gender = $request_data['gender'];
        $age = $request_data['age'];
        $hcp = $request_data['hcp'];
        $healthLimitation = $request_data['healthLimitation'];
        $distanceUnit = $request_data['distanceUnit'];
        $speedUnit = $request_data['speedUnit'];

        $hashedNewPassword = null;
        if ($isChangePassword) {


            if ($userData) {
                if (password_verify($oldPassword, $userData['Password'])) {

                    if (!empty($newPassword)) {
                        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                        $data['Password'] = $hashedPassword;
                    }

                    $Email = $userData['Email'];
                    $model->updateProfileData(
                        $Email,
                        $hashedNewPassword,
                        $age,
                        $distanceUnit,
                        $firstName,
                        $gender,
                        $hcp,
                        $healthLimitation,
                        $lastName,
                        $phone,
                        $speedUnit
                    );
                    $response = [
                        'pagination' => null,
                        'status' => 1,
                        'msg' => 'Profile successfully updated',
                        'data' => null
                    ];
                } else {
                    $response = [
                        'pagination' => null,
                        'status' => 0,
                        'msg' => 'Old password doesnot Match',
                        'data' => null
                    ];
                }
            } else {
                $response = [
                    'pagination' => null,
                    'status' => 0,
                    'msg' => 'User not found',
                    'data' => null
                ];
            }
        } else {
            if (!empty($newPassword)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $data['Password'] = $hashedPassword;
            }
            $model->updateProfileData(
                $userData['Email'],
                $hashedNewPassword,
                $age,
                $distanceUnit,
                $firstName,
                $gender,
                $hcp,
                $healthLimitation,
                $lastName,
                $phone,
                $speedUnit
            );
            $response = [
                'pagination' => null,
                'status' => 1,
                'msg' => 'Profile successfully updated',
                'data' => null
            ];
        }
        return $this->response->setJSON($response);

    }


    // CI4 Converted:
    public function addShotScore()
    {
        // $inputJSON = file_get_contents('php://input');
        $data = $this->request->getJSON(true);

        if (isset($data['dateTime']) && isset($data['isWithPro']) && isset($data['shotRecords']) && is_array($data['shotRecords']) && isset($data['studentId'])) {

            $model = new ApiModel();
            $response = [
                'status' => 1,
                'msg' => 'Record successfully updated',
                'data' => null
            ];
            $dateTime = $data['dateTime'];
            $isWithPro = $data['isWithPro'];
            $studentId = $data['studentId'];
            foreach ($data['shotRecords'] as $record) {
                $model->addShotScore($record, $dateTime, $isWithPro, $studentId);
            }
            // $this->output
            //     ->set_content_type('application/json')
            //     ->set_output(json_encode($response));
            return $this->response->setJSON($response);
        } else {
            $response = [
                'status' => 0,
                'msg' => 'Invalid request format',
                'data' => null
            ];
            return $this->response->setJSON($response, 400);
            // $this->output
            //     ->set_status_header(400)
            //     ->set_output(json_encode($response));
        }
    }

    // public function ShotScores()
// {
//     $inputJSON = file_get_contents('php://input');
//     $data = json_decode($inputJSON, true);
//     if (isset($data['date']) && isset($data['shotGroup']) && isset($data['studentId'])) {
//         $this->load->model('New_Model');
//         $date = $data['date'];
//         $shotGroup = $data['shotGroup'];
//         $studentId = $data['studentId'];
//         $shotScores = $this->New_Model->getShotScores($date, $shotGroup, $studentId);

    //           $response = array(
//             'pagination' => null,
//             'status' => 1,
//             'msg' => null,
//             'data' => array()
//         );



    //         if($shotScores !=null)
//         {

    //         foreach ($shotScores as $item) {
//             $formattedItem = array(
//                 'shotCategory' => $item['ShotCategory'],
//                 'shotType' => $item['ShotType'],
//                 'shots' => $item['Shots'],
//                 'goodShots' => $item['GoodShots'],
//             );

    //             $response['data'] = $formattedItem;
//         }

    //             $this->output->set_content_type('application/json')->set_output(json_encode($response));        
//         }
//         else {
//             $response = array(
//             'pagination' => null,
//             'status' => 1,
//             'msg' => 'No Records for requested date or ID',
//             'data' => array()
//         );
//         $this->output
//         ->set_status_header(200)
//         ->set_content_type('application/json')
//         ->set_output(json_encode($response));

    //     }

    //     } 
//     else {
//         $response = array(
//             'pagination' => null,
//             'status' => 0,
//             'msg' => 'Invalid request format',
//             'data' => array()
//         );
//         $this->output
//         ->set_status_header(400)
//         ->set_content_type('application/json')
//         ->set_output(json_encode($response));
//     }
// }

    //------------------New---

    // CI4 converted:
    public function ShotScores()
    {
        // $inputJSON = file_get_contents('php://input');
        $data = $this->request->getJSON(true);

        if (isset($data['date']) && isset($data['shotGroup']) && isset($data['studentId'])) {
            $model = new ApiModel();
            $date = $data['date'];
            $shotGroup = $data['shotGroup'];
            $studentId = $data['studentId'];
            $shotScores = $model->getShotScores($date, $shotGroup, $studentId);

            $response = [
                'pagination' => null,
                'status' => 1,
                'msg' => null,
                'data' => []
            ];

            if ($shotScores !== null) {

                foreach ($shotScores as $item) {
                    $formattedItem = [
                        'shotCategory' => $item['ShotCategory'],
                        'shotType' => $item['ShotType'],
                        'shots' => $item['Shots'],
                        'goodShots' => $item['GoodShots'],
                    ];

                    $response['data'][] = $formattedItem;
                }

                // $this->output->set_content_type('application/json')->set_output(json_encode($response));
            } else {
                $response = [
                    'pagination' => null,
                    'status' => 1,
                    'msg' => 'No Records for requested date or ID',
                    'data' => []
                ];
                return $this->response->setJSON($response);
                // $this->output
                //     ->set_status_header(200)
                //     ->set_content_type('application/json')
                //     ->set_output(json_encode($response));
            }
        } else {
            $response = [
                'pagination' => null,
                'status' => 0,
                'msg' => 'Invalid request format',
                'data' => []
            ];

            return $this->response->setJSON($response, 400);
        }
    }
    //--------------

    // CI4 Converted:

    public function getInstructorProfile()
    {
        $request = $this->request->getJSON(true);

        if (isset($request['StudentId'])) {
            $StudentId = $request['StudentId'];

            $model = new ApiModel();
            $instructorProfile = $model->getInstructorProfile($StudentId);

            if ($instructorProfile) {
                $response = [
                    'pagination' => null,
                    'status' => 1,
                    'msg' => null,
                    'data' => $instructorProfile
                ];
            } else {
                $response = [
                    'pagination' => null,
                    'status' => 0,
                    'msg' => 'Instructor not found',
                    'data' => null
                ];
            }
            return $this->respond($response, 200);

            // $this->output
            //     ->set_status_header(200)
            //     ->set_content_type('application/json', 'utf-8')
            //     ->set_output(json_encode($response, JSON_PRETTY_PRINT));
        } else {
            $response = [
                'pagination' => null,
                'status' => 0,
                'msg' => 'Missing StudentId in the request',
                'data' => null
            ];
            return $this->respond($response, 400);
            // $this->output
            //     ->set_status_header(400)
            //     ->set_content_type('application/json', 'utf-8')
            //     ->set_output(json_encode($response, JSON_PRETTY_PRINT));
        }
    }
    // CI4 Converted:
    public function getInstructorProfile1()
    {
        // $inputJSON = file_get_contents('php://input');
        $data = $this->request->getJSON(true);
        $status = 0;
        $msg = null;
        $instructorProfile = null;
        $header = 404;

        if (isset($data['StudentId'])) {
            $StudentId = $data['StudentId'];

            $model = new ApiModel();
            $instructorProfile = $model->getInstructorProfileByStudentId($StudentId);

            if ($instructorProfile) {
                $status = 1;
                $msg = null;
                $header = 200;
            } else {
                $status = 0;
                $msg = 'Instructor not found';
                $header = 404;
            }
        } else {
            $status = 0;
            $msg = 'Missing StudentId in the request';
            $header = 400;
        }

        $response = [
            'status' => $status,
            'msg' => $msg,
            'data' => $instructorProfile
        ];
        return $this->respond($response, $header);

        // $this->output
        //     ->set_status_header($header)
        //     ->set_content_type('application/json', 'utf-8')
        //     ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }

    // public function woodScore() {
//     $this->load->model('New_Model');

    //     $requestData = json_decode(file_get_contents('php://input'), true);

    //     if (!isset($requestData['studentId']) || !isset($requestData['date'])) {
//         $response = array(
//             'status' => 0,
//             'msg' => 'Invalid request data. Both studentId and date are required.',
//             'data' => null
//         );

    //         $this->output->set_content_type('application/json')->set_output(json_encode($response));
//         return;
//     }

    //     $studentId = $requestData['studentId'];
//     $date = $requestData['date'];
//     $result = $this->New_Model->clubData($studentId, $date);

    //     $response = array(
//         'pagination' => null,
//         'status' => 1,
//         'msg' => null,
//         'data' => array()
//     );

    //     if ($result != null) {
//         $uniqueClubs = array_unique(array_column($result, 'Club'));

    //         foreach ($uniqueClubs as $club) {
//             if ($club >= 101 && $club <= 112) {
//                 continue;
//             }

    //             $clubData = array_filter($result, function ($item) use ($club) {
//                 return $item['Club'] == $club;
//             });

    //             $averages = array(); 
//             $firstGround = null; 

    //             foreach ($clubData as $item) {
//                 foreach (['AngDistance', 'ClubHeadSpeed', 'SpinRate', 'Apex', 'BallsAmount', 'Rating'] as $field) {
//                     if (!isset($averages[$field])) {
//                         $averages[$field] = 0;
//                     }
//                     $averages[$field] += $item[$field];
//                 }

    //                 if ($firstGround === null) {
//                     $firstGround = $item['Ground'];
//                 }
//             }

    //             foreach ($averages as $field => $value) {
//                 $averages[$field] = $value / count($clubData);
//             }

    //             $formattedItem = array(
//                 'ground' => $firstGround,
//                 'club' => $club,
//                 'avgDistance' => $averages['AngDistance'],
//                 'clubHeadSpeed' => $averages['ClubHeadSpeed'],
//                 'spinRate' => $averages['SpinRate'],
//                 'smashFactor' => $averages['Apex'], 
//                 'ballsAmount' => $averages['BallsAmount'],
//                 'rating' => $averages['Rating'],
//             );

    //             $response['data'][] = $formattedItem;
//         }
//     }

    //     $this->output->set_content_type('application/json')->set_output(json_encode($response));
// }

    //-------------------------------------------------- New 
// public function wood() {
//     $this->load->model('New_Model');
//     $requestData = json_decode(file_get_contents('php://input'), true);

    //     if (!isset($requestData['studentId']) || !isset($requestData['date'])) {
//         $response = array(
//             'status' => 0,
//             'msg' => 'Invalid request data. Both studentId and date are required.',
//             'data' => null
//         );

    //         $this->output->set_content_type('application/json')->set_output(json_encode($response));
//         return;
//     }

    //     $studentId = $requestData['studentId'];
//     $date = $requestData['date'];
//     $result = $this->New_Model->clubData($studentId, $date);

    //     $response = array(
//         'pagination' => null,
//         'status' => 1,
//         'msg' => null,
//         'data' => array()
//     );

    //     if ($result != null) {
//         $result = array_filter($result, function ($item) {
//             $club = $item['Club'];
//             return ($club >= 201 && $club <= 212);
//         });

    //         $uniqueClubs = array_unique(array_column($result, 'Club'));

    //         foreach ($uniqueClubs as $club) {
//             $clubData = array_filter($result, function ($item) use ($club) {
//                 return $item['Club'] == $club;
//             });

    //             $averages = array(); 
//             $firstGround = null; 

    //             foreach ($clubData as $item) {
//                 foreach (['AngDistance', 'ClubHeadSpeed', 'SpinRate', 'Apex', 'BallsAmount', 'Rating'] as $field) {
//                     if (!isset($averages[$field])) {
//                         $averages[$field] = 0;
//                     }
//                     $averages[$field] += $item[$field];
//                 }

    //                 if ($firstGround === null) {
//                     $firstGround = $item['Ground'];
//                 }
//             }

    //             foreach ($averages as $field => $value) {
//                 $averages[$field] = $value / count($clubData);
//             }

    //             $formattedItem = array(
//                 'ground' => $firstGround,
//                 'club' => $club,
//                 'avgDistance' => $averages['AngDistance'],
//                 'clubHeadSpeed' => $averages['ClubHeadSpeed'],
//                 'spinRate' => $averages['SpinRate'],
//                 'smashFactor' => $averages['Apex'],
//                 'ballsAmount' => $averages['BallsAmount'],
//                 'rating' => $averages['Rating'],
//             );

    //             $response['data'][] = $formattedItem;

    //         }
//     }
//     $this->output->set_content_type('application/json')->set_output(json_encode($response));
// }


    // CI4 Converted:

    public function wood()
    {
        $model = new ApiModel();
        $requestData = $this->request->getJSON(true);

        if (!isset($requestData['studentId']) || !isset($requestData['date'])) {
            $response = [
                'status' => 0,
                'msg' => 'Invalid request data. Both studentId and date are required.',
                'data' => null
            ];

            return $this->respond($response, 400);
            // $this->output->set_content_type('application/json')->set_output(json_encode($response));
            // return;
        }

        $studentId = $requestData['studentId'];
        $date = $requestData['date'];
        $result = $model->clubData($studentId);

        $response = [
            'pagination' => null,
            'status' => 1,
            'msg' => null,
            'data' => []
        ];

        if ($result != null) {
            $result = array_filter($result, function ($item) {
                $club = $item['Club'];
                return ($club >= 201 && $club <= 212);
            });

            // Group data by club and ground
            $groupedData = [];
            foreach ($result as $item) {
                $groupedData[$item['Club']][$item['Ground']][] = $item;
            }

            foreach ($groupedData as $club => $clubData) {
                foreach ($clubData as $ground => $data) {
                    $averages = [];

                    foreach (['AngDistance', 'ClubHeadSpeed', 'SpinRate', 'Apex', 'BallsAmount', 'Rating'] as $field) {
                        $averages[$field] = array_sum(array_column($data, $field)) / count($data);
                    }

                    $formattedItem = [
                        'ground' => $ground,
                        'club' => $club,
                        'avgDistance' => $averages['AngDistance'],
                        'clubHeadSpeed' => $averages['ClubHeadSpeed'],
                        'spinRate' => $averages['SpinRate'],
                        'smashFactor' => $averages['Apex'],
                        'ballsAmount' => $averages['BallsAmount'],
                        'rating' => $averages['Rating'],
                    ];

                    $response['data'][] = $formattedItem;
                }
            }
        }
        return $this->respond($response);
        // $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    // public function iron() {
//     $this->load->model('New_Model');
//     $requestData = json_decode(file_get_contents('php://input'), true);

    //     if (!isset($requestData['studentId']) || !isset($requestData['date'])) {
//         $response = array(
//             'status' => 0,
//             'msg' => 'Invalid request data. Both studentId and date are required.',
//             'data' => null
//         );

    //         $this->output->set_content_type('application/json')->set_output(json_encode($response));
//         return;
//     }

    //     $studentId = $requestData['studentId'];
//     $date = $requestData['date'];
//     $result = $this->New_Model->clubData($studentId, $date);

    //     $response = array(
//         'pagination' => null,
//         'status' => 1,
//         'msg' => null,
//         'data' => array()
//     );

    //     if ($result != null) {
//         $uniqueClubs = array_unique(array_column($result, 'Club'));

    //         foreach ($uniqueClubs as $club) {
//             if ($club >= 201 && $club <= 212) {
//                 continue;
//             }

    //             $clubData = array_filter($result, function ($item) use ($club) {
//                 return $item['Club'] == $club;
//             });

    //             $averages = array(); 
//             $firstGround = null; 

    //             foreach ($clubData as $item) {
//                 foreach (['AngDistance', 'ClubHeadSpeed', 'SpinRate', 'Apex', 'BallsAmount', 'Rating'] as $field) {
//                     if (!isset($averages[$field])) {
//                         $averages[$field] = 0;
//                     }
//                     $averages[$field] += $item[$field];
//                 }

    //                 if ($firstGround === null) {
//                     $firstGround = $item['Ground'];
//                 }
//             }

    //             foreach ($averages as $field => $value) {
//                 $averages[$field] = $value / count($clubData);
//             }

    //             $formattedItem = array(
//                 'ground' => $firstGround,
//                 'club' => $club,
//                 'avgDistance' => $averages['AngDistance'],
//                 'clubHeadSpeed' => $averages['ClubHeadSpeed'],
//                 'spinRate' => $averages['SpinRate'],
//                 'smashFactor' => $averages['Apex'],
//                 'ballsAmount' => $averages['BallsAmount'],
//                 'rating' => $averages['Rating'],
//             );

    //             $response['data'][] = $formattedItem;
//         }
//     }

    //     $this->output->set_content_type('application/json')->set_output(json_encode($response));
// }
//----------------------------------------------New Logic
// public function iron() {
//     $this->load->model('New_Model');
//     $requestData = json_decode(file_get_contents('php://input'), true);

    //     if (!isset($requestData['studentId']) || !isset($requestData['date'])) {
//         $response = array(
//             'status' => 0,
//             'msg' => 'Invalid request data. Both studentId and date are required.',
//             'data' => null
//         );

    //         $this->output->set_content_type('application/json')->set_output(json_encode($response));
//         return;
//     }

    //     $studentId = $requestData['studentId'];
//     $date = $requestData['date'];
//     $result = $this->New_Model->clubData($studentId, $date);

    //     $response = array(
//         'pagination' => null,
//         'status' => 1,
//         'msg' => null,
//         'data' => array()
//     );

    //     if ($result != null) {
//         $result = array_filter($result, function ($item) {
//             $club = $item['Club'];
//             return ($club >= 101 && $club <= 112);
//         });

    //         $uniqueClubs = array_unique(array_column($result, 'Club'));

    //         foreach ($uniqueClubs as $club) {
//             $clubData = array_filter($result, function ($item) use ($club) {
//                 return $item['Club'] == $club;
//             });

    //             $averages = array(); 
//             $firstGround = null; 

    //             foreach ($clubData as $item) {
//                 foreach (['AngDistance', 'ClubHeadSpeed', 'SpinRate', 'Apex', 'BallsAmount', 'Rating'] as $field) {
//                     if (!isset($averages[$field])) {
//                         $averages[$field] = 0;
//                     }
//                     $averages[$field] += $item[$field];
//                 }

    //                 if ($firstGround === null) {
//                     $firstGround = $item['Ground'];
//                 }
//             }

    //             foreach ($averages as $field => $value) {
//                 $averages[$field] = $value / count($clubData);
//             }

    //             $formattedItem = array(
//                 'ground' => $firstGround,
//                 'club' => $club,
//                 'avgDistance' => $averages['AngDistance'],
//                 'clubHeadSpeed' => $averages['ClubHeadSpeed'],
//                 'spinRate' => $averages['SpinRate'],
//                 'smashFactor' => $averages['Apex'],
//                 'ballsAmount' => $averages['BallsAmount'],
//                 'rating' => $averages['Rating'],
//             );

    //             $response['data'][] = $formattedItem;

    //         }
//     }
//     $this->output->set_content_type('application/json')->set_output(json_encode($response));
// }
//------------------------------------------below API working perfectly


    // CI4 Converted

    public function iron()
    {
        $model = new ApiModel();
        $requestData = $this->request->getJSON(true);
        if (!isset($requestData['studentId']) || !isset($requestData['date'])) {
            $response = [
                'status' => 0,
                'msg' => 'Invalid request data. Both studentId and date are required.',
                'data' => null
            ];
            return $this->respond($response);
            // $this->output->set_content_type('application/json')->set_output(json_encode($response));
            // return;
        }

        $studentId = $requestData['studentId'];
        $date = $requestData['date'];
        $result = $model->clubData($studentId);

        $response = [
            'pagination' => null,
            'status' => 1,
            'msg' => null,
            'data' => []
        ];

        if ($result != null) {
            $result = array_filter($result, function ($item) {
                $club = $item['Club'];
                return ($club >= 101 && $club <= 112);
            });

            $groupedData = [];
            foreach ($result as $item) {
                $groupedData[$item['Club']][$item['Ground']][] = $item;
            }

            foreach ($groupedData as $club => $clubData) {
                foreach ($clubData as $ground => $data) {
                    $averages = [];

                    foreach (['AngDistance', 'ClubHeadSpeed', 'SpinRate', 'Apex', 'BallsAmount', 'Rating'] as $field) {
                        $averages[$field] = array_sum(array_column($data, $field)) / count($data);
                    }

                    $formattedItem = [
                        'ground' => $ground,
                        'club' => $club,
                        'avgDistance' => $averages['AngDistance'],
                        'clubHeadSpeed' => $averages['ClubHeadSpeed'],
                        'spinRate' => $averages['SpinRate'],
                        'smashFactor' => $averages['Apex'],
                        'ballsAmount' => $averages['BallsAmount'],
                        'rating' => $averages['Rating'],
                    ];

                    $response['data'][] = $formattedItem;
                }
            }
        }
        return $this->respond($response);
        // $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    // CI4 Converted:
    public function getStudents()
    {
        $request = $this->request->getJSON(true);

        if (isset($request['UserId'])) {
            $instructorUserId = $request['UserId'];

            $model = new ApiModel();
            $students = $model->getStudentsByInstructorId($instructorUserId);

            if ($students) {
                $response = [
                    'pagination' => null,
                    'status' => 1,
                    'msg' => null,
                    'data' => $students
                ];
            } else {
                $response = [
                    'pagination' => null,
                    'status' => 0,
                    'msg' => 'No students found for the given instructor UserId',
                    'data' => null
                ];
            }

            return $this->respond($response, 200);
            // $this->output
            //     ->set_status_header(200)
            //     ->set_content_type('application/json', 'utf-8')
            //     ->set_output(json_encode($response, JSON_PRETTY_PRINT));
        } else {
            $response = [
                'pagination' => null,
                'status' => 0,
                'msg' => 'Missing UserId in the request',
                'data' => null
            ];
            $this->respond($response)->setStatusCode(400);

            // $this->output
            //     ->set_status_header(400)
            //     ->set_content_type('application/json', 'utf-8')
            //     ->set_output(json_encode($response, JSON_PRETTY_PRINT));
        }
    }
    // CI4 Converted:
    public function getStudentDetails()
    {
        $request = $this->request->getJSON(true);

        if (isset($request['StudentId'])) {
            $studentId = $request['StudentId'];

            $model = new ApiModel();
            $studentDetails = $model->getStudentDetails($studentId);

            if ($studentDetails) {
                $response = [
                    'pagination' => null,
                    'status' => 1,
                    'msg' => null,
                    'data' => $studentDetails
                ];
            } else {
                $response = [
                    'pagination' => null,
                    'status' => 0,
                    'msg' => 'Student details not found',
                    'data' => null
                ];
            }
            return $this->respond($response)->setStatusCode(200);

            // $this->output
            //     ->set_status_header(200)
            //     ->set_content_type('application/json', 'utf-8')
            //     ->set_output(json_encode($response, JSON_PRETTY_PRINT));
        } else {
            $response = [
                'pagination' => null,
                'status' => 0,
                'msg' => 'Missing StudentId in the request',
                'data' => null
            ];
            return $this->respond($response)->setStatusCode(400);
            // $this->output
            //     ->set_status_header(400)
            //     ->set_content_type('application/json', 'utf-8')
            //     ->set_output(json_encode($response, JSON_PRETTY_PRINT));
        }
    }

    // CI4 Converted:
    public function getStudent()
    {
        $request = $this->request->getJSON(true);

        if (isset($request['StudentId'])) {
            $studentId = $request['StudentId'];

            $model = new ApiModel();
            $studentDetails = $model->getStudentDetails($studentId);

            if ($studentDetails) {
                $response = [
                    'pagination' => null,
                    'status' => 1,
                    'msg' => null,
                    'data' => $studentDetails
                ];
            } else {
                $response = [
                    'pagination' => null,
                    'status' => 0,
                    'msg' => 'Student details not found',
                    'data' => null
                ];
            }
            return $this->respond($response)->setStatusCode(200);

            // $this->output
            //     ->set_status_header(200)
            //     ->set_content_type('application/json', 'utf-8')
            //     ->set_output(json_encode($response, JSON_PRETTY_PRINT));
        } else {
            $response = [
                'pagination' => null,
                'status' => 0,
                'msg' => 'Missing StudentId in the request',
                'data' => null
            ];
            return $this->respond($response)->setStatusCode(400);

            // $this->output
            //     ->set_status_header(400)
            //     ->set_content_type('application/json', 'utf-8')
            //     ->set_output(json_encode($response, JSON_PRETTY_PRINT));
        }
    }

    // CI4 Converted:
    public function studentStats()
    {
        $model = new ApiModel();
        $requestData = $this->request->getJSON(true);

        if (!isset($requestData['StudentId'])) {
            $response = [
                'status' => 400,
                'msg' => 'Invalid request data. StudentId is required.',
                'data' => null
            ];
            return $this->respond($response)->setStatusCode(400);

            // $this->output->set_status_header(400);
            // $this->output->set_content_type('application/json')->set_output(json_encode($response));
            // return;
        }

        $studentId = $requestData['StudentId'];
        $result = $model->fetchStudentStats($studentId);

        $response = [
            'pagination' => null,
            'status' => 200,
            'msg' => null,
            'data' => $result
        ];
        return $this->respond($response)->setStatusCode(200);

        // $this->output->set_status_header(200);
        // $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    //----------------------------------Session
// public function SessionStart()
//     {
//         $request = json_decode($this->input->raw_input_stream, true);

    //         if (isset($request['StudentId']) && isset($request['ProRefId'])) {
//             $studentId = $request['StudentId'];
//             $proRefId = $request['ProRefId'];

    //             $this->load->model('New_Model');
//             $sessionId = $this->New_Model->insertSession($studentId, $proRefId);

    //             if ($sessionId) {
//                  $response = array(
//                 'pagination' => null,
//                 'status' => 1,
//                 'msg' => 'session started',
//                 'data' => $sessionId
//              );
//             } else {
//                  $response = array(
//                 'status' => 0,
//                 'msg' => 'Failed to insert session',
//                 'data' => null
//             );
//         }
//             $this->output
//                 ->set_status_header(200)
//                 ->set_content_type('application/json', 'utf-8')
//                 ->set_output(json_encode($response, JSON_PRETTY_PRINT));
//         } else {
//             $response = array(
//                 'status' => 0,
//                 'msg' => 'Missing required parameters in the request',
//                 'data' => null
//             );

    //             $this->output
//                 ->set_status_header(400)
//                 ->set_content_type('application/json', 'utf-8')
//                 ->set_output(json_encode($response, JSON_PRETTY_PRINT));
//         }
//     }




    // CI4 Converted:

    public function SessionStart()
    {
        try {
            $request = $this->request->getJSON(true);

            if (isset($request['StudentId']) && isset($request['ProRefId'])) {
                $studentId = $request['StudentId'];
                $proRefId = $request['ProRefId'];

                $model = new ApiModel();
                $sessionId = $model->insertSession($studentId, $proRefId);

                if ($sessionId) {
                    $response = [
                        'pagination' => null,
                        'status' => 1,
                        'msg' => 'session started',
                        'data' => $sessionId
                    ];
                } else {
                    $response = [
                        'status' => 0,
                        'msg' => 'Failed to insert session',
                        'data' => null
                    ];
                }

                return $this->respond($response, 200);
                // $this->output
                //     ->set_status_header(200)
                //     ->set_content_type('application/json', 'utf-8')
                //     ->set_output(json_encode($response, JSON_PRETTY_PRINT));
            } else {
                $response = [
                    'status' => 0,
                    'msg' => 'Missing required parameters in the request',
                    'data' => null
                ];
                return $this->respond($response, 400);

                // $this->output
                //     ->set_status_header(400)
                //     ->set_content_type('application/json', 'utf-8')
                //     ->set_output(json_encode($response, JSON_PRETTY_PRINT));
            }
        } catch (\Exception $e) {
            $response = [
                'status' => 0,
                'msg' => 'Failed to start session. Database error occurred.',
                'data' => null
            ];
            return $this->respond($response, 500);
            // $this->output
            //     ->set_status_header(500)
            //     ->set_content_type('application/json', 'utf-8')
            //     ->set_output(json_encode($response, JSON_PRETTY_PRINT));
        }
    }

    //------------------------------------------------------------------------------
// CI4 Converted:
    public function stopSession()
    {
        $sessionId = $this->request->getVar('sessionId');

        if (!$sessionId) {
            $response = [
                'pagination' => null,
                'status' => 0,
                'msg' => 'Invalid request data. sessionId is required.',
                'data' => null
            ];
            return $this->respond($response, 400);

            // $this->output->set_content_type('application/json')->set_output(json_encode($response));
            // return;
        }

        $model = new ApiModel(); // Adjust to your New model
        $model->stopSession($sessionId); // Call the method to stop the session

        $response = [
            'pagination' => null,
            'status' => 1,
            'msg' => 'Session stopped',
            'data' => null,
        ];

        return $this->respond($response);

        // $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
    //--------------------------Review
// CI4 Converted:
    public function addReview()
    {
        $model = new ApiModel();

        $requestData = $this->request->getJSON(true);

        if (!isset($requestData['proRefId']) || !isset($requestData['studentRefId'])) {
            $response = [
                'pagination' => null,
                'status' => 0,
                'msg' => 'Invalid request data. Both proRefId and studentRefId are required.',
                'data' => null
            ];
            return $this->respond($response, 400);
            // $this->output->set_content_type('application/json')->set_output(json_encode($response));
            // return;
        }

        $result = $model->addOrUpdateReview($requestData);
        return $this->respond($result);
        // $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }
    //--------------------------------------------------------------------------------------------------------------------------------    
// CI4 converted:
    public function getStats()
    {
        $model = new ApiModel();

        $requestData = $this->request->getJSON(true);

        if (!isset($requestData['duration']) || !isset($requestData['shotType']) || !isset($requestData['studentId'])) {
            $response = [
                'status' => 400,
                'msg' => 'Invalid request data. Duration, shotType, and studentId are required.',
                'data' => null
            ];
            return $this->respond($response, 400);
            // $this->output->set_status_header(400);
            // $this->output->set_content_type('application/json')->set_output(json_encode($response));
            // return;
        }

        $duration = $requestData['duration'];
        $shotType = $requestData['shotType'];
        $studentId = $requestData['studentId'];

        $result = $model->getStats($duration, $shotType, $studentId);

        $response = [
            'pagination' => null,
            'status' => 1,
            'msg' => null,
            'data' => $result
        ];
        return $this->respond($response, 200);
        // $this->output->set_status_header(200);
        // $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    // CI4 Converted:
    public function handleRequest($action)
    {
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
            // case 'testScore':
            //     $response = $this->testScore();
            //     break;
            case 'addEquipments':
                $response = $this->addEquipments();
                break;
            case 'Equipments':
                $response = $this->Equipments();
                break;
            default:
                $response = [
                    'pagination' => null,
                    'status' => 0,
                    'msg' => 'Invalid action',
                    'data' => null
                ];
                break;
        }

        return $this->respond($response);
        // header('Content-Type: application/json');
        // echo json_encode($response);
    }
}
