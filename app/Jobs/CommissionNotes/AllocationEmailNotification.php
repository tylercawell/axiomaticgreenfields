<?php

namespace App\Jobs\CommissionNotes;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Mail\CommissionNotes\AllocationNotificationEmail;

class AllocationEmailNotification implements ShouldQueue
{
    use Queueable;

    protected $employee;
    protected $amount;
    protected $note;
    /**
     * Create a new job instance.
     */
    public function __construct($employee, $amount, $note)
    {
        $this->employee = $employee;
        $this->amount = $amount;
        $this->note = $note;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = new AllocationNotificationEmail($this->employee, $this->amount, $this->note);
    }
}
