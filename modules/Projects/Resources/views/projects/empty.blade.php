<div class="card">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-md-6 text-center p-5">
                <img src="{{ asset('modules/Projects/Resources/assets/img/projects.png') }}" height="300" alt="@yield('title')"/>
            </div>

            <div class="col-md-6 text-center p-5">
                <p class="text-justify description">{!! trans('projects::general.empty.projects') !!}</p>
                <a href="{{ route('projects.projects.create') }}" class="btn btn-success btn-lg btn-icon header-button-top float-right mt-4">
                    <span class="btn-inner--icon text-white"><i class="fas fa-plus"></i></span>
                    <span class="btn-inner--text text-white">{{ trans('general.title.create', ['type' => trans_choice('projects::general.project', 1)]) }}</span>
                </a>
            </div>

        </div>
    </div>
</div>
