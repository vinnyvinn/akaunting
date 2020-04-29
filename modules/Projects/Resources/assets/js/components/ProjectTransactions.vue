<template>

<div class="card">
    <div class="card-header">
        <div class="row">
        	<div class="col-12 d-flex align-items-center">
                <span class="font-weight-400 d-none d-lg-block mr-2">{{ textFilter }}:</span>
    			<akaunting-select
                    class="col-md-6 mb-0"
                    :placeholder="textFilterPlaceholder"
                    :name="'doc_type'"
                    :options="filterOptions"
                    :icon="'fas fa-stream'"
                    :multiple="true"
                    v-on:change="filterType = $event"
				></akaunting-select>

                <span>
                	<a :href="routeExport" class="btn btn-default">
                		<i class="far fa-file-excel"></i>&nbsp;{{ textExport }}
        			</a>
    			</span>
			</div>
        </div>
	</div>
    
    <div class="card-body">
    	<div class="table-responsive">
    		<table class="table table-flush table-hover" id="tbl-transactions">
    			<thead class="thead-light">
    				<tr class="row table-head-line">
    					<th class="col-md-1"><a href="" v-on:click.stop.prevent="sortDate(sortDateNextOrder, false)">{{ textColumnDate }}&nbsp;<i :class="iconSortDate"></i></a></th>
    					<th class="col-md-1">{{ textColumnType }}</th>
    					<th class="col-md-1">{{ textColumnCategory }}</th>
    					<th class="col-md-7 hidden-xs">{{ textColumnDescription }}</th>
    					<th class="col-md-2 text-right amount-space"><a href="" v-on:click.stop.prevent="sortAmount(sortAmountNextOrder, false)">{{ textColumnAmount }}&nbsp;<i :class="iconSortAmount"></i></a></th>
    				</tr>
    			</thead>
    			<tbody>
    				<tr class="row align-items-center border-top-1" v-for="transaction of paginatedTransactions">
    					<td class="col-md-1">{{ transaction.transaction_at }}</td>
    					<td class="col-md-1">{{ transaction.type }}</td>
    					<td class="col-md-1">{{ transaction.category_name }}</td>
    					<td class="col-md-7 hidden-xs">{{ transaction.description }}</td>
    					<td class="col-md-2 text-right amount-space">{{ transaction.transaction_amount }}</td>
    				</tr>
    			</tbody>
    		</table>
    	</div>
    </div>
    
    <div class="card-footer table-action">
    	<div class="row">
    	    <template v-if="rows > 0">
		        <div class="col-6 d-flex align-items-center">
					<akaunting-select
						class="col-sm-3 mb-0"
						:name="'perPage'"
						:options="perPageLimitOptions"
						:value="perPage"
						v-on:change="perPage = $event"
					></akaunting-select>
		        
		        </div>
		
		        <div class="col-6">
		            <nav class="float-right">
				    	<b-pagination
							v-model="currentPage"
							:total-rows="rows"
							:per-page="perPage"
							:limit="3"
							class="mb-0"
					    ></b-pagination>
		            </nav>
		        </div>
    		</template>
    		<template v-else>
		        <div class="col-12" id="datatable-basic_info" role="status" aria-live="polite">
		            <small>{{ textNoRecords }}</small>
		        </div>
			</template>
    	
		</div>
    </div>
</div>

</template>

<script>
	import AkauntingSelect from './../../../../../../resources/assets/js/components/AkauntingSelect';

	export default {
		name: "project-transactions",
		
		components: {
			AkauntingSelect,
		},
		
		props: {
			transactions: null,
			textFilter: null,
			textFilterPlaceholder: null,
			filterOptions: null,
			routeExport: null,
			textExport: null,
			textColumnType: null,
			textColumnCategory: null,
			textColumnDescription: null,
			textColumnDate: null,
			textColumnAmount: null,
			textNoRecords: null,
			perPageLimitOptions: null,
		},
		
	    data() {
        	return {
	            filterType: null,
	            filteredTransactions: [],
	            paginatedTransactions: [],
	            iconSortNone:'fas fa-arrow-down sort-icon',
	            iconSortNumberAsc:'fas fa-sort-numeric-down',
	            iconSortNumberDesc:'fas fa-sort-numeric-up',
	            iconSortNormalAsc:'fas fa-sort-alpha-down',
	            iconSortNormalDesc:'fas fa-sort-alpha-up',
	            sortDateNextOrder: 'NONE',
	            sortedDateOrder: 'DESC',
	            sortAmountNextOrder: 'ASC',
	            sortedAmountOrder: 'NONE',
	            iconSortDate: null,
	            iconSortAmount: null,
	            currentPage: 1,
	            perPage: '24',
        	}
    	},
		
		methods: {
			sortDate(orderBy, fromWatch) {
				if (orderBy === 'ASC') {
					this.iconSortDate = this.iconSortNormalAsc;
					this.sortDateNextOrder = 'DESC';
					this.sortedDateOrder = 'ASC';
					this.filteredTransactions = this.sortedFilteredTransactionsAscByDate;
				} else if (orderBy === 'DESC') {
					this.iconSortDate = this.iconSortNormalDesc;
					this.sortDateNextOrder = 'NONE';
					this.sortedDateOrder = 'DESC';
					this.filteredTransactions = this.sortedFilteredTransactionsDescByDate;
				} else {
					this.iconSortDate = this.iconSortNone;
					this.sortDateNextOrder = 'ASC';
					this.sortedDateOrder = 'NONE';
					
					if (fromWatch === false) {
						this.filteredTransactions = this.transactions;
						this.filterTable(this.filterType);
					}
				}
				
				if (fromWatch === false) {
					this.iconSortAmount = this.iconSortNone;
					this.sortAmountNextOrder = 'ASC';
					this.sortedAmountOrder = 'NONE';
				}
			},
			sortAmount(orderBy, fromWatch) {
				if (orderBy === 'ASC') {
					this.iconSortAmount = this.iconSortNumberAsc;
					this.sortAmountNextOrder = 'DESC';
					this.sortedAmountOrder = 'ASC';
					this.filteredTransactions = this.sortedFilteredTransactionsAscByAmount;
				} else if (orderBy === 'DESC') {
					this.iconSortAmount = this.iconSortNumberDesc;
					this.sortAmountNextOrder = 'NONE';
					this.sortedAmountOrder = 'DESC';
					this.filteredTransactions = this.sortedFilteredTransactionsDescByAmount;
				} else {
					this.iconSortAmount = this.iconSortNone;
					this.sortAmountNextOrder = 'ASC';
					this.sortedAmountOrder = 'NONE';
					
					if (fromWatch === false) {
						this.filteredTransactions = this.transactions;
						this.filterTable(this.filterType);
					}
				}
				
				if (fromWatch === false) {
					this.iconSortDate = this.iconSortNone;
					this.sortDateNextOrder = 'ASC';
					this.sortedDateOrder = 'NONE';
				}
			},
			filterTable(filterList) {
				if (filterList.length) {
					this.filteredTransactions = [];
				}
				else {
					this.filteredTransactions = this.transactions;
				}
					
				var iterator = filterList.values();
					
				for (let elements of iterator) {
					for (var transaction in this.transactions) {
						if (elements == 0 && this.transactions[transaction].type === 'Invoice') {
							this.filteredTransactions.push(this.transactions[transaction]);
							continue;
						}
	
						if (elements == 1 && this.transactions[transaction].type === 'Revenue') {
							this.filteredTransactions.push(this.transactions[transaction]);
							continue;
						}
	
						if (elements == 2 && this.transactions[transaction].type === 'Bill') {
							this.filteredTransactions.push(this.transactions[transaction]);
							continue;
						}

						if (elements == 3 && this.transactions[transaction].type === 'Payment') {
							this.filteredTransactions.push(this.transactions[transaction]);
							continue;
						}
					}
				}
					
				this.sortDate(this.sortedDateOrder, true);
				this.sortAmount(this.sortedAmountOrder, true);
			},
			changePage(startIndex, endIndex) {
				this.paginatedTransactions = this.filteredTransactions.filter(function(value, index, arr) {
					return endIndex > index && startIndex <= index;
				});
			},
		},
		
		mounted() {

		},
		
		watch: {
			filterType: function(val) {
				this.filterTable(val);
			},
			filteredTransactions: function() {
				this.changePage((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
			},
			perPage: function() {
				this.changePage((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
			},
			currentPage: function() {
				this.changePage((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
			},
		},
		
		computed: {
			sortedFilteredTransactionsAscByDate: function() {
				return this.filteredTransactions.slice().sort(function(a, b) {
					if (a.paid_at < b.paid_at) {
    					return -1;
  					}
  					
  					if (a.paid_at > b.paid_at) {
    					return 1;
  					}

  					return 0;
				});
			},
			sortedFilteredTransactionsDescByDate: function() {
				return this.filteredTransactions.slice().sort(function(a, b) {
					if (a.paid_at < b.paid_at) {
    					return 1;
  					}
  					
  					if (a.paid_at > b.paid_at) {
    					return -1;
  					}

  					return 0;
				});
			},
			sortedFilteredTransactionsAscByAmount: function() {
				return this.filteredTransactions.slice().sort(function(a, b) {
					if (a.amount < b.amount) {
    					return -1;
  					}
  					
  					if (a.amount > b.amount) {
    					return 1;
  					}

  					return 0;
				});
			},
			sortedFilteredTransactionsDescByAmount: function() {
				return this.filteredTransactions.slice().sort(function(a, b) {
					if (a.amount < b.amount) {
    					return 1;
  					}
  					
  					if (a.amount > b.amount) {
    					return -1;
  					}

  					return 0;
				});
			},
			rows: function() {
				return this.filteredTransactions.length;
			}
		}
	}
</script>