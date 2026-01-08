<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reservations/getAll",
     *     summary="Get all reservations (Admin)",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of all reservations")
     * )
     */
    public function getAll()
    {
        $reservations = Reservation::orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $reservations->map(fn($r) => $this->mapReservation($r))
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/reservations/update/{id}",
     * @OA\Post(
     *     path="/api/reservations/add",
     *     summary="Create a new reservation",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"guest_count","reservation_date","reservation_time"},
     *             @OA\Property(property="guest_count", type="integer", example=4),
     *             @OA\Property(property="reservation_date", type="string", format="date", example="2024-01-20"),
     *             @OA\Property(property="reservation_time", type="string", format="time", example="19:00:00"),
     *             @OA\Property(property="special_requests", type="string", example="Window seat please")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Reservation created")
     * )
     */
    public function addReservation(Request $request)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'guest_count' => 'required_without:guestCount|integer|min:1',
            'guestCount' => 'required_without:guest_count|integer|min:1',
            'reservation_date' => 'required_without:reservationDate|date',
            'reservationDate' => 'required_without:reservation_date|date',
            'reservation_time' => 'required_without:reservationTime',
            'reservationTime' => 'required_without:reservation_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ], 400);
        }

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'phone_number' => $request->phone_number ?? $request->phoneNumber ?? $user->phone_number,
            'guest_name' => $request->guest_name ?? $request->guestName ?? $user->name,
            'guest_count' => $request->guest_count ?? $request->guestCount,
            'reservation_date' => $request->reservation_date ?? $request->reservationDate,
            'reservation_time' => $request->reservation_time ?? $request->reservationTime,
            'special_requests' => $request->special_requests ?? $request->specialRequests,
            'status' => 'PENDING',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reservation created',
            'data' => $this->mapReservation($reservation)
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/reservations/getByPhone/{phoneNumber}",
     *     summary="Get reservations by phone number",
     *     tags={"Reservations"},
     *     @OA\Parameter(name="phoneNumber", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="List of reservations")
     * )
     */
    public function getByPhone($phoneNumber)
    {
        $reservations = Reservation::where('phone_number', $phoneNumber)
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $reservations->map(fn($r) => $this->mapReservation($r))
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/reservations/getById/{id}",
     *     summary="Get reservation by ID",
     *     tags={"Reservations"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Reservation details")
     * )
     */
    public function getById($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $this->mapReservation($reservation)
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/reservations/confirm/{id}",
     *     summary="Confirm a reservation (Admin)",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Reservation confirmed")
     * )
     */
    public function confirmReservation($id)
    {
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return response()->json(['success' => false, 'message' => 'Reservation not found'], 404);
        }

        $reservation->status = 'CONFIRMED';
        $reservation->save();

        return response()->json([
            'success' => true,
            'message' => 'Reservation confirmed',
            'data' => $this->mapReservation($reservation)
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/reservations/update/{id}",
     *     summary="Update a reservation",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Reservation updated")
     * )
     */
    public function updateReservation(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return response()->json(['success' => false, 'message' => 'Reservation not found'], 404);
        }

        $reservation->update([
            'phone_number' => $request->phone_number ?? $request->phoneNumber ?? $reservation->phone_number,
            'guest_name' => $request->guest_name ?? $request->guestName ?? $reservation->guest_name,
            'guest_count' => $request->guest_count ?? $request->guestCount ?? $reservation->guest_count,
            'reservation_date' => $request->reservation_date ?? $request->reservationDate ?? $reservation->reservation_date,
            'reservation_time' => $request->reservation_time ?? $request->reservationTime ?? $reservation->reservation_time,
            'special_requests' => $request->special_requests ?? $request->specialRequests ?? $reservation->special_requests,
            'status' => $request->status ?? $reservation->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reservation updated',
            'data' => $this->mapReservation($reservation)
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/reservations/delete/{id}",
     *     summary="Cancel/Delete a reservation",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Reservation cancelled")
     * )
     */
    public function cancelReservation($id)
    {
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return response()->json(['success' => false, 'message' => 'Reservation not found'], 404);
        }

        $reservation->delete();
        return response()->json(['success' => true, 'message' => 'Reservation cancelled']);
    }

    private function mapReservation($item)
    {
        return [
            'id' => $item->id,
            'userId' => $item->user_id,
            'phoneNumber' => $item->phone_number,
            'guestName' => $item->guest_name,
            'guestCount' => $item->guest_count,
            'reservationDate' => $item->reservation_date,
            'reservationTime' => $item->reservation_time,
            'specialRequests' => $item->special_requests,
            'status' => $item->status,
            'createdAt' => $item->created_at,
            'updatedAt' => $item->updated_at,
        ];
    }
}
