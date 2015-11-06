@extends('frontend.layouts.master')

@section('content')
	<div class="row">

		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading"><i class="fa fa-home"></i> {{ trans('navs.home') }}</div>

				<div class="panel-body">
					{{ trans('strings.welcome_to', ['place' => app_name()]) }}
				</div>
			</div><!-- panel -->

		</div><!-- col-md-10 -->
{{--
		@role('Administrator')
            {{-- You can also send through the Role ID

		    <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-home"></i> {{ trans('strings.based_on.role') . trans('strings.using_blade_extensions') }}</div>

                    <div class="panel-body">
                        {{ trans('strings.test') . ' 1: ' . trans('strings.you_can_see_because', ['role' => trans('roles.administrator')]) }}
                    </div>
                </div><!-- panel -->

            </div><!-- col-md-10 -->
		@endauth

		@if (access()->hasRole('Administrator'))
		    <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-home"></i> {{ trans('strings.based_on.role') . trans('strings.using_access_helper.role_name') }}</div>

                    <div class="panel-body">
                        {{ trans('strings.test') . ' 2: ' . trans('strings.you_can_see_because', ['role' => trans('roles.administrator')]) }}
                    </div>
                </div><!-- panel -->

            </div><!-- col-md-10 -->
		@endif

		@if (access()->hasRole(1))
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-home"></i> {{ trans('strings.based_on.role') . trans('strings.using_access_helper.role_id') }}</div>

                    <div class="panel-body">
                        {{ trans('strings.test') . ' 3: ' . trans('strings.you_can_see_because', ['role' => trans('roles.administrator')]) }}
                    </div>
                </div><!-- panel -->

            </div><!-- col-md-10 -->
        @endif

        @if (access()->hasRoles(['Administrator', 1]))
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-home"></i> {{ trans('strings.based_on.role') . trans('strings.using_access_helper.array_roles_not') }}</div>

                    <div class="panel-body">
                        {{ trans('strings.test') . ' 4: ' . trans('strings.you_can_see_because', ['role' => trans('roles.administrator')]) }}
                    </div>
                </div><!-- panel -->

            </div><!-- col-md-10 -->
        @endif

        {{-- The second parameter says the user must have all the roles specified. Administrator does not have the role with an id of 2, so this will not show.
        @if (access()->hasRoles(['Administrator', 2], true))
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-home"></i> {{ trans('strings.based_on.role') . trans('strings.using_access_helper.array_roles') }}</div>

                    <div class="panel-body">
                        {{ trans('strings.you_can_see_because', ['role' => trans('roles.administrator')]) }}
                    </div>
                </div><!-- panel -->

            </div><!-- col-md-10 -->
        @endif

        @permission('view-backend')
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-home"></i> {{ trans('strings.based_on.permission') . trans('strings.using_access_helper.permission_name') }}</div>

                    <div class="panel-body">
                        {{ trans('strings.test') . ' 5: ' . trans('strings.you_can_see_because_permission', ['permission' => 'view-backend']) }}
                    </div>
                </div><!-- panel -->

            </div><!-- col-md-10 -->
        @endauth

        @if (access()->hasPermission(1))
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-home"></i> {{ trans('strings.based_on.permission') . trans('strings.using_access_helper.permission_id') }}</div>

                    <div class="panel-body">
                        {{ trans('strings.test') . ' 6: ' . trans('strings.you_can_see_because_permission', ['permission' => 'view_backend']) }}
                    </div>
                </div><!-- panel -->

            </div><!-- col-md-10 -->
        @endif

        @if (access()->hasPermissions(['view-backend', 1]))
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-home"></i> {{ trans('strings.based_on.permission') . trans('strings.using_access_helper.array_permissions_not') }}</div>

                    <div class="panel-body">
                        {{ trans('strings.test') . ' 7: ' . trans('strings.you_can_see_because_permission', ['permission' => 'view_backend']) }}
                    </div>
                </div><!-- panel -->

            </div><!-- col-md-10 -->
        @endif

        @if (access()->hasPermissions(['view-backend', 2], true))
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-home"></i> {{ trans('strings.based_on.permission') . trans('strings.using_access_helper.array_permissions') }}</div>

                    <div class="panel-body">
                        {{ trans('strings.you_can_see_because_permission', ['permission' => 'view_backend']) }}
                    </div>
                </div><!-- panel -->

            </div><!-- col-md-10 -->
        @endif

        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-home"></i> {{ trans('strings.js_injected_from_controller') }}</div>

                <div class="panel-body">
                    {{ trans('strings.test') . ' 8: ' . trans('strings.view_console_it_works') }}
                </div>
            </div><!-- panel -->
        </div><!-- col-md-10 -->
--}}
        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-search"></i> Find an event</div>

                <div class="panel-body">
                    <input type="text" id="findEvent" autofocus style="width:100%">
                </div>
            </div><!-- panel -->

        </div><!-- col-md-10 -->

        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-clock-o"></i> Current events</div>

                <div class="panel-body">
                    Event lists
                </div>
            </div><!-- panel -->

        </div><!-- col-md-10 -->

        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-calendar"></i> Upcoming events</div>

                <div class="panel-body">
                    Event lists
                </div>
            </div><!-- panel -->

        </div><!-- col-md-10 -->

    </div><!-- row -->

    <p>background-color: #565656;<br>
        color: #39ff14;<br>
        <a href="http://www.colorhexa.com/39ff14">http://www.colorhexa.com/39ff14</a></p>
@endsection

@section('after-scripts-end')
	<script>
		//Being injected from FrontendController
		console.log(test);
	</script>
@stop