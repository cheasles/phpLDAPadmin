@if($o->is_rdn)
	<span class="btn btn-sm btn-outline-focus mt-3"><i class="fas fa-fw fa-exchange"></i> @lang('Rename')</span>
@elseif($edit && $o->can_addvalues)
	<div class="p-0 m-0">
		<span class="btn btn-sm btn-outline-primary mt-3 addable @if(! $new)d-none @endif" id="{{ $o->name_lc }}"><i class="fas fa-fw fa-plus"></i> @lang('Add Value')</span>
		@if($new)
			<script type="text/javascript">
				$(document).ready(function() {
					// Create a new entry when Add Value clicked
					$('#{{ $o->name_lc }}.addable').click(function (item) {
						var cln = $(this).parent().parent().find('input:last').clone();
						cln.val('').attr('placeholder', '[@lang('NEW')]');
						cln.appendTo('#' + item.currentTarget.id)
					});
				});
			</script>
		@endif
	</div>
@endif

@section('page-scripts')
	@if(($edit && $o->can_addvalues))
		<script type="text/javascript">
			$(document).ready(function() {
				// Create a new entry when Add Value clicked
				$('#{{ $o->name_lc }}.addable').click(function (item) {
					var cln = $(this).parent().parent().find('input:last').clone();
					cln.val('').attr('placeholder', '[@lang('NEW')]');
					cln.appendTo('#' + item.currentTarget.id)
				});
			});
		</script>
	@endif
@append