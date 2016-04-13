{{--

	This template serves solely for handing over variables from stepped cart

--}}
@if ( isset($handover) )
	@foreach( $handover as $key => $value )

		@if ( is_array($value) )

			@foreach ( $value as $subkey => $subvalue )
				
				@if ( is_array($subvalue) ) 
					@foreach ( $subvalue as $subsubkey => $subsubvalue )
						<input type="hidden" name="{{ $key }}[{{ $subkey }}][{{ $subsubkey }}]" value="{{ $subsubvalue }}">
					@endforeach
				@else
					<input type="hidden" name="{{ $key }}[{{ $subkey }}]" value="{{ $subvalue }}">
				@endif

			@endforeach

		@else

			<input type="hidden" name="{{ $key }}" value="{{ $value }}">

		@endif


	@endforeach
@endif