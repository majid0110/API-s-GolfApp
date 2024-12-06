<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class New_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

public function insertUserData($role, $email, $hashedPassword, $profile) {
    $data = array(
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
    );

    $this->db->insert('users', $data);

    if ($this->db->affected_rows() > 0) {
        return true; 
    } else {
        return false; 
    }
}

public function getUserByEmail($email) {
    $this->db->where('Email', $email);
    $query = $this->db->get('users');
    if ($query->num_rows() > 0) {
        return $query->row_array();
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
    

    public function ironScore($dateTime, $studentId) {
        try {
            // Query to fetch ironScore data based on $dateTime and $studentId
            $query = $this->db->get_where('iron_scores', array('date' => $dateTime, 'student_id' => $studentId));
    
            if ($query->num_rows() > 0) {
                // Fetch the ironScore data
                $ironScoreData = $query->row_array();
    
                return $ironScoreData;
            } else {
                // No data found, return null or an empty array as needed
                return array();
            }
        } catch (Exception $e) {
            // Handle any exceptions if necessary
            return array(); // Return an empty array in case of an exception
        }
    }
    
    public function fetchIronScore($studentId, $dateTime) {
        // Query the database to fetch the iron score data
        $query = $this->db->get_where('clubpractices', array('StudentId' => $studentId, 'DateTime' => $dateTime));
    
        if ($query->num_rows() > 0) {
            // Fetch and return the data as an array
            return $query->result_array();
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
        $existingResult = $this->db
            ->select('ClubPracticeId')
            ->from('ClubPractices')
            ->where('StudentId', $studentId)
            ->where('DateTime', $dateTime)
            ->get();

        log_message('debug', 'SQL Query: ' . $this->db->last_query());

        if ($existingResult->num_rows() > 0) {
            $data = array(
                'IsWithPro' => $isWithPro,
                'Ground' => $ground,
                'avgDistance' => $avgDistance, 
                'ClubHeadSpeed' => $clubHeadSpeed,
                'SpinRate' => $spinRate,
                'Apex' => $apex,
                'BallsAmount' => $ballsAmount,
                'Rating' => $rating
            );

            $this->db
                ->where('StudentId', $studentId)
                ->where('DateTime', $dateTime)
                ->update('ClubPractices', $data);
        } else {
            $data = array(
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
            );

            $this->db->insert('ClubPractices', $data);
        }

        if ($this->db->affected_rows() > 0) {
            $response = array(
                'status' => 1,
                'msg' => 'Score added successfully',
                'data' => null
            );
        } else {
            $response = array(
                'status' => 0,
                'msg' => 'No rows affected',
                'data' => null
            );
        }

        return $response;
    } catch (Exception $e) {
        log_message('error', 'Exception in addScore: ' . $e->getMessage());
        $response = array(
            'status' => 0,
            'msg' => $e->getMessage(),
            'data' => null
        );

        return $response;
    }
}
public function getUserById($userId) {
    $this->db->where('UserId', $userId);
    $query = $this->db->get('users');
    
    if ($query->num_rows() > 0) {
        return $query->row_array();
    } else {
        return null;
    }
}


public function updateProfileData($email, $hashedNewPassword, $age, $distanceUnit, $firstName, $gender, $hcp, $healthLimitation, $lastName, $phone, $speedUnit) {
    // Define an array with the data to update
    $data = array(
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
    );

    if ($hashedNewPassword === null) {
        unset($data['Password']); // Remove the password field if not changing it
    }

    // Update the user's profile in the database based on their email
    $this->db->where('Email', $email);
    $this->db->update('users', $data);

    if ($this->db->affected_rows() > 0) {
        return true; // Profile update successful
    } else {
        return false; // Profile update failed
    }
}


}