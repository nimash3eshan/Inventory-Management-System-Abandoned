@if(!empty($items->firstItem()))
<div class="row">
    <div class="col-sm-6">
      Showing items {{$items->firstItem()}} to {{$items->lastItem()}} out of {{$items->total()}}
    </div>
    <div class="col-sm-6">
      <div class="pull-right">
        <ul class="pagination">
          @if($items->currentPage() > 1)
          <li><a href="javascript:;" data-ajax-url="{{$index_route}}?page={{$items->currentPage() - 1}}"><i class='fa fa-angle-double-left'></i> prev</a></li>
          @else
          <li class="disabled"><a href="javascript:;"><i class='fa fa-angle-double-left'></i> prev</a></li>
          @endif
          @for($i=1;$i<=$items->lastPage(); $i++)
            @if($items->currentPage() == $i) 
            <li class="active hidden-xs"><a href="javascript:;">{{$i}}</a></li>
            @else
            <li class="hidden-xs"><a href="javascript:;" data-ajax-url="{{$index_route}}?page={{$i}}">{{$i}}</a></li>
            @endif
          @endfor
          @if($items->currentPage() < $items->lastPage())
          <li><a href="javascript:;" data-ajax-url="{{$index_route}}?page={{$items->currentPage() + 1}}">next <i class='fa fa-angle-double-right'></i></a></li>
          @else
          <li class="disabled"><a href="javascript:;">next <i class='fa fa-angle-double-right'></i></a></li>
          @endif
        </ul>
      </div>
    </div>
  </div>
@endif