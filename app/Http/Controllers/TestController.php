<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
  public function test(Request $request)
  {
    return response()->json([
      'message' => 'some error message'
    ], 422);
  }
}
