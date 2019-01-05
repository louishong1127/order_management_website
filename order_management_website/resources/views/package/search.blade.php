@extends('master')

@section('content')
    <div class="col-12" id="packageSearchApp" style="min-height: 100vh">
        <form action="{{ route('package.search') }}" method="get" role="form">
            <div class="row bgc-white p-15 mB-20 mx-0 base-box-shadow align-items-center">
                <div class="col-3 pl-md-0">
                    <input type="text" name="order_name" class="form-control"
                           placeholder="{{ __('order.placeholder.name')}}">
                </div>
                <div class="col-3">
                    <input type="phone" name="order_phone" class="form-control"
                           placeholder="{{ __('order.placeholder.phone')}}">
                </div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-12 col-md-6 pr-md-1">
                            <datepicker
                                    format="yyyy-MM-dd"
                                    v-model="arrivedAtStartDate"
                                    input-class="bg-white"
                                    id="arrivedAtStartDate"
                                    calendar-button-icon="fa fa-calendar"
                                    :calendar-button="true"
                                    :clear-button="true"
                                    :bootstrap-styling="true"
                                    name="arrived_at_min"
                                    placeholder="{{ __('package.placeholder.arrived_at_start')}}"></datepicker>
                        </div>
                        <div class="col-12 col-md-6 pl-md-1">
                            <datepicker
                                    format="yyyy-MM-dd"
                                    v-model="arrivedAtEndDate"
                                    input-class="bg-white"
                                    id="arrivedAtEndDate"
                                    calendar-button-icon="fa fa-calendar"
                                    :calendar-button="true"
                                    :clear-button="true"
                                    :bootstrap-styling="true"
                                    name="arrived_at_max"
                                    placeholder="{{ __('package.placeholder.arrived_at_end')}}"></datepicker>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                        <input type="checkbox" id="filterShipped" class="peer" v-model="filter.shipped" true-value="1"
                               false-value="0" name="shipped">
                        <label for="filterShipped" class="peers peer-greed js-sb ai-c cur-p">
                            <span class="peer peer-greed">{{ __('package.filter.sent')}}</span>
                        </label>
                    </div>
                </div>
                <div class="col-auto ml-auto text-center pr-md-0">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-search"></i>
                        {{ __('package.functional.search') }}
                    </button>
                </div>
            </div>
            <h4 class="mb-0">{{ __('navigation.package.search') }}</h4>
            <div class="row bgc-white p-15 bd mB-20 mx-0">
                <div class="table-responsive">
                    <table id="dataTable" class="table mb-0" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle text-nowrap text-center">{{ __('package.section.sent_status') }}</th>
                            <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle">{{ __('order.fields.name') }}</th>
                            <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle">{{ __('order.fields.phone') }}</th>
                            <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle text-center">{{ __('order.fields.married_date') }}</th>
                            <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle text-center">{{ __('package.fields.arrived_at') }}</th>
                            <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle text-nowrap">{{ __('package.section.content') }}</th>
                            <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle text-nowrap">{{ __('package.section.amount') }}</th>
                            <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle text-center">
                                <button type="button" class="btn btn-sm btn-primary text-nowrap"
                                        v-on:click="handleExportReport">
                                    {{ __('package.functional.export') }}
                                </button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($packages))
                            @foreach($packages as $index => $package)
                                <tr :key="{{ $index }}">
                                    <td class="text-nowrap va-m" rowspan="{{ count($package->cases) + 1 }}">
                                        <div class="rounded-circle
                                                    pos-r
                                                    h-2r
                                                    w-2r
                                                    font-size-20
                                                    mr-auto
                                                    ml-auto
                                             {{ $package->sent_at ? 'bg-primary' : 'bg-secondary' }}">
                                            <i class="ti ti-truck text-white pos-a tl-50p centerXY"></i>
                                        </div>
                                    </td>
                                    <td class="text-nowrap va-m" rowspan="{{ count($package->cases) + 1 }}">
                                        <a href="{{ route('order.show', [
                                        'id' => $package->order_id
                                        ])}}">{{ $package->order_name }}</a>
                                    </td>
                                    <td class="text-nowrap va-m"
                                        rowspan="{{ count($package->cases) + 1 }}">{{ $package->package_phone ?: '-'     }}</td>
                                    <td class="text-nowrap va-m text-center"
                                        rowspan="{{ count($package->cases) + 1 }}">{{ $package->married_date ?: '-' }}</td>
                                    <td class="text-nowrap va-m text-center"
                                        rowspan="{{ count($package->cases) + 1 }}">{{ $package->arrived_at ? $package->arrived_at->toDateString() : '-' }}</td>
                                    <td class="p-0 bdwT-0 bdwB-0"></td>
                                </tr>
                                @foreach($package->cases as $caseIndex => $case)
                                    <tr :key="`package-{{ $index }}-case-{{ $caseIndex }}`">
                                        <td class="bdwB-0 va-m {{ ($index === 0 || $caseIndex > 0) ? 'bdwT-0' : '' }}">{{ $case->name }}</td>
                                        <td class="bdwB-0 va-m text-center {{ ($index === 0 || $caseIndex > 0) ? 'bdwT-0' : '' }}">{{ $case->amount }}</td>
                                        <td class="bdwB-0 va-m text-center {{ ($index === 0 || $caseIndex > 0) ? 'bdwT-0' : '' }}">
                                            <Checkbox
                                                :id="`${{{$index}}}_${{{$caseIndex}}}`"
                                                :is-checked="selected[{{ $package->id }}] && selected[{{ $package->id }}].includes({{ $case->case_id }})"
                                                :params="{package_id: {{ $package->id }}, case_id: {{ $case->case_id }}}"
                                            v-on:checked="handleCheckCase"/>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('custom-js')
    const packageExcelApiUrl = '{{ route('package.excel')}}';
    const orderBaseUrl = '{{ route('order.index')}}';
@endsection