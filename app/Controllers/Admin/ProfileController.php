<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileController extends BaseController
{

    public function index()
    {
        $users = auth()->user();
       
        
        $data = [
            
            "username"=> $users->username,
            "email" => $users->email,
            "role"=> $users->getGroups(),
            "title"=> "Profile"
        ];
        return view('admin/profile/index', $data);
    }

    public function updateProfile()
    {
        // Check if user is authenticated
        if (!auth()->loggedIn()) {
            return redirect()->to('login');
        }

        $user = auth()->user();
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');

        // Validate input
        if (empty($username) || empty($email)) {
            return redirect()->back()->with('error', 'Username and email are required.');
        }

        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Invalid email address.');
        }

        // Update user data
        $userProvider = auth()->getProvider();
        $user->username = $username;
        $user->email = $email;

        if ($userProvider->save($user)) {
            return redirect()->back()->with('success', 'Profile updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update profile. Please try again.');
        }
    }

    public function changePassword()
    {
        // Check if user is authenticated
        if (!auth()->loggedIn()) {
            return redirect()->to('login');
        }

        $user = auth()->user();
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validate input
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            return redirect()->back()->with('error', 'All password fields are required.');
        }

        // Check if new passwords match
        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'New password and confirmation do not match.');
        }

        // Check password length
        if (strlen($newPassword) < 8) {
            return redirect()->back()->with('error', 'Password must be at least 8 characters long.');
        }

        // Verify current password
        if (!password_verify($currentPassword, $user->password_hash)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        // Update password
        $userProvider = auth()->getProvider();
        $user->password = $newPassword;

        if ($userProvider->save($user)) {
            return redirect()->back()->with('success', 'Password changed successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to change password. Please try again.');
        }
    }
}
