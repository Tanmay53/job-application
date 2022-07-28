<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Profile</th>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Sills</th>
            <th>Preferred Locations</th>
            @can('delete candidates')
                <th>Actions</th>
            @endcan
        </tr>
    </thead>
    <tbody>
        @foreach ( $candidates as $candidate )
            <tr class="{{ $candidate->deleted_at ? 'table-danger' : '' }}">
                <td>{{ $candidate->id }}</td>
                <td> <img src="{{ route('candidatProfile', ['candidate' => $candidate->id]) }}" alt="Profile Image" class="profile-image"> </td>
                <td>{{ $candidate->first_name . " " . $candidate->last_name }}</td>
                <td>{{ $candidate->email }}</td>
                <td>{{ Str::ucfirst($candidate->gender) }}</td>
                <td>{{ $candidate->getSkills() }}</td>
                <td>{{ $candidate->getLocations() }}</td>
                @can('delete candidates')
                    <td>
                        @if ( !$candidate->deleted_at )
                            <form action="{{ route('deleteCandidate', ['candidate' => $candidate->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        @endif
                    </td>
                @endcan
            </tr>
        @endforeach
    </tbody>
</table>
