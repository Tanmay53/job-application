<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Http\Requests\StoreCandidateRequest;
use App\Http\Requests\UpdateCandidateRequest;
use App\Models\Location;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->merge([
            'filter_genders' => $request->filter_genders ? explode(",", $request->filter_genders) : [],
            'filter_skills' => $request->filter_skills ? explode(",", $request->filter_skills) : [],
            'filter_locations' => $request->filter_locations ? explode(",", $request->filter_locations) : []
        ]);

        $request->validate(
            [
                'page' => ["nullable", "integer", "min:1"],
                'filter_genders' => ["nullable", "array"],
                'filter_genders.*' => ["in:male,female,other"],
                'filter_skills' => ["nullable", "array"],
                'filter_skills.*' => ["exists:skills,id"],
                'filter_locations' => ["nullable", "array"],
                'filter_locations.*' => ["exists:locations,id"],
                'sort' => ["nullable", "in:gender-desc,gender-asc,updated_at-desc,updated_at-asc"]
            ]
        );

        $query_columns = ['id', 'first_name', 'last_name', 'email', 'gender', 'profile_url', 'deleted_at'];

        $paginator = Candidate::select($query_columns)
        ->when( Auth::user()->can('list deleted candidates'), function ($query) {
            return $query->withTrashed();
        })
        ->when( (count($request->filter_genders) > 0), function ($query) use($request) {
            return $query->whereIn('candidates.gender', $request->filter_genders);
        })
        ->when( (count($request->filter_skills) > 0), function ($query) use($request) {
            return $query->join('candidate_has_skills', 'candidates.id', '=', 'candidate_has_skills.candidate_id')
                            ->whereIn('candidate_has_skills.skill_id', $request->filter_skills);
        })
        ->when( (count($request->filter_locations) > 0), function ($query) use($request) {
            return $query->join('candidate_has_locations', 'candidates.id', '=', 'candidate_has_locations.candidate_id')
                            ->whereIn('candidate_has_locations.location_id', $request->filter_locations);
        })
        ->when( $request->sort, function($query) use($request) {
            [$column, $order] = explode("-", $request->sort);

            return $query->orderBy('candidates.' . $column, $order);
        })
        ->groupBy($query_columns)
        ->paginate(2);

        $current_page = $paginator->currentPage();
        $last_page = $paginator->lastPage();

        $start_page = ($current_page > 2) ? ($current_page - 1) : 1;
        $end_page = ($current_page < ($last_page - 2)) ? ($start_page + 2) : $last_page;

        $candidates = $paginator->items();

        $filter_genders = $request->filter_genders;
        $filter_skills = $request->filter_skills;
        $filter_locations = $request->filter_locations;
        $sort = $request->sort;

        $skills = Skill::all();
        $locations = Location::all();

        return view('dashboard', compact('candidates', 'start_page', 'end_page', 'current_page', 'filter_genders', 'skills', 'filter_skills', 'locations', 'filter_locations', 'sort'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $skills = Skill::select('id', 'name')->get()->toArray();

        $locations = Location::select('id', 'name')->get()->toArray();

        return view('welcome', compact('skills', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCandidateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCandidateRequest $request)
    {
        $profile_url = $request->profile ? $request->profile->store('uploads') : null;

        $candidate = Candidate::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'gender' => $request->gender,
            'profile_url' => $profile_url
        ]);

        $candidate->addSkills($request->skills);

        $candidate->addLocations($request->locations);

        Session::put('job-application-status', 'sent');

        return redirect()->route('jobApplication');
    }


    /**
     * Get Candidate's Profile Image
     *
     * @param Candidate $candidate
     */
    public function profile(int $candidate)
    {
        $candidate = Candidate::when( Auth::user()->can('list deleted candidates'), function ($query) { return $query->withTrashed(); })
                            ->where('id', '=', $candidate)
                            ->first();

        if( $candidate )
        {
            return Storage::response($candidate->profile_url);
        }

        return abort(404);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function show(Candidate $candidate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function edit(Candidate $candidate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCandidateRequest  $request
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCandidateRequest $request, Candidate $candidate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Candidate $candidate)
    {
        $candidate->delete();

        return back();
    }
}
