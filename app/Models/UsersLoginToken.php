<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class UsersLoginToken extends Model
{
    protected $table= 'users_logins_tokens';
    protected $fillable= [
        'token'
    ];
    public function getRouteKeyName()
    {   
        return 'token';
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
