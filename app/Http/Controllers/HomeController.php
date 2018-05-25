<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\professional_information;
use Carbon\Carbon;
use App\User;
use Mail;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        $user = User::find(Auth::id());
        Session(['entered' => $user->entered]);
        $privacyStatement = DB::table('privacy_statement')->orderby('id','DESC')->take(1)->get();
        $StatementForUser = $user->privacy_statement;
        $appointments = DB::table('medical_appointments')
           ->join('users', 'medical_appointments.user_doctor', '=', 'users.id')
           ->join('labor_information', 'medical_appointments.workplace', '=', 'labor_information.id')
           ->join('transaction_bank', 'medical_appointments.id', '=', 'transaction_bank.appointments')
           ->join('paymentsmethods', 'transaction_bank.paymentmethod', '=', 'paymentsmethods.id')
           ->where('medical_appointments.user', '=', Auth::id())
           ->where('medical_appointments.when', '>', Carbon::now())
           ->select('medical_appointments.id','medical_appointments.created_at','users.name', 'users.profile_photo', 'users.id as did','medical_appointments.when', 'medical_appointments.status', 'labor_information.workplace', 'labor_information.longitude', 'labor_information.latitude','paymentsmethods.cardnumber', 'paymentsmethods.provider', 'paymentsmethods.paypal_email','paymentsmethods.id as idtr')->get();

        $join = DB::table('professional_information')
            ->join('labor_information', 'professional_information.id', '=', 'labor_information.profInformation')
            ->join('users', 'professional_information.user', '=', 'users.id')
            ->select('labor_information.*', 'users.name', 'professional_information.specialty', 'users.id AS dr', 'users.profile_photo')
            ->get();

                

             foreach($join as $labor){
                 $workArray = array();
                 $cite = array();
                  $cites = DB::table('medical_appointments') ->where('user_doctor', '=', $labor->dr)->get();
                          foreach($cites  as $cit){
                            array_push($cite, $cit->when);
                          }

                $workboard = DB::table('workboard') ->where('workboard.labInformation', '=', $labor->id)->get();
                          foreach($workboard  as $work){
                            array_push($workArray, $work->workingDays.':'.$work->patient_duration_attention);
                          }



                    if($labor->specialty == 'Médico General'){
                        if(!$labor->profile_photo){
                        $mg = '["'.$labor->latitude.','.$labor->longitude.', "'.$labor->name.'", "'.$labor->workplace.'","'.$labor->general_amount.'",'.json_encode($workArray).', "'.$labor->id.'", "'.$labor->dr.'",'.json_encode($cite).', "https://s3.amazonaws.com/abiliasf/iconoo_doc_verde-01.png"]';
                        } else{
                        $mg = '["'.$labor->latitude.','.$labor->longitude.', "'.$labor->name.'", "'.$labor->workplace.'","'.$labor->general_amount.'",'.json_encode($workArray).', "'.$labor->id.'", "'.$labor->dr.'",'.json_encode($cite).', "'.$labor->profile_photo.'"]';
                    }
                    }
                    else{
                    if(!$labor->profile_photo){
                          $it[] = '["'.$labor->specialty.'",'.$labor->latitude.','.$labor->longitude.', "'.$labor->name.'", "'.$labor->workplace.'","'.$labor->general_amount.'",'.json_encode($workArray).', "'.$labor->id.'", "'.$labor->dr.'",'.json_encode($cite).', "https://s3.amazonaws.com/abiliasf/iconoo_doc_verde-01.png"]';

                        } else{
                    $it[] = '["'.$labor->specialty.'",'.$labor->latitude.','.$labor->longitude.', "'.$labor->name.'", "'.$labor->workplace.'","'.$labor->general_amount.'",'.json_encode($workArray).', "'.$labor->id.'", "'.$labor->dr.'",'.json_encode($cite).', "'.$labor->profile_photo.'"]';
                            }
                    $sp[] = '["'.$labor->specialty.'"]';
                    $mg = '0';
               
                        }
                     }

     

             Session(['it' => $it]);
             Session(['sp' => $sp]);
             Session(['mg' => $mg]);

        if($user->confirmed == false){
               return view('confirme', [
                    'userId'    => $user->id,
                    'username'  => $user->username,
                    'name'      => $user->name,
                    'photo'     => $user->profile_photo,
                    'date'      => $user->created_at,
                   
                ]
            );
        }     
        elseif(is_null($StatementForUser) || $StatementForUser != $privacyStatement[0]->id){
            $mode = 'Null';
            return view('privacyStatement', [
                    'privacy'   => $privacyStatement[0],
                    'userId'    => $user->id,
                    'username'  => $user->username,
                    'name'      => $user->name,
                    'photo'     => $user->profile_photo,
                    'date'      => $user->created_at,
                    'mode'      => $mode
                   
                ]
            );
        }

        $profInfo = DB::table('professional_information')
                            ->where('user', Auth::id() )
                            ->get();
        $statusRecordUser = DB::table('users')->where('id', Auth::id() )->value('status');
        if($profInfo->count() > 0 && $user->confirmed == false){
               return view('confirme', [
                    'userId'    => $user->id,
                    'username'  => $user->username,
                    'name'      => $user->name,
                    'photo'     => $user->profile_photo,
                    'date'      => $user->created_at,
                   
                ]
            );
        }  
        elseif($profInfo->count() > 0 && is_null($StatementForUser) || $StatementForUser != $privacyStatement[0]->id){
            $mode = 'Null';
            return view('privacyStatement', [
                    'privacy'   => $privacyStatement[0],
                    'userId'    => $user->id,
                    'username'  => $user->username,
                    'name'      => $user->name,
                    'photo'     => $user->profile_photo,
                    'date'      => $user->created_at,
                    'mode'      => $mode
                   
                ]
            );
        }
        if ($profInfo->count() > 0 && $statusRecordUser == 'In Progress') {
            Session(['utype' => 'doctor']);
            return redirect('doctor/edit/In%20Progress');
        }
        if ($profInfo->count() > 0 && $statusRecordUser != 'In Progress') {
             Session(['utype' => 'doctor']);
            return view('homemedical', [
                    'username'      => $user->username,
                    'name'          => $user->name,
                    'firstname'     => $user->firstname,
                    'lastname'      => $user->lastname,
                    'photo'         => $user->profile_photo,
                    'date'          => $user->created_at,
                    'userId'        => $user->id,
                    'labor'         => $join,
                    'photo'         => $user->profile_photo,
                    'workplaces'    => $this->getWorkPlaces(),
                    'medAppoints'   => $this->getMedicalAppointments(),
                ]);   
        }
        if(DB::table('users')->where('id', Auth::id() )->value('status') == 'In Progress'){
            Session(['utype' => 'mortal']); 
            return redirect('user/edit/In%20Progress');
        }
        else {
             Session(['utype' => 'mortal']); 
                return view('medicalconsultations', [
                        'username'  => $user->username,
                        'name'      => $user->name,
                        'firstname' => $user->firstname,
                        'lastname'  => $user->lastname,
                        'photo'     => $user->profile_photo,
                        'date'      => $user->created_at,
                        'userId'    => $user->id,
                        'labor'     => $join,
                        'appointments' => $appointments,
                        'title'     => 'Este doctor no tiene horarios agregados'

                    ]
                );
        }
    }
    /**
     * Show the application dashboard.
     *
     * @author  Hugo Hernández <hugo@doitcloud.consulting>
     * @return [Array] [List of workplaces]
     */
    public function getWorkPlaces(){
        return DB::table('labor_information')
            ->join('professional_information', 'labor_information.profInformation', '=', 'professional_information.id')
            ->where('professional_information.user', '=', Auth::id())->get();
    }
    /**
     * Method responsable of return the Medical Appoinments registered to current doctor.
     * @author  Hugo Hernández <hugo@doitcloud.consulting>
     * @return [Array] [List of Medical Appointments]
     */
    public function getMedicalAppointments(){
        return DB::table('medical_appointments')
            ->join('users', 'medical_appointments.user', '=', 'users.id')
            ->where(
                [
                    ['medical_appointments.user_doctor', '=', Auth::id()],
                    ['when', '<', Carbon::now()]
                ]
            )->get();
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function recent(Request $request)
  {     $user = User::find(Auth::id());
        $userSearch = json_decode($user->recent_search);
        $recent = array();
        $json = json_decode($request);
        if($request->search != null){
             if(!$user->recent_search){
                 array_push($recent, $request->search);
                     $user->recent_search  = json_encode($recent); 
             } else{  

              if(!in_array($request->search,  $userSearch)){
                 
                if(count($userSearch) == 4 ){
                    unset($userSearch[0]);
                    $userSearch = array_values($userSearch);
                    array_push($userSearch, $request->search);
                    $user->recent_search  = json_encode($userSearch); 
                } else{
                    array_push($userSearch, $request->search);
                    $user->recent_search  = json_encode($userSearch); 
                    }
                } 
        }

            $user->save();
           } 
            return response()->json($user->recent_search);
    }
     /**
     * Method responsable of list of recent
     */
    public function showrecent()
    {
         $user = User::find(Auth::id());
        return response()->json($user->recent_search);
    }


    //Function notify ajax master blade
        public function notify()
    {
          $user = User::find(Auth::id());
          Session(['entered' => $user->entered]);
        //if is for user or for all
         $notifications = DB::table('notifications')->where(function($q) {
          $q->where('user_id', Auth::id())
            ->orWhere('type', 'Global');})->get();

        return response()->json($notifications);
    }

        public function notify2()
    {
        //if is for user or for all
         $user = User::find(Auth::id());
         $user->entered  = true;
          Session(['entered' => $user->entered]);
        if($user->save())
        return response()->json($user->entered);
    }

            //Function messages ajax master blade top-nav
        public function messages()
    {
          $user = User::find(Auth::id());
          $array = array();
        //if is for messages
         $messages1 = DB::table('items_conversations')->where('by', $user->id)->get();
         $search = $messages1->unique('conversation');
         $messages = DB::table('items_conversations')
            ->join('conversations', 'items_conversations.conversation', '=', 'conversations.id')
            ->join('users', 'items_conversations.by', '=', 'users.id')
            ->select('items_conversations.*', 'conversations.name as namec', 'users.profile_photo')
            ->orderBy('items_conversations.created_at')
            ->get();

            foreach($search as $s){
                foreach($messages->sortByDesc('created_at') as $mess){
                    if($s->conversation == $mess->conversation && $mess->viewed == false && $mess->by != $user->id){
                       array_push($array, $mess);
                    }
                }
            }
        return response()->json($array);
    }

     /**
     * Method responsable of list of recent
     */
    public function appointments(){

         $user = User::find(Auth::id());
         $appointments = DB::table('medical_appointments')
           ->join('users', 'medical_appointments.user_doctor', '=', 'users.id')
           ->join('professional_information', 'medical_appointments.user_doctor', '=', 'professional_information.user')
           ->join('labor_information', 'medical_appointments.workplace', '=', 'labor_information.id')
           ->where('medical_appointments.user', '=', Auth::id())
            ->where('medical_appointments.when', '>', Carbon::now())
           ->select('medical_appointments.id','medical_appointments.created_at','users.name', 'users.id as did','medical_appointments.when', 'medical_appointments.status', 'labor_information.*', 'professional_information.specialty','users.profile_photo')->get();

                 return view('appointments', [
                'userId'    => $user->id,
                'username'  => $user->username,
                'name'      => $user->name,
                'photo'     => $user->profile_photo,
                'date'      => $user->created_at,
                'app'       => $appointments
            ]
        );
    }

        public function logoutback(){

        $parental = session()->get('parental');
        $user = DB::table('users')->where('username', $parental)->first();
        $user2 = User::find($user->id);

        Auth::login($user2, true);
        session()->flush();
        // if successful, then redirect to their intended location
        return redirect()->intended(route('medicalconsultations'));
    }

        public function returnverify()
            {
                $user = User::find(Auth::id());
                $user->confirmation_code = str_random(25);
                if($user->save()){
                $data = [
                'confirmation_code'      => $user->confirmation_code,
                'name'                   => $user->name
            ];
                Mail::send('emails.confirmation_code', $data, function ($message) {
                    $message->to('contacto@doitcloud.consulting')->subject('Por favor confirma tu correo');
                });
                    return redirect('/medicalconsultations');
                } else{
                          \Auth::logout();
                        return redirect('/login');
                }
            }

        public function verify($code)
           {
             $user = User::where('confirmation_code', $code)->first();
                if (!$user){
                 \Auth::logout();
                    return redirect('/login');
                }else{
                $user->confirmed = true;
                $user->confirmation_code = null;
                if($user->save())
                return redirect('/medicalconsultations')->with('notification', 'Has confirmado correctamente tu correo!');
            }
            }


    
}

