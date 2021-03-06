@extends('master')

@section('content')
<div class="row justify-content-center" id="managementApp" v-cloak>
    <div class="col-12 col-md-8">
        <management-modal
            modal-id="managementCreateModal"
            modal-title="{{ __('case.functional.add')}}"
            :show="managementModal.show.create"
            :langs="langs"
            :fetch-api="fetchCreateApi"
            :initial-data="managementModal.data.create"
            v-on:open="managementModal.show.create=true"
            v-on:close="cleanModalData('create')">
        </management-modal>

        <management-modal
            modal-id="managementEditModal"
            modal-title="{{ __('case.functional.edit')}}"
            :show="managementModal.show.edit"
            :langs="langs"
            :fetch-api="fetchUpdateApi"
            :initial-data="managementModal.data.edit"
            v-on:open="managementModal.show.edit=true"
            v-on:close="cleanModalData('edit')">
        </management-modal>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">{{ __('case.fields.name') }}</th>
                    <th scope="col">{{ __('case.fields.slug') }}</th>
                    <th scope="col">{{ __('case.fields.enabled') }}</th>
                    <th scope="col">
                        <button type="button" class="btn btn-primary" v-on:click="managementModal.show.create=true">{{ __('case.functional.add')}}</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(caseItem, index) in list" :key="index">
                    <th v-text="caseItem.name"></th>
                    <td v-text="caseItem.slug"></td>
                    <td>
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c mB-15">
                            <input type="checkbox" :id="'caseEnabled_'+index" class="peer" v-model="caseItem.enabled" true-value="1" false-value="0" v-on:change="itemEnabledHandler(caseItem, index)">
                            <label :for="'caseEnabled_'+index" class="peers peer-greed js-sb ai-c">
                                <span class="peer peer-greed">{{ __('case.replace_string.enabled')}}</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary" v-on:click="onClickEditItem(caseItem)">{{ __('case.functional.edit')}}</button>
                            <button type="button" class="btn btn-secondary" v-if="caseItem.deletable" v-on:click="onClickDeleteItem(caseItem.id)">{{ __('case.functional.del')}}</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</div>

@endsection

@section('custom-js')
    const list = @json($cases);
    const baseUrl = '{{ route('caseType.index')}}';
    const langs = @json(__('case'));
    const category = 'case';
@endsection
