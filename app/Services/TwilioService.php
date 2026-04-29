<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected ?Client $client;
    protected string $from;

    public function __construct()
    {
        $sid    = config('services.twilio.sid');
        $token  = config('services.twilio.token');
        $this->from = config('services.twilio.from');

        if ($sid && $token) {
            $this->client = new Client($sid, $token);
        } else {
            $this->client = null;
            Log::warning('TwilioService: credentials not configured.');
        }
    }

    /**
     * Send an SMS. Silently logs errors so the app never crashes on SMS failure.
     */
    public function send(string $to, string $message): bool
    {
        if (!$this->client) {
            Log::info("SMS (no Twilio configured) → {$to}: {$message}");
            return false;
        }

        // Normalise phone — ensure E.164 format
        $to = $this->normalisePhone($to);
        if (!$to) {
            Log::warning("TwilioService: invalid phone number, SMS skipped.");
            return false;
        }

        try {
            $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $message,
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error("TwilioService SMS failed to {$to}: " . $e->getMessage());
            return false;
        }
    }

    // -------------------------------------------------------------------------
    // Canned notification messages
    // -------------------------------------------------------------------------

    public function notifyInstructorNewRequest(string $phone, string $studentName, string $requestTitle, string $location): bool
    {
        $message = "NextStudent Help Request\n"
            . "Student: {$studentName}\n"
            . "Title: {$requestTitle}\n"
            . "Location: {$location}\n"
            . "Log in to respond: " . config('app.url');

        return $this->send($phone, $message);
    }

    public function notifyStudentRequestAccepted(string $phone, string $instructorName): bool
    {
        $message = "NextStudent Help System\n"
            . "Your help request has been picked up by {$instructorName}.\n"
            . "They are on their way!";

        return $this->send($phone, $message);
    }

    public function notifyStudentRequestCompleted(string $phone, string $instructorName): bool
    {
        $message = "NextStudent Help System\n"
            . "Your help request was marked complete by {$instructorName}.\n"
            . "We hope it was helpful!";

        return $this->send($phone, $message);
    }

    public function notifyStudentRequestCancelledByInstructor(string $phone, string $instructorName): bool
    {
        $message = "NextStudent Help System\n"
            . "Your help request was cancelled by {$instructorName}.\n"
            . "You can submit a new request at any time.";

        return $this->send($phone, $message);
    }

    public function notifyInstructorRequestCancelledByStudent(string $phone, string $studentName): bool
    {
        $message = "NextStudent Help System\n"
            . "{$studentName} cancelled their help request.";

        return $this->send($phone, $message);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function normalisePhone(string $phone): ?string
    {
        // Strip everything except digits and leading +
        $cleaned = preg_replace('/[^\d+]/', '', $phone);

        // Already E.164
        if (str_starts_with($cleaned, '+')) {
            return $cleaned;
        }

        // Assume US number — prepend +1
        if (strlen($cleaned) === 10) {
            return "+1{$cleaned}";
        }

        if (strlen($cleaned) === 11 && str_starts_with($cleaned, '1')) {
            return "+{$cleaned}";
        }

        return null;
    }
}
