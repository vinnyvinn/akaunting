<template>

<div class="card">
    <div class="card-header">
        <div class="row">
        	<div class="col-12 d-flex align-items-center">
	        	<span>
	            	<a href="" v-on:click.stop.prevent="modals.form.create.show = true" class="btn btn-success" v-if="permissionCreateProjectDiscussions">
	            		<i class="fas fa-plus"></i>&nbsp;{{ textNewDiscussion }}
	    			</a>
				</span>
			</div>
        </div>
	</div>
    
    <div class="card-body">
    	
    	<!-- table part starts -->
    	<div class="table-responsive">
    		<table class="table table-flush table-hover" id="tbl-discussions">
    			<thead class="thead-light">
    				<tr class="row table-head-line">
    					<th class="col-md-5">{{ textColumnSubject }}</th>
    					<th class="col-md-2">{{ textColumnCreatedBy }}</th>
    					<th class="col-md-1"><a href="" v-on:click.stop.prevent="sortLikes(sortLikesNextOrder, false)">{{ textColumnLikes }}&nbsp;<i :class="iconSortLikes"></i></a></th>
    					<th class="col-md-1 hidden-xs"><a href="" v-on:click.stop.prevent="sortComments(sortCommentsNextOrder, false)">{{ textColumnComments }}&nbsp;<i :class="iconSortComments"></i></a></th>
    					<th class="col-md-2"><a href="" v-on:click.stop.prevent="sortLastActivity(sortLastActivityNextOrder, false)">{{ textColumnLastActivity }}&nbsp;<i :class="iconSortLastActivity"></i></a></th>
    					<th class="col-md-1">{{ textColumnActions }}</th>
    				</tr>
    			</thead>
    			<tbody>
    				<tr class="row align-items-center border-top-1" v-for="discussion of paginatedDiscussionsList">
    					<td class="col-md-5">
    						<a href="" v-on:click.stop.prevent="showDiscussion(discussion.id)">
    							{{ discussion.name }}
							</a>
						</td>
						<td class="col-md-2">{{ discussion.created_by }}</td>
    					<td class="col-md-1">{{ discussion.total_like }}</td>
    					<td class="col-md-1 hidden-xs">{{ discussion.total_comment }}</td>
    					<td class="col-md-2">{{ discussion.last_activity }}</td>
    					<td class="col-md-1">
    						<a href="" v-if="permissionUpdateProjectDiscussions" v-on:click.stop.prevent="setUpdateModalShow(discussion.id)">
								<i class="fas fa-edit"></i>    							
    						</a>  
    						<a href="" v-if="permissionDeleteProjectDiscussions" v-on:click.stop.prevent="confirmDeleteDiscussion(discussion.id)">
								<i class="fas fa-trash-alt"></i>    							
    						</a>  
    					</td>
    				</tr>
    			</tbody>
    		</table>
    	</div>    	
		<!-- table part ends-->
    
		<!-- modal part starts -->
		<!-- modal create discussion starts -->
		<div>
			<modal :show.sync="modals.form.create.show">
				<template slot="header">
        			{{ modals.form.create.title }}
     			</template>
 			
 				<div>
      				<base-input
      					:label="modals.form.create.subject.label"
      					:required="modals.form.create.subject.required"
      					:prepend-icon="'far fa-comment-dots'"
      					:placeholder="modals.form.create.subject.placeholder"
      					v-on:input="modals.form.create.subject.val = $event"
      					:value="modals.form.create.subject.val"
      				></base-input>

					<label class="form-control-label">{{ modals.form.create.description.label }}</label>      				
      				<textarea class="form-control" :value="modals.form.create.description.val" rows="3" :placeholder="modals.form.create.description.placeholder" v-on:change="modals.form.create.description.val = $event.target.value"></textarea>
     			</div>
     		
     			<template slot="footer">
     				<div class="float-right">
						<base-button type="outline-secondary" @click="modals.form.create.show = false">{{ modals.form.create.cancel }}</base-button>
						<base-button type="primary" @click="createDiscussion()">{{ modals.form.create.save }}</base-button>
					</div>
     			</template>
			</modal>
		</div>
		<!-- modal create discussion ends -->
		
		<!-- modal update discussion starts -->
		<div>
			<modal :show.sync="modals.form.update.show">
				<template slot="header">
        			{{ modals.form.update.title }}
     			</template>
 			
 				<div>
      				<base-input
      					:label="modals.form.update.subject.label"
      					:required="modals.form.update.subject.required"
      					:prepend-icon="'far fa-comment-dots'"
      					:placeholder="modals.form.update.subject.placeholder"
      					v-on:input="modals.form.update.subject.val = $event"
      					:value="modals.form.update.subject.val"
      				></base-input>

					<label class="form-control-label">{{ modals.form.update.description.label }}</label>      				
      				<textarea class="form-control" :value="modals.form.update.description.val" rows="3" :placeholder="modals.form.update.description.placeholder" v-on:change="modals.form.update.description.val = $event.target.value"></textarea>
     			</div>
     		
     			<template slot="footer">
					<div class="float-right">
						<base-button type="outline-secondary" @click="modals.form.update.show = false">{{ modals.form.update.cancel }}</base-button>
						<base-button class="btn btn-success" type="primary" @click="updateDiscussion()">{{ modals.form.update.save }}</base-button>
					</div>
     			</template>
			</modal>
		</div>
		<!-- modal update discussion ends -->

		<!-- modal delete discussion starts -->
		<div>
			<modal :show.sync="modals.form.delete.show">
				<template slot="header">
        			{{ modals.form.delete.title }}
     			</template>
 			
 				<div>
					<label class="form-control-label">{{ modals.form.delete.message }}</label>      				
     			</div>
     		
     			<template slot="footer">
					<div class="float-right">
						<base-button type="outline-secondary" @click="modals.form.delete.show = false">{{ modals.form.delete.cancel }}</base-button>
						<base-button class="btn btn-danger" type="primary" @click="deleteDiscussion()">{{ modals.form.delete.delete }}</base-button>
					</div>
     			</template>
			</modal>
		</div>
		<!-- modal delete discussion ends -->

		<!-- modal show discussion starts -->
		<div>
			<modal :show.sync="modals.form.show.show">
				<template slot="header">
        			{{ modals.form.show.title }}
     			</template>
 			
				<div>
					<p class="mb-4">
  						{{ this.modals.form.show.discussion.description }}
					</p>
					<div class="row align-items-center my-3 pb-3 border-bottom">
						<div class="col-sm-12">
							<div class="icon-actions">
								<a href="#" class="like" :class="this.modals.form.show.isDiscussionLikedFromUser ? 'active' : ''" v-on:click.stop.prevent="modals.form.show.isDiscussionLikedFromUser ? unlikeDiscussion(modals.form.show.discussion.id) : likeDiscussion(modals.form.show.discussion.id)">
									<i class="ni ni-like-2"></i>
									<span class="text-muted">{{ this.modals.form.show.discussion.total_like }}</span>
								</a>
								<a>
									<i class="ni ni-chat-round"></i>
									<span class="text-muted">{{ this.modals.form.show.discussion.total_comment }}</span>
								</a>
							</div>
						</div>
					</div>
					<div class="mb-1">
						<div class="media media-comment" v-for="comment of this.modals.form.show.discussionComments">
							<img alt="User Image" :src="comment.user_image_path" class="avatar avatar-lg media-comment-avatar rounded-circle">
							<div class="media-body">
								<div class="media-comment-text">
									<h6 class="h5 mt-0">{{ comment.created_by }}</h6>
									<p class="text-sm lh-160">{{ comment.comment }}</p>
								</div>
							</div>
						</div>
						<hr>
						<div class="media align-items-center">
							<img alt="User Image" :src="this.userImage" class="avatar avatar-lg rounded-circle mr-4">
							<div class="media-body">
								<form class="mb-0">
									<textarea :placeholder="modals.form.show.commentPlaceholder" rows="1" class="form-control" v-on:keyup.enter="postComment(modals.form.show.discussion.id, $event.target)"></textarea>
								</form>
							</div>
						</div>
					</div>
				</div>
     		
     			<template slot="footer">
     			</template>
			</modal>
		</div>
		<!-- modal show discussion ends -->
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
	import BaseInput from './../../../../../../resources/assets/js/components/Inputs/BaseInput';
	import Modal from './../../../../../../resources/assets/js/components/Modal';
	import axios from "axios";
		
	export default {
		name: "project-discussions",
	
		components: {
			AkauntingSelect,
			BaseInput,
			Modal,
		},
		
		props: {
			projectId: null,
			discussions: null,
			textNewDiscussion: null,
			textEditDiscussion: null,
			textDeleteDiscussion: null,
			textDiscussionFeed: null,
			textNoRecords: null,
			textSave: null,
			textCancel: null,
			textDelete: null,
			textDeleteMessage: null,
			textSubject: null,
			textEnterSubject: null,
			textDescription: null,
			textEnterDescription: null,
			textColumnSubject: null,
			textColumnCreatedBy: null,
			textColumnLikes: null,
			textColumnComments: null,
			textColumnLastActivity: null,
			textColumnActions: null,
			textMessageUnknownError: null,
			textCommentPlaceholder: null,
			perPageLimitOptions: null,
			permissionCreateProjectDiscussions: null,
			permissionUpdateProjectDiscussions: null,
			permissionDeleteProjectDiscussions: null,
			routeStore: null,
			routeUpdate: null,
			routeDelete: null,
			routeListComments: null,
			routeDiscussionLikes: null,
			routeLikeDiscussion: null,
			routeUnlikeDiscussion: null,
			routePostComment: null,
			user: null,
			userImage: null,
		},

		data() {
			return {
	            discussionsList: this.discussions,
	            sortedDiscussionsList: this.discussions,
	            paginatedDiscussionsList: this.discussions,
	            currentPage: 1,
	            perPage: '24',
	            iconSortNone:'fas fa-arrow-down sort-icon',
	            iconSortNumberAsc:'fas fa-sort-numeric-down',
	            iconSortNumberDesc:'fas fa-sort-numeric-up',
	            iconSortNormalAsc:'fas fa-sort-alpha-down',
	            iconSortNormalDesc:'fas fa-sort-alpha-up',
	            sortLastActivityNextOrder: 'NONE',
	            sortedLastActivityOrder: 'DESC',
	            sortLikesNextOrder: 'ASC',
	            sortedLikesOrder: 'NONE',
	            sortCommentsNextOrder: 'ASC',
	            sortedCommentsOrder: 'NONE',
	            iconSortLastActivity: null,
		        iconSortLikes: null,
		        iconSortComments: null,
	            modals: {
	            	form: {
	            		create: {
	            			show: false,
	            			title: this.textNewDiscussion,
	            			save: this.textSave,
	            			cancel: this.textCancel,
	            			subject: {
	            				label: this.textSubject,
	            				placeholder: this.textEnterSubject,
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
	            			title: this.textEditDiscussion,
	            			save: this.textSave,
	            			cancel: this.textCancel,
	            			discussionId: null,
	            			subject: {
	            				label: this.textSubject,
	            				placeholder: this.textEnterSubject,
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
	            			discussionId: null,
	            			title: this.textDeleteDiscussion,
	            			delete: this.textDelete,
	            			cancel: this.textCancel,
	            			message: this.textDeleteMessage,
	            		},
	            		show: {
	            			show: false,
	            			title: this.textDiscussionFeed,
	            			discussion: Object,
	            			discussionComments: [],
	            			discussionLikes: [],
	            			isDiscussionLikedFromUser: false,
	            			commentPlaceholder: this.textCommentPlaceholder,
	            		},
	            	},
	            },
			}
		},
		
		methods: {
			sortLastActivity(orderBy, fromWatch) {
				if (orderBy === 'ASC') {
					this.iconSortLastActivity = this.iconSortNormalAsc;
					this.sortLastActivityNextOrder = 'DESC';
					this.sortedLastActivityOrder = 'ASC';
					this.sortedDiscussionsList = this.sortedDiscussionsAscByLastActivity;
				} else if (orderBy === 'DESC') {
					this.iconSortLastActivity = this.iconSortNormalDesc;
					this.sortLastActivityNextOrder = 'NONE';
					this.sortedLastActivityOrder = 'DESC';
					this.sortedDiscussionsList = this.sortedDiscussionsDescByLastActivity;
				} else {
					this.iconSortLastActivity = this.iconSortNone;
					this.sortLastActivityNextOrder = 'ASC';
					this.sortedLastActivityOrder = 'NONE';
					
					if (fromWatch === false) {
						this.sortedDiscussionsList = this.discussions;
					}
				}
				
				if (fromWatch === false) {
					this.iconSortLikes = this.iconSortNone;
					this.sortLikesNextOrder = 'ASC';
					this.sortedLikesOrder = 'NONE';
					this.iconSortComments = this.iconSortNone;
					this.sortCommentsNextOrder = 'ASC';
					this.sortedCommentsOrder = 'NONE';
				}
			},
			sortLikes(orderBy, fromWatch) {
				if (orderBy === 'ASC') {
					this.iconSortLikes = this.iconSortNumberAsc;
					this.sortLikesNextOrder = 'DESC';
					this.sortedLikesOrder = 'ASC';
					this.sortedDiscussionsList = this.sortedDiscussionsAscByLikes;
				} else if (orderBy === 'DESC') {
					this.iconSortLikes = this.iconSortNumberDesc;
					this.sortLikesNextOrder = 'NONE';
					this.sortedLikesOrder = 'DESC';
					this.sortedDiscussionsList = this.sortedDiscussionsDescByLikes;
				} else {
					this.iconSortLikes = this.iconSortNone;
					this.sortLikesNextOrder = 'ASC';
					this.sortedLikesOrder = 'NONE';
					
					if (fromWatch === false) {
						this.sortedDiscussionsList = this.discussions;
					}
				}
				
				if (fromWatch === false) {
					this.iconSortLastActivity = this.iconSortNone;
					this.sortLastActivityNextOrder = 'ASC';
					this.sortedLastActivityOrder = 'NONE';
					this.iconSortComments = this.iconSortNone;
					this.sortCommentsNextOrder = 'ASC';
					this.sortedCommentsOrder = 'NONE';
				}
			},		
			sortComments(orderBy, fromWatch) {
				if (orderBy === 'ASC') {
					this.iconSortComments = this.iconSortNumberAsc;
					this.sortCommentsNextOrder = 'DESC';
					this.sortedCommentsOrder = 'ASC';
					this.sortedDiscussionsList = this.sortedDiscussionsAscByComments;
				} else if (orderBy === 'DESC') {
					this.iconSortComments = this.iconSortNumberDesc;
					this.sortCommentsNextOrder = 'NONE';
					this.sortedCommentsOrder = 'DESC';
					this.sortedDiscussionsList = this.sortedDiscussionsDescByComments;
				} else {
					this.iconSortComments = this.iconSortNone;
					this.sortCommentsNextOrder = 'ASC';
					this.sortedCommentsOrder = 'NONE';
					
					if (fromWatch === false) {
						this.sortedDiscussionsList = this.discussions;
					}
				}
				
				if (fromWatch === false) {
					this.iconSortLastActivity = this.iconSortNone;
					this.sortLastActivityNextOrder = 'ASC';
					this.sortedLastActivityOrder = 'NONE';
					this.iconSortLikes = this.iconSortNone;
					this.sortLikesNextOrder = 'ASC';
					this.sortedLikesOrder = 'NONE';
				}
			},		
			changePage(startIndex, endIndex) {
				this.paginatedDiscussionsList = this.sortedDiscussionsList.filter(function(value, index, arr) {
					return endIndex > index && startIndex <= index;
				});
			},
			createDiscussion() {
				let formVue = this;
				
				axios.post(this.routeStore, {
					project_id: this.projectId,
					name: this.modals.form.create.subject.val,
					description: this.modals.form.create.description.val
				})
				.then(response => {
					if (this.rows === undefined) {
						this.discussionsList = [response.data.data];
					}
					else {
						this.discussionsList.push(response.data.data);
					}
					
					this.modals.form.create.show = false;
					this.modals.form.create.subject.val = '';
					this.modals.form.create.description.val = '';
					this.sortLastActivity(this.sortedLastActivityOrder, true);
					this.sortLikes(this.sortedLikesOrder, true);			
					this.sortComments(this.sortedCommentsOrder, true);
					this.$notify({type: 'success', message: response.data.message});
				})
				.catch(function (error) {
					formVue.modals.form.create.show = false;
					formVue.modals.form.create.subject.val = '';
					formVue.modals.form.create.description.val = '';
					formVue.$notify({type: 'danger', message: formVue.textMessageUnknownError});
				});
			},
			setUpdateModalShow(discussionId) {
				this.modals.form.update.show = true;
				this.modals.form.update.discussionId = discussionId;
				
				var discussion = this.discussionsList.find(function(element) {
					return element.id == discussionId;
				});
				
				this.modals.form.update.subject.val = discussion.name;
				this.modals.form.update.description.val = discussion.description;
			},
			updateDiscussion() {
				let formVue = this;
				
				axios.put(this.routeUpdate.replace('#', this.modals.form.update.discussionId), {
					name: this.modals.form.update.subject.val,
					description: this.modals.form.update.description.val
				})
				.then(response => {
					this.modals.form.update.show = false;
					
					for (var i in this.discussionsList) {
						if (this.discussionsList[i].id == this.modals.form.update.discussionId) {
        					this.discussionsList[i].name = this.modals.form.update.subject.val;
        					this.discussionsList[i].description = this.modals.form.update.description.val;
							var now = new Date();
							this.discussionsList[i].updated_at = now.toISOString();
        					break;
     					}
   					}
					
					this.modals.form.update.subject.val = '';
					this.modals.form.update.description.val = '';
					this.sortLastActivity(this.sortedLastActivityOrder, true);
					this.sortLikes(this.sortedLikesOrder, true);			
					this.sortComments(this.sortedCommentsOrder, true);
					this.$notify({type: 'success', message: response.data.message});
				})
				.catch(function (error) {
					formVue.modals.form.update.show = false;
					formVue.modals.form.update.subject.val = '';
					formVue.modals.form.update.description.val = '';
					formVue.$notify({type: 'danger', message: formVue.textMessageUnknownError});				
				});
			},
			confirmDeleteDiscussion(discussionId) {
				this.modals.form.delete.show = true;
				this.modals.form.delete.discussionId = discussionId;
				
				var discussion = this.discussionsList.find(function(element) {
					return element.id == discussionId;
				});
				
				this.modals.form.delete.message += ' ' + discussion.name + '?';
			},
			deleteDiscussion() {
				let formVue = this;
				
				axios.delete(this.routeDelete.replace('#', this.modals.form.delete.discussionId))
				.then(response => {
					this.modals.form.delete.show = false;
					
					for (var i in this.discussionsList) {
						if (this.discussionsList[i].id == this.modals.form.delete.discussionId) {
        					this.discussionsList.splice(i, 1);
        					break;
     					}
   					}
					
					this.sortLastActivity(this.sortedLastActivityOrder, true);
					this.sortLikes(this.sortedLikesOrder, true);			
					this.sortComments(this.sortedCommentsOrder, true);
					this.$notify({type: 'success', message: response.data.message});
				})
				.catch(function (error) {
					formVue.modals.form.delete.show = false;
					formVue.$notify({type: 'danger', message: formVue.textMessageUnknownError});				
				});
			},
			showDiscussion(discussionId) {
				let formVue = this;
				
				this.modals.form.show.isDiscussionLikedFromUser = false;
				this.modals.form.show.discussionComments = [];
				this.modals.form.show.discussionLikes = [];
								
				var discussion = this.discussionsList.find(function(element) {
					return element.id == discussionId;
				});

				axios.get(this.routeListComments.replace('#', discussionId), {
					discussion_id: discussionId
				})
				.then(response => {
					this.modals.form.show.discussionComments = response.data.data;
				})
				.catch(function(error) {
					this.modals.form.show.show = false;
					this.$notify({type: 'danger', message: textMessageUnknownError});
				});

				axios.get(this.routeDiscussionLikes.replace('#', discussionId), {
					discussion_id: discussionId
				})
				.then(response => {
					this.modals.form.show.discussionLikes = response.data.data;
					
					for (var i in this.modals.form.show.discussionLikes) {
						if (this.modals.form.show.discussionLikes[i].created_by == this.user.id) {
							this.modals.form.show.isDiscussionLikedFromUser = true;
						}
					}

					this.modals.form.show.discussion = discussion;
					this.modals.form.show.show = true;
				})
				.catch(function(error) {
					formVue.modals.form.show.show = false;
					formVue.$notify({type: 'danger', message: formVue.textMessageUnknownError});
				});
			},
			likeDiscussion(discussionId) {
				let formVue = this;
				
				axios.post(this.routeLikeDiscussion, {
					project_id: this.projectId,
					discussion_id: discussionId
				})
				.then(response => {
					this.modals.form.show.isDiscussionLikedFromUser = true;
					this.modals.form.show.discussion.total_like += 1;
				})
				.catch(function (error) {
					formVue.$notify({type: 'danger', message: formVue.textMessageUnknownError});
				});
			},
			unlikeDiscussion(discussionId) {
				let formVue = this;
				
				axios.delete(this.routeUnlikeDiscussion.replace('#', discussionId), {
					project_id: this.projectId,
					discussion_id: discussionId
				})
				.then(response => {
					this.modals.form.show.isDiscussionLikedFromUser = false;
					this.modals.form.show.discussion.total_like -= 1;
				})
				.catch(function (error) {
					formVue.$notify({type: 'danger', message: formVue.textMessageUnknownError});
				});
			},
			postComment(discussionId, comment) {
				let formVue = this;
				
				axios.post(this.routePostComment, {
					project_id: this.projectId,
					discussion_id: discussionId,
					comment: comment.value
				})
				.then(response => {
					this.modals.form.show.discussionComments.push(response.data.data);
					this.modals.form.show.discussion.total_comment += 1;
					comment.value = '';
				})
				.catch(function (error) {
					formVue.$notify({type: 'danger', message: formVue.textMessageUnknownError});
				});
			},
		},
		
		watch: {
			sortedDiscussionsList: function() {
				this.changePage((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
			},
			perPage: function() {
				this.changePage((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
			},
			currentPage: function() {
				this.changePage((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
			},
		},
		
		mounted() {
			this.sortLastActivity(this.sortedLastActivityOrder, true);
			this.sortLikes(this.sortedLikesOrder, true);			
			this.sortComments(this.sortedCommentsOrder, true);			
		},
		
		computed: {
			sortedDiscussionsAscByLastActivity: function() {
				return this.discussionsList.slice().sort(function(a, b) {
					if (a.updated_at < b.updated_at) {
    					return -1;
  					}
  					
  					if (a.updated_at > b.updated_at) {
    					return 1;
  					}

  					return 0;
				});
			},
			sortedDiscussionsDescByLastActivity: function() {
				return this.discussionsList.slice().sort(function(a, b) {
					if (a.updated_at < b.updated_at) {
    					return 1;
  					}
  					
  					if (a.updated_at > b.updated_at) {
    					return -1;
  					}

  					return 0;
				});
			},
			sortedDiscussionsAscByLikes: function() {
				return this.discussionsList.slice().sort(function(a, b) {
					if (a.total_like < b.total_like) {
    					return -1;
  					}
  					
  					if (a.total_like > b.total_like) {
    					return 1;
  					}

  					return 0;
				});
			},
			sortedDiscussionsDescByLikes: function() {
				return this.discussionsList.slice().sort(function(a, b) {
					if (a.total_like < b.total_like) {
    					return 1;
  					}
  					
  					if (a.total_like > b.total_like) {
    					return -1;
  					}

  					return 0;
				});
			},		
			sortedDiscussionsAscByComments: function() {
				return this.discussionsList.slice().sort(function(a, b) {
					if (a.total_comment < b.total_comment) {
    					return -1;
  					}
  					
  					if (a.total_comment > b.total_comment) {
    					return 1;
  					}

  					return 0;
				});
			},
			sortedDiscussionsDescByComments: function() {
				return this.discussionsList.slice().sort(function(a, b) {
					if (a.total_comment < b.total_comment) {
    					return 1;
  					}
  					
  					if (a.total_comment > b.total_comment) {
    					return -1;
  					}

  					return 0;
				});
			},		
			rows: function() {
				return this.sortedDiscussionsList.length;
			},
		},
	}
</script>

<style>
	.media-comment-text {
	    border-top-left-radius: 0.4375rem;
	    background-color: #ebebf0;
	    padding: 1rem 1.25rem 1rem 2.5rem;
	}
</style>