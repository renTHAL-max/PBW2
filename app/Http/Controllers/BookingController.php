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

            $vehicle = Vehicle::where('model', $request->vehicle_name)->first();

            if (!$vehicle) {
                return response()->json(['message' => 'Vehicle not found.'], 404);
            }

            $isBooked = RentalItem::where('vehicle_id', $vehicle->id)
                ->whereHas('rental', function ($query) use ($request) {
                    $query->whereIn('status', ['pending', 'active'])
                        ->where(function ($q) use ($request) {
                            $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                                ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                                ->orWhere(function ($inner) use ($request) {
                                    $inner->where('start_date', '<=', $request->start_date)
                                        ->where('end_date', '>=', $request->end_date);
                                });
                        });
                })->exists();

            if ($isBooked) {
                return response()->json(['message' => 'Vehicle already booked for these dates.'], 422);
            }

            $customer = Customer::updateOrCreate(
                ['id_card_number' => $request->id_card_number],
                [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address' => $request->address,
                    'birth_date' => $request->birth_date,
                ]
            );

            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            $duration = $start->diffInDays($end);
            if ($duration <= 0) $duration = 1; 
            
            $pricePerDay = $vehicle->price_per_day ?? 0;
            
            if ($pricePerDay == 0) {
                $prices = [
                    'Toyota Agya' => 250000,
                    'Toyota Avanza' => 350000,
                    'Toyota Fortuner' => 600000,
                    'Daihatsu Xenia' => 300000,
                    'Honda Mobilio' => 320000,
                ];
                $pricePerDay = $prices[$request->vehicle_name] ?? 0;
            }

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
                'notes' => 'Method: ' . ($request->payment_method ?? 'N/A') . ' | ID: ' . $request->id_card_number,
            ]);

            RentalItem::create([
                'rental_id' => $rental->id,
                'vehicle_id' => $vehicle->id,
                'price_per_day' => $pricePerDay,
                'days' => $duration,
                'subtotal' => $subtotal,
            ]);

            DB::commit();
            return response()->json(['message' => 'Success'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'System error: ' . $e->getMessage()], 500);
        }
    }
}