<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\User;
use App\Services\ResponseBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Books::all();
        return ResponseBuilder::success($books);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'author' => 'required|string',
            'isbn' => 'required|string',
            'published_at' => 'required|date|date_format:Y-m-d|before:now',
            'copies' => 'required|numeric|min:1'
        ]);
        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->all());
        }
        $book = Books::create($request->all());
        return ResponseBuilder::success($book, 201);
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'author' => 'required|string',
            'isbn' => 'required|string',
            'published_at' => 'required|date|date_format:Y-m-d|before:now',
            'copies' => 'required|numeric|min:1'
        ]);
        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->all());
        }
        $book = Books::findorFail($id);
        $book->update($request->all());
        return ResponseBuilder::success($book->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Books::findorFail($id);
        $book->delete();
        return ResponseBuilder::success("Book has been deleted");
    }

}
