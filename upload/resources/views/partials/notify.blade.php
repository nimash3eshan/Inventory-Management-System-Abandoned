<script>
@php while(($notify = session()->pull('notify',false))) {  @endphp
	$.notify({!! json_encode($notify[0]['options']) !!},{!! json_encode($notify[0]['settings']) !!});
@php } @endphp
</script>
