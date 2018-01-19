<div class="card-footer {{ (!$model->deleted_at) ? 'bg-danger text-white' : '' }}">
	<div class="row">
		<div class="col text-left">
			<i class="fa fa-plus-circle fa-fw"></i> <b>@include('snippets.dateTime', ['date' => $model->dateLoc('created_at'), 'no_date' => true, 'text' => 'Зарегистрирован'])</b>
		</div>

		@if ($model->deleted_at)
			<div class="col text-center">
				<i class="fa fa-trash fa-fw"></i> <b>@include('snippets.dateTime', ['date' => $model->dateLoc('deleted_at'), 'no_date' => true, 'text' => 'Помечен на удаление'])</b>
			</div>
		@endif

		<div class="col text-right">
			<i class="fa fa-pencil-square fa-fw"></i> <b>@include('snippets.dateTime', ['date' => $model->dateLoc('updated_at'), 'no_date' => true, 'text' => 'Последнее изменение'])</b>
		</div>
	</div>
</div>
