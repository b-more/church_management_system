<?php

namespace App\Http\Controllers;

use App\Models\UssdSession;
use App\Models\Member;
use App\Models\OfferingType;
use App\Models\UssdPrayerRequest;
use App\Models\UssdGiving;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use UssdTransactionHandler;
use Illuminate\Support\Facades\DB;
use App\Services\SmsService;

class UssdSessionController extends Controller
{
    private function sendSMSWithRetry($phone, $message, $maxRetries = 3)
    {
        $attempt = 1;
        $lastError = null;

        while ($attempt <= $maxRetries) {
            try {
                $url_encoded_message = urlencode($message);

                Log::info('SMS: Attempting to send SMS', [
                    'attempt' => $attempt,
                    'phone' => $phone,
                    'api_url' => 'https://www.cloudservicezm.com/smsservice/httpapi',
                    'sender_id' => 'HKChurch'
                ]);

                $response = Http::timeout(30)
                    ->withoutVerifying()
                    ->post('https://www.cloudservicezm.com/smsservice/httpapi', [
                        'username' => config('services.sms.username'),
                        'password' => config('services.sms.password'),
                        'msg' => $url_encoded_message . '.',
                        'shortcode' => '2343',
                        'sender_id' => 'HKC',
                        'phone' => $phone,
                        'api_key' => config('services.sms.api_key')
                    ]);

                Log::info('SMS: API Response', [
                    'status_code' => $response->status(),
                    'body' => $response->body(),
                    'attempt' => $attempt
                ]);

                if ($response->successful()) {
                    return $response;
                }

                $lastError = new \Exception('SMS API returned error: ' . $response->body());
                Log::error('SMS: API error response', [
                    'error' => $response->body(),
                    'attempt' => $attempt
                ]);
            } catch (\Exception $e) {
                $lastError = $e;
                Log::error('SMS: Exception during sending', [
                    'error' => $e->getMessage(),
                    'attempt' => $attempt,
                    'trace' => $e->getTraceAsString()
                ]);
                sleep(2);
            }

            $attempt++;
        }

        Log::error('SMS: All retries failed', [
            'final_error' => $lastError->getMessage(),
            'phone' => $phone
        ]);

        throw $lastError;
    }

    private function sendSMS($phone, $message)
    {
        try {
            return $this->sendSMSWithRetry($phone, $message, 3);
        } catch (\Exception $e) {
            Log::error('SMS sending failed after retries: ' . $e->getMessage());
            // Don't throw the error - let the process continue
            return null;
        }
    }

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
                        // Fetch active offering types from database
                        $offeringTypes = OfferingType::where('is_active', true)->get();

                        if ($offeringTypes->isEmpty()) {
                            $message_string = "No offering types are currently available. Please try again later.";
                            $request_type = "3";
                            break;
                        }

                        $message_string = "Select giving type:\n";
                        foreach ($offeringTypes as $index => $type) {
                            $message_string .= ($index + 1) . ". " . $type->name . "\n";
                        }
                        $message_string .= "\n0. Back to main menu";

                        // Save offering types to session data for reference
                        $getLastSessionInfo->update([
                            'data' => [
                                'offering_types' => $offeringTypes->pluck('name', 'id')->toArray()
                            ]
                        ]);

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
                            //$this->sendSMS($phone, "Your HKC membership details have been accessed via USSD. If this wasn't you, please contact the church office.");
                            SmsService::send("Your HKC membership details have been accessed via USSD. If this wasn't you, please contact the church office.", $phone);
                        } else {
                            $message_string = "Phone number not registered. Please register as a member.";
                            SmsService::send("Phone number not registered. Please register as a member.", $phone);
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

                    default:
                        $message_string = "Invalid selection. Please try again:\n"
                            . "1. Givings & Offerings\n"
                            . "2. Membership Registration\n"
                            . "3. Check Membership Status\n"
                            . "4. Prayer Request\n"
                            . "5. Admin Login";
                        break;
                }
                break;

            case 2: // Givings & Offerings Process
                    switch ($step_no) {
                        case 1: // Select giving type
                            Log::info('USSD: Selecting giving type', [
                                'phone' => $phone,
                                'input' => $last_part,
                                'session_id' => $session_id
                            ]);

                            $offering_types = $getLastSessionInfo->data['offering_types'] ?? [];

                            if ($last_part == '0') {
                                return $this->returnToMainMenu($session_id);
                            }

                            $selected_index = $last_part - 1;
                            $offering_type_ids = array_keys($offering_types);

                            if (isset($offering_type_ids[$selected_index])) {
                                $selected_type_id = $offering_type_ids[$selected_index];
                                $selected_type_name = $offering_types[$selected_type_id];

                                // Store data in session
                                $sessionData = [
                                    'offering_types' => $offering_types,
                                    'selected_type_id' => $selected_type_id,
                                    'selected_type' => $selected_type_name,
                                    'selected_type_name' => $selected_type_name
                                ];

                                Log::info('USSD: Offering type selected', [
                                    'type' => $selected_type_name,
                                    'type_id' => $selected_type_id,
                                    'session_id' => $session_id
                                ]);

                                UssdSession::where('session_id', $session_id)->update([
                                    'data' => $sessionData,
                                    'step_no' => 2
                                ]);

                                $message_string = "Enter amount (ZMW):";
                            } else {
                                Log::warning('USSD: Invalid offering type selection', [
                                    'input' => $last_part,
                                    'session_id' => $session_id
                                ]);

                                $message_string = "Invalid selection. Please select a valid offering type:\n";
                                foreach ($offering_types as $index => $type) {
                                    $message_string .= ($index + 1) . ". " . $type . "\n";
                                }
                                $message_string .= "\n0. Back to main menu";
                            }
                            break;

                        case 2: // Enter amount
                            Log::info('USSD: Entering amount', [
                                'phone' => $phone,
                                'input' => $last_part,
                                'session_id' => $session_id
                            ]);

                            $sessionData = $getLastSessionInfo->data;

                            if (!isset($sessionData['selected_type'])) {
                                Log::error('USSD: Session data lost', [
                                    'session_id' => $session_id,
                                    'data' => $sessionData
                                ]);
                                $message_string = "Session expired. Please start again.";
                                return $this->returnToMainMenu($session_id);
                            }

                            if (is_numeric($last_part) && $last_part > 0) {
                                if ($last_part > 100000) {
                                    $message_string = "Amount exceeds maximum limit (100,000 ZMW). Please enter a smaller amount:";
                                    break;
                                }

                                // Store amount in session
                                $sessionData['amount'] = $last_part;

                                Log::info('USSD: Amount stored', [
                                    'amount' => $last_part,
                                    'session_id' => $session_id
                                ]);

                                UssdSession::where('session_id', $session_id)->update([
                                    'data' => $sessionData,
                                    'step_no' => 3
                                ]);

                                $message_string = "Enter your full name:";
                            } else {
                                Log::warning('USSD: Invalid amount entered', [
                                    'input' => $last_part,
                                    'session_id' => $session_id
                                ]);
                                $message_string = "Invalid amount. Please enter a valid number:";
                            }
                            break;

            case 3: // Enter full name and process
                            Log::info('USSD: Processing transaction', [
                                'phone' => $phone,
                                'name_input' => $last_part,
                                'session_id' => $session_id
                            ]);

                            $sessionData = $getLastSessionInfo->data;

                            if (!isset($sessionData['amount']) || !isset($sessionData['selected_type'])) {
                                Log::error('USSD: Required session data missing', [
                                    'session_id' => $session_id,
                                    'data' => $sessionData
                                ]);
                                $message_string = "Session expired. Please start again.";
                                return $this->returnToMainMenu($session_id);
                            }

                            if (strlen($last_part) < 3) {
                                $message_string = "Name is too short. Please enter your full name:";
                                break;
                            }

                            try {
                                DB::beginTransaction();

                                $amount = $sessionData['amount'];
                                $giving_type = $sessionData['selected_type'];
                                $full_name = $last_part;
                                $reference = 'HKC-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

                                Log::info('USSD: Creating transaction records', [
                                    'amount' => $amount,
                                    'type' => $giving_type,
                                    'reference' => $reference
                                ]);

                                // Create/find transaction category
                                $category = TransactionCategory::firstOrCreate(
                                    ['name' => $giving_type],
                                    [
                                        'type' => 'income',
                                        'description' => 'Auto-created from USSD',
                                        'is_active' => true
                                    ]
                                );

                                // Create transaction record
                                $transaction = Transaction::create([
                                    'branch_id' => 1, // Default branch
                                    'member_id' => Member::where('phone', $phone)->value('id'),
                                    'transaction_type' => strtolower($giving_type),
                                    'amount' => $amount,
                                    'payment_method' => 'mobile_money',
                                    'payment_reference' => $reference,
                                    'transaction_date' => now(),
                                    'description' => "{$giving_type} via USSD",
                                    'category_id' => $category->id,
                                    'recorded_by' => 1, // System user ID
                                    'status' => 'completed', // Auto-complete for testing
                                    'notes' => "USSD Payment - Phone: {$phone}"
                                ]);

                                Log::info('USSD: Transaction created', [
                                    'transaction_id' => $transaction->id,
                                    'reference' => $reference
                                ]);

                                // Create USSD giving record
                                $giving = UssdGiving::create([
                                    'phone_number' => $phone,
                                    'member_id' => Member::where('phone', $phone)->value('id'),
                                    'amount' => $amount,
                                    'giving_type' => $giving_type,
                                    'full_name' => $full_name,
                                    'status' => 'completed',
                                    'payment_reference' => $reference,
                                    'offering_type_id' => $sessionData['selected_type_id'] ?? null,
                                    'ussd_session_id' => $session_id
                                ]);

                                Log::info('USSD: Giving record created', [
                                    'giving_id' => $giving->id,
                                    'reference' => $reference
                                ]);

                                DB::commit();

                                $formatted_amount = number_format($amount, 2);

                                // Prepare SMS message
                                $message = "DIGITAL RECEIPT\n"
                                    . "His Kingdom Church\n"
                                    . "-------------\n"
                                    . "Name: {$full_name}\n"
                                    . "Type: {$giving_type}\n"
                                    . "Amount: ZMW {$formatted_amount}\n"
                                    . "Ref: {$reference}\n"
                                    . "Date: " . now()->format('d/m/Y H:i') . "\n"
                                    . "Thank you for your giving!";

                                // Replace the existing SMS code with this simpler version:
                                //$message = "Payment was made successfully! Amount: ZMW {$formatted_amount} Reference: {$reference}";

                                // Use SmsService directly
                                $smsResult = SmsService::send($message, $phone);

                                if ($smsResult) {
                                Log::info('USSD: SMS sent successfully', [
                                    'phone' => $phone,
                                    'reference' => $reference
                                ]);
                                } else {
                                Log::warning('USSD: Failed to send SMS', [
                                    'phone' => $phone,
                                    'reference' => $reference
                                ]);
                                }

                                $message_string = "Thank you {$full_name}!\nYour {$giving_type} of ZMW {$formatted_amount} has been recorded.\nReference: {$reference}";
                                $request_type = "3";

                            } catch (\Exception $e) {
                                DB::rollBack();
                                Log::error('USSD: Transaction processing failed', [
                                    'error' => $e->getMessage(),
                                    'phone' => $phone,
                                    'session_id' => $session_id,
                                    'trace' => $e->getTraceAsString()
                                ]);
                                $message_string = "Sorry, an error occurred. Please try again later or contact support.";
                                $request_type = "3";
                            }
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
                        //$this->sendSMS($phone, $message);
                        SmsService::send($message, $phone);

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
    // private function sendSMS($phone, $message)
    // {
    //     $url_encoded_message = urlencode($message);
    //     return Http::withoutVerifying()
    //         ->post('https://www.cloudservicezm.com/smsservice/httpapi', [
    //             'username' => config('services.sms.username'),
    //             'password' => config('services.sms.password'),
    //             'msg' => $url_encoded_message . '.',
    //             'shortcode' => '2343',
    //             'sender_id' => 'HKChurch',
    //             'phone' => $phone,
    //             'api_key' => config('services.sms.api_key')
    //         ]);
    // }

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
