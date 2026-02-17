<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Exception;
use App\User;
use App\Patient;
use App\MedicalInfo;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if (Sentinel::guest() == false) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i|max:50',
            'password' => 'required|max:50'
        ]);
        try {
            $remember = $request->remember == 'On' ? true : false;
            $user = Sentinel::authenticate($validatedData, $remember);
            if ($user) {
                // Los pacientes ya no se crean como usuarios, por lo que no necesitamos verificar perfil
                return redirect('/dashboard');
            } else {
                return redirect()->back()->with('error', 'Email o contraseña incorrectos');
            }
        } catch (NotActivatedException $e) {
            return redirect()->back()->with('error', 'Tu cuenta ha sido desactivada. Contacta con soporte para más detalles.');
        } catch (ThrottlingException $e) {
            $second = $e->getDelay();
            $delay = gmdate('i', $second);
            return redirect('login')->with('error', 'No puedes iniciar sesión durante los próximos ' . $delay . ' minutos.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Algo salió mal: ' . $e->getMessage());
        }
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Sentinel::logout(null, true);
        session()->flush();
        return redirect('login');
    }

    /**
     * Show the application's registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        // La autoregistración de pacientes ha sido deshabilitada
        // Los pacientes son creados solo por administradores/receptcionistas
        return redirect('login')->with('error', 'El auto-registro no está disponible. Contacta con administración.');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // La autoregistración de pacientes ha sido deshabilitada
        return redirect('login')->with('error', 'El auto-registro no está disponible. Contacta con administración.');
    }

    /**
     * Show the application's forgot password form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showForgotPasswordForm()
    {
        return view('auth.passwords.forgotPassword');
    }

    /**
     * Handle a forgot password request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i|max:50'
        ]);
        try {
            $user = User::whereEmail($validatedData)->first();
            if ($user == null) {
                return redirect()->back()->with('error', 'Email not Found!!!');
            }
            $user = Sentinel::findById($user->id);
            $reminder = Reminder::exists($user) ? null : Reminder::create($user);
            if ($reminder == null) {
                return redirect()->back()->with('error', 'Reset Password email is already sent to your registered email...');
            } else {
                if ($this->sendMail($user, $reminder->code)) {
                    return redirect()->back()->with('success', 'Reset Password email sent to your registered email...');
                } else {
                    return redirect()->back()->with('error', 'Something went wrong!!! Please try again...');
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!!! ' . $e->getMessage());
        }
    }

    /**
     * Handle a email sending request for the application.
     *
     * @param App\User $user
     * @param  Reminder->code $code
     * @return \Illuminate\Http\Response
     */
    public function sendMail($user, $token)
    {
        Mail::send(
            'auth.passwords.email',
            ['user' => $user, 'token' => $token],
            function ($message) use ($user) {
                $message->to($user->email);
                $message->subject("Reset Password Request");
            }
        );
        return true;
    }

    /**
     * Show the application's reset password form.
     *
     * @param $email
     * @param $code
     * @return \Illuminate\Http\Response
     */
    public function showResetPasswordForm($user_id, $token)
    {
        $user = User::find($user_id);
        if ($user == null) {
            return redirect('login')->with('error', 'User not found in our database!!! Please try again!!!');
        } else {
            $reminder = Reminder::exists($user, $token);
            if ($reminder) {
                return view('auth.passwords.reset')->with(['user' => $user, 'token' => $token]);
            } else {
                return redirect('forgot-password')->with('error', 'Password reset link expired!!! Please try again!!!');
            }
        }
    }

    /**
     * Handle a reset password request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|max:50'
        ]);
        try {
            $user = Sentinel::findById($request->user_id);
            $reminder = Reminder::exists($user, $request->token);
            if ($reminder) {
                Reminder::complete($user, $request->token, $request->password);
                Sentinel::logout(null, true);
                return redirect('login')->with('success', 'Password reset successfully. Please login with new password.');
            } else {
                return redirect('forgot-password')->with('error', 'Password reset link expired!!! Please try again!!!');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!!! ' . $e->getMessage());
        }
    }

    /**
     * Show the application's change password form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showChangePasswordForm()
    {
        $user = Sentinel::getUser();
        if($user){
            return view('auth.passwords.changePassword');
        }
        else {
            return redirect('login')->with('error','Please Login');
        }
    }

    /**
     * Handle application's change password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {

        $request->validate([
            'oldpassword' => 'required',
            'password' => 'required|min:8|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|max:50'
        ],
        [
            'oldpassword.required'=>'The current password field is required',
        ]);
        try {
            $hasher = Sentinel::getHasher();
            $user = Sentinel::getUser();
            if (!$hasher->check($request->oldpassword, $user->password)) {
                return redirect()->back()->with('error', 'Old password doesn\'t match!!!');
            } else {
                Sentinel::update($user, array(
                    'password' => $request->password
                ));
                Sentinel::logout(null, true);
                return redirect('/dashboard')->with('success', 'Password successfully changed.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!!! ' . $e->getMessage());
        }
    }
}
