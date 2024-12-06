<?php
namespace App\Models;

use CodeIgniter\Model;
// defined('BASEPATH') or exit('No direct script access allowed');


class ApiModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        // No need to load the database, it's done automatically
    }

    //converted to CI4
    public function getTournamentScore($date, $studentId)
    {
        $builder = $this->db->table("games"); //table name
        $builder->select('*');
        // $builder->from('games');
        $builder->where('DateTime', $date);
        $builder->where('StudentId', $studentId);
        // $builder->where('GameType', $gameType); // Constant GameType value
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
            // return $query->row_array();
        } else {
            return null;
        }

        

    }
    // Converted to CI4
    public function getPlayRoundScore($date, $studentId)
    {
        $builder = $this->db->table('games');
        $builder->select('*');
        // $builder->from('games');
        $builder->where('DateTime', $date);
        $builder->where('StudentId', $studentId);
        // $builder->where('GameType', 2);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            // return $query->row_array();
            return $query->getResultArray();

        } else {
            return null;
        }
    }
// Ci4 Model
    public function addGames($data, $dateTime,$studentId)
    {
    
        try {
            $insertData = [
                // 'DateTime' => $dateTime,
                'GameType' => $data['gameType'],
                'WarmupTime' => $data['warmupTime'],
                'DriverPeaces' => $data['driverPeaces'],
                'IronPeaces' => $data['ironPeaces'],
                'ChipPeaces' => $data['chipPeaces'],
                'SandPeaces' => $data['sandPeaces'],
                'PuttPeaces' => $data['puttPeaces'],
                'GolfCourse' => $data['golfCourse'],
                'ExactHcp' => $data['exactHcp'],
                'PlayingHcp' => $data['playingHcp'],
                'Nervous' => $data['nervous'],
                'FlightPartnersRating' => $data['flightPartnersRating'],
                'DriversRating' => $data['driversRating'],
                'DriversLeft' => $data['driversLeft'],
                'DriversCenter' => $data['driversCenter'],
                'DriversRight' => $data['driversRight'],
                'IronsRating' => $data['ironsRating'],
                'IronsLeft' => $data['ironsLeft'],
                'IronsCenter' => $data['ironsCenter'],
                'IronsRight' => $data['ironsRight'],
                'WoodsRating' => $data['woodsRating'],
                'WoodsLeft' => $data['woodsLeft'],
                'WoodsCenter' => $data['woodsCenter'],
                'WoodsRight' => $data['woodsRight'],
                'ShortIronGameRating' => $data['shortIronGameRating'],
                'BunkerShortsRating' => $data['bunkerShortsRating'],
                'PuttingStrokes' => $data['puttingStrokes'],
                'GreenSpeedRating' => $data['greenSpeedRating'],
                'Strokes' => $data['strokes'],
                'NewHcp' => $data['newHcp'],
                'Walking' => $data['walking'],
                'DistanceWalked' => $data['distanceWalked'],
                'GameDuration' => $data['gameDuration'],
                'Holes' => $data['holes'] ?? 0,
                'StudentId' => $studentId,
                'StablefordPoints' => $data['stablefordPoints'] ?? 0,
            ];

            // Check if the game entry exists
            $builder = $this->db->table('games');
            $builder->where('DateTime', $dateTime);
            $builder->where('StudentId', $studentId);
            $builder->where('GameType', $data['gameType']);

            if ($builder->countAllResults() > 0) {
                // Update existing record
                $builder->where('DateTime', $dateTime);
                $builder->where('StudentId', $studentId);
                $builder->where('GameType', $data['gameType']);
                $builder->update($insertData);
            } else {
                // Insert new record
                $builder->insert($insertData);
            }

            // Prepare response based on affected rows
            if ($this->db->affectedRows() > 0) {
                return [
                    'pagination' => null,
                    'status' => 1,
                    'msg' => 'Game data added successfully',
                    'data' => null,
                ];
            } else {
                return [
                    'pagination' => null,
                    'status' => 0,
                    'msg' => 'No rows affected',
                    'data' => null,
                ];
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception in addGames: ' . $e->getMessage());
            return [
                'pagination' => null,
                'status' => 0,
                'msg' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
    // CI4 Model
    public function insertUserData($role, $email, $hashedPassword, $profile)
    {
        $data = [
            'Role' => $role,
            'Email' => $email,
            'Password' => $hashedPassword,
            'Profile_FirstName' => $profile['firstName'],
            'Profile_LastName' => $profile['lastName'],
            'Profile_Phone' => $profile['phone'],
            'Profile_Gender' => $profile['gender'],
            'Profile_Age' => $profile['age'],
            'Profile_Hcp' => $profile['hcp'],
            'Profile_HealthLimitation' => $profile['healthLimitation'],
            'Profile_DistanceUnit' => $profile['distanceUnit'],
            'Profile_SpeedUnit' => $profile['speedUnit']
        ];

        $this->db->table('users')->insert($data);

        if (
            $this->db->affectedRows
            () > 0
        ) {
            return true;
        } else {
            return false;
        }
    }
    // CI4 Model:
    public function addEquipment($data)
    {
        $data = [
            'Type' => $data['type'],
            'Club' => $data['club'],
            'Brand' => $data['brand'],
            'Shaft' => $data['shaft'],
            'Model' => $data['model'],
            'ClubLoft' => 0,
            'Grip' => $data['grip'],
            'Size' => $data['size'],
            'Pairs' => $data['pairs'],
            'Pieces' => $data['pieces'],
            'Studentid' => $data['studentId']
        ];

        $this->db->table('equipments')->insert($data);

        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // CI4 Model
    public function fetchEquipments($type, $studentId)
    {
        $builder = $this->db->table('equipments');

        $query = $builder->getWhere(['Type' => $type, 'Studentid' => $studentId]);

        if ($query->getNumRows() > 0) {
            // Fetch and return the data as an array
            return $query->getResultArray();
        } else {
            return null;
        }
    }
    // Converted to CI4    
    public function deleteEquipments($id)
    {


        $query = $this->db->table('equipments')->delete(['Equipmentid' => $id]);

        return $query;

    }
    // Converted to CI 4:
    public function getUserByEmail($email)
    {
        $builder = $this->db->table('users')->where('Email',$email);
        $query = $builder->get();
        // return $query->getResultArray();
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return null;
        }
    }
    // CI4 Converted:
    public function getUsersByRole($role)
    {
        // $query = $this->db->get_where('users', array('role' => $role));
        $builder = $this->db->table('users');
        $query =$builder->where('role',$role)->get();
        return $query->getResult();
    }
    // CI4 Model:
    public function getUserById($userId)
    {
        $builder= $this->db->table('users');
        $query = $builder->where('UserId', $userId)->get();
        // $query = $this->db->get('users');

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return null;
        }
    }

    // public function calculateIronScore($dateTime) {
    //     $query = "SELECT 
    //                 1 AS ground,
    //                 101 AS club,
    //                 AVG(distance) AS avgDistance,
    //                 AVG(club_head_speed) AS clubHeadSpeed,
    //                 AVG(spin_rate) AS spinRate,
    //                 AVG(apex) AS apex,
    //                 COUNT(*) AS ballsAmount,
    //                 AVG(rating) AS rating
    //               FROM clubpractices
    //               WHERE date = ?";

    //     $result = $this->db->query($query, array($dateTime));

    //     if ($result->num_rows() > 0) {
    //         $ironScoreData = $result->row_array();
    //     } else {
    //         $ironScoreData = array(
    //             'ground' => 1,
    //             'club' => 101,
    //             'avgDistance' => null,
    //             'clubHeadSpeed' => null,
    //             'spinRate' => null,
    //             'apex' => null,
    //             'ballsAmount' => 0,
    //             'rating' => null
    //         );
    //     }

    //     return $ironScoreData;
    // }
    
// Ci4 Model:\


    public function ironScore($dateTime, $studentId)
    {
        try {
            // Query to fetch ironScore data based on $dateTime and $studentId
            $builder = $this->db->table('iron_scores');
            $builder->where('date',$dateTime, );
            $builder->where('student_id',$studentId);
            $query = $builder->get();

            if ($query->getNumRows() > 0) {
                // Fetch the ironScore data
                $ironScoreData = $query->getRowArray();

                return $ironScoreData;
            } else {
                // No data found, return null or an empty array as needed
                return [];
            }
        } catch (\Exception $e) {
            // Handle any exceptions if necessary
            log_message('error','Exception in ironScores:' . $e->getMessage());
            return []; // Return an empty array in case of an exception
        }
    }

    // Ci4 Model:
    public function fetchIronScore($studentId, $dateTime)
    {
        // Query the database to fetch the iron score data
        $builder = $this->db->table('clubpractices');
        $builder->where('StudentId',$studentId);;
        $builder->where('DateTime',$dateTime);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            // Fetch and return the data as an array
            return $query->getResultArray();
        } else {
            return null;
        }
    }
//---------------------------------No change Below (angDistance)
    // public function addScore($dateTime, $isWithPro, $ground, $angDistance, $clubHeadSpeed, $spinRate, $apex, $ballsAmount, $rating, $studentId)
    // {
    //     try {
    //         $existingResult = $this->db
    //             ->select('ClubPracticeId')
    //             ->from('ClubPractices')
    //             ->where('StudentId', $studentId)
    //             ->where('DateTime', $dateTime)
    //             ->get();

    //             log_message('debug', 'SQL Query: ' . $this->db->last_query());

    //         if ($existingResult->num_rows() > 0) {
    //             $data = array(
    //                 'IsWithPro' => $isWithPro,
    //                 'Ground' => $ground,
    //                 'AngDistance' => $angDistance,
    //                 'ClubHeadSpeed' => $clubHeadSpeed,
    //                 'SpinRate' => $spinRate,
    //                 'Apex' => $apex,
    //                 'BallsAmount' => $ballsAmount,
    //                 'Rating' => $rating
    //             );

    //             $this->db
    //                 ->where('StudentId', $studentId)
    //                 ->where('DateTime', $dateTime)
    //                 ->update('ClubPractices', $data);
    //         } else {
    //             $data = array(
    //                 'DateTime' => $dateTime,
    //                 'IsWithPro' => $isWithPro,
    //                 'Ground' => $ground,
    //                 'AngDistance' => $angDistance,
    //                 'ClubHeadSpeed' => $clubHeadSpeed,
    //                 'SpinRate' => $spinRate,
    //                 'Apex' => $apex,
    //                 'BallsAmount' => $ballsAmount,
    //                 'Rating' => $rating,
    //                 'StudentId' => $studentId
    //             );

    //             $this->db->insert('ClubPractices', $data);
    //         }

    //         if ($this->db->affected_rows() > 0) {
    //             $response = array(
    //                 'status' => 1,
    //                 'msg' => 'Score added successfully',
    //                 'data' => null
    //             );
    //         } else {
    //             $response = array(
    //                 'status' => 0,
    //                 'msg' => 'No rows affected',
    //                 'data' => null
    //             );
    //         }

    //         return $response;
    //     } catch (Exception $e) {
    //         log_message('error', 'Exception in addScore: ' . $e->getMessage());
    //         $response = array(
    //             'status' => 0,
    //             'msg' => $e->getMessage(),
    //             'data' => null
    //         );

    //         return $response;
    //     }
    // }

    //------------------------------------------No change above---------------
    // CI4 Model:
    public function addScore($data)
    {
        try {

            $dateTime = $data['dateTime'];
            $isWithPro = $data['isWithPro'];
            $ground = $data['ground'];
            $avgDistance = $data['avgDistance'];
            $clubHeadSpeed = $data['clubHeadSpeed'];
            $spinRate = $data['spinRate'];
            $apex = $data['apex'];
            $ballsAmount = $data['ballsAmount'];
            $rating = $data['rating'];
            $studentId = $data['studentId'];
            $builder = $this->db->table('ClubPractices');
            $existingResult = $builder
                ->select('ClubPracticeId')
                ->where('StudentId', $studentId)
                ->where('DateTime', $dateTime)
                ->get();

            log_message('debug', 'SQL Query: ' . $this->db->getLastQuery());

            if ($existingResult->getNumRows() > 0) {
                $data = [
                    'IsWithPro' => $isWithPro,
                    'Ground' => $ground,
                    'avgDistance' => $avgDistance,
                    'ClubHeadSpeed' => $clubHeadSpeed,
                    'SpinRate' => $spinRate,
                    'Apex' => $apex,
                    'BallsAmount' => $ballsAmount,
                    'Rating' => $rating
                ];

                $builder
                    ->where('StudentId', $studentId)
                    ->where('DateTime', $dateTime)
                    ->update( $data);
            } else {
                $data = [
                    'DateTime' => $dateTime,
                    'IsWithPro' => $isWithPro,
                    'Ground' => $ground,
                    'avgDistance' => $avgDistance,
                    'ClubHeadSpeed' => $clubHeadSpeed,
                    'SpinRate' => $spinRate,
                    'Apex' => $apex,
                    'BallsAmount' => $ballsAmount,
                    'Rating' => $rating,
                    'StudentId' => $studentId
                ];

                $builder->insert( $data);
            }

            if ($this->db->affectedRows() > 0) {
                $response = [
                    'status' => 1,
                    'msg' => 'Score added successfully',
                    'data' => null
                ];
            } else {
                $response = [
                    'status' => 0,
                    'msg' => 'No rows affected',
                    'data' => null
                ];
            }

            return $response;
        } catch (\Exception $e) {
            log_message('error', 'Exception in addScore: ' . $e->getMessage());
            $response = [
                'status' => 0,
                'msg' => $e->getMessage(),
                'data' => null
            ];

            return $response;
        }
    }
    


    // CI4 Model:
    public function addScore2($data, $IsWithPro)
    {
        // Define the columns you want to insert data into
        $dateTime = $data['dateTime'];
        $studentId = $data['studentId'];
        $club = $data['club'];
        $ground = $data['ground'];
        $AngDistance = $data['avgDistance'];
        $ClubHeadSpeed = $data['clubHeadSpeed'];
        $SpinRate = $data['spinRate'];
        $Apex = $data['apex'];
        $BallsAmount = $data['ballsAmount'];
        $Rating = $data['rating'];

        $insertData = [
            'DateTime' => $data['dateTime'],
            'IsWithPro' => $IsWithPro,
            'Ground' => $data['ground'],
            'Club' => $data['club'],
            'AngDistance' => $data['avgDistance'],
            'ClubHeadSpeed' => $data['clubHeadSpeed'],
            'SpinRate' => $data['spinRate'],
            'Apex' => $data['apex'],
            'BallsAmount' => $data['ballsAmount'],
            'Rating' => $data['rating'],
            'StudentId' => $data['studentId']
        ];
        
        $builder = $this->db->table('clubpractices');
        $existingResult = $builder
            ->select('ClubPracticeId')
            ->where('StudentId', $studentId)
            ->where('DateTime', $dateTime)
            ->where('Club', $club)
            ->where('Ground', $ground)
            ->get();

        if ($existingResult->getNumRows() > 0) {

            $builder
                ->where('StudentId', $studentId)
                ->where('DateTime', $dateTime)
                ->where('Club', $club)
                ->where('Ground', $ground)
                ->update( $insertData);

        } else {
            // Insert the data into the database table

            if (
                $AngDistance != 0 &&
                $ClubHeadSpeed != 0 &&
                $SpinRate != 0 &&
                $Apex != 0 &&
                $BallsAmount != 0 &&
                $Rating != 0
            ) {
                $builder->insert( $insertData);
            }

        }


    }

    // CI4 Model:
    public function updatePass($email, $password)
    {

        $insertData = [
            'Password' => $password
        ];
        $builder = $this->db->table('users');

        $$builder
            ->where('Email', $email)
            ->update($insertData);
    }
    // Ci4 Model:
    public function addSkills($data)
    {


        try {

            $dateTime = $data['dateTime'];
            $studentId = $data['studentId'];


            $insertData = [
                'DateTime' => $data['dateTime'],
                'Stretching' => $data['stretching'],
                'FitnessSessionLowerBody' => $data['fitnessSessionLowerBody'],
                'FitnessSessionUpperBody' => $data['fitnessSessionUpperBody'],
                'FitnessSessionCore' => $data['fitnessSessionCore'],
                'MentalTraining' => $data['mentalTraining'],
                'AlignmentDrill' => $data['alignmentDrill'],
                'GreenReading' => $data['greenReading'],
                'CourseManagement' => $data['courseManagement'],
                'RulesQuiz' => $data['rulesQuiz'],
                'VideoSwingAnalysis' => $data['videoSwingAnalysis'],
                '_18HolesWalk' => $data['_18HolesWalk'],
                '_9HolesWalk' => $data['_9HolesWalk'],
                '_18HolesPlayedWithGolfCar' => $data['_18HolesPlayedWithGolfCar'],
                'StudentId' => $data['studentId']

            ];

            $builder = $this->db->table('Skills');
            $existingResult = $builder
                ->select('SkillId')
                ->where('StudentId', $studentId)
                ->where('DateTime', $dateTime)
                ->get();

            if ($existingResult->getNumRows() > 0) {

                $result = $builder
                    ->where('StudentId', $studentId)
                    ->where('DateTime', $dateTime)
                    ->update( $insertData);

                if ($result) {
                    $response =[
                        'pagination' => null,
                        'status' => 1,
                        'msg' => 'Skills Updated Successfully',
                        'data' => null
                    ];
                }
            } else {
                // Insert the data into the database table

                $builder->insert( $insertData);

                if ($this->db->affectedRows() > 0) {
                    $response = [
                        'pagination' => null,
                        'status' => 1,
                        'msg' => 'Skills Added Successfully',
                        'data' => null
                    ];
                }
            }

            return $response;
        } catch (\Exception $e) {
            log_message('error', 'Exception in addSkills: ' . $e->getMessage());
            $response = [
                'pagination' => null,
                'status' => 0,
                'msg' => $e->getMessage(),
                'data' => null
            ];

            return $response;
        }
    }

    // CI4 Model:
    public function fetchSkills($studentId, $dateTime)
    {
        $builder =$this->db->table('Skills');
        $query = $builder->where('StudentId', $studentId)->where( 'DateTime', $dateTime)->get();

        if ($query->getNumRows() > 0) {
            // Fetch and return the data as an array
            return $query->getRowArray();
        } else {
            return null;
        }
    }

    // CI4 Model:
    public function updateProfileData($email, $hashedNewPassword, $age, $distanceUnit, $firstName, $gender, $hcp, $healthLimitation, $lastName, $phone, $speedUnit)
    {
        // Define an array with the data to update
        $data = [
            'Password' => $hashedNewPassword, // Only if changing the password, otherwise set it to the current hashed password
            'Profile_FirstName' => $firstName,
            'Profile_LastName' => $lastName,
            'Profile_Phone' => $phone,
            'Profile_Gender' => $gender,
            'Profile_Age' => $age,
            'Profile_Hcp' => $hcp,
            'Profile_HealthLimitation' => $healthLimitation,
            'Profile_DistanceUnit' => $distanceUnit,
            'Profile_SpeedUnit' => $speedUnit
        ];

        if ($hashedNewPassword === null) {
            unset($data['Password']); // Remove the password field if not changing it
        }
        $builder=$this->db->table('users');

        // Update the user's profile in the database based on their email
        $builder->where('Email', $email);
        $builder->update( $data);

        if ($this->db->affectedRows() > 0) {
            return true; // Profile update successful
        } else {
            return false; // Profile update failed
        }
    }
    
    // CI4 Model:
    public function addShotScore($record, $dateTime, $isWithPro, $studentId)
    {
        $goodShots = $record['goodShots'];
        $holed = $record['holed'];
        $putt = $record['putt'];
        $rating = $record['rating'];
        $shotCategory = $record['shotCategory'];
        $shotType = $record['shotType'];
        $shots = $record['shots'];
        if ($shots != 0) {

            $avg = ($goodShots / $shots) * 100;
            $rating_stars = ($avg / 100) * 5;

            $insertData = [
                'DateTime' => $dateTime,
                'IsWithPro' => $isWithPro,
                'ShotCategory' => $shotCategory,
                'ShotType' => $shotType,
                'Shots' => $shots,
                'GoodShots' => $goodShots,
                'Rating' => $rating_stars,
                'StudentId' => $studentId
            ];
            $builder = $this->db->table('ShotPractices');
            $existingResult = $builder
                ->select('ShotPracticeId')
                ->where('StudentId', $studentId)
                ->where('DateTime', $dateTime)
                ->where('ShotCategory', $shotCategory)
                ->where('ShotType', $shotType)
                ->get();
            if ($existingResult->getNumRows() > 0) {
                $builder
                    ->where('StudentId', $studentId)
                    ->where('DateTime', $dateTime)
                    ->where('ShotCategory', $shotCategory)
                    ->where('ShotType', $shotType)
                    ->update( $insertData);
            } else {
                $builder->insert( $insertData);
            }
        }
    }
   
    // CI4 Model:
    public function getShotScores($date, $shotGroup, $studentId)
    {

        $builder = $this->db->table('ShotPractices');
        $query = $builder->where('StudentId', $studentId)
        ->where( 'DateTime', $date)->get();

        if ($query->getNumRows() > 0) {

            return $query->getResultArray();
        } else {
            return null;
        }


    }

    //------------------------New
// public function getShotScores($date, $shotGroup, $studentId)
// {
//     $this->db->where('StudentId', $studentId);
//     $this->db->where('DateTime', $date);

    //     if ($shotGroup !== null) {
//         $this->db->where('ShotType', $shotGroup);
//     }

    //     $query = $this->db->get('ShotPractices');

    //     return ($query->num_rows() > 0) ? $query->result_array() : null;
// }
//-----------------------

// CI4 Model:

    public function getInstructorProfile($StudentId)
    {
        $builder = $this->db->table('users');
        $builder->select('ProRefId');
        $builder->where('ProRefId', $StudentId);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            $ProRefId = $row->ProRefId;

            $builder->select('ProRefId as UserId, Profile_LastName as LastName, Profile_FirstName as FirstName, Email, Profile_Age as age, Profile_Gender as gender, Profile_Hcp as hcp');
            $builder->where('ProRefId', $ProRefId);
            $query = $builder->get();

            if ($query->getNumRows() > 0) {
                $instructorProfile = $query->getRow();
                return $instructorProfile;
            }
        }

        return null;
    }

    // CI4 Model:
    public function getInstructorProfileByStudentId($StudentId)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.UserId as UserId, u.Profile_LastName as LastName, u.Profile_FirstName as FirstName, u.Email, u.Profile_Age as age, u.Profile_Gender as gender, u.Profile_Hcp as hcp');
        $builder->join('users u2', 'u2.ProRefId = u.UserId');
        $builder->where('u2.UserId', $StudentId);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $instructorProfile = $query->getRow();
            return $instructorProfile;
        }

        return null;
    }

    // CI4 Model:
    public function clubData($studentId)
    {
        $builder = $this->db->table('clubpractices');
        $query = $builder->where('StudentId',$studentId)->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return null;
        }
    }

    // CI4 Model:
    public function getStudentsByInstructorId($instructorUserId)
    {
        $builder = $this->db->table('users');
        $builder->select('UserId as userId, Email as email, Profile_FirstName as firstName, Profile_LastName as lastName');
        $builder->where('ProRefId', $instructorUserId);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $students = $query->getResult();
            return $students;
        }

        return null;
    }

    //------------------------------------------------------- studentStats Model
// public function fetchStudentStats($studentId) {
//     $this->db->select('
//         u.ProViewLevel as proViewLevel,
//         u.ProViewHcp as proViewHCP,
//         u.ProViewHcp as currentHCP,
//         s.StudentId as studentId,
//         s._18HolesWalk as played18Holes,
//         s._9HolesWalk as played9Holes,
//         s._18HolesPlayedWithGolfCar as playedTorunments,
//         s.VideoSwingAnalysis as videoAnalysis,
//         0 as proLessons,               
//         sess.End as lastProLesson, 
//         0 as ballsWithPro,            
//         8 as ballsByStudent           
//     ');
//     $this->db->from('users u');
//     $this->db->join('Skills s', 'u.UserId = s.StudentId', 'left');
//     $this->db->join('Sessions sess', 'u.UserId = sess.StudentRefId', 'left');
//     $this->db->where('u.UserId', $studentId);
//     $this->db->order_by('sess.End', 'DESC');

    //     $query = $this->db->get();

    //   if ($query->num_rows() > 0) {
//         $result = $query->row_array();

    //         $result['ProViewLevel'] =  isset($result['ProViewLevel']) ? $result['ProViewLevel'] : 0;
//         $result['ProViewHcp'] =  isset($result['ProViewHcp']) ? $result['ProViewHcp'] : 0;
//         $result['currentHCP'] =  isset($result['currentHCP']) ? $result['currentHCP'] : 0;
//         $result['studentId'] =  isset($result['studentId']) ? $result['studentId'] : 0;
//         $result['played18Holes'] =  isset($result['played18Holes']) ? $result['played18Holes'] : 0;
//         $result['played9Holes'] =  isset($result['played9Holes']) ? $result['played9Holes'] : 0;
//         $result['playedTorunments'] =  isset($result['playedTorunments']) ? $result['playedTorunments'] : 0;
//         $result['videoAnalysis'] = isset($result['videoAnalysis']) ? $result['videoAnalysis'] : 0;
//         $result['proLessons'] = 0;
//         $result['lastProLesson'] = isset($result['lastProLesson']) ? $result['lastProLesson'] : 0;
//         $result['ballsWithPro'] = 0;
//         $result['ballsByStudent'] = 0;


    //          return $result;
//     } else {
//         return null;
//     }
// }
//-------------------------------------------------------New (added Balls too)
// Ci4 Model   
   
public function fetchStudentStats($studentId)
{
    $builder = $this->db->table('users u');
    $builder->select('
        u.ProViewLevel as proViewLevel,
        u.ProViewHcp as proViewHCP,
        u.ProViewHcp as currentHCP,
        s.StudentId as studentId,
        s._18HolesWalk as played18Holes,
        s._9HolesWalk as played9Holes,
        s._18HolesPlayedWithGolfCar as playedTorunments,
        s.VideoSwingAnalysis as videoAnalysis,
        0 as proLessons,               
        sess.End as lastProLesson, 
        SUM(CASE WHEN sp.IsWithPro = 1 THEN sp.Shots ELSE 0 END) as ballsWithPro,            
        SUM(CASE WHEN sp.IsWithPro = 0 THEN sp.Shots ELSE 0 END) as ballsByStudent           
    ');
    // Join tables
    // $this->db->from('users u');
    $builder>join('Skills s', 'u.UserId = s.StudentId');
    $builder->join('Sessions sess', 'u.UserId = sess.StudentRefId', 'left');
    $builder->join('ShotPractices sp', 'u.UserId = sp.StudentId', 'left');
    // Add where clause
    $builder->where('u.UserId', $studentId);

    // Group by and order by:
    $builder->groupBy('u.ProViewLevel, u.ProViewHcp, u.ProViewHcp, s.StudentId, s._18HolesWalk, s._9HolesWalk, s._18HolesPlayedWithGolfCar, s.VideoSwingAnalysis, sess.End');
    $builder->orderBy('sess.End', 'DESC');

    $query = $builder->get();


    if ($query->getNumRows() > 0) {
        $result = $query->getRowArray();

        // Set default values if not present
        $result['ProViewLevel'] = $result['ProViewLevel'] ?? 0;
        $result['ProViewHcp'] = $result['ProViewHcp'] ?? 0;
        $result['currentHCP'] = $result['currentHCP'] ?? 0;
        $result['studentId'] = $result['studentId'] ?? 0;
        $result['played18Holes'] = $result['played18Holes'] ?? 0;
        $result['played9Holes'] = $result['played9Holes'] ?? 0;
        $result['playedTorunments'] = $result['playedTorunments'] ?? 0;
        $result['videoAnalysis'] = $result['videoAnalysis'] ?? 0;
        $result['proLessons'] = 0; // Always zero as per original code
        $result['lastProLesson'] = $result['lastProLesson'] ?? 0;
        $result['ballsWithPro'] = $result['ballsWithPro'] ?? 0;
        $result['ballsByStudent'] = $result['ballsByStudent'] ?? 0;

        return $result;
    } else {
        return null;
    }

    }


    //------------------------------------------------------- Session API Model
    // CI4 Model:
    public function insertSession($studentId, $proRefId)
    {

        $currentTime = date('Y-m-d H:i:s');

        $data = [
            'Start' => $currentTime,
            'End' => $currentTime,
            'StudentRefId' => $studentId,
            'StudentUserId' => null,
            'ProRefId' => $proRefId,
            'ProUserId' => null,
        ];
        $builder = $this->db->table('Sessions');

        $builder->insert( $data);
        return $this->db->insertID();
    }

    // public function insertSession($studentId, $proRefId)
// {
//     try {
//         $currentTime = date('Y-m-d H:i:s');

    //         $data = array(
//             'Start' => $currentTime,
//             'End' => $currentTime,
//             'StudentRefId' => $studentId,
//             'StudentUserId' => null,
//             'ProRefId' => $proRefId,
//             'ProUserId' => null,
//         );

    //         $this->db->insert('Sessions', $data);

    //         // Check for database errors
//         $lastError = error_get_last();
//         if ($lastError !== null && strpos($lastError['message'], 'foreign key constraint fails') !== false) {
//             // Handle foreign key constraint error
//             $response = array(
//                 'status' => 0,
//                 'msg' => 'Failed to start session. Foreign key constraint error.',
//                 'data' => null
//             );

    //             $this->output
//                 ->set_status_header(400)  // Use 400 status for client-side errors
//                 ->set_content_type('application/json', 'utf-8')
//                 ->set_output(json_encode($response, JSON_PRETTY_PRINT));

    //             // Log the error for further investigation
//             log_message('error', 'Foreign key constraint error in insertSession: ' . $lastError['message']);
//             return;
//         }

    //         return $this->db->insert_id();
//     } catch (Exception $e) {
//         $response = array(
//             'status' => 0,
//             'msg' => 'Failed to start session. Database error occurred.',
//             'data' => null
//         );

    //         $this->output
//             ->set_status_header(500)
//             ->set_content_type('application/json', 'utf-8')
//             ->set_output(json_encode($response, JSON_PRETTY_PRINT));
//         log_message('error', 'Database error in insertSession: ' . $e->getMessage());
//     }
// }
//------------------------------------------------------------------------------ Session Update


// CI4 Model
    public function stopSession($sessionId)
    {
        $data = [
            'End' => date('Y-m-d H:i:s')
        ];
        $builder =$this->db->table('Sessions');
        $builder->where('SessionId', $sessionId);
        $builder->update( $data);
    }



    //------------------------------------------------------- Review Model
    public function addOrUpdateReview($data)
    {
        $proRefId = $data['proRefId'];
        $studentRefId = $data['studentRefId'];
        // Builder initializing
        $builder = $this->db->table('Reviews');
        $existingReview = $builder->where('ProRefId',$proRefId)->where('StudentRefId',$studentRefId)->get()->getRowArray();
        // $existingReview = $builder->get_where('Reviews', array('ProRefId' => $proRefId, 'StudentRefId' => $studentRefId))->row_array();

        if ($existingReview) {
            $builder->where('ReviewId', $existingReview['ReviewId']);
            $builder->update( $data);

            return [
                'pagination' => null,
                'status' => 1,
                'msg' => 'Review updated successfully',
                'data' => null
            ];
        } else {
            $builder->insert( $data);

            return [
                'pagination' => null,
                'status' => 1,
                'msg' => 'Review added successfully',
                'data' => null
            ];
        }
    }

}