<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all of the Posts for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Posts()
    {
        return $this->hasMany(Post::class);
    }


    //Relaciones de amistad
    /**
     * The from that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function from()
    {
        return $this->belongsToMany(User::class, 'friends', 'from_id', 'to_id');
    }

    /**
     * The 'to' that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function to()
    {
        return $this->belongsToMany(User::class, 'friends', 'to_id', 'from_id');
    }

    //amistad: union entre las solicitudes enviadas y recibidas aceptadas
    public function friends(){
        return $this->friendsFrom->merge($this->friendsTo);
    }

    //consultar si hay una relacion
    public function isRelated(User $user) {
        if (auth()->user()->id === $user->id) {
            return true;
        }
        
        return $this->from()->where('to_id', $user->id)->exists() 
        || $this->to()->where('from_id', $user->id)->exists();
    }

    //solicitudes aceptadas 
    /**
     * The friendsFrom that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function friendsFrom()
    {
        return $this->from()->wherePivot('accepted',true);
    }

    /**
     * The friendsTo that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function friendsTo()
    {
        return $this->to()->wherePivot('accepted',true);
    }

    //solicitudes pendientes
    /**
     * The friendsFrom that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pendingFrom()
    {
        return $this->from()->wherePivot('accepted',false);
    }

    /**
     * The friendsTo that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pendingTo()
    {
        return $this->to()->wherePivot('accepted',false);
    }
}
