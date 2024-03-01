<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource {
	public static function label() {
		return 'Usuarios';
	}

	public static $group = 'Colaboradores';

	public static function indexQuery(NovaRequest $request, $query) {
		if ($request->user()->hasPermissionTo('view all users')) {
			return $query;
		}

		if ($request->user()->hasPermissionTo('view own users')) {
			return $query->where('cdr_id', $request->user()->cdr_id);
		}
	}

	public function authorizedToUpdate(Request $request): bool {

		if ($request->user()->hasPermissionTo('edit users')) {
			return true;
		}

		if ($request->user()->hasPermissionTo('edit own users')) {
			return $this->cdr_id == $request->user()->cdr_id;
		}

		return $request->user()->hasPermissionTo('edit own users');
	}

	public function authorizedToDelete(Request $request): bool {

		if ($request->user()->hasPermissionTo('delete users')) {
			return true;
		}

		if($request->user()->hasPermissionTo('delete own users')) {
			return $this->cdr_id == $request->user()->cdr_id;
		}

		return $request->user()->hasPermissionTo('delete own users');
	}

	public function authorizedToRestore(Request $request): bool {

		if ($request->user()->hasPermissionTo('restore users')) {
			return true;
		}

		if($request->user()->hasPermissionTo('delete own users')) {
			return $this->cdr_id == $request->user()->cdr_id;
		}
	}

	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\User::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'name';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id', 'name', 'email',
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function fields(Request $request) {
		return [
			ID::make()->sortable(),

			//Gravatar::make()->maxWidth(50),

			Text::make('Name')
				->sortable()
				->rules('required', 'max:255'),

			BelongsTo::make('CDR', 'cdr', 'App\Nova\Cdr')
				->filterable()
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),

			Text::make('Email')
				->sortable()
				->rules('required', 'email', 'max:254')
				->creationRules('unique:users,email')
				->updateRules('unique:users,email,{{resourceId}}'),

			DateTime::make('Verificado', 'email_verified_at'),

			Boolean::make('Súper', 'is_super')
				->canSee(function ($request) {
					return $request->user()->is_super;
				}),

			Boolean::make('Administrador', 'is_admin')
				->canSee(function ($request) {
					return $request->user()->is_super;
				}),

			Boolean::make('CDR', 'is_cdr')
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),

			Boolean::make('Administrador de CDR', 'is_cdr_admin')
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),

			Boolean::make('Entidad Asociada', 'is_associated')
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),

			Boolean::make('Entidad Colaboradora', 'is_collaborator')
				->canSee(function ($request) {
					return $request->user()->is_admin;
				}),

			Password::make('Password')
				->onlyOnForms()
				->creationRules('required', 'string', 'min:8')
				->updateRules('nullable', 'string', 'min:8'),

			BelongsToMany::make('Roles', 'roles', \Pktharindu\NovaPermissions\Nova\Role::class),
		];
	}

	/**
	 * Get the cards available for the request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function cards(Request $request) {
		return [];
	}

	/**
	 * Get the filters available for the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function filters(Request $request) {
		return [];
	}

	/**
	 * Get the lenses available for the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function lenses(Request $request) {
		return [];
	}

	/**
	 * Get the actions available for the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function actions(Request $request) {
		return [];
	}
}
