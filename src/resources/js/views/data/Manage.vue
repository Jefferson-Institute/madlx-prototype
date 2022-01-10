<template>
  <div class="app-container">
    <el-row type="flex" justify="center" :gutter="10">
      <el-col :xs="24" :sm="24" :md="24">
        <el-card class="box-card" style="margin:20px">
          <div slot="header" style="display: flex;justify-content: space-between;align-items: center;font-weight:600">
            <span>Imported Sources</span>
          </div>
          <el-table
            v-loading="loading"
            :data="authorities"
            stripe
            border
            style="width: 100%"
          >
            <el-table-column
              label="Source"
              width="180"
            >
              <template slot-scope="scope">
                {{ scope.row.authority }}
              </template>
            </el-table-column>
            <el-table-column
              label="Operations"
            >
              <template slot-scope="scope">
                <el-button
                  size="mini"
                  type="danger"
                  @click="handleDelete(scope.row)"
                >Delete All Statements</el-button>
              </template>
            </el-table-column>
          </el-table>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'Settings',
  data() {
    return {
      authorities: [],
      loading: true,
    };
  },
  created() {
    this.getAuthorities();
  },
  methods: {
    getAuthorities() {
      axios.get('/api/imports', {
      }).then(response => {
        this.authorities = response.data.data;
      }).then(() => {
        this.loading = false;
      });
    },
    handleDelete(row) {
      this.loading = true;
      // axios.post('/api/imports/delete', {authority: row.authority}).then(response => {
      //   this.authorities = response.data.data;
      // }).then(() => {
      //   this.loading = false;
      //   this.$notify({
      //     title: 'Success',
      //     message: 'Source Removed',
      //     type: 'success',
      //     duration: 2000,
      //   });
      // }).catch(error => {
      //   this.loading = false;
      //   this.percentage = 100;
      //   this.$notify({
      //     title: 'Error',
      //     message: error.response.data.response,
      //     type: 'error',
      //     duration: 2000,
      //   });
      // });
    },
  },
};
</script>

<style lang="scss" scoped>

</style>
