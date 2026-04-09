<?php

namespace App\Services;

use App\Models\ClassSection;
use App\Models\HelpRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RequestAssignmentService
{
    public function __construct(protected TwilioService $twilio) {}

    /**
     * Given a newly created HelpRequest, find the instructor via the
     * class section and assign the request.
     *
     * Returns true on success, false if no instructor could be found.
     */
    public function assignInstructor(HelpRequest $helpRequest): bool
    {
        $session = ClassSection::with('instructor')->find($helpRequest->class_section_id);

        if (!$session || !$session->instructor) {
            return false;
        }

        $instructor = $session->instructor;

        DB::transaction(function () use ($helpRequest, $instructor) {
            $helpRequest->update([
                'assigned_instructor_id' => $instructor->id,
            ]);

            // In-app notification
            Notification::create([
                'user_id'         => $instructor->id,
                'help_request_id' => $helpRequest->id,
                'type'            => 'in_app',
                'title'           => 'New Help Request',
                'message'         => "{$helpRequest->student->fullName()} needs help: {$helpRequest->title}",
                'sent_at'         => now(),
            ]);

            // Activity log
            $helpRequest->activityLogs()->create([
                'user_id'     => null,
                'action'      => 'instructor_assigned',
                'description' => "Auto-assigned to {$instructor->fullName()} via section {$helpRequest->classSection->course_code}",
            ]);
        });

        // SMS (outside transaction — failure should not roll back the assignment)
        if ($instructor->phone_number) {
            $this->twilio->notifyInstructorNewRequest(
                $instructor->phone_number,
                $helpRequest->student->fullName(),
                $helpRequest->title,
                $helpRequest->location ?? 'Not specified',
            );
        }

        // SMS to student confirming their request is live
        if ($helpRequest->student->phone_number) {
            $this->twilio->notifyStudentRequestAccepted(
                $helpRequest->student->phone_number,
                $instructor->fullName(),
            );
        }

        return true;
    }
}
