<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TravelOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Notifications\TravelOrderStatusUpdated;


class TravelOrderController extends Controller
{

    public function index(Request $request)
    {

        $query = TravelOrder::query();


        if (Gate::denies('is-admin')) {
            $query->where('user_id', Auth::id());
        }


        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->has('destination')) {
            $query->where('destination', 'like', '%' . $request->input('destination') . '%');
        }
        if ($request->has('start_date')) {
            $query->whereDate('start_date', '>=', $request->input('start_date'));
        }
        if ($request->has('end_date')) {
            $query->whereDate('start_date', '<=', $request->input('end_date'));
        }


        $travelOrders = $query->with('user:id,name,email')->latest()->get();

        return response()->json($travelOrders);
    }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'destination' => 'required|string|max:255',
            'start_date'  => 'required|date_format:Y-m-d',
            'end_date'    => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'reason'      => 'required|string',
        ]);


        $user = Auth::user();

        $travelOrder = $user->travelOrders()->create([
            'destination' => $validatedData['destination'],
            'start_date'  => $validatedData['start_date'],
            'end_date'    => $validatedData['end_date'],
            'reason'      => $validatedData['reason'],
            'status'      => 'Pendente',
        ]);


        return response()->json($travelOrder, 201);
    }


    public function show(TravelOrder $travelOrder)
    {

        $this->authorize('view', $travelOrder);
        return response()->json($travelOrder);
    }


    public function updateStatus(Request $request, TravelOrder $travelOrder)
    {

        if (Gate::denies('is-admin')) {
            return response()->json(['message' => 'Ação não autorizada.'], 403);
        }


        $validated = $request->validate([
            'status' => 'required|string|in:Aprovado,Cancelado', // Ajustado para 'Aprovado' e 'Cancelado'
        ]);


        if ($validated['status'] === 'Cancelado' && $travelOrder->status === 'Aprovado') {
            return response()->json([
                'message' => 'Não é possível cancelar um pedido que já foi aprovado.'
            ], 422);
        }


        $travelOrder->status = $validated['status'];
        $travelOrder->save();


        $travelOrder->load('user');
        $user = $travelOrder->user;


        $user->notify(new TravelOrderStatusUpdated($travelOrder));

        return response()->json($travelOrder);
    }


    public function update(Request $request, TravelOrder $travelOrder) {}


    public function destroy(TravelOrder $travelOrder) {}
}
