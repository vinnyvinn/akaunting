<thead>
<tr>
    <th class="col-md-2 text-center">{{ trans('crm::general.done') }}</th>
    <th class="col-md-2 text-center">{{ trans_choice('general.types',1) }}</th>
    <th class="col-md-2 text-center">{{ trans('crm::general.field_title') }}</th>
    <th class="col-md-2 text-center">{{ trans('general.date') }}</th>
    <th class="col-md-2 text-center">{{ trans('crm::general.assigned') }}</th>
    <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
</tr>
</thead>
<tbody>
@foreach($activities as $item)
    <tr>
        <td class="text-center">
            <a href="#" id="button-done-{{ $item->id}}" data-action="{{ route('crm.deals.activity.done',$item->id) }}" class="activity-done btn btn-default btn-xs">
                {{trans('crm::general.done')}}
            </a>
        </td>
        <td>{{ $item->activity_type}}</td>
        <td>{{ $item->title}}</td>
        <td>{{ $item->date}}</td>
        <td>{{ $item->getAssign($item->assigned)->name }}</td>
        <td class="text-center">
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                    <i class="fa fa-ellipsis-h"></i>
                </button>
                {{-- <ul class="dropdown-menu dropdown-menu-right">
                     <li><a href="#" data-action="{{ route('crm.get.contact.activity.edit', [$line['activity'], $line['id']]) }}">{{ trans('general.edit') }}</a></li>
                     <li class="divider"></li>
                     <li><a href="#" data-action="{{ route('crm.get.contact.activity.edit', [$line['activity'], $line['id']]) }}">{{ trans('general.delete') }}</a></li>
                 </ul>--}}
            </div>
        </td>
    </tr>
@endforeach
</tbody>