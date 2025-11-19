<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Base
{
    protected $table = 'notes';
	public $timestamps = true;

	use HasFactory,SoftDeletes;

	protected $dates = ['deleted_at','created_at','updated_at'];
	protected $fillable = array('content', 'note_title_id', 'notable_id', 'notable_type','user_id','updated_at');
    // protected $appends =  ['since', 'user_name'];


    public function notable(){
        return $this->morphTo();
    }

    public function note_title(){
        return $this->belongsTo(NoteTitle::class);
    }

    public function user() {
        return $this->belongsTo(User::class);//->first();
    }

    public function getContentAttribute(){
        $title = optional($this->note_title)->title;
        $content = $this->attributes['content'] ?? '';

        return $title . $content;
    }

    public function getSinceAttribute(){
        return $this->created_at->diffForHumans();
    }

    public function getUserNameAttribute(){
        return $this->user()->value('name') ?? null;
    }
}