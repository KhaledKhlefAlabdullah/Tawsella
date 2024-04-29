<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validateWithBag('updatePassword', [
                'current_password' => ['required', 'current_password'],
                'new_password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            $request->user()->update([
                'password' => Hash::make($validated['new_password']),
            ]);

            if (request()->wantsJson()) {
                return api_response(message: 'تم تغيير كلمة المرور بنجاح');
            }

            return redirect()->back()->with('success', 'تم تحديث كلمة السر');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هناك خطأ' . $e->getMessage());
        }
    }


    public function updateDriverPassword(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            $driver = getAndCheckModelById(User::class, $id);
            $driver->update([
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()->back()->with('success', 'تم تحديث كلمة السر');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('هناك خطأ' . $e->getMessage());
        }
    }
}
