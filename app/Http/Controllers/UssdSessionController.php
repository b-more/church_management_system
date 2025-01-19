<?php

namespace App\Http\Controllers;

use App\Models\UssdSession;
use App\Models\Member;
use App\Models\UssdPrayerRequest;
use App\Models\UssdGiving;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class UssdSessionController extends Controller
{
    public function ussd(Request $request)
    {
        // Declaration of variables
        $message_string = "";
        $case_no = 0;
        $step_no = 1;
        $phone = $request->input('MSISDN');
        $user_input = $request->input('MESSAGE');
        $session_id = $request->input('SESSION_ID');
        $lastPart = explode("*", $user_input);
        $parts = count($lastPart);
        $last_part = $lastPart[$parts - 1];
        $request_type = "2"; // continue

        // Getting last session info
        $getLastSessionInfo = UssdSession::where('phone_number', $phone)
            ->where('session_id', $session_id)
            ->orderBy('id', 'DESC')
            ->first();

        // Checking if there is an active session
        if (!empty($getLastSessionInfo)) {
            $case_no = $getLastSessionInfo->case_no;
            $step_no = $getLastSessionInfo->step_no;
        } else {
            // Save new session record
            $new_session = UssdSession::create([
                "phone_number" => $phone,
                "case_no" => 0,
                "step_no" => 1,
                "session_id" => $session_id,
                "data" => []
            ]);
        }

        // Initial Menu
        if ($case_no == 0 && $step_no == 1) {
            $message_string = "Welcome to His Kingdom Church.\nSelect:\n1. Givings & Offerings\n2. Membership Registration\n3. Check Membership Status\n4. Prayer Request\n5. Admin Login";
            $request_type = "2";
            
            // Update session
            UssdSession::where('session_id', $session_id)->update([
                "case_no" => 1,
                "step_no" => 1
            ]);
            
            return $this->ussdResponse($message_string, $request_type);
        }

        // Main Menu Logic
        switch ($case_no) {
            case 1: // Main Menu Selection
                switch ($last_part) {
                    case '1': // Givings & Offerings
                        $message_string = "Select giving type:\n1. Tithe\n2. Offering\n3. Special Offering\n\n0. Back to main menu";
                        UssdSession::where('session_id', $session_id)->update([
                            "case_no" => 2,
                            "step_no" => 1
                        ]);
                        break;

                    case '2': // Membership Registration
                        $message_string = "Enter your first name:";
                        UssdSession::where('session_id', $session_id)->update([
                            "case_no" => 3,
                            "step_no" => 1
                        ]);
                        break;

                    case '3': // Check Membership Status
                        $member = Member::where('phone', $phone)->first();
                        if ($member) {
                            $message_string = "Membership Status:\nName: {$member->first_name} {$member->last_name}\nStatus: {$member->membership_status}\nMember Since: {$member->membership_date}";
                            $this->sendSMS($phone, "Your HKC membership details have been accessed via USSD. If this wasn't you, please contact the church office.");
                        } else {
                            $message_string = "Phone number not registered. Please register as a member.";
                        }
                        $request_type = "3";
                        break;

                    case '4': // Prayer Request
                        $message_string = "Enter your prayer request:";
                        UssdSession::where('session_id', $session_id)->update([
                            "case_no" => 4,
                            "step_no" => 1
                        ]);
                        break;

                    case '5': // Admin Login
                        $message_string = "Enter your admin PIN:";
                        UssdSession::where('session_id', $session_id)->update([
                            "case_no" => 5,
                            "step_no" => 1
                        ]);
                        break;

                    case '0': // Return to main menu
                        $message_string = "Welcome to His Kingdom Church.\nSelect:\n1. Givings & Offerings\n2. Membership Registration\n3. Check Membership Status\n4. Prayer Request\n5. Admin Login";
                        UssdSession::where('session_id', $session_id)->update([
                            "case_no" => 1,
                            "step_no" => 1
                        ]);
                        break;
                }
                break;

            case 2: // Givings & Offerings Process
                switch ($step_no) {
                    case 1: // Select giving type
                        if (in_array($last_part, ['1', '2', '3'])) {
                            $giving_types = ['tithe', 'offering', 'special_offering'];
                            $giving_type = $giving_types[$last_part - 1];
                            
                            $getLastSessionInfo->update(['data' => ['giving_type' => $giving_type]]);
                            $message_string = "Enter amount (ZMW):";
                            UssdSession::where('session_id', $session_id)->update([
                                "step_no" => 2
                            ]);
                        } elseif ($last_part == '0') {
                            // Return to main menu
                            return $this->returnToMainMenu($session_id);
                        }
                        break;

                    case 2: // Enter amount
                        if (is_numeric($last_part) && $last_part > 0) {
                            $amount = $last_part;
                            $giving_type = $getLastSessionInfo->data['giving_type'];
                            
                            // Create giving record
                            $giving = UssdGiving::create([
                                'phone_number' => $phone,
                                'member_id' => Member::where('phone', $phone)->value('id'),
                                'amount' => $amount,
                                'giving_type' => $giving_type,
                                'status' => 'pending'
                            ]);

                            // Send confirmation SMS
                            $message = "Thank you for your {$giving_type} of ZMW{$amount}. Your reference number is: HKC-{$giving->id}. To complete your payment, please send to mobile money number: 0975020473";
                            $this->sendSMS($phone, $message);

                            $message_string = "Your {$giving_type} of ZMW{$amount} has been recorded. You will receive payment instructions via SMS.";
                            $request_type = "3";
                        } else {
                            $message_string = "Invalid amount. Please enter a valid number:";
                        }
                        break;
                }
                break;

            case 3: // Membership Registration
                switch ($step_no) {
                    case 1: // First Name
                        $getLastSessionInfo->update(['data' => ['first_name' => $last_part]]);
                        $message_string = "Enter your last name:";
                        UssdSession::where('session_id', $session_id)->update([
                            "step_no" => 2
                        ]);
                        break;

                    case 2: // Last Name
                        $getLastSessionInfo->update(['data' => array_merge($getLastSessionInfo->data, ['last_name' => $last_part])]);
                        $message_string = "Enter your email (or press # if none):";
                        UssdSession::where('session_id', $session_id)->update([
                            "step_no" => 3
                        ]);
                        break;

                    case 3: // Email
                        $email = $last_part == '#' ? null : $last_part;
                        $data = $getLastSessionInfo->data;
                        
                        // Create member record
                        $member = Member::create([
                            'first_name' => $data['first_name'],
                            'last_name' => $data['last_name'],
                            'email' => $email,
                            'phone' => $phone,
                            'membership_status' => 'First Timer',
                            'membership_date' => now(),
                            'title' => 'Mr/Mrs', // Default title
                            'address' => 'To be updated', // Required field
                            'marital_status' => 'Single', // Default status
                            'date_of_birth' => now(), // Required field, to be updated
                            'gender' => 'Other', // Required field, to be updated
                        ]);

                        // Send welcome SMS
                        $message = "Welcome to His Kingdom Church! Your membership registration has been received. Please visit the church office to complete your registration. God bless you!";
                        $this->sendSMS($phone, $message);

                        $message_string = "Registration successful! Please visit the church office to complete your profile.";
                        $request_type = "3";
                        break;
                }
                break;

            case 4: // Prayer Request
                switch ($step_no) {
                    case 1:
                        // Create prayer request
                        UssdPrayerRequest::create([
                            'phone_number' => $phone,
                            'member_id' => Member::where('phone', $phone)->value('id'),
                            'prayer_request' => $last_part,
                            'status' => 'pending'
                        ]);

                        // Send confirmation SMS
                        $message = "Your prayer request has been received. Our prayer team will pray for you. 'The prayer of a righteous person is powerful and effective.' - James 5:16";
                        $this->sendSMS($phone, $message);

                        $message_string = "Your prayer request has been submitted. We will pray for you.";
                        $request_type = "3";
                        break;
                }
                break;

            case 5: // Admin Login
                switch ($step_no) {
                    case 1: // PIN Entry
                        $user = User::where('phone', $phone)->first();
                        if ($user && Hash::check($last_part, $user->password)) {
                            $message_string = "Admin Menu:\n1. View Today's Offerings\n2. View Recent Members\n3. View Prayer Requests\n\n0. Back to main menu";
                            UssdSession::where('session_id', $session_id)->update([
                                "step_no" => 2
                            ]);
                        } else {
                            $message_string = "Invalid PIN. Access denied.";
                            $request_type = "3";
                        }
                        break;

                    case 2: // Admin Menu Selection
                        switch ($last_part) {
                            case '1': // Today's Offerings
                                $today_total = UssdGiving::whereDate('created_at', today())
                                    ->where('status', 'completed')
                                    ->sum('amount');
                                $message_string = "Today's Offerings: ZMW" . number_format($today_total, 2);
                                $request_type = "3";
                                break;

                            case '2': // Recent Members
                                $recent_count = Member::whereDate('created_at', '>=', now()->subDays(7))->count();
                                $message_string = "New members in last 7 days: " . $recent_count;
                                $request_type = "3";
                                break;

                            case '3': // Prayer Requests
                                $pending_count = UssdPrayerRequest::where('status', 'pending')->count();
                                $message_string = "Pending prayer requests: " . $pending_count;
                                $request_type = "3";
                                break;

                            case '0': // Return to main menu
                                return $this->returnToMainMenu($session_id);
                        }
                        break;
                }
                break;
        }

        return $this->ussdResponse($message_string, $request_type);
    }

    /**
     * Helper method to send SMS
     */
    private function sendSMS($phone, $message)
    {
        $url_encoded_message = urlencode($message);
        return Http::withoutVerifying()
            ->post('https://www.cloudservicezm.com/smsservice/httpapi', [
                'username' => config('services.sms.username'),
                'password' => config('services.sms.password'),
                'msg' => $url_encoded_message . '.',
                'shortcode' => '2343',
                'sender_id' => 'HKChurch',
                'phone' => $phone,
                'api_key' => config('services.sms.api_key')
            ]);
    }

    /**
     * Helper method to return to main menu
     */
    private function returnToMainMenu($session_id)
    {
        UssdSession::where('session_id', $session_id)->update([
            "case_no" => 1,
            "step_no" => 1
        ]);
        
        return $this->ussdResponse(
            "Welcome to His Kingdom Church.\nSelect:\n1. Givings & Offerings\n2. Membership Registration\n3. Check Membership Status\n4. Prayer Request\n5. Admin Login",
            "2"
        );
    }

    /**
     * Helper method to format USSD response
     */
    private function ussdResponse($message, $type)
    {
        return response()->json([
            "ussd_response" => [
                "USSD_BODY" => $message,
                "REQUEST_TYPE" => $type
            ]
        ]);
    }
}
