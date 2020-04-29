<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    <div class="card">
        @include($class->views['header'], ['header_class' => 'border-bottom-0'])

        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                    <tbody>
                    @if($deals->count())
                    @foreach($deals as $deal)
                        <tr class="row border-top-1">
                            <td class="text-left">
                                <a href="{{url('crm/deals/'.$deal->id)}}"> {{$deal->title}}</a>
                            </td>
                        </tr>
                    @endforeach
                    @else
                        <h5 class="text-center">{{ trans('general.no_records') }}</h5>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
