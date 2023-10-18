<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Database\QueryException;

class AccountController extends Controller
{
  public function index()
  {
    return view('index');
  }
  public function import()
  {
    $total = 0;
    $added = 0;
    $updated = 0;

    $response = file_get_contents("https://randomuser.me/api/?results=5000");

    if ($response === false){
      echo ("Error: Unable to retrieve data from the API.");
    } else {
      $data = json_decode($response, true);

      foreach ($data['results'] as $key => $value){
        try {
          Account::create([
            'first_name' => $value['name']['first'],
            'last_name' => $value['name']['last'],
            'email' => $value['email'],
            'age' => $value['dob']['age']
          ]);

          $added++;
        } catch (QueryException $e){
          //If duplicate entry update account
          if ($e->errorInfo[1] == '1062'){
            Account::where('email', $value['email'])
                ->update([
                  'first_name' => $value['name']['first'],
                  'last_name' => $value['name']['last'],
                  'age' => $value['dob']['age']
                ]);
            $updated++;
          }
        }
      }

      $total = Account::get()->count();
    }

    return response()->json([
        'total' => $total,
        'added' => $added,
        'updated' => $updated,
    ]);
  }
}
