<?php

namespace App\Jobs\CommissionNotes;

use BulkSms;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class AllocationNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $cleanedNumber = $this->cleanPhoneNumber($this->employee->phone);
        if (!$cleanedNumber) {
            // Handle invalid phone number
            return;
        }

        $number = '+' . $cleanedNumber;
        $message = 'Good Day' .' ' . $this->employee->first_name .', a commission note has been allocated to you for the amount of R' . number_format($this->amount, 2) . '. Note: ' . $this->note;

        BulkSMS::send($number, $message); 
    }

    private function cleanPhoneNumber($number): ?string
    {
        // Remove all non-numeric characters
        $cleaned = preg_replace('/\D+/', '', (string) $number);

        if (!$cleaned) {
            return null;
        }

        // Already in SA international format: 27 + 9 digits
        if (preg_match('/^27\d{9}$/', $cleaned)) {
            return $cleaned;
        }

        // Local SA format: 0 + 9 digits -> convert to 27 + 9 digits
        if (preg_match('/^0\d{9}$/', $cleaned)) {
            return '27' . substr($cleaned, 1);
        }

        // Missing leading 0 / 27: just 9 digits
        if (preg_match('/^\d{9}$/', $cleaned)) {
            return '27' . $cleaned;
        }

        return null;
    }
}
