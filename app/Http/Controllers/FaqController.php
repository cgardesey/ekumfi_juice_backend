<?php

namespace App\Http\Controllers;

use App\InstructorCourse;
use App\Faq;
use App\User;
use Illuminate\Http\Request;
use App\Traits\PaymentsTrait;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    use PaymentsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Faq::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $faq_id = Str::uuid();
        Faq::forceCreate(
            ['faq_id' => $faq_id] +
            $request->all());

        $faq = Faq::where('faq_id', $faq_id)->first();

        return response()->json($faq);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Faq $faq
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        return $faq;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Faq $faq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faq $faq)
    {
        $faq->update($request->all());

        $updated_faq = Faq::where('faq_id', $faq->faq_id)->first();

        return response()->json($updated_faq);
    }
}
