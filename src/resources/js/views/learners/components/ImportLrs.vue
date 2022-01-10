<template>
  <div>
    <div style="display:flex;margin-bottom: 20px;justify-content: space-between;align-items: flex-end;">
      <el-button type="primary" icon="el-icon-upload" @click="importSelected">Import selected</el-button>
      <span>Selected: {{ multipleSelection.length }}</span>
    </div>
    <el-table
      ref="lrsTable"
      v-loading="loading"
      :data="learners"
      stripe
      border
      height="500"
      style="width: 100%"
      @selection-change="handleSelectionChange"
    >
      <el-table-column type="selection" width="55" />
      <el-table-column
        prop="username"
        label="Username"
      />
      <el-table-column
        prop="email"
        label="Email"
      />
      <el-table-column
        prop="source"
        label="Source"
      />
    </el-table>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'LrsImport',
  data() {
    return {
      loading: true,
      learners: [],
      multipleSelection: [],
    };
  },
  mounted() {
    this.getLearners();
  },
  methods: {
    async getLearners() {
      await axios.get('/api/learners/lrs_users', {
      }).then(response => {
        this.loading = false;
        this.learners = response.data.data;
      }).then(() => {
      });
    },
    async importSelected() {
      this.loading = true;
      await axios.post('/api/learners/lrs_users', { 'learners': this.multipleSelection }).then(response => {
        this.loading = false;
        this.$notify({
          title: 'Success',
          message: 'Imported Learners successfully',
          type: 'success',
          duration: 2000,
        });
        this.$emit('learnersImported');
      }).catch(() => {
        this.loading = false;
        this.$notify({
          title: 'Error',
          message: 'Learners NOT imported.',
          type: 'error',
          duration: 2000,
        });
      });
    },
    handleSelectionChange(val) {
      this.multipleSelection = val;
    },
  },
};
</script>

<style lang="scss" scoped>

</style>
