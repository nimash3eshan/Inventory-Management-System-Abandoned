<div class="box box-success" id="paymentTypeTable">
    <div class="box-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="box-title">{{__('Payment Types')}}</h3>
            </div>
            <div class="col-md-6">
                <form class="form-inline" action="{{route('flexiblepossetting.payment_type')}}" method="POST">
                    @csrf
                    <div class="form-group">
                      <label for="name">Payment Type:</label>
                      <input type="text" placeholder="Payment Type Name" class="form-control" id="name" name="name">
                    </div>
                    <button type="submit" class="btn btn-success">Add Payment Type</button>
                  </form>
            </div>
        </div>
    </div>
    <div class="box-body">
        <table class="table">
            <thead>
            <tr>
                <th>Sl</th>
                <th>Payment Types</th>
            </tr>
            </thead>
            <tbody>
            @foreach($payment_types as $type)
                <tr>
                <td>{{$type->id}}</td>
                <td>{{$type->name}}</td>
                <td>{{$type->status==1 ? 'Active' : 'Inactive'}}</td>
                @if($type->status)
                <td class="p-1"><a class="btn btn-sm btn-warning" href="javascript:;" data-ajax-url="{{route('flexiblepossetting.payment_type.update', $type->id)}}">{{__('Make Inactive') }}</a></td>
                @else
                <td class="p-1"><a class="btn btn-sm btn-info" href="javascript:;" data-ajax-url="{{route('flexiblepossetting.payment_type.update', $type->id)}}">{{__('Make Active') }}</a></td>
                @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
  </div>