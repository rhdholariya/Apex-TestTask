<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\Checkouts;
use App\Services\ResponseBuilder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $checkouts = Checkouts::all();
        return ResponseBuilder::success($checkouts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|string|exists:users,id',
        ]);

        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->all());
        }

        // check book copies is available or not
        $book = Books::findorfail($request->book_id);

        if ($book->copies<=0){
            return ResponseBuilder::error("Selected book is not available yet!");
        }
        $book->update(['copies' => ($book->copies - 1)]);
        $checkout = Checkouts::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'checkout_date' => Carbon::now()->format('Y-m-d')
        ]);

        return ResponseBuilder::success($checkout->fresh(), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // check Checkout is available or not
        $checkout = Checkouts::findorfail($id);
        if ($checkout->return_date) {
            return ResponseBuilder::error("Book already submitted!");
        }
        // check book is available or not.
        $book = Books::findorfail($checkout->book_id);

        $checkout->update([
            'return_date' => Carbon::now()->format('Y-m-d')
        ]);
        $book->update(['copies' => ($book->copies + 1)]);

        return ResponseBuilder::success("Book has been submitted!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
