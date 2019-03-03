<?php

namespace App\Controller;

use Exception;
use Illuminate\Database\QueryException;
use JustTheBasicz\Controller;
use App\Model\User;

class UserController extends Controller
{

    public function get($userId)
    {
        $user = User::where('id', $userId)->first();
        if ($user) {
            return $this->renderJSON($user);
        }
        return $this->renderJSON(array("error" => "User not found."), 400);
    }

    public function getAll()
    {
        return $this->renderJSON(User::all());
    }

    public function create()
    {
        try {
            $user = new User;
            $this->setUserValues($user);
            return $this->renderJSON($user);
        } catch (QueryException $e) {
            $message = $e->errorInfo;
            return $this->renderJSON(array("error" => "Unable to create user.", "message" => $message[2]), 400);
        }
    }

    public function update($userId)
    {
        try {
            $user = User::where('id', $userId)->first();
            if (!$user) {
                return $this->renderJSON(array("error" => "User not found."), 400);
            }
            $this->setUserValues($user);
            return $this->renderJSON($user);
        } catch (QueryException $e) {
            $message = $e->errorInfo;
            return $this->renderJSON(array("error" => "Unable to update user.", "message" => $message[2]), 400);
        }
    }

    private function setUserValues(User $user)
    {
        $user->first_name = $this->data['first_name'];
        $user->last_name = $this->data['last_name'];

        $email = $this->data['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->renderJSON(array("error" => "Invalid email."), 400);
        }
        $user->email = $email;

        $user->password = $this->data['password'];
        $user->save();
    }

    public function uploadAvatar($userId)
    {
        $user = User::where('id', $userId)->first();
        if (!$user) {
            return $this->renderJSON(array("error" => "User not found."), 400);
        }

        // Should probably make a FileManager singleton that handles this
        $targetFile = IMAGES_ROOT . basename($_FILES["avatar"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            return $this->renderJSON(array("error" => "Must be PNG, JPEG, PNG or GIF file type."), 400);
        }

        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile)) {
            $user->avatar_path = $targetFile;
            $user->save();
            return $this->renderJSON(array("message" => "Upload successful."));
        }

        return $this->renderJSON(array("error" => "Unable to upload file."), 400);
    }

    public function delete($userId)
    {
        try {
            User::where('id', $userId)->delete();
            return $this->renderJSON(array("message" => "User deleted."));
        } catch (Exception $e) {
            return $this->renderJSON(array("error" => "Unable to delete user.", "message" => $e->getMessage()), 400);
        }
    }
}
