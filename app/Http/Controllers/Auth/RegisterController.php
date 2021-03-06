<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Specialty;
use App\Role;
use App\Models\Officer;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
        $this->middleware(['auth', 'auth.admin']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required'],
            'date_in_position' => ['required', 'before:tomorrow'],
            'specialtiess' => ['array'],
            'specialtiess.*' => ['max:255'],
            'notes' => ['max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'unit_number' => ['nullable', 'numeric', 'min:0'],
            'emergency_number' => ['nullable', 'numeric', 'min:0'],
            'vehicle_number' => ['nullable', 'numeric', 'min:0'],
            'zone' => ['nullable', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'date_in_position' => $data['date_in_position'],
            'notes' => $data['notes'],
            'password' => Hash::make($data['password']),
        ]);

                
        if($data['unit_number'] || $data['emergency_number'] || $data['vehicle_number'] || $data['zone']) {      
            $officer = Officer::create([
                'user_id' => $user->id,
                'unit_number' => $data['unit_number'],
                'emergency_number' => $data['emergency_number'],
                'vehicle_number' => $data['vehicle_number'],
                'zone' => $data['zone']
            ]);
        }

        // attach the role
        $user->roles()->attach($data['role']);

        // attach all the specialties
        foreach($data['specialtiess'] as $specialty){
            $user->specialties()->attach($specialty);
        }
        
        return $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        // get all roles expet root.
        $roles = Role::where('name', '!=', 'root')->get();
        $specialties = Specialty::all();

        return view('auth.register')->with([
            'roles' => $roles, 
            'specialties' => $specialties
        ]);
    }

    /**
     * Handle a registration request for the application.
     * Modified/overwriten to prevent auto-login after registration
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        //$this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
