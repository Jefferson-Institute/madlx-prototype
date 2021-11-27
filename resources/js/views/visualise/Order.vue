<template>
  <div>
    <el-button type="primary" plain :disabled="disabled" @click="dialog = true">Set Order</el-button>
    <el-dialog
      title="Order By"
      :visible.sync="dialog"
      max-width="30%"
    >

      <draggable v-model="list" @end="handleDragEnd">
        <div v-for="member in list" :key="member.id" class="order-member">
          <div class="order-member-name" style="cursor:ns-resize">
            <i class="el-icon-d-caret" />

            <span>{{ member.title }}</span>
          </div>
          <el-button-group>
            <el-button type="default" :disabled="member.order === 'asc'" @click="orderChange('asc', member.id)">ASC</el-button>
            <el-button type="default" :disabled="member.order === 'desc'" @click="orderChange('desc', member.id)">DESC</el-button>
            <el-button type="default" :disabled="member.order === 'none'" @click="orderChange('none', member.id)">NONE</el-button>
          </el-button-group>
        </div>
      </draggable>

    </el-dialog>
  </div>
</template>

<script>
import draggable from 'vuedraggable';
export default {
  name: 'Order',
  components: {
    draggable,
  },
  props: {
    orderMembers: {
      type: Array,
      required: true,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      dialog: false,
    };
  },
  computed: {
    list: {
      get() {
        return this.orderMembers;
      },
      set(value) {
        return value;
      },
    },
  },
  methods: {
    handleDragEnd(event) {
      this.$emit('reorder', event.oldIndex, event.newIndex);
    },
    orderChange(value, id) {
      this.$emit('orderChange', id, value);
    },
  },
};
</script>

<style scoped>
.order-member {
  display: flex;
  justify-content: space-between;
  width: 100%;
  margin: 16px 0;
}
.order-member-name {
  cursor: grab;
}
.order-member-name > i {
  margin-right: 8px;
}
</style>
