<?php

namespace App\Http\Controllers\Admin;

use App\Models\InterviewStandardOrder;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InterviewStandard;
use App\Models\Order;

class InterviewStandardOrderController extends Controller
{

    // public function create()
    // {
    //     //
    //     $interview_standards = InterviewStandard::all();
    //     $order = Order::findOrFail(Request()->order_id);
    //     // $evaluation = InterviewStandardOrder::where('order_id', Request()->order_id);
    //     return view('admin.interview-standards-order.create', compact('order', 'interview_standards'));
    // }
    // //??=========================================================================================================
    // public function store(Request $request)
    // {
    //     $existingEntry = InterviewStandardOrder::where('order_id', $request->order_id)
    //         ->whereIn('interview_standard_id', array_keys($request->scores))
    //         ->exists();

    //     if ($existingEntry) {
    //         return back()->with(['message' => trans('translation.order interview already exists'), 'alert-type' => 'error']);
    //     }
    //     $order = Order::findOrFail($request->order_id);

    //     $scores = $request->scores;
    //     $max_scores = $request->max_scores;
    //     foreach ($scores as $key => $score) {
    //         InterviewStandardOrder::create([
    //             'order_id' => $request->order_id,
    //             'interview_standard_id' => $key,
    //             'score' => $scores[$key],
    //             'max_score' => $max_scores[$key],
    //         ]);
    //     }
    //     $order->update($request->only(['bonus']));

    //     return redirect()->route('admin.interview-standard-orders.show', $request->order_id)->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success']);
    // }
    // //??=========================================================================================================
    // public function show($order_id)
    // {
    //     $order = Order::findOrFail($order_id);
    //     $statuses = Status::order_interview_statuses()->get();

    //     return view('admin.interview-standards-order.show', compact('order','statuses'));
    // }
    // //??=========================================================================================================
    // public function edit($order_id)
    // {
    //     $order = Order::findOrFail(Request()->order_id ?? 0);
    //     $order_interviews = $order->interview_standard_orders;
    //     // $evaluation = InterviewStandardOrder::where('order_id', Request()->order_id);
    //     return view('admin.interview-standards-order.edit', compact('order', 'order_interviews'));
    // }
    // //??=========================================================================================================
    // public function update_scores(Request $request, Order $order)
    // {
    //     $order->load('interview_standard_orders');
    //     // dd($request->all(),$order->interview_standard_orders->pluck('score')->toArray());
    //     foreach ($request->scores as $id => $order_interview_score) {
    //         InterviewStandardOrder::findOrFail($id)->update([
    //             'score' => $order_interview_score,
    //         ]);
    //     }
    //     $order->update($request->only(['bonus']));

    //     return redirect()->route('admin.interview-standard-orders.show', $order->id)->with(['message' => trans('translation.Updated successfully'), 'alert-type' => 'success']);
    // }
    // //??=========================================================================================================
}
