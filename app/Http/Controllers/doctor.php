<?php
namespace App\Http\Controllers;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\Workboard;
use App\LaborInformation;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Image;
use Carbon\Carbon;
use App\professional_information;
use App\assistant;
use Mail;
class doctor extends Controller
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
    public function index()
    {
        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $user2 = User::find(Auth::id());
        $assistant = DB::table('assistant')
             ->join('users', 'assistant.user_doctor', '=', 'users.id')
             ->where('user_assist', Auth::id())
             ->select('assistant.*', 'users.name', 'users.profile_photo', 'users.id as iddr')
             ->get();
         if(count($assistant) > 0){
            Session(['utype' => 'assistant']); 
              if(session()->get('asdr') == null){
                  Session(['asdr' => $assistant[0]->iddr]);
                 }
             $user = User::find(session()->get('asdr'));
                             /*Validate doctor online*/
                               $donli = array();
                               foreach($assistant as $as){
                                 $doconline = User::find($as->iddr);
                                         if($doconline->isOnline() > 0){
                                            array_push($donli, ['id' => $as->iddr, 'online' => '1']);
                                       }else{
                                            array_push($donli, ['id' => $as->iddr, 'online' => '0']);
                                       }
                                 }
                               /*Validate doctor online*/  
         }else{  
                $user = User::find(Auth::id());
                $assistant = null;
                $donli = null;
          }
        $assist = DB::table('assistant')
            ->join('users', 'assistant.user_assist', '=', 'users.id')
            ->where('assistant.user_doctor', $user->id)
            ->where('assistant.confirmation', true)
            ->select('assistant.*', 'users.firstname', 'users.profile_photo', 'users.age', 'users.name')
            ->get();
        $nodes = array();
    //Json que guarda datos de familiares para generar externalidad//
      if(count($assist) < 1){
        if($user->profile_photo != null)
         array_push( $nodes, ['name' => 'Yo', 'photo' => $user->profile_photo. '?'. Carbon::now()->format('h:i'), 'id' => '0']);
            else{
                array_push( $nodes, ['name' => 'Yo', 'photo' => asset('profile-42914_640.png') .'?'.  Carbon::now()->format('h:i'), 'id' => '0']);
            }
          for($i = 1; $i < 2; $i++){
                array_push($nodes, ['name' => 'Agregar asistente', 'target' => [0] , 'photo' => 'https://image.freepik.com/iconen-gratis/zwart-plus_318-8487.jpg' , 'id' => 'n']);
            }
      }   else {
               
          array_push( $nodes, ['name' => 'Yo', 'photo' => $user->profile_photo. '?'. Carbon::now()->format('h:i'), 'id' => $user->id]);
          for($i = 0; $i < count($assist); $i++){
            if($assist[$i]->profile_photo != null){
                array_push($nodes, ['name' => $assist[$i]->firstname, 'target' => [0] , 'photo' => $assist[$i]->profile_photo. '?'. Carbon::now()->format('h:i') , 'id' => $assist[$i]->id, 'namecom' => $assist[$i]->name]);
                  }else {
                        array_push($nodes, ['name' => $assist[$i]->firstname, 'target' => [0] , 'photo' => asset('profile-42914_640.png'), 'id' => $assist[$i]->id, 'namecom' => $assist[$i]->name]);
                  }
            }
          }
        $professionali = DB::table('professional_information')->where('user', $user->id)->get();
        $bus = $professionali[0]->id;
        $prof = professional_information::find($bus);
        $labor = DB::table('labor_information')->where('profInformation', $bus)->get();
        $asso = DB::table('medical_association')->where('parent', '>', '0')->get();
        return view('profileDoctor', [
                 /** SYSTEM INFORMATION */
                'userId'        => $user->id,
                /** INFORMATION USER */
                'firstname'     => $user2->firstname,
                'lastname'      => $user2->lastname,
                'email'         => $user2->email,
                'username'      => $user2->username,
                'name'          => $user2->name,
                'age'           => $user->age,
                'photo'         => $user2->profile_photo,
                'date'          => $user2->created_at,
                'created_at'          => $user2->created_at,
                /** PERSONAL INFORMATION */
                'username2' => $user->username,
                'email2'         => $user->email,
                'name2'          => $user->name,
                'photo2'         => $user->profile_photo,
                'gender'        => $user->gender,
                'occupation'    => $user->occupation,
                'scholarship'   => $user->scholarship,
                'maritalstatus' => $user->maritalstatus,
                'mobile'        => $user->mobile,
                'updated_at'    => $user->updated_at,
                /** PROFESSIONAL INFORMATION  */
                'professional_license'  =>  $professionali[0]->professional_license,
                'specialty'     => $professionali[0]->specialty,
                'schoolOfMedicine' => $professionali[0]->schoolOfMedicine,
                'facultyOfSpecialization' => $professionali[0]->facultyOfSpecialization,
                'practiseProfessional'    => $professionali[0]->practiseProfessional,
                'medical_society'         => $professionali[0]->medical_society,  
                /** ADDRESS FISICAL USER  */
                'country'       => (   empty($user->country)        ) ? '' : $user->country, 
                'state'         => (   empty($user->state)          ) ? '' : $user->state, 
                'delegation'    => (   empty($user->delegation)     ) ? '' : $user->delegation, 
                'colony'        => (   empty($user->colony)         ) ? '' : $user->colony, 
                'street'        => (   empty($user->street)         ) ? '' : $user->street, 
                'streetnumber'  => (   empty($user->streetnumber)   ) ? '' : $user->streetnumber, 
                'interiornumber'=> (   empty($user->interiornumber) ) ? '' : $user->interiornumber, 
                'postalcode'    => (   empty($user->postalcode)     ) ? '' : $user->postalcode,
                'longitude'     => (   empty($user->longitude)      ) ? '' : $user->longitude,
                'latitude'      => (   empty($user->latitude)       ) ? '' : $user->latitude,
                'mode'          => 'doctor',
                'labor'         => $labor,
                'asso'          => $asso,
                /*NODES ASSISTANT*/
                'nodes'         => json_encode($nodes),
                'donli'         => $donli,
                'as'            => $assistant 
            ]
        );
    }
        public function saveAssistant (Request $request)
      {     
        $user2 = User::find(Auth::id());
        $assistant = DB::table('assistant')
             ->join('users', 'assistant.user_doctor', '=', 'users.id')
             ->where('user_assist', Auth::id())
             ->select('assistant.*', 'users.name', 'users.profile_photo', 'users.id as iddr')
             ->get();
         if(count($assistant) > 0){
            Session(['utype' => 'assistant']); 
              if(session()->get('asdr') == null){
                  Session(['asdr' => $assistant[0]->iddr]);
                 }
             $user = User::find(session()->get('asdr'));
                             /*Validate doctor online*/
                               $donli = array();
                               foreach($assistant as $as){
                                 $doconline = User::find($as->iddr);
                                         if($doconline->isOnline() > 0){
                                            array_push($donli, ['id' => $as->iddr, 'online' => '1']);
                                       }else{
                                            array_push($donli, ['id' => $as->iddr, 'online' => '0']);
                                       }
                                 }
                               /*Validate doctor online*/  
         }else{  
                $user = User::find(Auth::id());
                $assistant = null;
                $donli = null;
          }
        if($request->idassist != null){        
          $assist = new assistant;
          $assist->user_doctor = $user->id;
          $assist->user_assist = $request->idassist;
          if($assist->save()){
             $userass=  DB::table('users')->where('id', $assist->user_assist )->first();
           $notification = array(
                //In case the payment is approved it shows a message reminding you the amount you paid.
            'message' => 'Usuario agregado como asistente de manera exitosa.', 
            'success' => 'success'
            );
          $data = [
                                'username'      => $user->username,
                                'name'      => $user->name,
                                'email'     => $user->email,                
                                'firstname' => $user->firstname,                
                                'lastname'  => $user->lastname,    
                                'user_assist'        => $assist->user_assist,
                                'id'                => $assist->id
                                ];
                                $email = $userass->email;
                                 Mail::send('emails.assistant', $data, function ($message) {
                                            $message->subject('Un doctor te ha asignado como su asistente');
                                            $message->to('contacto@doitcloud.consulting');
                                        });
         }else{
           $notification = array(
                //In case the payment is approved it shows a message reminding you the amount you paid.
            'message' => 'No se pudo agregar el usuario, vuelva a intentarlo más tarde.', 
            'error' => 'error'
            );
         }
          
        } else{
         $notification = array(
                //In case the payment is approved it shows a message reminding you the amount you paid.
            'message' => 'No se pudo agregar el usuario, vuelva a intentarlo más tarde.', 
            'error' => 'error'
            );
        } 
             return redirect('doctor/doctor/' . $user->id)->with($notification);
              
        } 
         public function verify($id)
           {
                 $assistant = assistant::where('id', $id)->first();
                if (!$assistant){
             $notification = array(
                            //In case the payment is approved it shows a message reminding you the amount you paid.
                        'message' => 'Ha ocurrido un error con la confirmación, al parecer fue eliminado', 
                        'error' => 'error'
                        );
                      
                    return  redirect('user/profile/' . Auth::id() )->with($notification);
                }
                else{
          $assist = User::where('id', $assistant->user_assist)->first();
          $doctor = User::where('id', $assistant->user_doctor)->first();
                $assistant->confirmation = true;
                if($assistant->save()){
                    $notification = array(
                        'message' => 'Se confirmó exitosamente se agregó por defecto a tu perfil', 
                        'success' => 'success'
                        );
                      $data = [
                                'username'      => $assist->username,
                                'name'      => $assist->name,
                                ];
                               $email = $doctor->email;
                                 Mail::send('emails.assistantconfirm', $data, function ($message) {
                                            $message->subject('El asistente que agregaste ha confirmado exitosamente');
                                            $message->to('contacto@doitcloud.consulting');
                                        });
                return redirect('user/profile/' . Auth::id())->with($notification);
              }
            }
        }

    public function deleteAssistant($id){
         $assistant = DB::table('assistant')
             ->join('users', 'assistant.user_doctor', '=', 'users.id')
             ->where('user_assist', Auth::id())
             ->select('assistant.*', 'users.name', 'users.profile_photo', 'users.id as iddr')
             ->get();
         if(count($assistant) > 0){
             $user = User::find(session()->get('asdr'));
          }else{
              $user = User::find(Auth::id());
          }
            DB::delete('delete from assistant where id = ?',[$id]) ;    
            return redirect('doctor/doctor/' . $user->id);
    }   

    public function redirecting($page)
    {      $assistant = DB::table('assistant')
             ->join('users', 'assistant.user_doctor', '=', 'users.id')
             ->where('user_assist', Auth::id())
             ->select('assistant.*', 'users.name', 'users.profile_photo', 'users.id as iddr')
             ->get();
         if(count($assistant) > 0){
             $user = User::find(session()->get('asdr'));
          }else{
              $user = User::find(Auth::id());
          }
        switch ($page) {
            case 'show':
                return redirect('doctor/doctor/' . $user->id); //show
                break;
            
            default:
                return redirect('/medicalconsultations'); //medicalconsultations
                break;
        }   
    }
    /**
     * Show the form for editing the specified resource.
     *questions_clinic_history
        id
        code_translation
        question
        text_help
        answers_clinic_history
        id
        code_translation
        answer
        text_help
        question
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($status){
        $user2 = User::find(Auth::id());
        $assistant = DB::table('assistant')
             ->join('users', 'assistant.user_doctor', '=', 'users.id')
             ->where('user_assist', Auth::id())
             ->select('assistant.*', 'users.name', 'users.profile_photo', 'users.id as iddr')
             ->get();
         if(count($assistant) > 0){
            Session(['utype' => 'assistant']); 
              if(session()->get('asdr') == null){
                  Session(['asdr' => $assistant[0]->iddr]);
                 }
             $user = User::find(session()->get('asdr'));
                             /*Validate doctor online*/
                               $donli = array();
                               foreach($assistant as $as){
                                 $doconline = User::find($as->iddr);
                                         if($doconline->isOnline() > 0){
                                            array_push($donli, ['id' => $as->iddr, 'online' => '1']);
                                       }else{
                                            array_push($donli, ['id' => $as->iddr, 'online' => '0']);
                                       }
                                 }
                               /*Validate doctor online*/  
         }else{  
                $user = User::find(Auth::id());
                $assistant = null;
                $donli = null;
          }
        $professionali = DB::table('professional_information')->where('user', $user->id )->get();
        $bus = $professionali[0]->id;
        $prof = professional_information::find($bus);
        $labor = DB::table('labor_information')->where('profInformation', $bus)->get();
        $asso = DB::table('medical_association')->where('parent', '>', '0')->get();
        return view('profileDoctor', [
                /** SYSTEM INFORMATION */
                'userId'        => $user->id,
                'status'        => $status,
                /** INFORMATION USER */
                'firstname'     => $user2->firstname,
                'lastname'      => $user2->lastname,
                'email'         => $user2->email,
                'username'      => $user2->username,
                'name'          => $user2->name,
                'age'           => $user->age,
                'photo'         => $user2->profile_photo,
                'date'          => $user->created_at,
                /** PERSONAL INFORMATION */
                'firstname2'     => $user->firstname,
                'lastname2'      => $user->lastname,
                'email2'         => $user->email,
                'username2'      => $user->username,
                'name2'          => $user->name,
                'photo2'         => $user->profile_photo,
                'gender'        => $user->gender,
                'occupation'    => $user->occupation,
                'scholarship'   => $user->scholarship,
                'maritalstatus' => $user->maritalstatus,
                'mobile'        => $user->mobile,
                /** PROFESSIONAL INFORMATION  */
                'professional_license'  =>  $professionali[0]->professional_license,
                'specialty'     => $professionali[0]->specialty,
                'schoolOfMedicine' => $professionali[0]->schoolOfMedicine,
                'facultyOfSpecialization' => $professionali[0]->facultyOfSpecialization,
                'practiseProfessional'    => $professionali[0]->practiseProfessional,
                'medical_society'         => $professionali[0]->medical_society,  
                /** ADDRESS FISICAL USER  */
                'country'       => (   empty($user->country)        ) ? '' : $user->country, 
                'state'         => (   empty($users->state)         ) ? '' : $user->state, 
                'delegation'    => (   empty($user->delegation)     ) ? '' : $user->delegation, 
                'colony'        => (   empty($user->colony)         ) ? '' : $user->colony, 
                'street'        => (   empty($user->street)         ) ? '' : $user->street, 
                'streetnumber'  => (   empty($user->streetnumber)   ) ? '' : $user->streetnumber, 
                'interiornumber'=> (   empty($user->interiornumber) ) ? '' : $user->interiornumber, 
                'postalcode'    => (   empty($user->postalcode)     ) ? '' : $user->postalcode,
                'mode'          => 'doctor',
                'labor'         => $labor,
                'asso'          => $asso,
                'as'            => $assistant,
                'donli'         => $donli
            ]
        );
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
        $user2 = User::find(Auth::id());
        $assistant = DB::table('assistant')
             ->join('users', 'assistant.user_doctor', '=', 'users.id')
             ->where('user_assist', Auth::id())
             ->select('assistant.*', 'users.name', 'users.profile_photo', 'users.id as iddr')
             ->get();
         if(count($assistant) > 0){
            Session(['utype' => 'assistant']); 
              if(session()->get('asdr') == null){
                  Session(['asdr' => $assistant[0]->iddr]);
                 }
             $user = User::find(session()->get('asdr'));
                             /*Validate doctor online*/
                               $donli = array();
                               foreach($assistant as $as){
                                 $doconline = User::find($as->iddr);
                                         if($doconline->isOnline() > 0){
                                            array_push($donli, ['id' => $as->iddr, 'online' => '1']);
                                       }else{
                                            array_push($donli, ['id' => $as->iddr, 'online' => '0']);
                                       }
                                 }
                               /*Validate doctor online*/  
         }else{  
                $user = User::find(Auth::id());
                $assistant = null;
                $donli = null;
          }
        $professionali = DB::table('professional_information')->where('user', $user->id)->get();
        $bus = $professionali[0]->id;
        $prof = professional_information::find($bus);
        $labor = DB::table('labor_information')->where('profInformation', $bus)->get();
         $asso = DB::table('medical_association')->where('parent', '>', '0')->get();
        if ($request->change == "true") {
        $user->status        = $request->status;         
        $user->firstname     = $request->firstname;         
        $user->lastname      = $request->lastname;         
        $user->email         = $request->email;         
        $user->username      = $request->username;         
        $user->age           = $request->age;         
        $user->gender        = $request->gender;         
        $user->occupation    = $request->occupation;         
        $user->scholarship   = $request->scholarship;         
        $user->maritalstatus = $request->maritalstatus;         
        $user->mobile        = $request->mobile;         
        $user->status        = 'Complete';
        $user->country       = $request->country; 
        $user->state         = $request->state; 
        $user->delegation    = $request->delegation; 
        $user->colony        = $request->colony; 
        $user->street        = $request->street; 
        $user->postalcode    = $request->postalcode; 
        $user->latitude      = $request->latitude; 
        $user->longitude     = $request->longitude;
        $prof->professional_license  =  $request->professional_license;
        $prof->specialty                = $request->specialty;
        $prof->schoolOfMedicine         = $request->schoolOfMedicine;
        $prof->facultyOfSpecialization  = $request->facultyOfSpecialization;
        $prof->practiseProfessional     = $request->practiseProfessional;
        $prof->medical_society          = $request->medical_society ;  
        $prof->save();
        $user->save();
        }
         return view('profileDoctor', [
                /** SYSTEM INFORMATION */
                'userId'        => $user->id,
                'name'          => $user2->name,
                'photo'         => $user2->profile_photo,
                'date'          => $user2->created_at,
                'mode'          => 'labor',
                'asso'          => $asso,
                /* DIRECTION LABOR PROFESSIONAL  */
                'labor'         => $labor,
                'as'            => $assistant,
                'donli'         => $donli
            ]
        );
    }
        public function laborInformationNext(Request $request, $id)
    {
        $user2 = User::find(Auth::id());
        $assistant = DB::table('assistant')
             ->join('users', 'assistant.user_doctor', '=', 'users.id')
             ->where('user_assist', Auth::id())
             ->select('assistant.*', 'users.name', 'users.profile_photo', 'users.id as iddr')
             ->get();
         if(count($assistant) > 0){
            Session(['utype' => 'assistant']); 
              if(session()->get('asdr') == null){
                  Session(['asdr' => $assistant[0]->iddr]);
                 }
             $user = User::find(session()->get('asdr'));
                             /*Validate doctor online*/
                               $donli = array();
                               foreach($assistant as $as){
                                 $doconline = User::find($as->iddr);
                                         if($doconline->isOnline() > 0){
                                            array_push($donli, ['id' => $as->iddr, 'online' => '1']);
                                       }else{
                                            array_push($donli, ['id' => $as->iddr, 'online' => '0']);
                                       }
                                 }
                               /*Validate doctor online*/  
         }else{  
                $user = User::find(Auth::id());
                $assistant = null;
                $donli = null;
          }
        $professionali = DB::table('professional_information')->where('user', $user->id)->get();
        $bus = $professionali[0]->id;
        $prof = professional_information::find($bus);
        $services = array();
        if($request->Estacionamiento){
         array_push($services,'Estacionamiento');
        }
        if($request->Cafeteria){
          array_push($services,'Cafeteria');
        }
        if($request->Ambulancias){
           array_push($services,'Ambulancias');
        }
        if($request->Elevador){
           array_push($services,'Elevador');
        }
        if($request->Wifi){
           array_push($services,'Wifi');
        }
        $laborInformation = new laborInformation;
        $laborInformation->workplace       = $request->workplace; 
        $laborInformation->professionalPosition       = $request->professionalPosition; 
        $laborInformation->country       = $request->country; 
        $laborInformation->state         = $request->state; 
        $laborInformation->delegation    = $request->delegation; 
        $laborInformation->colony        = $request->colony; 
        $laborInformation->street        = $request->street; 
        $laborInformation->postalcode    = $request->postalcode; 
        $laborInformation->latitude      = $request->lati; 
        $laborInformation->longitude     = $request->long;
        $laborInformation->general_amount = $request->cost;
        $laborInformation->profInformation  =   $prof->id;
        $laborInformation->services         = json_encode($services);
   
        $laborInformation->save();
          $labor = DB::table('labor_information')->where('profInformation', $bus)->get();
          return view('profileDoctor', [
                /** SYSTEM INFORMATION */
                'userId'        => $user->id,
                'name'          => $user2->name,
                'photo'         => $user2->profile_photo,
                'date'          => $user2->created_at,
                'mode'          => 'labor',
                /* DIRECTION LABOR PROFESSIONAL  */
                'labor'         => $labor,
                'as'            => $assistant,
                'donli'         => $donli
            ]
        );
    }
    public function laborInformationView($id)
    {    
        $user2 = User::find(Auth::id());
        $assistant = DB::table('assistant')
             ->join('users', 'assistant.user_doctor', '=', 'users.id')
             ->where('user_assist', Auth::id())
             ->select('assistant.*', 'users.name', 'users.profile_photo', 'users.id as iddr')
             ->get();
         if(count($assistant) > 0){
            Session(['utype' => 'assistant']); 
              if(session()->get('asdr') == null){
                  Session(['asdr' => $assistant[0]->iddr]);
                 }
             $user = User::find(session()->get('asdr'));
                             /*Validate doctor online*/
                               $donli = array();
                               foreach($assistant as $as){
                                 $doconline = User::find($as->iddr);
                                         if($doconline->isOnline() > 0){
                                            array_push($donli, ['id' => $as->iddr, 'online' => '1']);
                                       }else{
                                            array_push($donli, ['id' => $as->iddr, 'online' => '0']);
                                       }
                                 }
                               /*Validate doctor online*/  
         }else{  
                $user = User::find(Auth::id());
                $assistant = null;
                $donli = null;
          }
        $professionali = DB::table('professional_information')->where('user', $user->id)->get();
        $bus = $professionali[0]->id;
        $prof = professional_information::find($bus);
          $labor = DB::table('labor_information')->where('profInformation', $bus)->get();
          return view('profileDoctor', [
                /** SYSTEM INFORMATION */
                'userId'        => $user->id,
                'name'          => $user2->name,
                'photo'         => $user2->profile_photo,
                'date'          => $user2->created_at,
                'mode'          => 'viewlabor',
                /* DIRECTION LABOR PROFESSIONAL  */
                'labor'         => $labor,
                'as'            => $assistant,
                'donli'         => $donli
            ]
        );
    }
    public function updateDoctor(Request $request, $id)
    {    $assistant = DB::table('assistant')
             ->join('users', 'assistant.user_doctor', '=', 'users.id')
             ->where('user_assist', Auth::id())
             ->select('assistant.*', 'users.name', 'users.profile_photo', 'users.id as iddr')
             ->get();
         if(count($assistant) > 0){
             $user = User::find(session()->get('asdr'));
          }else{
              $user = User::find(Auth::id());
          }
        $file = $request->file('file');
         $imagen = getimagesize($file);    //Sacamos la información
          $width = $imagen[0];              //Ancho
          $height = $imagen[1];  
          if($height > '600' || $width > '400'){
            $height = $height / 2;
            $width = $width / 2;
          }
          if($height > '800' || $width > '600'){
            $height = $height / 2.5;
            $width = $width / 2.5;
          }
            if($height > '1000' || $width > '900'){
                $height = $height / 3;
                $width = $width / 3;
              }
        $img = Image::make($file);
        $img->resize($width, $height);
        $img->encode('jpg');
        Storage::disk('s3')->put( $id.'temporal.jpg',  (string) $img, 'public');
        $filename = $id.'temporal.jpg';
        $path = Storage::cloud()->url($filename);
        $path2= 'https://s3.amazonaws.com/'. env('S3_BUCKET') .'/'. $filename;
       
        $user->profile_photo = $path2;   
               
        if($user->save()){
        Session(['val' => 'true']);
        return redirect('doctor/doctor/' . $id );
        }
    }
    public function cropDoctor(Request $request, $id)
    {     $assistant = DB::table('assistant')
             ->join('users', 'assistant.user_doctor', '=', 'users.id')
             ->where('user_assist', Auth::id())
             ->select('assistant.*', 'users.name', 'users.profile_photo', 'users.id as iddr')
             ->get();
         if(count($assistant) > 0){
             $user = User::find(session()->get('asdr'));
          }else{
              $user = User::find(Auth::id());
          }
        $targ_w = $targ_h = 300;
        $jpeg_quality = 90;
        $src = $user->profile_photo;
        $img_r = imagecreatefromjpeg($src);
        $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
        imagecopyresampled($dst_r,$img_r,0,0,$request->x,$request->y,
            $targ_w,$targ_h,$request->w,$request->h);
        $filename = $id.'.jpg';
        $path2= 'https://s3.amazonaws.com/'. env('S3_BUCKET') .'/'. $filename;
        
        ob_start();
        imagejpeg($dst_r);
        $jpeg_file_contents = ob_get_contents();
        ob_end_clean();
        Storage::disk('s3')->put( $id.'.jpg',  $jpeg_file_contents, 'public');
        
        $path = Storage::cloud()->url($filename);
         Session(['val' => 'false']);
       
        $user->profile_photo = $path2;   
        Storage::disk('s3')->delete('https://s3.amazonaws.com/'. env('S3_BUCKET') .'/'.$user->id.'temporal.jpg');
        if($user->save()){
        //Imagen copia circular//
            $newwidth = 150;
            $newheight = 150;
        $image = imagecreatetruecolor( $newwidth, $newheight);
        $image_s = imagecreatefromstring(file_get_contents($path2));
       /* $image_z = imagecreatefromstring(file_get_contents('https://s3.amazonaws.com/abiliasf/circle.png'));*/
        $width = imagesx($image_s);
        $height = imagesy($image_s);
        imagealphablending($image, true);
        imagecopyresampled($image, $image_s, 0, 0, 0, 0, $newwidth, $newheight,$width,$height);
        //create masking
        $mask = imagecreatetruecolor( $width,$height);
        $transparent = imagecolorallocate($mask, 255, 0, 0);
        imagecolortransparent($mask,$transparent);
        imagefilledellipse($mask,  $newwidth/2, $newheight/2, $newwidth, $newheight, $transparent);
        $red = imagecolorallocate($mask, 0, 0, 0);
        imagecopymerge($image, $mask, 0, 0, 0, 0, $newwidth,$newheight, 100);
        imagecolortransparent($image,$red);
        imagefill($image, 0, 0, $red);
        ob_start();
        imagepng($image);
        $png_file = ob_get_contents();
        ob_end_clean();
        Storage::disk('s3')->put( $id.'-circle.png',  $png_file, 'public');
        //Imagen copia circular//
            return redirect('doctor/edit/complete' . $id );
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
        $assistant = DB::table('assistant')
             ->join('users', 'assistant.user_doctor', '=', 'users.id')
             ->where('user_assist', Auth::id())
             ->select('assistant.*', 'users.name', 'users.profile_photo', 'users.id as iddr')
             ->get();
         if(count($assistant) > 0){
             $user = User::find(session()->get('asdr'));
          }else{
              $user = User::find(Auth::id());
          }
        $workboard = DB::table('workboard')->where('labInformation', $id)->get();
        $appointments = DB::table('medical_appointments')->where('workplace', $id)->get();
       if(count($workboard) > 0){
          DB::table('workboard')->where('labInformation', $id)->delete();   
       }
        if(count($appointments) > 0){
          DB::table('medical_appointments')->where('workplace', $id)->delete();   
       }
          DB::delete('delete from labor_information where id = ?',[$id]) ;    
          return redirect('doctor/laborInformationView/'.$user->id);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function settingAss()
    {
      $user = User::find(Auth::id());
       $assistants = DB::table('assistant')
             ->join('users', 'assistant.user_assist', '=', 'users.id')
             ->where('user_doctor', $user->id)
             ->select('assistant.*', 'users.name', 'users.profile_photo', 'users.id as idass')
             ->get();
        return response()->json($assistants);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewPatient($id)
    {
      //event(new EventName('rebbeca.goncalves@doitcloud.consulting'));
        $userOne = User::find(Auth::id());
        $users = DB::table('users')->where('id', $id)->get();
        $family = DB::table('family')
            ->join('users', 'family.activeUser', '=', 'users.id')
            ->where('family.parent', $id)
            ->select('family.*', 'users.firstname', 'users.profile_photo', 'users.age', 'users.name')
            ->get();
        $nodes = array();
    //Json que guarda datos de familiares para generar externalidad//           
          array_push( $nodes, ['name' => $users[0]->firstname, 'photo' => $users[0]->profile_photo. '?'. Carbon::now()->format('h:i'), 'id' => $users[0]->id]);
          for($i = 0; $i < count($family); $i++){
            $session = "0";
            if($family[$i]->relationship == "son" && $family[$i]->age < 18){
                $session = "1";
            } 
            if($family[$i]->profile_photo != null){
                array_push($nodes, ['name' => $family[$i]->firstname, 'target' => [0] , 'photo' => $family[$i]->profile_photo. '?'. Carbon::now()->format('h:i') , 'id' => $family[$i]->activeUser, 'relationship' => trans('adminlte::adminlte.'.$family[$i]->relationship), "session" => $session, 'namecom' => $family[$i]->name]);
                  }else {
                        array_push($nodes, ['name' => $family[$i]->firstname, 'target' => [0] , 'photo' => asset('profile-42914_640.png') , 'id' => $family[$i]->activeUser, 'relationship' => trans('adminlte::adminlte.'.$family[$i]->relationship), "session" => $session, 'namecom' => $family[$i]->name]);
                  }
            }
    //Json que guarda datos de familiares para generar externalidad//   


        #Code HDHM
        $question = DB::table('questions_clinic_history')
            ->join('answers_clinic_history', 'questions_clinic_history.id', '=', 'answers_clinic_history.question')
            ->where('answers_clinic_history.question','!=', null)
            ->select('answers_clinic_history.answer', 'answers_clinic_history.parent', 'answers_clinic_history.parent_answer','questions_clinic_history.question', 'questions_clinic_history.id', 'answers_clinic_history.id AS a')
            ->get();

        //dd($question); 

        $answer;

        for ($i=0; $i < count($question); $i++) { 
            if ($question[$i]->question == "¿Está actualmente tomando algún fármaco con prescripción?") {
                $answer = $question[$i]->parent_answer;
                break;
            }
        }
        $this->history = new history;
        $allhistory = $this->history->iframe($id);
        $this->clinicHistory = new clinicHistory;
        $data = $this->clinicHistory->helperIndex($users[0]);

        return view('viewPatient', [
                
                 /** SYSTEM INFORMATION */

                'firstname'     => $userOne->firstname,
                'lastname'      => $userOne->lastname,
                'email'         => $userOne->email,

                'name'          => $userOne->name,

                'username'      => $userOne->username,
                'age'           => $userOne->age,
                'photo'         => $userOne->profile_photo,
                'date'          => $userOne->created_at,
                /** INFORMATION USER */

                'pfirstname'     => $users[0]->firstname,
                'plastname'      => $users[0]->lastname,
                'pemail'         => $users[0]->email,

                'pname'          => $users[0]->name,

                'pusername'      => $users[0]->username,
                'agep'           => $users[0]->age,
                'pphoto'         => $users[0]->profile_photo,
                'pdate'          => $users[0]->created_at,

                /** PERSONAL INFORMATION */

                'gender'        => $users[0]->gender,
                'occupation'    => $users[0]->occupation,
                'scholarship'   => $users[0]->scholarship,
                'maritalstatus' => $users[0]->maritalstatus,
                'mobile'        => $users[0]->mobile,
                'updated_at'    => $users[0]->updated_at,
                'created_at'    => $users[0]->created_at,
                'current_prescription'    => $answer,
                /** ADDRESS FISICAL USER  */

                'country'       => (   empty($users[0]->country)        ) ? '' : $users[0]->country, 
                'state'         => (   empty($users[0]->state)          ) ? '' : $users[0]->state, 
                'delegation'    => (   empty($users[0]->delegation)     ) ? '' : $users[0]->delegation, 
                'colony'        => (   empty($users[0]->colony)         ) ? '' : $users[0]->colony, 
                'street'        => (   empty($users[0]->street)         ) ? '' : $users[0]->street, 
                'streetnumber'  => (   empty($users[0]->streetnumber)   ) ? '' : $users[0]->streetnumber, 
                'interiornumber'=> (   empty($users[0]->interiornumber) ) ? '' : $users[0]->interiornumber, 
                'postalcode'    => (   empty($users[0]->postalcode)     ) ? '' : $users[0]->postalcode,
                'longitude'     => (   empty($users[0]->longitude)      ) ? '' : $users[0]->longitude,
                'latitude'      => (   empty($users[0]->latitude)       ) ? '' : $users[0]->latitude,
                'nodes'         => json_encode($nodes)
            ]
        )->with($allhistory)->with($data);
    }
}