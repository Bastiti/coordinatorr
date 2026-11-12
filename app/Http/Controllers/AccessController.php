<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;

class AccessController extends Controller
{
    public function show()
    {
        //nothing to unlock if no access code is configured or access was already granted
        if(db4scw_has_activator_access())
        {
            return redirect()->route('home');
        }

        return view('access');
    }

    public function unlock()
    {
        //validate input
        $validator = \Illuminate\Support\Facades\Validator::make(request()->all(), [
            'access_code' => 'required|string|max:255'
        ],
        [
            'access_code.required' => 'Please enter the event access code.'
        ]);

        //handle fail of validation
        if ($validator->fails()) {
            return redirect()->route('access')->with('danger', db4scw_validatorerrors($validator));
        }

        //check access code
        $accesscode = (string) config('app.db4scw_activator_access_code');

        if($accesscode == '' or !hash_equals($accesscode, (string) $validator->validated()['access_code']))
        {
            return redirect()->route('access')->with('danger', 'Wrong access code.');
        }

        //remember access in this browser for 30 days
        Cookie::queue('coordinatorr_access', db4scw_activator_access_token(), 60 * 24 * 30);

        //return to the page the user came from
        return redirect()->intended(route('home'))->with('success', 'Access granted. You can now log and plan activations.');
    }
}
