<template>
  <div>
    <el-button type="primary" plain @click="dialog = true">Set Limit</el-button>
    <el-dialog
      title="Limit"
      :visible.sync="dialog"
      max-width="30%"
      :before-close="handleClose"
    >
      <el-input v-model="newLimit" type="number" placeholder="Limit" />
      <span slot="footer" class="dialog-footer">
        <el-button @click="handleClose()">Cancel</el-button>
        <el-button type="primary" @click="handleConfirm()">Confirm</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'Limit',
  props: {
    limit: {
      type: Number,
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
      newLimit: 0,
    };
  },
  watch: {
    limit: function(newVal, oldVal) {
      this.newLimit = newVal;
    },
  },
  mounted() {
    this.newLimit = this.limit;
  },
  methods: {
    handleClose() {
      this.newLimit = this.limit;
      this.dialog = false;
    },
    handleConfirm() {
      this.$emit('update', Number(this.newLimit));
      this.dialog = false;
    },
  },
};
</script>
