<?php

namespace App\Services;

use App\Models\HelpRequest;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class RequestAssignmentService
{
    public function assignInstructor(HelpRequest $helpRequest)
    {
        // Find available instructor with least workload
        $instructor = $this->findBestInstructor($helpRequest);

        if (!$instructor) {
            // No available instructors - add to queue
            $this->addToQueue($helpRequest);
            return false;
        }

        DB::transaction(function () use ($helpRequest, $instructor) {
            $helpRequest->update([
                'assigned_instructor_id' => $instructor->id,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);

            // Remove from queue if exists
            $helpRequest->queuePosition?->delete();

            // Create notification for instructor
            $this->notifyInstructor($instructor, $helpRequest);

            // Log activity
            $helpRequest->activityLogs()->create([
                'user_id' => null,
                'action' => 'instructor_assigned',
                'description' => "Assigned to instructor {$instructor->first_name} {$instructor->last_name}",
                'new_values' => ['assigned_instructor_id' => $instructor->id],
            ]);
        });

        return true;
    }

    protected function findBestInstructor(HelpRequest $helpRequest)
    {
        $student = $helpRequest->student;

        return User::where('school_id', $student->school_id)
            ->where('role', 'instructor')
            ->where('is_available', true)
            ->withCount(['helpRequestsAsInstructor' => function ($query) {
                $query->whereIn('status', ['assigned', 'in_progress']);
            }])
            ->orderBy('help_requests_as_instructor_count', 'asc')
            ->first();
    }

    protected function addToQueue(HelpRequest $helpRequest)
    {
        // Get current max position in queue
        $maxPosition = DB::table('queue_positions')->max('position') ?? 0;

        // Calculate estimated wait time (assume 15 minutes per request ahead)
        $estimatedWait = ($maxPosition + 1) * 15;

        $helpRequest->queuePosition()->create([
            'position' => $maxPosition + 1,
            'estimated_wait_minutes' => $estimatedWait,
        ]);
    }

    protected function notifyInstructor(User $instructor, HelpRequest $helpRequest)
    {
        Notification::create([
            'user_id' => $instructor->id,
            'help_request_id' => $helpRequest->id,
            'type' => 'in_app',
            'title' => 'New Help Request Assigned',
            'message' => "You have been assigned a new {$helpRequest->priority_level} priority request from {$helpRequest->student->first_name}.",
            'sent_at' => now(),
        ]);

        // TODO: Send actual SMS via Twilio in the future
    }
}
