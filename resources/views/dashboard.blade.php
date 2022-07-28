<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Candidates') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-2 mb-3 d-flex">
                <div class="col-3 d-flex">
                    <div class="col-4">Gender :</div>
                    <select name="filter_genders" id="genderSelect" multiple class="col-8">
                        <option value="male" {{ in_array('male', $filter_genders) ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ in_array('female', $filter_genders) ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ in_array('other', $filter_genders) ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-3 d-flex">
                    <div class="col-4">Skill :</div>
                    <select name="filter_skills" id="skillSelect" multiple class="col-8">
                        @foreach ( $skills as $skill )
                            <option value="{{ $skill->id }}" {{ in_array($skill->id, $filter_skills) ? 'selected' : '' }}> {{ $skill->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 d-flex">
                    <div class="col-4">Locations :</div>
                    <select name="filter_locations" id="locationSelect" multiple class="col-8">
                        @foreach ( $locations as $location )
                            <option value="{{ $location->id }}" {{ in_array($location->id, $filter_locations) ? 'selected' : '' }}> {{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 d-flex">
                    <div class="col-4">Sort :</div>
                    <select name="sort" id="sortSelect" class="col-8">
                        <option value="">None</option>
                        <option value="gender-desc" {{ ('gender-desc' == $sort) ? 'selected' : '' }}> Gender (Descending) </option>
                        <option value="gender-asc" {{ ('gender-asc' == $sort) ? 'selected' : '' }}> Gender (Ascending) </option>
                        <option value="updated_at-desc" {{ ('updated_at-desc' == $sort) ? 'selected' : '' }}> Updated At (Ascending) </option>
                        <option value="updated_at-asc" {{ ('updated_at-asc' == $sort) ? 'selected' : '' }}> Updated At (Descending) </option>
                    </select>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @include('candidates')
            </div>

            <nav class="d-flex justify-content-center py-3">
                <ul class="pagination">

                    <li class="page-item {{ $current_page == $start_page ? 'disabled' : ''}}">
                        <a class="page-link" data-page="{{ $start_page }}">Previous</a>
                    </li>

                    @for ($page = $start_page; $page <= $end_page; $page++)
                        <li class="page-item {{ $current_page == $page ? 'active' : '' }}">
                            <a class="page-link" data-page="{{ $page }}">{{ $page }}</a>
                        </li>
                    @endfor

                    <li class="page-item {{ $current_page == $end_page ? 'disabled' : ''}}">
                        <a class="page-link" data-page="{{ $end_page }}">Next</a>
                    </li>
                </ul>
            </nav>
            {{-- <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

            </div> --}}
        </div>
    </div>

    @section('scripts')

    <script>
        $(function() {
            $("#genderSelect, #skillSelect, #locationSelect, #sortSelect").chosen();

            $("#genderSelect").on("change", function(event) {
                addQuery('filter_genders', $(this).val());
            });

            $("#skillSelect").on("change", function(event) {
                addQuery('filter_skills', $(this).val());
            });

            $("#locationSelect").on("change", function(event) {
                addQuery('filter_locations', $(this).val());
            });

            $(".page-link").on("click", function(event) {
                addQuery('page', $(this).data('page'));
            });

            $("#sortSelect").on("change", function(event) {
                addQuery('sort', $(this).val());
            });

            function addQuery(key, value)
            {
                if ('URLSearchParams' in window) {
                    var searchParams = new URLSearchParams(window.location.search);
                    searchParams.set(key, value);
                    window.location.search = searchParams.toString();
                }
            }
        });
    </script>
    @endsection
</x-app-layout>
