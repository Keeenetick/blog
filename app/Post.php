<?php

namespace App;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sluggable;
    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;
    protected $fillable = ['title','content'];
    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function author()
    {
        return $this->hasOne(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'post_tags',            //Таблица для связи с тегами
            'post_id', 
            'tag_id'                                         
    
    );
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function add($fields)
    {
        $post = new static;
        $post->fill($fields);   //Заполняется данными из fillable
        $post->user_id = 1;    // Что бы пользов. не прописал свои значения от имени админа
        $post->save();

        return $post;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove()
    {
        Storage::delete('uploads/'. $this->image);
        $this->destroy();
    }

    public function uploadImage($image)
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
            return '/img/no-image.png';
        }
        return '/uploads/'.$this->image;
    }
    public function setCatgory($id)// установка катогорий
    {
        if($id == null){return;}
        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids)  //установка тегов 
    {
        if($ids == null){return;}
        $this->tags()->sync($ids); // синхронизирум статью с данными $ids,
    }

    public function setDraft()   //Черновик
    {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

    public function setPublic()  //Опубликовано
    {
        $this->status = Post::IS_PUBLIC;
        $this->save();
    }

    public function toggleStatus($value)
    {
        if($value == null)
        {
            return $this->setDraft();
        }
        else
        {
            return $this->setPublic();
        }
    }
      
    public function setFeatured()   
    {
        $this->is_featured = 1;
        $this->save();
    }

    public function setStandart()  
    {
        $this->is_featured = 0;
        $this->save();
    }

    public function toggleFeatured($value)
    {
        if($value == null)
        {
            return $this->setStandart();
        }
        else
        {
            return $this->setFeatured();
        }
    }

    
}
