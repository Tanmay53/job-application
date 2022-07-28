<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'gender',
        'profile_url'
    ];


    /**
     * Add skills to the Candidate
     *
     * @param array $skill_ids
     */
    public function addSkills(array $skill_ids)
    {
        foreach( $skill_ids as $skill_id )
        {
            DB::table('candidate_has_skills')->insert([
                'candidate_id' => $this->id,
                'skill_id' => $skill_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        return $this;
    }


    /**
     * Add locations to the Candidate
     *
     * @param array $location_ids
     */
    public function addLocations(array $location_ids)
    {
        foreach( $location_ids as $skill_id )
        {
            DB::table('candidate_has_locations')->insert([
                'candidate_id' => $this->id,
                'location_id' => $skill_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        return $this;
    }


    /**
     * Relate to skills
     *
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'candidate_has_skills', 'candidate_id', 'skill_id');
    }


    /**
     * Get skills seperated by ", "
     *
     */
    public function getSkills()
    {
        $skills = $this->skills->pluck('name')->toArray();

        return implode(", ", $skills);
    }


    /**
     * Relate to locations
     *
     */
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'candidate_has_locations', 'candidate_id', 'location_id');
    }


    /**
     * Get skills seperated by ", "
     *
     */
    public function getLocations()
    {
        $locations = $this->locations->pluck('name')->toArray();

        return implode(", ", $locations);
    }
}
