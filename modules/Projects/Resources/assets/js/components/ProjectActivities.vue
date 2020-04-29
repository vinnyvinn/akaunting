<template>

<div class="card">
    <div class="card-body">
    	<div class="timeline timeline-one-side">
    		
    		<template v-for="activity of activities">
    			
                <div class="timeline-block">
                	<span class="badge badge-success badge-pill badge-md" v-if="activity.activity_type === 'NewLabel'">{{ activity.created }}</span>
                	
                	<!-- invoice -->
                	<span class="timeline-step badge-danger" v-else-if="activity.activity_type === pathInvoice">
                    	<i class="fas fa-file-invoice"></i>
                	</span>
                	
                	<!-- revenue -->
                	<span class="timeline-step badge-success" v-else-if="activity.activity_type === pathRevenue">
                		<i class="fas fa-plus"></i>
                	</span>

                	<!-- bill -->
                	<span class="timeline-step badge-default" v-else-if="activity.activity_type === pathBill">
                		<i class="fas fa-receipt"></i>
                	</span>
                	
                	<!-- payment -->
                	<span class="timeline-step badge-warning" v-else-if="activity.activity_type === pathPayment">
                		<i class="fas fa-minus"></i>
                	</span>
                	
                	<!-- task -->
                	<span class="timeline-step badge-primary" v-else-if="activity.activity_type === pathTask">
                		<i class="fas fa-tasks"></i>
                	</span>
                	
                	<!-- sub-task -->
                	<span class="timeline-step badge-primary" v-else-if="activity.activity_type === pathSubtask">
                		<i class="far fa-check-circle"></i>
                	</span>
                	
                	<!-- discussion -->
                	<span class="timeline-step badge-info" v-else-if="activity.activity_type === pathDiscussion">
                		<i class="far fa-comments"></i>
                	</span>
                	
                	<!-- comment -->
                	<span class="timeline-step badge-info" v-else-if="activity.activity_type === pathComment">
                		<i class="far fa-comment-dots"></i>
                	</span>
                	
                	<!-- project -->
                	<span class="timeline-step badge-success" v-else-if="activity.activity_type === pathProject">
                		<i class="fas fa-project-diagram"></i>
            		</span>
                	
                	<div class="timeline-content" v-if="activity.activity_type != 'NewLabel'">
                		<small class="text-muted font-weight-bold">{{ activity.created_time }}</small>
                		<p class=" text-sm mt-1 mb-0">{{ activity.description }}</p>
                	</div>
                </div>
                <!-- END timeline item -->
    			
    		</template>
    		
    		<div class="timeline-block">
    			<span class="timeline-step badge-success">
    				<i class="far fa-clock"></i>
    			</span>
    			<div class="timeline-content">
            		<p class=" text-sm mt-1 mb-10"></p>
            	</div>
    		</div>
    	</div>
    </div>
</div>

</template>

<script>
	export default {
		name: "project-activities",
		
		props: {
			activities: null,
		},

		data() {
			return {
				pathInvoice: 'App\\Models\\Sale\\Invoice',
				pathBill: 'App\\Models\\Purchase\\Bill',
				pathPayment: 'App\\Models\\Banking\\Transaction\\Payment',
				pathRevenue: 'App\\Models\\Banking\\Transaction\\Revenue',
				pathTask: 'Modules\\Projects\\Models\\Task',
				pathSubtask: 'Modules\\Projects\\Models\\SubTask',
				pathDiscussion: 'Modules\\Projects\\Models\\Discussion',
				pathComment: 'Modules\\Projects\\Models\\Comment',
				pathProject: 'Modules\\Projects\\Models\\Project',
			}
		},
		
	}
</script>