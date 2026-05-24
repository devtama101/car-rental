<?php

namespace App\Http\Controllers;

use App\Models\Rental;

class BookingReceiptController extends Controller
{
    public function __invoke(string $reference)
    {
        $rental = Rental::query()
            ->where('booking_reference', $reference)
            ->with([
                'vehicle',
                'user.person',
                'driver.user',
                'payments.bank',
            ])
            ->firstOrFail();

        $payment = $rental->payments->first();
        $customerPerson = $rental->user->person;

        return view('invoice.receipt', compact('rental', 'payment', 'customerPerson'));
    }
}
