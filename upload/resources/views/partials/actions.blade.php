<div class="btn-group action-btn-group">
    <button type="button" class="btn action-btn dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-cog" aria-hidden="true"></i><span class="sr-only">Toggle Dropdown</span></button>
    <ul class="dropdown-menu" role="menu">
        @foreach($actions as $action)
            @if($action['name'] == 'delete')
                <li><a href="#" class="delete-form" onclick="return confirm('are you sure?')"><i class="fa fa-trash-o"></i>{{ Form::open(array('url' => $action['url'], 'class' => 'form-inline')) }}
                {{ Form::hidden('_method', 'DELETE') }}
                {{ Form::submit(trans('item.delete'), array('class' => 'delete-btn')) }}
                {{ Form::close() }}</a></li>
            @else
                <li><a href="{{$action['url']}}" 
                    @if(!empty($action['data-replace'])) data-replace-empty="{{$action['data-replace']}}" @endif
                    @if(!empty($action['ajax-url'])) data-ajax-url="{{$action['ajax-url']}}" data-toggle="modal" @endif
                 ><i class="fa fa-{{!empty($action['icon']) ? $action['icon'] : 'eye'}}"></i>{{$action['name']}}</a></li>
            @endif
        @endforeach
    </ul>
  </div>