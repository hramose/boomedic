<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\clinic_history;
use App\answers_clinic_history;
use App\questions_clinic_history;


class clinicHistory extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $user = User::find(Auth::id());
        $clinic_history = DB::table('clinic_history')->get();
        $answers_clinic_history = DB::table('answers_clinic_history')->get();
        $questions_clinic_history = DB::table('questions_clinic_history')->get();

        return view('clinicHistory', [
                'userId'            => $user->id,
                'username'          => $user->username,
                'name'              => $user->name,
                'photo'             => $user->profile_photo,
                'date'              => $user->created_at,
                'q_clinic_history'  => $questions_clinic_history,
                'a_clinic_history'  => $answers_clinic_history,
                'clinic_history'    => $clinic_history,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function redirecting($page)
    {
        switch ($page) {
            case 'index':
                return redirect('clinicHistory/index'); //show
                break;
            
            default:
                return redirect('/medicalconsultations'); //medicalconsultations
                break;
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    DB::delete('delete from privacy_statement where id = ?',[$id]) ;
    
    // redirect
    
        return redirect('clinicHistory/index');
    }

    
}
