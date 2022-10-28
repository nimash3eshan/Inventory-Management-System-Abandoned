<div class="box box-success" id="mailSettings">
    <div class="box-header">
        <h3 class="box-title">{{__('Mail Settings')}}</h3>
    </div>
<div class="box-body" >
    {{ Form::open(array('route' => 'flexiblepossetting.store_settings', 'files' => true,)) }}
    <div class="col-md-6">
        <div class="form-group row">
            {{ Form::label('mail_driver', __('Mail Driver'), ['class'=>'col-sm-3']) }}
            <div class="col-sm-8"> 
            {{ Form::text('mail_driver', setting('mail_driver'), array('class' => 'form-control', 'required')) }}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('mail_host', __('Mail Host'), ['class'=>'col-sm-3']) }}
            <div class="col-sm-8"> 
                {{ Form::text('mail_host', setting('mail_host'), array('class' => 'form-control', 'required')) }}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('mail_port', __('Mail Port'), ['class'=>'col-sm-3']) }}
            <div class="col-sm-8"> 
            {{ Form::text('mail_port', setting('mail_port'), array('class' => 'form-control', 'required')) }}
            </div>
        </div>
  </div>

  <div class="col-md-6">
      <div class="form-group row">
            {{ Form::label('mail_username', __('Mail Username'), ['class'=>'col-sm-3']) }}
            <div class="col-sm-8"> 
                {{ Form::text('mail_username', setting('mail_username'), array('class' => 'form-control', 'required')) }}
            </div>
      </div>
      <div class="form-group row">
          {{ Form::label('mail_password', __('Mail Password'), ['class'=>'col-sm-3']) }}
          <div class="col-sm-8"> 
            {{ Form::text('mail_password', setting('mail_password'), array('class' => 'form-control', 'required')) }}
          </div>
      </div>
      <div class="form-group row">
          {{ Form::label('mail_encryption', __('Mail Encryption'), ['class'=>'col-sm-3']) }}
          <div class="col-sm-8"> 
            {{ Form::text('mail_encryption', setting('mail_encryption'), array('class' => 'form-control', 'required')) }}
          </div>
         
      </div>
      <div class="row">
          <div class="col-sm-12 text-right">
              {{ Form::submit(__('Submit'), array('class' => 'btn btn-success btn-sp')) }}
          </div>
      </div>
  </div>
  {{ Form::close() }}
</div>
</div>