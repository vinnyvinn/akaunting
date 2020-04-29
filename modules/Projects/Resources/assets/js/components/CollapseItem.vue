<template>
	<div class="card">
    <div role="tab" class="card-header" :aria-expanded="active">
			<div class="row">
        <div class="col-md-10">
          <a data-toggle="collapse" data-parent="#accordion" :href="`#${itemId}`" @click.prevent="activate" :aria-controls="`content-${itemId}`">
            <i class="tim-icons icon-minimal-down"></i>
            <slot name="title"> {{ title }} </slot>
          </a>
        </div>
      			
        <div class="col-md-2">
          <slot name="actions"></slot>
        </div>
      </div>
		</div>    
    <collapse-transition :duration="animationDuration">
		  <div v-show="active" :id="`content-${itemId}`" role="tabpanel" :aria-labelledby="title" class="collapsed">
			  <div class="card-body">
          <slot></slot>
    		</div>
      </div>
    </collapse-transition>
	</div>
</template>

<script>
import { CollapseTransition } from 'vue2-transitions';

export default {
  name: 'collapse-item',
  components: {
    CollapseTransition
  },
  props: {
    title: {
      type: String,
      default: '',
      description: 'Collapse item title'
    },
    id: String
  },
  inject: {
    animationDuration: {
      default: 250
    },
    multipleActive: {
      default: false
    },
    addItem: {
      default: () => {}
    },
    removeItem: {
      default: () => {}
    },
    deactivateAll: {
      default: () => {}
    }
  },
  computed: {
    itemId() {
      return this.id || this.title;
    }
  },
  data() {
    return {
      active: false
    };
  },
  methods: {
    activate() {
      let wasActive = this.active;
      if (!this.multipleActive) {
        this.deactivateAll();
      }
      this.active = !wasActive;
    }
  },
  mounted() {
    this.addItem(this);
  },
  destroyed() {
    if (this.$el && this.$el.parentNode) {
      this.$el.parentNode.removeChild(this.$el);
    }
    this.removeItem(this);
  }
};
</script>

<style>
.accordion .card-header:after {
  content: "\EA0F";
  position: absolute;
  right: unset;
  top: 50%;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
  font: normal normal normal 14px/1 NucleoIcons;
  line-height: 0;
  -webkit-transition: all 0.15s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  transition: all 0.15s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
</style>
