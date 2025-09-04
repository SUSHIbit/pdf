<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MalaysianPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Allow empty values since phone is optional
        }

        // Remove all spaces, dashes, and brackets
        $phone = preg_replace('/[\s\-\(\)]/', '', $value);
        
        // Remove + sign if present
        $phone = ltrim($phone, '+');
        
        // Malaysian phone number patterns
        $patterns = [
            // Mobile numbers (starting with +60 1)
            '/^60(1[0-9]{8,9})$/',
            // Mobile numbers (starting with 01)
            '/^0(1[0-9]{8,9})$/',
            // Landline numbers (starting with +60 3,4,5,6,7,8,9)
            '/^60([3-9][0-9]{7,8})$/',
            // Landline numbers (starting with 0)
            '/^0([3-9][0-9]{7,8})$/',
            // Just the number part without country code for mobile
            '/^(1[0-9]{8,9})$/',
            // Just the number part without country code for landline
            '/^([3-9][0-9]{7,8})$/',
        ];

        $isValid = false;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $phone)) {
                $isValid = true;
                break;
            }
        }

        if (!$isValid) {
            $fail('The :attribute must be a valid Malaysian phone number (e.g., +60123456789, 0123456789, or 123456789).');
        }
    }
}