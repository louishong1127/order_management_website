import Datepicker from 'vuejs-datepicker'
import Checkbox from '../components/checkbox'

const PackageApp = new Vue({
	el: '#packageSearchApp',
	data: {
		list: {},
		filter: {},
		packageExcelApiUrl: packageExcelApiUrl,
		orderBaseUrl: orderBaseUrl,
		loading: false,
		selected: {}
	},
	computed: {
		arrivedAtStartDate: {
			get: function() {
				return _.get(this.filter, 'arrived_at_min', null)
			},
			set: function(newValue) {
				if (newValue) {
					return _.set(this.filter, 'arrived_at_min', moment(newValue).format('YYYY-MM-DD'))
				}
				this.$delete(this.filter, 'arrived_at_min')
			}
		},
		arrivedAtEndDate: {
			get: function() {
				return _.get(this.filter, 'arrived_at_max', null)
			},
			set: function(newValue) {
				if (newValue) {
					return _.set(this.filter, 'arrived_at_max', moment(newValue).format('YYYY-MM-DD'))
				}
				this.$delete(this.filter, 'arrived_at_max')
			}
		}
	},
	components: {
		Datepicker,
		Checkbox
	},
	methods: {
		handleCheckCase(isChecked, params) {
			if (isChecked) {
				this.selected[params.package_id] = this.selected[params.package_id]
					? [ ...this.selected[params.package_id], params.case_id ]
					: [ params.case_id ]
			} else {
				if (this.selected[params.package_id].length > 1) {
					const targetIndex = this.selected[params.package_id].indexOf(params.case_id)
					this.selected[params.package_id].splice(targetIndex, 1)
				} else {
					delete this.selected[params.package_id]
				}
			}
		},
		handleExportReport() {
			$.ajax({
				url: `${this.packageExcelApiUrl}`,
				data: this.selected,
				type: 'POST',
				xhrFields: {
					responseType: 'blob'
				}
			}).done(function(response) {
				const url = window.URL.createObjectURL(new Blob([ response ]))
				const link = document.createElement('a')
				link.href = url
				link.setAttribute('download', 'report.xls')
				document.body.appendChild(link)
				link.click()
			})
		}
	}
})

export default PackageApp
