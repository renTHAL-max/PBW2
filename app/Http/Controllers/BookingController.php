<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Customer;
use App\Models\RentalItem;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'id_card_number' => 'required|string|max:20',
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'vehicle_name' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        try {
            DB::beginTransaction();

            $customer = Customer::updateOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'id_card_number' => $request->id_card_number,
                    'address' => $request->address,
                    'birth_date' => $request->birth_date,
                ]
            );

            $vehicle = Vehicle::where('model', $request->vehicle_name)->first();
            
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            $duration = $start->diffInDays($end);
            if ($duration <= 0) $duration = 1; 
            
            $prices = [
                'Toyota Agya' => 250000,
                'Toyota Avanza' => 350000,
                'Toyota Fortuner' => 600000,
                'Daihatsu Xenia' => 300000,
                'Honda Mobilio' => 320000,
            ];
            $pricePerDay = $vehicle ? $vehicle->price : ($prices[$request->vehicle_name] ?? 0);

            $subtotal = $pricePerDay * $duration;

            $rental = Rental::create([
                'customer_id' => $customer->id,
                'user_id' => 1, 
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'duration_days' => $duration,
                'subtotal' => $subtotal,
                'total_amount' => $subtotal,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => 'Metode: ' . $request->payment_method . ' | No.KTP: ' . $request->id_card_number,
            ]);

            if ($vehicle) {
                RentalItem::create([
                    'rental_id' => $rental->id,
                    'vehicle_id' => $vehicle->id,
                    'price_per_day' => $pricePerDay,
                    'subtotal' => $subtotal,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Success'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getCode() == 23000 ? "ID Card Number already exists in our system." : $e->getMessage();
            return response()->json(['message' => $msg], 500);
        }
    }
}