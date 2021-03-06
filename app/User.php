<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use \Storage;

class User extends Authenticatable
{
    
    use Notifiable;
    const  IS_BANNED = 1;
    const  IS_ACTIVE = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);   //Один пользователь может иметь множество статей
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);   //Один пользователь может иметь множество комментарий
    }

    public static function add($fields)
    {
        $user = new static;
        $user->fill($fields);
        $user->save();

        return $user;
    }

    public function edit($fields)
    {
        $this->fill($fields);                    
        $this->save();
    }
    public function generatePassword($password)
    {
        if($password != null){             //для того, чтобы в БД при не изменении pw, пароль не меняльса 
            $this->password = bcrypt($password);
            $this->save();
            }
    }

    public function remove()
    {
        Storage::delete('uploads/'. $this->avatar);
        $this->delete();
    }

    public function uploadAvatar($image)
    {
        if($image == null){return;}
        if($this->avatar != null)
        {
            Storage::delete('uploads/'. $this->avatar);
        }
        $filename = str_random(10).'.'.$image->extension(); //строка,и ее расширение
        $image->storeAs('uploads',$filename);
        $this->avatar = $filename;// в $image закидываем значение из filename
        $this->save();
        //не забыть зайти в config-> filesystem и в disks заменить public_path(),
    }

    public function getImage()
    {
        if($this->avatar == null)
        {
            return '/img/no-image.png';
        }
        return '/uploads/'.$this->avatar;
    }

    public function makeAdmin()
    {
        $this->is_admin  = 1;
        $this->save();
    }

    public function makeNormal()
    {
        $this->is_admin  = 0;
        $this->save();
    }

    public function toggleAdmin($value)
    {
        if($value == null)
        {
           return $this->makeNormal();
        }
           return $this->makeAdmin();
    }

    public function ban()
    {
        $this->staus = User::IS_BANNED;
        $this->save();
    }

    public function unban()
    {
        $this->staus = User::IS_ACTIVE;
        $this->save();
    }

    public function toggleBan($value)
    {
        if($value == null)
        {
            return $this->unban();
        }
            return $this->ban();
    }
}
