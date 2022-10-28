<div class="row">
    <form class="form-inline custom-filter" action="{{$filter_route}}" method="GET" id="{{$filter_id}}">
        <div class="col-md-12">
            <div class="form-group pr-2">
              <label class="pr-1" for="per_page">{{__('Show')}} </label>
              <select autocomplete="off" class="form-control" name="filter[per_page]" id="per_page"  onchange="$('#{{$filter_id}}').submit()">
                  <option value="10">10 entries</option>
                  <option value="25">25 entries</option>
                  <option value="50">50 entries</option>
                  <option value="100">100 entries</option>
              </select>
            </div>
            @if($filter_id == 'dailyreportFilter' || $filter_id == 'saleFilter' || $filter_id == 'receivingFilter')
            <div class="form-group pr-2">
                <label class="pr-1">{{__('From')}} </label>
                <input autocomplete="off" type="text" name="filter[start_date]" id="start_date" class="form-control" onchange="$('#{{$filter_id}}').submit()" />
            </div>
            <div class="form-group pr-2">
                <label class="pr-1" for="EndDate">{{__('To')}} </label>
                <input autocomplete="off" type="text" name="filter[end_date]" id="end_date" class="form-control" onchange="$('#{{$filter_id}}').submit()" />
            </div>
            @else
            <div class="form-group pull-right">
                <label class="pr-1" for="search">{{__('Search')}} </label>
                <input autocomplete="off" type="text" name="filter[search]" id="search" class="form-control" onkeyup="submitOnEnter('#{{$filter_id}}')" />
            </div>
            @endif
            @if($filter_id == 'saleFilter')
            <div class="form-group pr-2">
                <label class="pr-1" for="customer">{{__('Customers')}} </label>
                <select autocomplete="off" class="form-control select2" name="filter[customer]" id="customer"  onchange="$('#{{$filter_id}}').submit()">
                    <option value="">Select Customer</option>
                    @foreach($customers as $key=>$value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </div>
            @endif
            @if($filter_id == 'receivingFilter')
            <div class="form-group pr-2">
                <label class="pr-1" for="supplier">{{__('Suppliers')}} </label>
                <select autocomplete="off" class="form-control select2" name="filter[supplier]" id="supplier"  onchange="$('#{{$filter_id}}').submit()">
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $key=>$value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>
    </form>
  </div>