<template>
  <div>
    <el-button type="primary" plain :disabled="!pivotConfig || disabled" @click="dialog = true">Pivot</el-button>
    <el-dialog
      title="Pivot"
      :visible.sync="dialog"
      max-width="30%"
    >

      <div class="container">
        <div>
          <div class="axis-name">x</div>
          <draggable id="x" v-model="draggableX" class="list-group" group="pivot">
            <div v-for="member in draggableX" :key="member" class="list-group-item">
              <i class="el-icon-rank" />

              <span>{{ member }}</span>
            </div>
          </draggable>
        </div>

        <div />

        <div>
          <div class="axis-name">y</div>
          <draggable id="y" v-model="draggableY" class="list-group" group="pivot">
            <div v-for="member in draggableY" :key="member" class="list-group-item">
              <i class="el-icon-rank" />

              <span>{{ member }}</span>
            </div>
          </draggable>
        </div>
      </div>

    </el-dialog>
  </div>
</template>

<script>
import draggable from 'vuedraggable';

export default {
  name: 'PivotConfig',
  components: {
    draggable,
  },
  props: {
    pivotConfig: {
      type: Object,
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
    draggableX: {
      get() {
        return this.pivotConfig.x;
      },
      set(value) {
        this.$emit('move', {
          x: value,
        });
      },
    },
    draggableY: {
      get() {
        return this.pivotConfig.y;
      },
      set(value) {
        this.$emit('move', {
          y: value,
        });
      },
    },
  },
  methods: {
    checkMove(event) {
      // Make sure the `measures` is always the last item on axis
      const sourceAxis = event.from.id;
      const destinationAxis = event.to.id;
      let maxIndexOnAxis = this.pivotConfig[destinationAxis].length - 1;

      if (sourceAxis === destinationAxis) {
        maxIndexOnAxis--;
      }

      if (event.draggedContext.element === 'measures') {
        if (event.draggedContext.futureIndex <= maxIndexOnAxis) {
          return false;
        }
      } else {
        const { length } = this.pivotConfig[destinationAxis];
        if (this.pivotConfig[destinationAxis][length - 1] === 'measures') {
          if (event.draggedContext.futureIndex > maxIndexOnAxis) {
            return false;
          }
        }
      }

      return true;
    },
  },
};
</script>

<style scoped>
.container {
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  padding-bottom: 15px;
}

.axis-name {
  border: 1px solid #f0f0f0;
  font-weight: bold;
  text-align: center;
  background: #fafafa;
  padding: 5px;
  font-size: 16px;
}

.list-group {
  height: 100%;
  padding: 10px;
  color: #2196f3;
  background: #fafafa;
  border: 1px solid #f0f0f0;
}

.list-group-item {
  cursor: move;
}

.list-group-item > i {
  margin-right: 8px;
}

.divider {
  margin: 0 12px;
}
</style>
