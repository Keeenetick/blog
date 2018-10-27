<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'name', 'email', 'password',
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
        $user->password = bcryt($fields['password']);
        $user->save();

        return $user;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->password = bcryt($fields['password']);
        $this->save();
    }

    public function remove()
    {
        $this->destroy();
    }

    public function uploadAvatar($image)
    {
        if($image == null){return;}
        Storage::delete('uploads/'. $this->image);
        $filename = str_random(10).'.'.$image->extension(); //строка,и ее расширение
        $image->saveAs('uploads',$filename);
        $this->image = $filename;// в $image закидываем значение из filename
        $this->save();
        //не забыть зайти в config-> filesystem и в disks заменить public_path(),
    }

    public function getImage()
    {
        if($this->image == null)
        {
            return '/img/no-user-image.png';
        }
        return '/uploads/'.$this->image;
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
