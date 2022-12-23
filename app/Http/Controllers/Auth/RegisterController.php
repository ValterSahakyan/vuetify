<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\PhoneBook;
use App\Models\UserCountry;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $valid = $this->validator($request->all());

        if($valid->errors()->count()){
            return response()->json([
                'success' => 'error',
                'errors' => $valid->errors()
            ]);
        }

        event(new Registered($user = $this->create($request->all())));
        /*$this->guard()->login($user);*/

        if ($response = $this->registered($request, $user)) {
            return $response;
        }
        $basic  = new Basic("91e19db5", "lIMeDxoOgZwL0DUB");
        $client = new Client($basic);
        $response = $client->sms()->send(
            new SMS($request->phone_index.$request->phone, 'ahaaaaaaay', 'A text message sent using the Nexmo SMS API')
        );
        Mail::send([], [], function ($message) use ($request)
        {
            $message->to($request->email);
            $message->subject('subject');
            $message->setBody('body');
        });

        $message = $response->current();

        if ($message->getStatus() == 0) {
            echo "The message was sent successfully\n";
        } else {
            echo "The message failed with status: " . $message->getStatus() . "\n";
        }

        return $request->wantsJson()
            ? new JsonResponse(['success' => 'success'], 201)
            : redirect($this->redirectPath());
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
            'phone_index' => ['required','string','max:10'],
            'phone' => ['required','string','max:20'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        PhoneBook::create([
            'user_id' => $user->id,
            'phone'=> $data['phone']
        ]);
        UserCountry::create([
            'user_id' => $user->id,
            'country_id' => $data['country']
        ]);
        return $user;

    }
}
