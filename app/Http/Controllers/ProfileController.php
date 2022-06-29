<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\User;



class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
    //    if (auth()->user()->id == 1) {
      //      return back()->withErrors(['not_allow_profile' => __('You are not allowed to change data for a default user.')]);
    //    }
        
         // Auth()->user()->update(['secure_check'=>$request->secure_check]);
        auth()->user()->update($request->all());
    // dd($request);
        if($request->hasFile('image')){
            $filename = rand(10,1000).$request->file('image')->getClientOriginalName();
            //dd($filename);
            Auth()->user()->update(['image'=>$filename]);
            $request->image->move(public_path('argon/img/theme'),$filename);
            
        }

        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        /*if (auth()->user()->id == 1) {
            return back()->withErrors(['not_allow_password' => __('You are not allowed to change the password for a default user.')]);
        }*/

        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Password successfully updated.'));
    }
}
