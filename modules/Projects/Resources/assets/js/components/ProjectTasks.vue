<template>

<div class="card">
    <div class="card-header">
        <div class="row">
        	<div class="col-12 d-flex align-items-center">
        		<span>
					<a href="" v-on:click.stop.prevent="modals.form.tasks.create.show = true" class="btn btn-success"  v-if="permissionCreateProjectTasks" @click="clearFormTasksCreate()">
						<i class="fas fa-plus"></i>&nbsp;{{ textNewTask }}
					</a>
    			</span>
			</div>
        </div>
	</div>
    
    <div class="card-body">
    	<template v-if="paginatedTasks.length > 0">
			<collapse>
				<template v-for="task of paginatedTasks">
					<collapse-item :id="'task.project_id'">
					    <h4 slot="title" class="mb-0 ml-4 long-texts">
					    	<b>{{ task.name }}</b> - {{ task.description }}
				    	</h4>
				     	<template slot="actions">
							<a href="" v-on:click.stop.prevent="modals.form.subtasks.create.show = true" class="btn btn-success btn-sm"  v-if="permissionCreateProjectSubtasks" @click="clearFormSubtasksCreate(task.id)">
								<i class="fas fa-plus"></i>&nbsp;{{ textNewSubtask }}
							</a>
							<a href="" v-if="permissionUpdateProjectTasks" v-on:click.stop.prevent="setTaskUpdateModalShow(task.id)" class="mr-1">
								<i class="fas fa-edit"></i>    							
    						</a>  
    						<a href="" v-if="permissionDeleteProjectTasks" v-on:click.stop.prevent="confirmDeleteTask(task.id)">
								<i class="fas fa-trash-alt"></i>    							
    						</a>
				     	</template>
						<!-- table part starts -->
						<div class="table-responsive" v-if="permissionReadProjectSubtasks">
							<table class="table table-flush table-hover" id="tbl-subtasks">
								<thead class="thead-light">
									<tr class="row table-head-line">
										<th class="col-md-6">{{ textSubtask }}</th>
										<th class="col-md-1">{{ textPriority }}</th>
										<th class="col-md-2">{{ textStatus }}</th>
										<th class="col-md-2">{{ textMember }}</th>
										<th class="col-md-1">{{ textActions }}</th>
									</tr>
								</thead>
								<tbody>
									<tr class="row align-items-center border-top-1" v-for="subtask of task.subtasks">
										<td class="col-md-6 long-texts">
											<a href="" v-on:click.stop.prevent="setSubtaskUpdateModalShow(task.id, subtask.id, true)">
												<b>{{ subtask.name }}</b> - {{ subtask.description }}
											</a>
										</td>
										<td class="col-md-1">{{ subtask.priority_text }}</td>
										<td class="col-md-2">
											<badge rounded type="danger" v-if="subtask.status == 0">{{ subtask.status_text }}</badge>
											<badge rounded type="warning" v-if="subtask.status == 1">{{ subtask.status_text }}</badge>
											<badge rounded type="success" v-if="subtask.status == 2">{{ subtask.status_text }}</badge>
										</td>
										<td class="col-md-2">
											<div class="avatar-group">
												<template v-for="user of subtask.users">
													<a href="#" class="avatar avatar-sm rounded-circle" data-toggle="tooltip" :data-original-title="user.username">
														<span v-html="user.picture"></span>
													</a>
												</template>
                							</div>
										</td>
										<td class="col-md-1">
											<a href="" v-if="permissionUpdateProjectSubtasks" v-on:click.stop.prevent="setSubtaskUpdateModalShow(task.id, subtask.id, false)" class="mr-1">
												<i class="fas fa-edit"></i>    							
											</a>  
											<a href="" v-if="permissionDeleteProjectSubtasks" v-on:click.stop.prevent="confirmDeleteSubtask(task.id, subtask.id)">
												<i class="fas fa-trash-alt"></i>    							
											</a>  
										</td>
									</tr>
								</tbody>
							</table>
						</div>    	
						<!-- table part ends-->
					</collapse-item>
				</template>
			</collapse>
		</template>
		
		<!-- modal part starts -->
		<!-- modal create task starts -->
		<div>
			<modal :show.sync="modals.form.tasks.create.show">
				<template slot="header">
        			{{ modals.form.tasks.create.title }}
     			</template>
 			
 				<div>
      				<base-input
      					:label="modals.form.tasks.create.name.label"
      					:required="modals.form.tasks.create.name.required"
      					:prepend-icon="'fas fa-tasks'"
      					:placeholder="modals.form.tasks.create.name.placeholder"
      					v-on:input="modals.form.tasks.create.name.val = $event"
      					:value="modals.form.tasks.create.name.val"
      				></base-input>

					<label class="form-control-label">{{ modals.form.tasks.create.description.label }}</label>      				
      				<textarea class="form-control" :value="modals.form.tasks.create.description.val" rows="3" :placeholder="modals.form.tasks.create.description.placeholder" v-on:change="modals.form.tasks.create.description.val = $event.target.value"></textarea>
     			</div>
     		
     			<template slot="footer">
     				<div class="float-right">
						<base-button type="outline-secondary" @click="modals.form.tasks.create.show = false">{{ modals.form.tasks.create.cancel }}</base-button>
						<base-button class="btn btn-success" type="primary" @click="createTask()">{{ modals.form.tasks.create.save }}</base-button>
					</div>
     			</template>
			</modal>
		</div>
		<!-- modal create task ends -->
		
		<!-- modal update task starts -->
		<div>
			<modal :show.sync="modals.form.tasks.update.show">
				<template slot="header">
        			{{ modals.form.tasks.update.title }}
     			</template>
 			
 				<div>
      				<base-input
      					:label="modals.form.tasks.update.name.label"
      					:required="modals.form.tasks.update.name.required"
      					:prepend-icon="'fas fa-tasks'"
      					:placeholder="modals.form.tasks.update.name.placeholder"
      					v-on:input="modals.form.tasks.update.name.val = $event"
      					:value="modals.form.tasks.update.name.val"
      				></base-input>

					<label class="form-control-label">{{ modals.form.tasks.update.description.label }}</label>      				
      				<textarea class="form-control" :value="modals.form.tasks.update.description.val" rows="3" :placeholder="modals.form.tasks.update.description.placeholder" v-on:change="modals.form.tasks.update.description.val = $event.target.value"></textarea>
     			</div>
     		
     			<template slot="footer">
					<div class="float-right">
						<base-button type="outline-secondary" @click="modals.form.tasks.update.show = false">{{ modals.form.tasks.update.cancel }}</base-button>
						<base-button class="btn btn-success" type="primary" @click="updateTask()">{{ modals.form.tasks.update.save }}</base-button>
					</div>
     			</template>
			</modal>
		</div>
		<!-- modal update task ends -->
		
		<!-- modal delete task starts -->
		<div>
			<modal :show.sync="modals.form.tasks.delete.show">
				<template slot="header">
        			{{ modals.form.tasks.delete.title }}
     			</template>
 			
 				<div>
					<label class="form-control-label">{{ modals.form.tasks.delete.message }}</label>      				
     			</div>
     		
     			<template slot="footer">
					<div class="float-right">
						<base-button type="outline-secondary" @click="modals.form.tasks.delete.show = false">{{ modals.form.tasks.delete.cancel }}</base-button>
						<base-button class="btn btn-danger" type="primary" @click="deleteTask()">{{ modals.form.tasks.delete.delete }}</base-button>
					</div>
     			</template>
			</modal>
		</div>
		<!-- modal delete task ends -->

		<!-- modal create subtask starts -->
		<div>
			<modal :show.sync="modals.form.subtasks.create.show">
				<template slot="header">
        			{{ modals.form.subtasks.create.title }}
     			</template>
 			
 				<div>
      				<base-input
      					:label="modals.form.subtasks.create.name.label"
      					:required="modals.form.subtasks.create.name.required"
      					:prepend-icon="'fas fa-tasks'"
      					:placeholder="modals.form.subtasks.create.name.placeholder"
      					v-on:input="modals.form.subtasks.create.name.val = $event"
      					:value="modals.form.subtasks.create.name.val"
      				></base-input>

					<div class="form-group">
						<label class="form-control-label">{{ modals.form.subtasks.create.description.label }}</label>      				
						<textarea class="form-control" :value="modals.form.subtasks.create.description.val" rows="3" :placeholder="modals.form.subtasks.create.description.placeholder" v-on:change="modals.form.subtasks.create.description.val = $event.target.value"></textarea>
					</div>

					<akaunting-date
						ref="formSubtasksCreateDeadline"
						:title="modals.form.subtasks.create.deadline.label"
      					:icon="'far fa-calendar-alt'"
						:value="modals.form.subtasks.create.deadline.val"
						:config="{ allowInput: true }"
						@interface="modals.form.subtasks.create.deadline.val = $event"
					></akaunting-date>

					<akaunting-select
						ref="formSubtasksCreatePriority"
						:title="modals.form.subtasks.create.priority.label"
						:icon="'fas fa-list-ol'"
						:placeholder="modals.form.subtasks.create.priority.placeholder"
						:options="taskPriorities"
						@change="modals.form.subtasks.create.priority.val = $event"
					></akaunting-select>

					<akaunting-select
						ref="formSubtasksCreateMember"
						:title="modals.form.subtasks.create.member.label"
						:icon="'fas fa-user'"
						:placeholder="modals.form.subtasks.create.member.placeholder"
						:options="members"
						:multiple="true"
						@change="modals.form.subtasks.create.member.val = $event"
					></akaunting-select>
					
					<akaunting-select
						ref="formSubtasksCreateStatus"
						:title="modals.form.subtasks.create.status.label"
						:icon="'fas fa-hourglass'"
						:placeholder="modals.form.subtasks.create.status.placeholder"
						:options="taskStatuses"
						@change="modals.form.subtasks.create.status.val = $event"
					></akaunting-select>
     			</div>
     		
     			<template slot="footer">
     				<div class="float-right">
						<base-button type="outline-secondary" @click="modals.form.subtasks.create.show = false">{{ modals.form.subtasks.create.cancel }}</base-button>
						<base-button class="btn btn-success" type="primary" @click="createSubtask()">{{ modals.form.subtasks.create.save }}</base-button>
					</div>
     			</template>
			</modal>
		</div>
		<!-- modal create subtask ends -->

		<!-- modal update subtask starts -->
		<div>
			<modal :show.sync="modals.form.subtasks.update.show">
				<template slot="header">
        			{{ modals.form.subtasks.update.title }}
     			</template>
 			
 				<div>
      				<base-input
      					:label="modals.form.subtasks.update.name.label"
      					:required="modals.form.subtasks.update.name.required"
      					:prepend-icon="'fas fa-tasks'"
      					:placeholder="modals.form.subtasks.update.name.placeholder"
      					v-on:input="modals.form.subtasks.update.name.val = $event"
      					:value="modals.form.subtasks.update.name.val"
						:disabled="modals.form.subtasks.update.disabled"
      				></base-input>

					<div class="form-group">
						<label class="form-control-label">{{ modals.form.subtasks.update.description.label }}</label>      				
						<textarea 
							class="form-control" 
							:value="modals.form.subtasks.update.description.val" 
							rows="3" 
							:placeholder="modals.form.subtasks.update.description.placeholder" 
							:disabled="modals.form.subtasks.update.disabled"
							v-on:change="modals.form.subtasks.update.description.val = $event.target.value"
						></textarea>
					</div>

					<akaunting-date
						ref="formSubtasksUpdateDeadline"
						:title="modals.form.subtasks.update.deadline.label"
      					:icon="'far fa-calendar-alt'"
						:value="modals.form.subtasks.update.deadline.val"
						:config="{ allowInput: true }"
						:disabled="modals.form.subtasks.update.disabled"
						@interface="modals.form.subtasks.update.deadline.val = $event"
					></akaunting-date>

					<akaunting-select
						ref="formSubtasksUpdatePriority"
						:title="modals.form.subtasks.update.priority.label"
						:icon="'fas fa-list-ol'"
						:placeholder="modals.form.subtasks.update.priority.placeholder"
						:options="taskPriorities"
						:disabled="modals.form.subtasks.update.disabled"
						@change="modals.form.subtasks.update.priority.val = $event"
					></akaunting-select>

					<akaunting-select
						ref="formSubtasksUpdateMember"
						:title="modals.form.subtasks.update.member.label"
						:icon="'fas fa-user'"
						:placeholder="modals.form.subtasks.update.member.placeholder"
						:options="members"
						:multiple="true"
						:disabled="modals.form.subtasks.update.disabled"
						@change="modals.form.subtasks.update.member.val = $event"
					></akaunting-select>
					
					<akaunting-select
						ref="formSubtasksUpdateStatus"
						:title="modals.form.subtasks.update.status.label"
						:icon="'fas fa-hourglass'"
						:placeholder="modals.form.subtasks.update.status.placeholder"
						:options="taskStatuses"
						:disabled="modals.form.subtasks.update.disabled"
						@change="modals.form.subtasks.update.status.val = $event"
					></akaunting-select>
     			</div>
     		
     			<template slot="footer">
     				<div class="float-right">
						<base-button type="outline-secondary" @click="modals.form.subtasks.update.show = false">{{ modals.form.subtasks.update.cancel }}</base-button>
						<base-button class="btn btn-success" type="primary" @click="updateSubtask()">{{ modals.form.subtasks.update.save }}</base-button>
					</div>
     			</template>
			</modal>
		</div>
		<!-- modal update subtask ends -->

		<!-- modal delete update starts -->
		<div>
			<modal :show.sync="modals.form.subtasks.delete.show">
				<template slot="header">
        			{{ modals.form.subtasks.delete.title }}
     			</template>
 			
 				<div>
					<label class="form-control-label">{{ modals.form.subtasks.delete.message }}</label>      				
     			</div>
     		
     			<template slot="footer">
					<div class="float-right">
						<base-button type="outline-secondary" @click="modals.form.subtasks.delete.show = false">{{ modals.form.subtasks.delete.cancel }}</base-button>
						<base-button class="btn btn-danger" type="primary" @click="deleteSubtask()">{{ modals.form.subtasks.delete.delete }}</base-button>
					</div>
     			</template>
			</modal>
		</div>
		<!-- modal delete update ends -->
		<!-- modal part ends -->
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
	import AkauntingDate from './../../../../../../resources/assets/js/components/AkauntingDate';
	import Collapse from './../../../../../../resources/assets/js/components/Collapse/Collapse';
	import BaseButton from './../../../../../../resources/assets/js/components/BaseButton';
	import CollapseItem from './CollapseItem';

	export default {
		name: "project-tasks",
		
		components: {
			AkauntingSelect,
			AkauntingDate,
			Collapse,
			CollapseItem,
			BaseButton,
		},
		
		props: {
			projectId: null,
			tasks: null,
			taskPriorities: null,
			members: null,
			taskStatuses: null,
			textNewTask: null,
			textEditTask: null,
			textSubtask: null,
			textNewSubtask: null,
			textEditSubtask: null,
			textName: null,
			textEnterName: null,
			textDescription: null,
			textEnterDescription: null,
			textDeadline: null,
			textPriority: null,
			textEnterPriority: null,
			textMember: null,
			textEnterMember: null,
			textStatus: null,
			textEnterStatus: null,
			textNoRecords: null,
			textSave: null,
			textCancel: null,
			textDelete: null,
			textDeleteMessage: null,
			textDeleteTask: null,
			textDeleteSubtask: null,
			textMessageUnknownError: '',
			textActions: null,
			perPageLimitOptions: null,
			permissionCreateProjectTasks: null,
			permissionUpdateProjectTasks: null,
			permissionDeleteProjectTasks: null,
			permissionCreateProjectSubtasks: null,
			permissionReadProjectSubtasks: null,
			permissionUpdateProjectSubtasks: null,
			permissionDeleteProjectSubtasks: null,
			routeTaskStore: null,	
			routeTaskUpdate: null,	
			routeTaskDelete: null,	
			routeSubtaskStore: null,	
			routeSubtaskUpdate: null,	
			routeSubtaskDelete: null,	
		},
		
	    data() {
        	return {
        		paginatedTasks: [],
	            currentPage: 1,
	            perPage: '24',
	            modals: {
	            	form: {
						tasks: {
							create: {
								show: false,
								title: this.textNewTask,
								save: this.textSave,
								cancel: this.textCancel,
								name: {
									label: this.textName,
									placeholder: this.textEnterName,
									required: true,
									val: '',
								},
								description: {
									label: this.textDescription,
									placeholder: this.textEnterDescription,
									val: '',
								},
							},
							update: {
								show: false,
								title: this.textEditTask,
								save: this.textSave,
								cancel: this.textCancel,
								taskId: null,
								name: {
									label: this.textName,
									placeholder: this.textEnterName,
									required: true,
									val: '',
								},
								description: {
									label: this.textDescription,
									placeholder: this.textEnterDescription,
									val: '',
								},
							},
							delete: {
								show: false,
								taskId: null,
								title: this.textDeleteTask,
								delete: this.textDelete,
								cancel: this.textCancel,
								message: this.textDeleteMessage,
							},
						},
						subtasks: {
							create: {
								show: false,
								title: this.textNewSubtask,
								save: this.textSave,
								cancel: this.textCancel,
								taskId: null,
								name: {
									label: this.textName,
									placeholder: this.textEnterName,
									required: true,
									val: '',
								},
								description: {
									label: this.textDescription,
									placeholder: this.textEnterDescription,
									val: '',
								},
								deadline: {
									label: this.textDeadline,
									val: new Date(),
								},
								priority: {
									label: this.textPriority,
									placeholder: this.textEnterPriority,
									val: '',
								},
								member: {
									label: this.textMember,
									placeholder: this.textEnterMember,
									val: '',
								},
								status: {
									label: this.textStatus,
									placeholder: this.textEnterStatus,
									val: '',
								},
							},
							update: {
								show: false,
								title: this.textEditSubtask,
								save: this.textSave,
								cancel: this.textCancel,
								disabled: false,
								taskId: null,
								subtaskId: null,
								name: {
									label: this.textName,
									placeholder: this.textEnterName,
									required: true,
									val: '',
								},
								description: {
									label: this.textDescription,
									placeholder: this.textEnterDescription,
									val: '',
								},
								deadline: {
									label: this.textDeadline,
									val: new Date(),
								},
								priority: {
									label: this.textPriority,
									placeholder: this.textEnterPriority,
									val: '',
								},
								member: {
									label: this.textMember,
									placeholder: this.textEnterMember,
									val: '',
								},
								status: {
									label: this.textStatus,
									placeholder: this.textEnterStatus,
									val: '',
								},
							},
							delete: {
								show: false,
								taskId: null,
								subtaskId: null,
								title: this.textDeleteSubtask,
								delete: this.textDelete,
								cancel: this.textCancel,
								message: this.textDeleteMessage,
							},
						},
	            	},
            	},
        	}
    	},
		
		methods: {
			clearFormTasksCreate() {
				this.modals.form.tasks.create.name.val = '';
				this.modals.form.tasks.create.description.val = '';
			},
			clearFormSubtasksCreate(taskId) {
				this.modals.form.subtasks.create.name.val = '';
				this.modals.form.subtasks.create.description.val = '';
				this.$refs.formSubtasksCreateDeadline.real_model = new Date();
				this.$refs.formSubtasksCreatePriority.real_model = '';
				this.$refs.formSubtasksCreateMember.real_model = '';
				this.$refs.formSubtasksCreateStatus.real_model = '';
				this.modals.form.subtasks.create.taskId = taskId;
			},
			changePage(startIndex, endIndex) {
				this.paginatedTasks = this.tasks.filter(function(value, index, arr) {
					return endIndex > index && startIndex <= index;
				});
			},
			createTask() {
				let formVue = this;
				
				axios.post(this.routeTaskStore, {
					project_id: this.projectId,
					name: this.modals.form.tasks.create.name.val,
					description: this.modals.form.tasks.create.description.val
				})
				.then(response => {
					if (this.rows === undefined) {
						this.tasks = [response.data.data];
					}
					else {
						this.tasks.unshift(response.data.data);
					}
					
					this.modals.form.tasks.create.show = false;
					this.modals.form.tasks.create.name.val = '';
					this.modals.form.tasks.create.description.val = '';
					this.$notify({type: 'success', message: response.data.message});
				})
				.catch(function (error) {
					formVue.modals.form.tasks.create.show = false;
					formVue.modals.form.tasks.create.name.val = '';
					formVue.modals.form.tasks.create.description.val = '';
					formVue.$notify({type: 'danger', message: formVue.textMessageUnknownError});
				});			
			},
			setTaskUpdateModalShow(taskId) {
				this.modals.form.tasks.update.show = true;
				this.modals.form.tasks.update.taskId = taskId;
				
				var task = this.tasks.find(function(element) {
					return element.id == taskId;
				});
				
				this.modals.form.tasks.update.name.val = task.name;
				this.modals.form.tasks.update.description.val = task.description;
			},
			updateTask() {
				let formVue = this;
				
				axios.put(this.routeTaskUpdate.replace('#', this.modals.form.tasks.update.taskId), {
					name: this.modals.form.tasks.update.name.val,
					description: this.modals.form.tasks.update.description.val
				})
				.then(response => {
					this.modals.form.tasks.update.show = false;
					
					for (var i in this.tasks) {
						if (this.tasks[i].id == this.modals.form.tasks.update.taskId) {
        					this.tasks[i].name = this.modals.form.tasks.update.name.val;
        					this.tasks[i].description = this.modals.form.tasks.update.description.val;
        					break;
     					}
   					}
					
					this.modals.form.tasks.update.name.val = '';
					this.modals.form.tasks.update.description.val = '';
					this.$notify({type: 'success', message: response.data.message});
				})
				.catch(function (error) {
					formVue.modals.form.tasks.update.show = false;
					formVue.modals.form.tasks.update.name.val = '';
					formVue.modals.form.tasks.update.description.val = '';
					formVue.$notify({type: 'danger', message: formVue.textMessageUnknownError});				
				});
			},
			confirmDeleteTask(taskId) {
				this.modals.form.tasks.delete.show = true;
				this.modals.form.tasks.delete.taskId = taskId;
				
				var task = this.tasks.find(function(element) {
					return element.id == taskId;
				});
				
				this.modals.form.tasks.delete.message = this.textDeleteMessage + ' ' + task.name + '?';
			},
			deleteTask() {
				let formVue = this;
				
				axios.delete(this.routeTaskDelete.replace('#', this.modals.form.tasks.delete.taskId))
				.then(response => {
					this.modals.form.tasks.delete.show = false;
					
					for (var i in this.tasks) {
						if (this.tasks[i].id == this.modals.form.tasks.delete.taskId) {
        					this.tasks.splice(i, 1);
        					break;
     					}
   					}
					
					this.$notify({type: 'success', message: response.data.message});
				})
				.catch(function (error) {
					formVue.modals.form.tasks.delete.show = false;
					formVue.$notify({type: 'danger', message: formVue.textMessageUnknownError});				
				});
			},
			createSubtask() {
				let formVue = this;
				
				axios.post(this.routeSubtaskStore, {
					project_id: this.projectId,
					task_id: this.modals.form.subtasks.create.taskId,
					name: this.modals.form.subtasks.create.name.val,
					description: this.modals.form.subtasks.create.description.val,
					deadline_at: this.modals.form.subtasks.create.deadline.val,
					priority: this.modals.form.subtasks.create.priority.val,
					status: this.modals.form.subtasks.create.status.val,
					members: this.modals.form.subtasks.create.member.val,
				})
				.then(response => {
					for (var i in this.tasks) {
						if (this.tasks[i].id == this.modals.form.subtasks.create.taskId) {
							this.tasks[i].subtasks.unshift(response.data.data);
							break;
     					}
   					}
					
					this.modals.form.subtasks.create.show = false;
					this.clearFormSubtasksCreate(0);
					this.$notify({type: 'success', message: response.data.message});
				})
				.catch(function (error) {
					formVue.modals.form.subtasks.create.show = false;
					formVue.modals.form.subtasks.create.name.val = '';
					formVue.modals.form.subtasks.create.description.val = '';
					formVue.$refs.formSubtasksCreateDeadline.real_model = new Date();
					formVue.$refs.formSubtasksCreatePriority.real_model = '';
					formVue.$refs.formSubtasksCreateMember.real_model = '';
					formVue.$refs.formSubtasksCreateStatus.real_model = '';
					formVue.modals.form.subtasks.create.taskId = 0;
					formVue.$notify({type: 'danger', message: formVue.textMessageUnknownError});
				})
			},
			setSubtaskUpdateModalShow(taskId, subtaskId, isDisabled) {
				this.modals.form.subtasks.update.show = true;
				this.modals.form.subtasks.update.taskId = taskId;
				this.modals.form.subtasks.update.subtaskId = subtaskId;
				
				var task = this.tasks.find(function(element) {
					return element.id == taskId;
				});

				var subtask = task.subtasks.find(function(element){
					return element.id == subtaskId;
				});

				var users = [];

				subtask.users.forEach(element => {
					users.push(element.user_id.toString())
				});

				this.modals.form.subtasks.update.name.val = subtask.name;
				this.modals.form.subtasks.update.description.val = subtask.description;
				this.$refs.formSubtasksUpdateDeadline.real_model = subtask.deadline_at;
				this.$refs.formSubtasksUpdatePriority.real_model = subtask.priority;
				this.$refs.formSubtasksUpdateMember.real_model = users;
				this.$refs.formSubtasksUpdateStatus.real_model = subtask.status;
				this.modals.form.subtasks.update.disabled = isDisabled;
			},
			updateSubtask() {
				let formVue = this;
				
				axios.put(this.routeSubtaskUpdate.replace('#', this.modals.form.subtasks.update.subtaskId), {
					name: this.modals.form.subtasks.update.name.val,
					description: this.modals.form.subtasks.update.description.val,
					deadline_at: this.modals.form.subtasks.update.deadline.val,
					priority: this.$refs.formSubtasksUpdatePriority.real_model,
					status: this.$refs.formSubtasksUpdateStatus.real_model,
					members: this.$refs.formSubtasksUpdateMember.real_model,
				})
				.then(response => {
					this.modals.form.subtasks.update.show = false;
					
					for (var i in this.tasks) {
						if (this.tasks[i].id == this.modals.form.subtasks.update.taskId) {
							for (var j in this.tasks[i].subtasks) {
								if (this.tasks[i].subtasks[j].id == this.modals.form.subtasks.update.subtaskId) {
									this.tasks[i].subtasks[j] = response.data.data;
									break;
								}
							}	
						}
   					}
					
					this.clearFormSubtasksCreate(0);
					this.$notify({type: 'success', message: response.data.message});
				})
				.catch(function (error) {
					formVue.modals.form.tasks.update.show = false;
					formVue.modals.form.subtasks.update.name.val = '';
					formVue.modals.form.subtasks.update.description.val = '';
					formVue.$refs.formSubtasksCreateDeadline.real_model = new Date();
					formVue.$refs.formSubtasksCreatePriority.real_model = '';
					formVue.$refs.formSubtasksCreateMember.real_model = '';
					formVue.$refs.formSubtasksCreateStatus.real_model = '';
					formVue.modals.form.subtasks.create.taskId = 0;
					formVue.modals.form.subtasks.create.subtaskId = 0;
					formVue.$notify({type: 'danger', message: formVue.textMessageUnknownError});				
				});
			},
			confirmDeleteSubtask(taskId, subtaskId) {
				this.modals.form.subtasks.delete.show = true;
				this.modals.form.subtasks.delete.taskId = taskId;
				this.modals.form.subtasks.delete.subtaskId = subtaskId;
				
				var task = this.tasks.find(function(element) {
					return element.id == taskId;
				});

				var subtask = task.subtasks.find(function(element){
					return element.id == subtaskId;
				});
				
				this.modals.form.subtasks.delete.message = this.textDeleteMessage + ' ' + subtask.name + '?';
			},
			deleteSubtask() {
				let formVue = this;
				
				axios.delete(this.routeSubtaskDelete.replace('#', this.modals.form.subtasks.delete.subtaskId))
				.then(response => {
					this.modals.form.subtasks.delete.show = false;
					
					for (var i in this.tasks) {
						if (this.tasks[i].id == this.modals.form.subtasks.delete.taskId) {
							for (var j in this.tasks[i].subtasks) {
								if (this.tasks[i].subtasks[j].id == this.modals.form.subtasks.delete.subtaskId) {
									this.tasks[i].subtasks.splice(j, 1);
									break;
								}
							}
     					}
   					}
					
					this.$notify({type: 'success', message: response.data.message});
				})
				.catch(function (error) {
					formVue.modals.form.subtasks.delete.show = false;
					formVue.$notify({type: 'danger', message: formVue.textMessageUnknownError});				
				});
			},
		},
		
		mounted() {
			this.paginatedTasks = this.tasks;
		},
		
		watch: {
			tasks: function() {
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
			rows: function() {
				return this.paginatedTasks.length;
			}
		}
	}
</script>